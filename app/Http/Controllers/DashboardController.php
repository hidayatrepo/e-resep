<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = session('user');
        $today = now()->format('Y-m-d');
        
        // Hitung statistik
        $stats = $this->getDashboardStats($today);
        $charts = $this->getChartData($today);
        
        // Ambil data obat yang paling sering diresepkan
        $topMedicines = $this->getTopMedicines();
        
        // Buat distribusi kategori obat berdasarkan topMedicines
        $medicationCategories = $this->getMedicineCategoriesFromTopMedicines($topMedicines);

        $medicationCategories_new = [];
        foreach ($topMedicines as $item) {
            $medicationCategories_new[$item->medicine_name] = $item->total_quantity;
        }

        // print("<pre>".print_r($medicationCategories,true)."</pre>");
        // die();
        
        // Debug log
        \Log::info('Dashboard Data:', [
            'top_medicines_count' => $topMedicines->count(),
            'medication_categories' => $medicationCategories,
            'stats' => $stats
        ]);
        
        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'charts' => $charts,
            'medication_categories' => $medicationCategories,
            // 'medication_categories' => $medicationCategories_new,
            'top_medicines' => $topMedicines
            // 'top_medicines' => $medicationCategories_new
        ]);
    }
    
    private function getDashboardStats($today)
    {
        // 1. Resep Hari Ini
        $prescriptionsToday = DB::table('prescriptions')
            ->whereDate('created_at', $today)
            ->count();
        
        // 2. Pasien Aktif (unik berdasarkan nama pasien dalam 7 hari terakhir)
        $activePatients = DB::table('prescriptions')
            ->where('created_at', '>=', now()->subDays(7))
            ->distinct('patient_name')
            ->count('patient_name');
        
        // 3. Total obat berbeda yang pernah diresepkan
        $totalMedicines = DB::table('prescription_items')
            ->distinct('medicine_id')
            ->count('medicine_id');
        
        // 4. Pembayaran Hari Ini
        $paymentsToday = DB::table('prescriptions')
            ->where('payment_status', 'paid')
            ->whereDate('payment_date', $today)
            ->count();
        
        // 5. Total Pendapatan Hari Ini
        $revenueToday = DB::table('prescriptions')
            ->where('payment_status', 'paid')
            ->whereDate('payment_date', $today)
            ->sum('total_price') ?? 0;
        
        // 6. Resep dalam proses
        $processPrescriptions = DB::table('prescriptions')
            ->where('status', 'process')
            ->count();
        
        // 7. Resep belum dibayar
        $pendingPayments = DB::table('prescriptions')
            ->where('payment_status', 'pending')
            ->where('status', '!=', 'draft')
            ->count();
        
        // 8. Total item obat terjual
        $totalItemsSold = DB::table('prescription_items')
            ->sum('quantity');
        
        // 9. Total pendapatan keseluruhan
        $totalRevenue = DB::table('prescriptions')
            ->where('payment_status', 'paid')
            ->sum('total_price') ?? 0;
        
        return [
            'prescriptions_today' => $prescriptionsToday,
            'active_patients' => $activePatients,
            'total_medicines' => $totalMedicines,
            'payments_today' => $paymentsToday,
            'revenue_today' => $revenueToday,
            'process_prescriptions' => $processPrescriptions,
            'pending_payments' => $pendingPayments,
            'total_items_sold' => $totalItemsSold,
            'total_revenue' => $totalRevenue
        ];
    }
    
    private function getChartData($today)
    {
        // Data untuk grafik resep 7 hari terakhir
        $last7Days = [];
        $prescriptionData = [];
        $paymentData = [];
        $revenueData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->translatedFormat('D');
            $last7Days[] = $dayName;
            
            // Hitung resep per hari
            $prescriptionsCount = DB::table('prescriptions')
                ->whereDate('created_at', $date)
                ->count();
            $prescriptionData[] = $prescriptionsCount;
            
            // Hitung pembayaran per hari
            $paymentsCount = DB::table('prescriptions')
                ->where('payment_status', 'paid')
                ->whereDate('payment_date', $date)
                ->count();
            $paymentData[] = $paymentsCount;
            
            // Hitung pendapatan per hari
            $revenue = DB::table('prescriptions')
                ->where('payment_status', 'paid')
                ->whereDate('payment_date', $date)
                ->sum('total_price') ?? 0;
            $revenueData[] = $revenue;
        }
        
        // Data untuk chart bulanan
        $monthlyData = $this->getMonthlyData();
        
        return [
            'last_7_days' => $last7Days,
            'prescription_data' => $prescriptionData,
            'payment_data' => $paymentData,
            'revenue_data' => $revenueData,
            'monthly_data' => $monthlyData
        ];
    }
    
    
    private function getMedicineCategoriesFromTopMedicines($topMedicines)
    {
        try {
            \Log::info('=== START getMedicineCategoriesFromTopMedicines ===');
            
            if ($topMedicines->isEmpty()) {
                \Log::info('No top medicines data found, returning empty array');
                return [];
            }
            
            $categories = [
                'Analgesik & Antiinflamasi' => 0,
                'Antibiotik' => 0,
                'Kardiovaskular' => 0,
                'Gastrointestinal' => 0,
                'Psikotropika' => 0,
                'Vitamin & Suplemen' => 0,
                'Antialergi & Pernapasan' => 0,
                'Lainnya' => 0
            ];
            
            // Improved keyword mapping untuk setiap kategori
            $keywords = [
                'Analgesik & Antiinflamasi' => [
                    'paracetamol', 'ibuprofen', 'mefenamic', 'metamizole', 'diclofenac', 
                    'ketoprofen', 'analgesik', 'antiinflamasi', 'nyeri', 'tranexamic',
                    'asam mefenamat'
                ],
                'Antibiotik' => [
                    'amox', 'cef', 'cipro', 'levo', 'azithro', 'tetra', 'doxy', 
                    'antibiotik', 'ampicillin', 'clindamycin', 'erythromycin'
                ],
                'Kardiovaskular' => [
                    'propranolol', 'amlodipine', 'captopril', 'losartan', 'atorvastatin', 
                    'simvastatin', 'kardiovaskular', 'jantung', 'hipertensi', 'tensi',
                    'atenolol', 'bisoprolol', 'carvedilol'
                ],
                'Gastrointestinal' => [
                    'omeprazole', 'esomeprazole', 'ranitidine', 'lansoprazole', 
                    'domperidone', 'gastro', 'lambung', 'maag', 'antacid', 'sucralfate'
                ],
                'Psikotropika' => [
                    'diazepam', 'lorazepam', 'clonazepam', 'alprazolam', 'fluoxetine', 
                    'sertraline', 'psikotropika', 'antidepresan', 'anti kejang',
                    'risperidone', 'haloperidol'
                ],
                'Vitamin & Suplemen' => [
                    'vitamin', 'calciferol', 'mecobalamin', 'asam folat', 'kalsium', 
                    'suplemen', 'vit', 'mineral', 'becom', 'cholecalciferol',
                    'vit e', 'vit c', 'vit b', 'vit d', 'd3', 'multivitamin'
                ],
                'Antialergi & Pernapasan' => [
                    'desloratadine', 'cetirizine', 'loratadine', 'fexofenadine', 
                    'chlorpheniramine', 'antialergi', 'alergi', 'pernapasan', 'asma',
                    'deksametason', 'pseudoefedrin', 'ambroxol'
                ]
            ];
            
            foreach ($topMedicines as $medicine) {
                $medicineName = strtolower(trim($medicine->medicine_name));
                $totalQuantity = $medicine->total_quantity ?? 0;
                $categoryFound = false;
                
                \Log::info("Analyzing medicine: '{$medicineName}' (total quantity: {$totalQuantity})");
                
                if (empty($medicineName)) {
                    $categories['Lainnya'] += $totalQuantity;
                    \Log::info("  → Empty name, categorized as Lainnya");
                    continue;
                }
                
                // Cek berdasarkan keyword mapping
                foreach ($keywords as $category => $kwList) {
                    foreach ($kwList as $keyword) {
                        if (strpos($medicineName, $keyword) !== false) {
                            $categories[$category] += $totalQuantity;
                            $categoryFound = true;
                            \Log::info("  → Categorized as: {$category} (keyword: '{$keyword}' found in '{$medicineName}')");
                            break 2;
                    }
                }
            }
                
                // Jika tidak ditemukan di keyword mapping, cek manual berdasarkan nama spesifik
                if (!$categoryFound) {
                    \Log::info("  → Not found in keyword mapping, checking manually...");
                    
                    // Manual categorization based on specific medicine names
                    if (strpos($medicineName, 'cholecalciferol') !== false || 
                        strpos($medicineName, 'prove d3') !== false ||
                        strpos($medicineName, 'd3') !== false) {
                        $categories['Vitamin & Suplemen'] += $totalQuantity;
                        $categoryFound = true;
                        \Log::info("  → Manually categorized as Vitamin & Suplemen (cholecalciferol/D3)");
                    } 
                    elseif (strpos($medicineName, 'desloratadine') !== false || 
                           strpos($medicineName, 'ddga') !== false) {
                        $categories['Antialergi & Pernapasan'] += $totalQuantity;
                        $categoryFound = true;
                        \Log::info("  → Manually categorized as Antialergi & Pernapasan (desloratadine)");
                    }
                    elseif (strpos($medicineName, 'vitamin e') !== false || 
                           strpos($medicineName, 'vitamin c') !== false ||
                           strpos($medicineName, 'vit b1') !== false ||
                           strpos($medicineName, 'becom') !== false) {
                        $categories['Vitamin & Suplemen'] += $totalQuantity;
                        $categoryFound = true;
                        \Log::info("  → Manually categorized as Vitamin & Suplemen (vitamin combo)");
                    }
                }
                
                // Jika masih tidak ditemukan, masukkan ke Lainnya
                if (!$categoryFound) {
                    $categories['Lainnya'] += $totalQuantity;
                    \Log::info("  → No match found, categorized as Lainnya");
                }
            }
            
            // Filter: hanya tampilkan kategori yang memiliki data
            $categories = array_filter($categories, function($count) {
                return $count > 0;
            });
            
            // Jika setelah filter masih kosong, kembalikan array kosong
            if (empty($categories)) {
                \Log::info('No categories after filtering, returning empty array');
                return [];
            }
            
            // Urutkan berdasarkan jumlah terbanyak
            arsort($categories);
            
            \Log::info('Final analyzed categories:', $categories);
            \Log::info('=== END getMedicineCategoriesFromTopMedicines ===');
            
            return $categories;
            
        } catch (\Exception $e) {
            \Log::error('Error analyzing medicine categories: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            return [];
        }
    }
    
    private function getTopMedicines()
    {
        try {
            // Ambil 10 obat paling sering diresepkan berdasarkan TOTAL QUANTITY bukan prescription_count
            $topMedicines = DB::table('prescription_items')
                ->select(
                    'medicine_id',
                    'medicine_name',
                    DB::raw('COUNT(*) as prescription_count'),
                    DB::raw('SUM(quantity) as total_quantity'),
                    DB::raw('SUM(total_price) as total_revenue')
                )
                ->whereNotNull('medicine_name')
                ->where('medicine_name', '!=', '')
                ->groupBy('medicine_id', 'medicine_name')
                ->orderBy('total_quantity', 'desc') // Urutkan berdasarkan total quantity
                ->limit(10)
                ->get();
            
            \Log::info('Top medicines found: ' . $topMedicines->count());
            \Log::info('Top medicines data:', $topMedicines->toArray());
            
            return $topMedicines;
                
        } catch (\Exception $e) {
            \Log::error('Error fetching top medicines: ' . $e->getMessage());
            return collect();
        }
    }
    
    private function getMonthlyData()
    {
        $monthlyPrescriptions = [];
        $monthlyRevenue = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            $monthName = now()->subMonths($i)->translatedFormat('M');
            
            // Resep bulanan
            $monthlyPrescriptions[$monthName] = DB::table('prescriptions')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            // Pendapatan bulanan
            $monthlyRevenue[$monthName] = DB::table('prescriptions')
                ->where('payment_status', 'paid')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->sum('total_price') ?? 0;
        }
        
        return [
            'labels' => array_keys($monthlyPrescriptions),
            'prescriptions' => array_values($monthlyPrescriptions),
            'revenue' => array_values($monthlyRevenue)
        ];
    }
}