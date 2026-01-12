<?php
// app/Http/Controllers/PrescriptionController.php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    /**
     * GET - Ambil data resep
     * POST /api/prescriptions/get
     * Jika ada id: ambil 1 resep
     * Jika tidak ada id: ambil semua dengan filter
     */
    public function get(Request $request)
    {
        try {
            $id = $request->input('id');
            
            if ($id) {
                // Get 1 resep
                $prescription = Prescription::get($id);
                
                if (!$prescription) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Resep tidak ditemukan'
                    ], 404);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $prescription
                ]);
            } else {
                // Get semua resep dengan filter
                $filters = [
                    'search' => $request->input('search'),
                    'status' => $request->input('status'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'page' => $request->input('page', 1),
                    'per_page' => $request->input('per_page', 10)
                ];

                $result = Prescription::get(null, $filters);

                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data'
            ], 500);
        }
    }

    /**
     * ADD - Tambah resep baru
     * POST /api/prescriptions/add
     */
    public function add(Request $request)
    {
        try {
            $data = $request->all();
            
            // Validasi
            $errors = Prescription::validate($data);
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 400);
            }

            // Format date
            if (isset($data['examination_date'])) {
                $data['examination_date'] = date('Y-m-d H:i:s', strtotime($data['examination_date']));
            }

            $prescription = Prescription::add($data);

            return response()->json([
                'success' => true,
                'data' => $prescription,
                'message' => 'Resep berhasil dibuat'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat resep: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * UPDATE - Update resep
     * POST /api/prescriptions/update
     */
    public function update(Request $request)
    {
        try {
            $id = $request->input('id');
            
            if (empty($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID harus diisi'
                ], 400);
            }

            // Cek apakah resep ada
            $existing = Prescription::get($id);
            if (!$existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resep tidak ditemukan'
                ], 404);
            }

            $data = $request->except(['id']);
            
            // Validasi jika ada items
            if (isset($data['items'])) {
                $errors = Prescription::validate(array_merge(['items' => $data['items']], $data));
                if (!empty($errors)) {
                    return response()->json([
                        'success' => false,
                        'errors' => $errors
                    ], 400);
                }
            }

            // Format date jika ada
            if (isset($data['examination_date'])) {
                $data['examination_date'] = date('Y-m-d H:i:s', strtotime($data['examination_date']));
            }

            $prescription = Prescription::updatePrescription($id, $data);

            return response()->json([
                'success' => true,
                'data' => $prescription,
                'message' => 'Resep berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui resep: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE - Hapus resep (soft delete)
     * POST /api/prescriptions/delete
     */
    public function delete(Request $request)
    {
        try {
            $id = $request->input('id');
            
            if (empty($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID harus diisi'
                ], 400);
            }

            // Cek apakah resep ada
            $existing = Prescription::get($id);
            if (!$existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resep tidak ditemukan'
                ], 404);
            }

            $deleted = Prescription::deletePrescription($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Resep berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus resep'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus resep'
            ], 500);
        }
    }

    /**
     * View untuk web
     */
    public function index()
    {
        return view('prescriptions.index');
    }

    /**
     * Get medicine data with price based on date
     * Single function untuk semua kebutuhan obat
     */
    public function getMedicineData(Request $request)
    {
        try {
            $action = $request->input('action', 'list'); // 'list' atau 'price'
            $medicineId = $request->input('medicine_id');
            $date = $request->input('date', date('Y-m-d'));
            
            // Ambil konfigurasi dari database
            $hospital = DB::table('app_settings')
                ->whereNotNull('api_email')
                ->first();
            
            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi rumah sakit tidak ditemukan'
                ], 404);
            }
            
            // 1. LOGIN UNTUK DAPATKAN TOKEN
            $loginResponse = Http::post($hospital->api_base_url . '/auth', [
                'email' => $hospital->api_email,
                'password' => $hospital->api_password
            ]);
            
            if (!$loginResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal login ke API rumah sakit'
                ], 401);
            }
            
            $tokenData = $loginResponse->json();
            $token = $tokenData['access_token'] ?? null;
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan dalam response'
                ], 401);
            }
            
            // 2. PROSES BERDASARKAN ACTION
            if ($action === 'price' && $medicineId) {
                // AMBIL HARGA OBAT BERDASARKAN TANGGAL
                $priceResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token
                ])->get($hospital->api_base_url . '/medicines/' . $medicineId . '/prices');
                
                if (!$priceResponse->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengambil harga obat'
                    ], 400);
                }
                
                $prices = $priceResponse->json()['prices'] ?? [];
                
                // Cari harga yang berlaku pada tanggal tertentu
                $applicablePrice = null;
                foreach ($prices as $price) {
                    $startDate = $price['start_date']['value'] ?? null;
                    $endDate = $price['end_date']['value'] ?? null;
                    
                    $isValid = false;
                    
                    if ($endDate === null) {
                        // Jika end_date null, berlaku mulai start_date sampai selamanya
                        $isValid = strtotime($date) >= strtotime($startDate);
                    } else {
                        // Periksa apakah tanggal berada dalam rentang
                        $isValid = strtotime($date) >= strtotime($startDate) && 
                                  strtotime($date) <= strtotime($endDate);
                    }
                    
                    if ($isValid) {
                        $applicablePrice = [
                            'id' => $price['id'],
                            'unit_price' => $price['unit_price'],
                            'start_date' => $price['start_date'],
                            'end_date' => $price['end_date']
                        ];
                        break;
                    }
                }
                
                if (!$applicablePrice) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Harga tidak ditemukan untuk tanggal ' . $date
                    ], 404);
                }
                
                return response()->json([
                    'success' => true,
                    'price' => $applicablePrice,
                    'date_checked' => $date
                ]);
                
            } else {
                // AMBIL DAFTAR OBAT (default action)
                $medicinesResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token
                ])->get($hospital->api_base_url . '/medicines');
                
                if (!$medicinesResponse->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengambil daftar obat'
                    ], 400);
                }
                
                $medicines = $medicinesResponse->json()['medicines'] ?? [];
                
                return response()->json([
                    'success' => true,
                    'medicines' => $medicines,
                    'total' => count($medicines)
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}