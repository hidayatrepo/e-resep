<?php
// app/Models/Prescription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    /**
     * GET - Ambil data resep
     * Jika ada $id, ambil 1 resep dengan itemsnya
     * Jika tidak ada $id, ambil semua dengan filter
     */
    public static function get($id = null, $filters = [])
    {
        if ($id) {
            // Get 1 resep dengan items
            $prescription = DB::table('prescriptions')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$prescription) {
                return null;
            }

            // Get items
            $prescription->items = DB::table('prescription_items')
                ->where('prescription_id', $id)
                ->get();

            return $prescription;
        } else {
            // Get semua resep dengan filter
            $query = DB::table('prescriptions')
                ->whereNull('deleted_at')
                ->orderBy('examination_date', 'desc');

            // Search filter
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function($q) use ($search) {
                    $q->where('prescription_number', 'like', "%{$search}%")
                      ->orWhere('patient_name', 'like', "%{$search}%")
                      ->orWhere('doctor_name', 'like', "%{$search}%");
                });
            }

            // Status filter
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Date filter
            if (!empty($filters['start_date'])) {
                $query->whereDate('examination_date', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $query->whereDate('examination_date', '<=', $filters['end_date']);
            }

            // Pagination
            $perPage = $filters['per_page'] ?? 10;
            $page = $filters['page'] ?? 1;
            $offset = ($page - 1) * $perPage;
            
            $total = $query->count();
            $data = $query->skip($offset)->take($perPage)->get();

            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'last_page' => ceil($total / $perPage)
            ];
        }
    }

    /**
     * ADD - Tambah resep baru
     * Insert ke 2 tabel: prescriptions dan prescription_items
     */
    public static function add($data)
    {
        DB::beginTransaction();
        try {
            // Generate nomor resep
            $count = DB::table('prescriptions')->count() + 1;
            $prescriptionNumber = 'RX-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            
            // Hitung total harga
            $totalPrice = 0;
            $items = $data['items'] ?? [];
            foreach ($items as $item) {
                $totalPrice += ($item['quantity'] * $item['unit_price']);
            }
            
            // Insert prescription
            $prescriptionId = DB::table('prescriptions')->insertGetId([
                'prescription_number' => $prescriptionNumber,
                'patient_name' => $data['patient_name'],
                'patient_phone' => $data['patient_phone'] ?? '',
                'patient_address' => $data['patient_address'] ?? '',
                'doctor_name' => $data['doctor_name'],
                'examination_date' => $data['examination_date'],
                'height' => $data['height'] ?? null,
                'weight' => $data['weight'] ?? null,
                'systole' => $data['systole'] ?? null,
                'diastole' => $data['diastole'] ?? null,
                'heart_rate' => $data['heart_rate'] ?? null,
                'respiration_rate' => $data['respiration_rate'] ?? null,
                'temperature' => $data['temperature'] ?? null,
                'examination_result' => $data['examination_result'] ?? '',
                'status' => $data['status'] ?? 'draft',
                'total_price' => $totalPrice,
                'notes' => $data['notes'] ?? '',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Insert items
            foreach ($items as $item) {
                DB::table('prescription_items')->insert([
                    'prescription_id' => $prescriptionId,
                    'medicine_id' => $item['medicine_id'] ?? '',
                    'medicine_name' => $item['medicine_name'],
                    'unit' => $item['unit'] ?? 'tablet',
                    'quantity' => $item['quantity'],
                    'instructions' => $item['instructions'] ?? '',
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return self::get($prescriptionId);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * UPDATE - Update resep
     * Update prescriptions, hapus items lama, insert items baru
     */
    public static function updatePrescription($id, $data)
    {
        DB::beginTransaction();
        try {
            // Update prescription data
            $updateData = [
                'patient_name' => $data['patient_name'] ?? '',
                'patient_phone' => $data['patient_phone'] ?? '',
                'patient_address' => $data['patient_address'] ?? '',
                'doctor_name' => $data['doctor_name'] ?? '',
                'examination_date' => $data['examination_date'] ?? now(),
                'height' => $data['height'] ?? null,
                'weight' => $data['weight'] ?? null,
                'systole' => $data['systole'] ?? null,
                'diastole' => $data['diastole'] ?? null,
                'heart_rate' => $data['heart_rate'] ?? null,
                'respiration_rate' => $data['respiration_rate'] ?? null,
                'temperature' => $data['temperature'] ?? null,
                'examination_result' => $data['examination_result'] ?? '',
                'status' => $data['status'] ?? 'draft',
                'notes' => $data['notes'] ?? '',
                'updated_at' => now()
            ];

            // Jika ada items, update total price dan items
            if (isset($data['items'])) {
                // Hitung total baru
                $totalPrice = 0;
                foreach ($data['items'] as $item) {
                    $totalPrice += ($item['quantity'] * $item['unit_price']);
                }
                $updateData['total_price'] = $totalPrice;
                
                // Hapus items lama
                DB::table('prescription_items')
                    ->where('prescription_id', $id)
                    ->delete();
                
                // Insert items baru
                foreach ($data['items'] as $item) {
                    DB::table('prescription_items')->insert([
                        'prescription_id' => $id,
                        'medicine_id' => $item['medicine_id'] ?? '',
                        'medicine_name' => $item['medicine_name'],
                        'unit' => $item['unit'] ?? 'tablet',
                        'quantity' => $item['quantity'],
                        'instructions' => $item['instructions'] ?? '',
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['quantity'] * $item['unit_price'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::table('prescriptions')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update($updateData);

            DB::commit();
            return self::get($id);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * DELETE - Soft delete resep
     * Update deleted_at, tidak hapus fisik
     */
    public static function deletePrescription($id)
    {
        return DB::table('prescriptions')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now()
            ]);
    }

    /**
     * Validasi data
     */
    public static function validate($data)
    {
        $errors = [];
        
        if (empty($data['patient_name'])) {
            $errors[] = 'Nama pasien harus diisi';
        }
        
        if (empty($data['doctor_name'])) {
            $errors[] = 'Nama dokter harus diisi';
        }
        
        if (empty($data['examination_date'])) {
            $errors[] = 'Tanggal pemeriksaan harus diisi';
        }
        
        if (empty($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
            $errors[] = 'Minimal satu obat harus ditambahkan';
        } else {
            foreach ($data['items'] as $index => $item) {
                if (empty($item['medicine_name'])) {
                    $errors[] = "Nama obat ke-" . ($index + 1) . " harus diisi";
                }
                if (empty($item['quantity']) || $item['quantity'] < 1) {
                    $errors[] = "Jumlah obat ke-" . ($index + 1) . " harus diisi";
                }
                if (empty($item['unit_price']) || $item['unit_price'] < 0) {
                    $errors[] = "Harga obat ke-" . ($index + 1) . " harus diisi";
                }
            }
        }
        
        return $errors;
    }
}