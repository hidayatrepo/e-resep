<?php
// app/Http/Controllers/PrescriptionController.php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

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
}