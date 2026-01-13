@extends('layouts.main')

@section('title', 'Laporan - E-Resep')
@section('page_title', 'Laporan')

@section('content')
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
    <div>
      <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Laporan</h1>
      <p class="text-secondary text-sm md:text-base">Lihat laporan lengkap resep, pembayaran, dan data pasien.</p>
    </div>
    <div class="flex items-center gap-2 md:gap-3 ml-auto md:ml-0">
      <!-- Date Filter -->
      <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
        <input type="date" name="start_date" value="{{ $startDate }}"
          class="px-3 py-2 border border-border rounded-lg text-sm">
        <span class="text-secondary">s/d</span>
        <input type="date" name="end_date" value="{{ $endDate }}"
          class="px-3 py-2 border border-border rounded-lg text-sm">

        <button type="submit"
          class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-2 ring-1 ring-primary text-primary rounded-full font-medium hover:bg-primary hover:text-white transition-all md:basis-1/10">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search"
            class="lucide lucide-search w-4 h-4">
            <path d="m21 21-4.34-4.34"></path>
            <circle cx="11" cy="11" r="8"></circle>
          </svg>
          <span>Cari</span>
        </button>

      </form>

      <button onclick="exportReport()"
        class="hidden flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
        <i data-lucide="download" class="w-4 h-4"></i>
        <span>Ekspor PDF</span>
      </button>
      <button onclick="window.print()"
        class="hidden flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
        <i data-lucide="printer" class="w-4 h-4"></i>
        <span>Cetak</span>
      </button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
    <!-- Total Pasien -->
    <div class="rounded-2xl border border-border p-6 bg-white">
      <div class="flex items-center gap-3 mb-4">
        <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
          <i data-lucide="users" class="size-6 text-primary"></i>
        </div>
        <p class="text-sm text-secondary">Total Pasien</p>
      </div>
      <p class="text-3xl font-bold text-foreground">{{ number_format($totalPatients) }}</p>
      <p class="text-xs mt-2 {{ $patientDiff >= 0 ? 'text-success' : 'text-error' }}">
        {{ $patientDiff >= 0 ? '+' : '' }}{{ $patientDiff }} pasien bulan ini
      </p>
    </div>

    <!-- Total Resep -->
    <div class="rounded-2xl border border-border p-6 bg-white">
      <div class="flex items-center gap-3 mb-4">
        <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
          <i data-lucide="file-text" class="size-6 text-info"></i>
        </div>
        <p class="text-sm text-secondary">Total Resep</p>
      </div>
      <p class="text-3xl font-bold text-foreground">{{ number_format($totalPrescriptions) }}</p>
      <p class="text-xs mt-2 {{ $prescriptionDiff >= 0 ? 'text-info' : 'text-error' }}">
        {{ $prescriptionDiff >= 0 ? '+' : '' }}{{ $prescriptionDiff }} resep bulan ini
      </p>
    </div>

    <!-- Obat Terjual -->
    <div class="rounded-2xl border border-border p-6 bg-white">
      <div class="flex items-center gap-3 mb-4">
        <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
          <i data-lucide="package" class="size-6 text-warning-dark"></i>
        </div>
        <p class="text-sm text-secondary">Jenis Obat Terjual</p>
      </div>
      <p class="text-3xl font-bold text-foreground">{{ number_format($totalMedicineTypes) }}</p>
      <p class="text-xs mt-2 {{ $medicineTypesDiff >= 0 ? 'text-warning-dark' : 'text-error' }}">
        {{ $medicineTypesDiff >= 0 ? '+' : '' }}{{ $medicineTypesDiff }} jenis obat bulan ini
      </p>
    </div>


    <!-- Total Pembayaran -->
    <div class="rounded-2xl border border-border p-6 bg-white">
      <div class="flex items-center gap-3 mb-4">
        <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
          <i data-lucide="credit-card" class="size-6 text-success"></i>
        </div>
        <p class="text-sm text-secondary">Total Pembayaran</p>
      </div>
      <p class="text-3xl font-bold text-foreground">Rp {{ number_format($totalPayment, 0, ',', '.') }}</p>
      <p class="text-xs mt-2 {{ $paymentDiff >= 0 ? 'text-success' : 'text-error' }}">
        {{ $paymentDiff >= 0 ? '+' : '' }}Rp {{ number_format($paymentDiff, 2) }} juta bulan ini
      </p>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="flex gap-2 mb-6 border-b border-border">
    <button class="px-4 py-3 font-medium text-foreground border-b-2 border-primary transition-all">
      Resep
    </button>
    <button class="px-4 py-3 font-medium text-secondary hover:text-foreground transition-all hidden">
      Pembayaran
    </button>
    <button class="px-4 py-3 font-medium text-secondary hover:text-foreground transition-all hidden">
      Pasien
    </button>
  </div>

  <!-- Resep Report Table -->
  <div class="rounded-2xl border border-border overflow-hidden bg-white mb-6 md:mb-8">
    <div class="px-6 py-4 border-b border-border bg-muted flex justify-between items-center">
      <h3 class="font-semibold text-foreground">Laporan Resep ({{ $currentMonth }})</h3>
      <p class="text-sm text-secondary">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    @if($prescriptions->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-border bg-gray-50">
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary">No. Resep</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary">Nama Pasien</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary">Dokter</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary">Total Obat</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-secondary">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($prescriptions as $prescription)
              <tr class="border-b border-border hover:bg-gray-50">
                <td class="px-6 py-3 text-sm text-foreground">{{ $prescription->prescription_number }}</td>
                <td class="px-6 py-3 text-sm text-secondary">{{ $prescription->patient_name }}</td>
                <td class="px-6 py-3 text-sm text-secondary">{{ $prescription->examination_date_formatted }}</td>
                <td class="px-6 py-3 text-sm text-secondary">{{ $prescription->doctor_name }}</td>
                <td class="px-6 py-3 text-sm text-secondary">{{ $prescription->total_medicines }}</td>
                <td class="px-6 py-3 text-sm text-right">
                  @php
                    $statusConfig = [
                      'draft' => ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'edit'],
                      'process' => ['color' => 'bg-info-light text-info-dark', 'icon' => 'clock'],
                      'completed' => ['color' => 'bg-success-light text-success-dark', 'icon' => 'check-circle'],
                      'cancelled' => ['color' => 'bg-error-light text-error-dark', 'icon' => 'x-circle']
                    ];
                    $config = $statusConfig[$prescription->status] ?? $statusConfig['draft'];
                  @endphp
                  <span
                    class="inline-flex items-center gap-1 px-2 py-1 rounded-full {{ $config['color'] }} text-xs font-medium">
                    <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                    {{ ucfirst($prescription->status) }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="px-6 py-4 border-t border-border bg-gray-50">
        @php
          $completed = $prescriptionStats['completed'] ?? 0;
          $process = $prescriptionStats['process'] ?? 0;
          $draft = $prescriptionStats['draft'] ?? 0;
          $cancelled = $prescriptionStats['cancelled'] ?? 0;
        @endphp
        <p class="text-sm text-secondary">
          Total: <span class="font-semibold text-foreground">{{ number_format($totalPrescriptions) }} resep</span> dengan
          <span class="font-semibold text-success-dark">{{ number_format($completed) }} selesai</span>,
          <span class="font-semibold text-info-dark">{{ number_format($process) }} proses</span>,
          <span class="font-semibold text-secondary">{{ number_format($draft) }} draft</span>,
          <span class="font-semibold text-error-dark">{{ number_format($cancelled) }} dibatalkan</span>
        </p>
      </div>
    @else
      <div class="p-8 text-center">
        <i data-lucide="file-search" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
        <p class="text-gray-500">Tidak ada data resep untuk periode yang dipilih.</p>
      </div>
    @endif
  </div>

  <!-- Chart Section -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
    <!-- Resep Trend Chart -->
    <div class="rounded-2xl border border-border p-6 bg-white">
      <h3 class="font-semibold text-foreground mb-4">Tren Resep (12 Bulan Terakhir)</h3>
      <div class="h-[300px]">
        <canvas id="resepTrendChart"></canvas>
      </div>
    </div>

    <!-- Pembayaran Trend Chart -->
    <div class="rounded-2xl border border-border p-6 bg-white">
      <h3 class="font-semibold text-foreground mb-4">Tren Pembayaran (12 Bulan Terakhir)</h3>
      <div class="h-[300px]">
        <canvas id="paymentTrendChart"></canvas>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function initializeReportCharts() {
        // Data dari controller
        const chartData = @json($chartData);

        // Resep Trend Chart
        const resepCtx = document.getElementById('resepTrendChart');
        if (resepCtx && typeof Chart !== 'undefined') {
          new Chart(resepCtx, {
            type: 'bar',
            data: {
              labels: chartData.labels,
              datasets: [{
                label: 'Jumlah Resep',
                data: chartData.prescription_trend,
                backgroundColor: '#0443A8',
                borderColor: '#0443A8',
                borderWidth: 1
              }]
            },
            options: {
              animation: false,
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: 'bottom'
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      return `Resep: ${context.parsed.y}`;
                    }
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    callback: function (value) {
                      return value.toLocaleString('id-ID');
                    }
                  }
                }
              }
            }
          });
        }

        // Payment Trend Chart
        const paymentCtx = document.getElementById('paymentTrendChart');
        if (paymentCtx && typeof Chart !== 'undefined') {
          new Chart(paymentCtx, {
            type: 'line',
            data: {
              labels: chartData.labels,
              datasets: [{
                label: 'Total Pembayaran (Juta)',
                data: chartData.payment_trend,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
              }]
            },
            options: {
              animation: false,
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: 'bottom'
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      return `Rp ${context.parsed.y.toLocaleString('id-ID', { minimumFractionDigits: 2 })} juta`;
                    }
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    callback: function (value) {
                      return `Rp ${value.toLocaleString('id-ID', { minimumFractionDigits: 2 })}`;
                    }
                  }
                }
              }
            }
          });
        }
      }

      function exportReport() {
        // Implementasi ekspor PDF
        fetch('{{ route("reports.export") }}?start_date={{ $startDate }}&end_date={{ $endDate }}')
          .then(response => response.json())
          .then(data => {
            alert('Fitur ekspor PDF akan segera tersedia.');
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengekspor laporan.');
          });
      }

      document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
        initializeReportCharts();
      });
    </script>
  @endpush
@endsection