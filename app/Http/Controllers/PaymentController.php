<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * GET - Halaman index pembayaran
     */
    public function index()
    {
        return view('payments.index');
    }

    /**
     * GET - Ambil data pembayaran
     * POST /api/payments/get
     */
    public function get(Request $request)
    {
        try {
            $id = $request->input('id');
            
            if ($id) {
                // Get 1 pembayaran - TAMPILKAN SEMUA STATUS
                $payment = Payment::get($id);
                
                if (!$payment) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pembayaran tidak ditemukan'
                    ], 404);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $payment
                ]);
            } else {
                // Get semua pembayaran dengan filter
                $filters = [
                    'search' => $request->input('search'),
                    'payment_status' => $request->input('payment_status'),
                    'status' => $request->input('status'), // Filter status resep
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'page' => $request->input('page', 1),
                    'per_page' => $request->input('per_page', 10)
                ];

                $result = Payment::get(null, $filters);

                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST - Proses pembayaran
     * POST /api/payments/update
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

            // Cek apakah data ada (TAMPILKAN SEMUA STATUS)
            $existing = Payment::get($id);
            if (!$existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pembayaran tidak ditemukan'
                ], 404);
            }

            // Cek status awal bisa diproses (draft atau process)
            if (!in_array($existing->status, ['draft', 'process'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status resep harus "Draf" atau "Diproses" untuk bisa dibayar'
                ], 400);
            }

            $data = $request->all();
            
            // Validasi
            $errors = Payment::validate($data);
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 400);
            }

            // Proses pembayaran
            $payment = Payment::processPayment($id, $data);

            return response()->json([
                'success' => true,
                'data' => $payment,
                'message' => 'Pembayaran berhasil diproses'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST - Batalkan pembayaran
     * POST /api/payments/cancel
     */
    public function cancel(Request $request)
    {
        try {
            $id = $request->input('id');
            $notes = $request->input('notes');
            
            if (empty($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID harus diisi'
                ], 400);
            }

            $cancelled = Payment::cancelPayment($id, $notes);

            if ($cancelled) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dibatalkan'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan pembayaran atau pembayaran tidak dapat dibatalkan'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pembayaran'
            ], 500);
        }
    }

    /**
     * GET - Statistik pembayaran
     * GET /api/payments/statistics
     */
    public function statistics()
    {
        try {
            $statistics = Payment::getStatistics();

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    /**
     * GET - Cetak invoice PDF
     * GET /api/payments/invoice/{id}/pdf
     */
    public function generateInvoice($id)
    {
        try {
            $payment = Payment::getForPdf($id);
            
            if (!$payment) {
                abort(404, 'Data pembayaran tidak ditemukan');
            }

            if ($payment->payment_status !== 'paid') {
                abort(400, 'Pembayaran belum diproses');
            }

            $data = [
                'prescription' => $payment,
                'items' => $payment->items,
                'receipt_number' => $payment->receipt_number,
                'print_date' => now()->format('d F Y H:i'),
                'pharmacist' => auth()->user()->name ?? 'Apoteker'
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Fitur cetak PDF akan tersedia setelah install DomPDF'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghasilkan invoice: ' . $e->getMessage()
            ], 500);
        }
    }
}