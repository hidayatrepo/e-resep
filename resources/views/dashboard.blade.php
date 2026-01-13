@extends('layouts.main')

@section('title', 'Dashboard - E-Resep')
@section('page_title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
  <div>
    <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Dashboard E-Resep</h1>
    <p class="text-secondary text-sm md:text-base">Selamat datang, {{ $user->name ?? 'Pengguna' }}! Berikut ringkasan aktivitas resep elektronik Anda.</p>
  </div>
  <div class="flex items-center gap-2 md:gap-3 ml-auto md:ml-0">
    <button onclick="exportReport()"
      class="hidden flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Laporan</span>
    </button>
    <a href="{{ route('prescriptions.index') }}"
      class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Resep Baru</span>
    </a>
  </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
  
  <!-- Resep Hari Ini -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white hover:shadow-md transition-shadow">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="file-text" class="size-6 text-info"></i>
      </div>
      <p class="font-medium text-secondary">Resep Hari Ini</p>
    </div>
    <div class="flex items-center justify-between">
      <p class="font-bold text-[32px] leading-10">{{ $stats['prescriptions_today'] ?? 0 }}</p>
      <a href="{{ route('prescriptions.index') }}" class="text-primary text-sm hover:underline">Lihat</a>
    </div>
  </div>

  <!-- Pasien Aktif -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white hover:shadow-md transition-shadow">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="users" class="size-6 text-warning"></i>
      </div>
      <p class="font-medium text-secondary">Pasien Aktif (7 hari)</p>
    </div>
    <p class="font-bold text-[32px] leading-10">{{ $stats['active_patients'] ?? 0 }}</p>
  </div>

  <!-- Total Obat Berbeda -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white hover:shadow-md transition-shadow">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="package" class="size-6 text-purple-600"></i>
      </div>
      <p class="font-medium text-secondary">Jenis Obat</p>
    </div>
    <div class="flex items-center justify-between">
      <p class="font-bold text-[32px] leading-10">{{ $stats['total_medicines'] ?? 0 }}</p>
      <span class="text-purple-600 text-sm font-medium">Berbeda</span>
    </div>
  </div>

  <!-- Pembayaran Hari Ini -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white hover:shadow-md transition-shadow">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="credit-card" class="size-6 text-success"></i>
      </div>
      <p class="font-medium text-secondary">Pembayaran Hari Ini</p>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <p class="font-bold text-[32px] leading-10">Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}</p>
        <!-- <p class="text-success text-sm font-medium">{{ $stats['payments_today'] ?? 0 }}</p> -->
      </div>
      <a href="{{ route('payments.index') }}" class="text-primary text-sm hover:underline">Detail</a>
    </div>
  </div>
</div>

<!-- Additional Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8 hidden">
  <!-- Resep Proses -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-3">
      <div class="size-11 bg-yellow-100 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="clock" class="size-6 text-yellow-600"></i>
      </div>
      <div>
        <p class="font-medium text-secondary">Resep Proses</p>
        <p class="font-bold text-2xl">{{ $stats['process_prescriptions'] ?? 0 }}</p>
      </div>
    </div>
  </div>

  <!-- Pembayaran Tertunda -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-3">
      <div class="size-11 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="alert-circle" class="size-6 text-red-600"></i>
      </div>
      <div>
        <p class="font-medium text-secondary">Pembayaran Tertunda</p>
        <p class="font-bold text-2xl">{{ $stats['pending_payments'] ?? 0 }}</p>
      </div>
    </div>
  </div>

  <!-- Total Item Terjual -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-3">
      <div class="size-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="package-check" class="size-6 text-blue-600"></i>
      </div>
      <div>
        <p class="font-medium text-secondary">Item Terjual</p>
        <p class="font-bold text-2xl">{{ $stats['total_items_sold'] ?? 0 }}</p>
      </div>
    </div>
  </div>

  <!-- Total Pendapatan -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-3">
      <div class="size-11 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="dollar-sign" class="size-6 text-green-600"></i>
      </div>
      <div>
        <p class="font-medium text-secondary">Total Pendapatan</p>
        <p class="font-bold text-2xl">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
      </div>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
  <!-- Tren Resep 7 Hari -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-6 bg-white">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
      <h3 class="font-bold text-lg text-foreground">Tren Resep 7 Hari Terakhir</h3>
      <div class="flex items-center gap-2">
        <span class="text-sm text-secondary">Data Real-time dari Database</span>
        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
      </div>
    </div>
    <div class="w-full overflow-x-auto">
      <div class="min-w-[400px] h-[250px] md:h-[300px]">
        <canvas id="prescriptionChart"></canvas>
      </div>
    </div>
    <div class="grid grid-cols-2 gap-4 text-sm">
      <div class="flex items-center gap-2">
        <div class="w-3 h-3 bg-[#0443A8] rounded-full"></div>
        <span class="text-gray-600">Total Resep: {{ array_sum($charts['prescription_data'] ?? []) }}</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-3 h-3 bg-[#10B981] rounded-full"></div>
        <span class="text-gray-600">Total Pembayaran: {{ array_sum($charts['payment_data'] ?? []) }}</span>
      </div>
    </div>
  </div>

  <!-- Distribusi Kategori Obat dari topMedicines -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
      <div>
        <h3 class="font-bold text-lg text-foreground">Distribusi Kategori Obat</h3>
        <p class="text-sm text-gray-500">Berdasarkan 10 obat teratas</p>
      </div>
      <button onclick="refreshChart()"
        class="flex items-center rounded-3xl border border-border py-2 px-4 gap-2 bg-primary/10 hover:bg-primary/20 w-fit cursor-pointer transition-colors">
        <i data-lucide="refresh-cw" class="size-4 text-primary"></i>
        <p class="font-medium text-sm text-primary">Refresh</p>
      </button>
    </div>
    
    @if(isset($medication_categories) && count($medication_categories) > 0)
    <div class="w-full overflow-x-auto">
      <div class="min-w-[400px] h-[250px] md:h-[300px]">
        <canvas id="medicationChart"></canvas>
      </div>
      
      <!-- Legend Table -->
      <div class="mt-6">
        <h4 class="font-medium text-gray-700 mb-3">Detail Distribusi Kategori (berdasarkan total quantity):</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
          @php
            $colorPalette = [
              '#0443A8', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', 
              '#EC4899', '#6366F1', '#EF4444', '#14B8A6', '#F97316'
            ];
            $totalUsage = array_sum($medication_categories);
          @endphp
          @foreach($medication_categories as $category => $totalQuantity)
          @php
            $percentage = $totalUsage > 0 ? round(($totalQuantity / $totalUsage) * 100, 1) : 0;
          @endphp
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex items-center gap-2">
              <div class="size-3 rounded-full" style="background-color: {{ $colorPalette[$loop->index % count($colorPalette)] }}">
              </div>
              <div>
                <span class="text-sm font-medium text-gray-700 block">{{ $category }}</span>
                <span class="text-xs text-gray-500">{{ $percentage }}% dari total</span>
              </div>
            </div>
            <div class="text-right">
              <span class="font-bold text-gray-900 block">{{ $totalQuantity }}</span>
              <span class="text-xs text-gray-500">unit</span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @else
    <div class="text-center py-12">
      <div class="size-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
        <i data-lucide="package-x" class="size-8 text-gray-400"></i>
      </div>
      <p class="text-gray-500 mb-2">Belum ada data kategori obat</p>
      <p class="text-sm text-gray-400">Data akan muncul setelah ada resep</p>
    </div>
    @endif
  </div>
</div>

<!-- Top Medicines Section -->
@if(isset($top_medicines) && $top_medicines->count() > 0)
<div class="rounded-2xl border border-border p-6 bg-white mb-6 md:mb-8">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
    <h3 class="font-bold text-lg text-foreground">Obat Paling Sering Diresepkan</h3>
    <span class="text-sm text-gray-500">Top 10 berdasarkan total quantity</span>
  </div>
  
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-border">
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">#</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Nama Obat</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Jumlah Resep</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Total Quantity</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Total Revenue</th>
        </tr>
      </thead>
      <tbody>
        @foreach($top_medicines as $index => $medicine)
        <tr class="border-b border-border hover:bg-gray-50 transition-colors">
          <td class="py-3 px-4">
            <div class="flex items-center justify-center size-8 bg-gray-100 rounded-full">
              <span class="font-semibold text-gray-700">{{ $index + 1 }}</span>
            </div>
          </td>
          <td class="py-3 px-4">
            <div class="flex flex-col">
              <span class="font-medium text-gray-900">{{ $medicine->medicine_name }}</span>
              <span class="text-xs text-gray-500">ID: {{ Str::limit($medicine->medicine_id, 10) }}</span>
            </div>
          </td>
          <td class="py-3 px-4">
            <div class="flex items-center gap-2">
              <span class="font-semibold text-gray-900">{{ $medicine->prescription_count }}</span>
              <span class="text-xs text-gray-500">kali</span>
            </div>
          </td>
          <td class="py-3 px-4">
            <div class="flex items-center gap-2">
              <span class="font-semibold text-gray-900">{{ $medicine->total_quantity }}</span>
              <span class="text-xs text-gray-500">unit</span>
            </div>
          </td>
          <td class="py-3 px-4">
            <div class="flex items-center gap-2">
              <span class="font-semibold text-green-600">Rp {{ number_format($medicine->total_revenue ?? 0, 0, ',', '.') }}</span>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

<!-- Recent Activity -->
<div class="rounded-2xl border border-border p-6 bg-white">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
    <h3 class="font-bold text-lg text-foreground">Aktivitas Terbaru</h3>
    <a href="{{ route('prescriptions.index') }}" class="text-primary text-sm hover:underline flex items-center gap-1">
      Lihat Semua
      <i data-lucide="arrow-right" class="size-4"></i>
    </a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-border">
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Waktu</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">No. Resep</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Pasien</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Status</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Pembayaran</th>
          <th class="text-left py-3 px-4 text-sm font-medium text-secondary">Total</th>
        </tr>
      </thead>
      <tbody>
        @php
          $recentPrescriptions = DB::table('prescriptions')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        @endphp
        
        @forelse($recentPrescriptions as $prescription)
        <tr class="border-b border-border hover:bg-gray-50 transition-colors">
          <td class="py-3 px-4 text-sm">
            <div class="flex flex-col">
              <span class="text-gray-900">{{ \Carbon\Carbon::parse($prescription->created_at)->format('H:i') }}</span>
              <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m') }}</span>
            </div>
          </td>
          <td class="py-3 px-4 text-sm font-medium">
            <span class="text-primary">{{ $prescription->prescription_number }}</span>
          </td>
          <td class="py-3 px-4 text-sm">
            <div class="flex flex-col">
              <span class="text-gray-900">{{ $prescription->patient_name }}</span>
              <span class="text-xs text-gray-500">{{ $prescription->doctor_name }}</span>
            </div>
          </td>
          <td class="py-3 px-4">
            @php
              $statusColors = [
                'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                'process' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800']
              ];
              $statusConfig = $statusColors[$prescription->status] ?? $statusColors['draft'];
            @endphp
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
              {{ ucfirst($prescription->status) }}
            </span>
          </td>
          <td class="py-3 px-4">
            @php
              $paymentColors = [
                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800']
              ];
              $paymentConfig = $paymentColors[$prescription->payment_status ?? 'pending'] ?? $paymentColors['pending'];
            @endphp
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $paymentConfig['bg'] }} {{ $paymentConfig['text'] }}">
              {{ ucfirst($prescription->payment_status ?? 'pending') }}
            </span>
          </td>
          <td class="py-3 px-4 text-sm font-medium">
            Rp {{ number_format($prescription->total_price, 0, ',', '.') }}
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="py-8 text-center text-gray-500">
            <div class="flex flex-col items-center gap-2">
              <i data-lucide="inbox" class="size-8 text-gray-300"></i>
              <p>Tidak ada aktivitas terbaru</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Data from controller
  const chartData = @json($charts);
  const medicationCategories = @json($medication_categories ?: []);
  
  // Initialize charts function
  function initializeCharts() {
    // Prescription Trend Chart
    const prescriptionCtx = document.getElementById('prescriptionChart');
    if (prescriptionCtx && typeof Chart !== 'undefined') {
      new Chart(prescriptionCtx, {
        type: 'line',
        data: {
          labels: chartData.last_7_days || ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
          datasets: [
            {
              label: 'Resep',
              data: chartData.prescription_data || [0,0,0,0,0,0,0],
              borderColor: '#0443A8',
              backgroundColor: 'rgba(4, 67, 168, 0.1)',
              fill: true,
              tension: 0.4,
              borderWidth: 2
            },
            {
              label: 'Pembayaran',
              data: chartData.payment_data || [0,0,0,0,0,0,0],
              borderColor: '#10B981',
              backgroundColor: 'rgba(16, 185, 129, 0.1)',
              fill: true,
              tension: 0.4,
              borderWidth: 2
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 20,
                boxWidth: 12
              }
            },
            tooltip: {
              mode: 'index',
              intersect: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah'
              },
              ticks: {
                precision: 0
              }
            }
          },
          interaction: {
            intersect: false,
            mode: 'index'
          }
        }
      });
    }

    // Medication Categories Chart (if data exists)
    const medicationCtx = document.getElementById('medicationChart');
    if (medicationCtx && typeof Chart !== 'undefined' && medicationCategories && Object.keys(medicationCategories).length > 0) {
      const labels = Object.keys(medicationCategories);
      const data = Object.values(medicationCategories);
      
      // Generate dynamic colors
      const colorPalette = [
        '#0443A8', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6',
        '#EC4899', '#6366F1', '#EF4444', '#14B8A6', '#F97316'
      ];
      
      const backgroundColors = labels.map((_, index) => 
        colorPalette[index % colorPalette.length]
      );
      
      new Chart(medicationCtx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: data,
            backgroundColor: backgroundColors,
            borderWidth: 1,
            borderColor: '#fff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
              labels: {
                boxWidth: 12,
                padding: 15,
                font: {
                  size: 11
                }
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.raw || 0;
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = Math.round((value / total) * 100);
                  return `${label}: ${value} unit (${percentage}%)`;
                }
              }
            }
          },
          cutout: '65%'
        }
      });
    }
  }

  function exportReport() {
    const today = new Date().toISOString().split('T')[0];
    const filename = `laporan-dashboard-${today}.pdf`;
    
    // In production, use a proper PDF generation library like dompdf or TCPDF
    // For now, show a notification
    showNotification('info', `Laporan ${filename} akan di-generate. Fitur ekspor PDF masih dalam pengembangan.`);
  }

  function refreshChart() {
    // Show loading indicator
    const button = event?.target?.closest('button');
    if (button) {
      const originalHTML = button.innerHTML;
      button.innerHTML = '<i data-lucide="loader-2" class="size-4 text-primary animate-spin"></i><span class="font-medium text-sm text-primary">Loading...</span>';
      button.disabled = true;
    }
    
    // Reload page after a short delay
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  }

  function showNotification(type, message) {
    // Simple notification function
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg border-l-4 ${type === 'info' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-green-50 border-green-500 text-green-700'}`;
    notification.innerHTML = `
      <div class="flex items-center gap-2">
        <i data-lucide="${type === 'info' ? 'info' : 'check-circle'}" class="size-5"></i>
        <span>${message}</span>
      </div>
    `;
    document.body.appendChild(notification);
    
    // Add lucide icons
    lucide.createIcons();
    
    // Auto remove after 3 seconds
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }

  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    initializeCharts();
    
    // Auto-refresh data every 2 minutes (120000 ms)
    const refreshInterval = 120000;
    setInterval(() => {
      const shouldRefresh = confirm('Refresh data dashboard? Data akan diperbarui otomatis.');
      if (shouldRefresh) {
        refreshChart();
      }
    }, refreshInterval);
  });

  // Expose functions to global scope
  window.initializeCharts = initializeCharts;
  window.exportReport = exportReport;
  window.refreshChart = refreshChart;
</script>
@endpush
@endsection