<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal default (bulan ini)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Total pasien unik (berdasarkan nama)
        $totalPatients = DB::table('prescriptions')
            ->whereBetween('examination_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->distinct('patient_name')
            ->count('patient_name');
        
        // Total resep
        $totalPrescriptions = DB::table('prescriptions')
            ->whereBetween('examination_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
        
        // Total pembayaran
        $totalPayment = DB::table('prescriptions')
            ->whereBetween('examination_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->sum('payment_amount');
        
        // Total jenis obat terjual
        $totalMedicineTypes = DB::table('prescription_items')
            ->join('prescriptions', 'prescriptions.id', '=', 'prescription_items.prescription_id')
            ->whereBetween('prescriptions.examination_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->distinct('prescription_items.medicine_name')
            ->count('prescription_items.medicine_name');
        
        // Statistik resep per status
        $prescriptionStats = DB::table('prescriptions')
            ->whereBetween('examination_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        // Data resep untuk tabel
        $prescriptions = DB::table('prescriptions')
            ->whereBetween('examination_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                'prescription_number',
                'patient_name',
                'examination_date',
                'doctor_name',
                'status'
            )
            ->orderBy('examination_date', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($prescription) {
                // Hitung total obat per resep
                $totalMedicines = DB::table('prescription_items')
                    ->where('prescription_id', function ($query) use ($prescription) {
                        $query->select('id')
                            ->from('prescriptions')
                            ->where('prescription_number', $prescription->prescription_number);
                    })
                    ->sum('quantity');
                
                $prescription->total_medicines = $totalMedicines;
                $prescription->examination_date_formatted = Carbon::parse($prescription->examination_date)->format('d M Y');
                return $prescription;
            });
        
        // Data tren 12 bulan terakhir
        $monthlyTrends = [];
        $paymentTrends = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            // Resep per bulan
            $monthlyPrescriptions = DB::table('prescriptions')
                ->whereBetween('examination_date', [$monthStart, $monthEnd])
                ->count();
            
            // Pembayaran per bulan
            $monthlyPayment = DB::table('prescriptions')
                ->whereBetween('examination_date', [$monthStart, $monthEnd])
                ->where('payment_status', 'paid')
                ->sum('payment_amount');
            
            $monthlyTrends[] = $monthlyPrescriptions;
            $paymentTrends[] = $monthlyPayment / 1000000; // Konversi ke juta
            $labels[] = $month->translatedFormat('M');
        }
        
        // Data untuk chart
        $chartData = [
            'labels' => $labels,
            'prescription_trend' => $monthlyTrends,
            'payment_trend' => $paymentTrends
        ];
        
        // Data bulan sebelumnya untuk perbandingan
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        $previousMonthPatients = DB::table('prescriptions')
            ->whereBetween('examination_date', [$previousMonthStart, $previousMonthEnd])
            ->distinct('patient_name')
            ->count('patient_name');
        
        $previousMonthPrescriptions = DB::table('prescriptions')
            ->whereBetween('examination_date', [$previousMonthStart, $previousMonthEnd])
            ->count();
        
        $previousMonthPayment = DB::table('prescriptions')
            ->whereBetween('examination_date', [$previousMonthStart, $previousMonthEnd])
            ->where('payment_status', 'paid')
            ->sum('payment_amount');
        
        $previousMonthMedicineTypes = DB::table('prescription_items')
            ->join('prescriptions', 'prescriptions.id', '=', 'prescription_items.prescription_id')
            ->whereBetween('prescriptions.examination_date', [$previousMonthStart, $previousMonthEnd])
            ->distinct('prescription_items.medicine_name')
            ->count('prescription_items.medicine_name');
        
        // Hitung perbedaan untuk summary cards
        $patientDiff = $totalPatients - $previousMonthPatients;
        $prescriptionDiff = $totalPrescriptions - $previousMonthPrescriptions;
        $paymentDiff = ($totalPayment - $previousMonthPayment) / 1000000; // Dalam juta
        $medicineTypesDiff = $totalMedicineTypes - $previousMonthMedicineTypes;
        
        return view('reports.index', [
            'totalPatients' => $totalPatients,
            'totalPrescriptions' => $totalPrescriptions,
            'totalPayment' => $totalPayment,
            'totalMedicineTypes' => $totalMedicineTypes,
            'prescriptionStats' => $prescriptionStats,
            'prescriptions' => $prescriptions,
            'chartData' => $chartData,
            'patientDiff' => $patientDiff,
            'prescriptionDiff' => $prescriptionDiff,
            'paymentDiff' => $paymentDiff,
            'medicineTypesDiff' => $medicineTypesDiff,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentMonth' => Carbon::now()->translatedFormat('F Y')
        ]);
    }
    
    public function export(Request $request)
    {
        // TODO: Implement PDF export functionality
        return response()->json(['message' => 'Export feature coming soon']);
    }
}