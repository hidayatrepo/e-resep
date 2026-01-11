<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    /**
     * GET - Ambil data pembayaran
     */
    public static function get($id = null, $filters = [])
    {
        if ($id) {
            // Get 1 resep untuk pembayaran dengan items - TAMPILKAN SEMUA STATUS
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
            // Get semua pembayaran dengan filter
            $query = DB::table('prescriptions')
                ->select('prescriptions.*', DB::raw('CONCAT(prescriptions.patient_name, " - No. ", prescriptions.prescription_number) as display_name'))
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

            // Payment status filter
            if (!empty($filters['payment_status'])) {
                $query->where('payment_status', $filters['payment_status']);
            }

            // Prescription status filter (status resep)
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
     * UPDATE - Proses pembayaran
     */
    public static function processPayment($id, $data)
    {
        DB::beginTransaction();
        try {
            // Generate receipt number
            $count = DB::table('prescriptions')
                ->where('payment_status', 'paid')
                ->count() + 1;
            $receiptNumber = 'INV-' . date('Ymd') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            
            // Update prescription payment info
            $updateData = [
                'payment_status' => 'paid',
                'payment_amount' => $data['payment_amount'],
                'payment_date' => now(),
                'payment_method' => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? $receiptNumber,
                'payment_notes' => $data['payment_notes'] ?? null,
                'pharmacist_name' => auth()->user()->name ?? 'Apoteker',
                'served_at' => now(),
                'status' => 'completed', // Set status jadi completed setelah bayar
                'updated_at' => now()
            ];

            // Update berdasarkan status awal (draft atau process)
            DB::table('prescriptions')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->whereIn('status', ['draft', 'process'])
                ->update($updateData);

            // Log activity
            DB::table('payment_logs')->insert([
                'prescription_id' => $id,
                'action' => 'payment_processed',
                'details' => json_encode([
                    'payment_amount' => $data['payment_amount'],
                    'payment_method' => $data['payment_method'],
                    'payment_reference' => $data['payment_reference'] ?? $receiptNumber,
                    'pharmacist' => auth()->user()->name ?? 'Apoteker'
                ]),
                'user_type' => 'pharmacist',
                'user_name' => auth()->user()->name ?? 'Apoteker',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            
            // Get updated prescription data
            $prescription = DB::table('prescriptions')
                ->where('id', $id)
                ->first();
            
            $prescription->items = DB::table('prescription_items')
                ->where('prescription_id', $id)
                ->get();
            $prescription->receipt_number = $receiptNumber;

            return $prescription;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * UPDATE - Batalkan pembayaran
     */
    public static function cancelPayment($id, $notes = null)
    {
        DB::beginTransaction();
        try {
            $updateData = [
                'payment_status' => 'cancelled',
                'status' => 'cancelled', // Juga update status resep jadi cancelled
                'payment_notes' => $notes,
                'updated_at' => now()
            ];

            $affected = DB::table('prescriptions')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->whereIn('payment_status', ['pending', 'paid'])
                ->update($updateData);

            if ($affected) {
                // Log activity
                DB::table('payment_logs')->insert([
                    'prescription_id' => $id,
                    'action' => 'payment_cancelled',
                    'details' => json_encode([
                        'notes' => $notes,
                        'pharmacist' => auth()->user()->name ?? 'Apoteker'
                    ]),
                    'user_type' => 'pharmacist',
                    'user_name' => auth()->user()->name ?? 'Apoteker',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return $affected;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * GET - Statistik pembayaran
     */
    public static function getStatistics()
    {
        $today = now()->format('Y-m-d');
        $month = now()->format('Y-m');
        
        return [
            'today' => [
                'total_payments' => DB::table('prescriptions')
                    ->whereDate('payment_date', $today)
                    ->where('payment_status', 'paid')
                    ->count(),
                'total_amount' => DB::table('prescriptions')
                    ->whereDate('payment_date', $today)
                    ->where('payment_status', 'paid')
                    ->sum('payment_amount') ?? 0,
            ],
            'month' => [
                'total_payments' => DB::table('prescriptions')
                    ->where('payment_date', 'LIKE', "{$month}%")
                    ->where('payment_status', 'paid')
                    ->count(),
                'total_amount' => DB::table('prescriptions')
                    ->where('payment_date', 'LIKE', "{$month}%")
                    ->where('payment_status', 'paid')
                    ->sum('payment_amount') ?? 0,
            ],
            'pending' => DB::table('prescriptions')
                ->where('payment_status', 'pending')
                ->whereIn('status', ['draft', 'process'])
                ->whereNull('deleted_at')
                ->count(),
            'payment_methods' => DB::table('prescriptions')
                ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(payment_amount) as total'))
                ->where('payment_status', 'paid')
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get()
        ];
    }

    /**
     * Validasi data pembayaran
     */
    public static function validate($data)
    {
        $errors = [];
        
        if (empty($data['payment_amount']) || $data['payment_amount'] <= 0) {
            $errors[] = 'Jumlah pembayaran harus diisi';
        }
        
        if (empty($data['payment_method'])) {
            $errors[] = 'Metode pembayaran harus dipilih';
        }
        
        // Cek metode pembayaran valid
        $validMethods = ['cash', 'debit_card', 'credit_card', 'qris', 'transfer'];
        if (!empty($data['payment_method']) && !in_array($data['payment_method'], $validMethods)) {
            $errors[] = 'Metode pembayaran tidak valid';
        }
        
        return $errors;
    }

    /**
     * GET - Data untuk export PDF
     */
    public static function getForPdf($id)
    {
        $prescription = DB::table('prescriptions')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$prescription) {
            return null;
        }

        $prescription->items = DB::table('prescription_items')
            ->where('prescription_id', $id)
            ->get();

        // Generate receipt number
        $count = DB::table('prescriptions')
            ->where('payment_status', 'paid')
            ->whereDate('payment_date', now()->format('Y-m-d'))
            ->count() + 1;
        $prescription->receipt_number = 'INV-' . date('Ymd') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        return $prescription;
    }
}