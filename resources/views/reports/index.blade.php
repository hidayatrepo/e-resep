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
    <button
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor PDF</span>
    </button>
    <button
      class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
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
    <p class="text-3xl font-bold text-foreground">142</p>
    <p class="text-xs text-success mt-2">+5 pasien baru bulan ini</p>
  </div>

  <!-- Total Resep -->
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-4">
      <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="file-text" class="size-6 text-info"></i>
      </div>
      <p class="text-sm text-secondary">Total Resep</p>
    </div>
    <p class="text-3xl font-bold text-foreground">487</p>
    <p class="text-xs text-info mt-2">+48 resep bulan ini</p>
  </div>

  <!-- Total Pembayaran -->
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-4">
      <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="credit-card" class="size-6 text-success"></i>
      </div>
      <p class="text-sm text-secondary">Total Pembayaran</p>
    </div>
    <p class="text-3xl font-bold text-foreground">Rp 12.5M</p>
    <p class="text-xs text-success mt-2">+Rp 1.8M bulan ini</p>
  </div>

  <!-- Obat Terjual -->
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-4">
      <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="package" class="size-6 text-warning-dark"></i>
      </div>
      <p class="text-sm text-secondary">Jenis Obat Terjual</p>
    </div>
    <p class="text-3xl font-bold text-foreground">156</p>
    <p class="text-xs text-warning-dark mt-2">+12 jenis obat bulan ini</p>
  </div>
</div>

<!-- Tab Navigation -->
<div class="flex gap-2 mb-6 border-b border-border">
  <button class="px-4 py-3 font-medium text-foreground border-b-2 border-primary transition-all">
    Resep
  </button>
  <button class="px-4 py-3 font-medium text-secondary hover:text-foreground transition-all">
    Pembayaran
  </button>
  <button class="px-4 py-3 font-medium text-secondary hover:text-foreground transition-all">
    Pasien
  </button>
</div>

<!-- Resep Report Table -->
<div class="rounded-2xl border border-border overflow-hidden bg-white mb-6 md:mb-8">
  <div class="px-6 py-4 border-b border-border bg-muted">
    <h3 class="font-semibold text-foreground">Laporan Resep (Bulan Januari 2026)</h3>
  </div>
  
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
        <tr class="border-b border-border hover:bg-gray-50">
          <td class="px-6 py-3 text-sm text-foreground">RX-2026-001</td>
          <td class="px-6 py-3 text-sm text-secondary">Budi Santoso</td>
          <td class="px-6 py-3 text-sm text-secondary">10 Jan 2026</td>
          <td class="px-6 py-3 text-sm text-secondary">Dr. Sarah Johnson</td>
          <td class="px-6 py-3 text-sm text-secondary">3</td>
          <td class="px-6 py-3 text-sm text-right">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-3 h-3"></i>
              Selesai
            </span>
          </td>
        </tr>
        <tr class="border-b border-border hover:bg-gray-50">
          <td class="px-6 py-3 text-sm text-foreground">RX-2026-002</td>
          <td class="px-6 py-3 text-sm text-secondary">Siti Nurhaliza</td>
          <td class="px-6 py-3 text-sm text-secondary">09 Jan 2026</td>
          <td class="px-6 py-3 text-sm text-secondary">Dr. Sarah Johnson</td>
          <td class="px-6 py-3 text-sm text-secondary">1</td>
          <td class="px-6 py-3 text-sm text-right">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-3 h-3"></i>
              Selesai
            </span>
          </td>
        </tr>
        <tr class="border-b border-border hover:bg-gray-50">
          <td class="px-6 py-3 text-sm text-foreground">RX-2026-003</td>
          <td class="px-6 py-3 text-sm text-secondary">Ahmad Wijaya</td>
          <td class="px-6 py-3 text-sm text-secondary">08 Jan 2026</td>
          <td class="px-6 py-3 text-sm text-secondary">Dr. James Miller</td>
          <td class="px-6 py-3 text-sm text-secondary">2</td>
          <td class="px-6 py-3 text-sm text-right">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-info-light text-info-dark text-xs font-medium">
              <i data-lucide="clock" class="w-3 h-3"></i>
              Proses
            </span>
          </td>
        </tr>
        <tr class="border-b border-border hover:bg-gray-50">
          <td class="px-6 py-3 text-sm text-foreground">RX-2026-004</td>
          <td class="px-6 py-3 text-sm text-secondary">Rini Wijayanti</td>
          <td class="px-6 py-3 text-sm text-secondary">07 Jan 2026</td>
          <td class="px-6 py-3 text-sm text-secondary">Dr. Sarah Johnson</td>
          <td class="px-6 py-3 text-sm text-secondary">4</td>
          <td class="px-6 py-3 text-sm text-right">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-3 h-3"></i>
              Selesai
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="px-6 py-4 border-t border-border bg-gray-50">
    <p class="text-sm text-secondary">Total: <span class="font-semibold text-foreground">487 resep</span> dengan <span class="font-semibold text-foreground">456 selesai</span> dan <span class="font-semibold text-foreground">31 proses</span></p>
  </div>
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
    // Resep Trend Chart
    const resepCtx = document.getElementById('resepTrendChart');
    if (resepCtx && typeof Chart !== 'undefined') {
      new Chart(resepCtx, {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
          datasets: [{
            label: 'Jumlah Resep',
            data: [32, 45, 38, 52, 41, 48, 55, 49, 43, 39, 42, 38],
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
            }
          },
          scales: {
            y: {
              beginAtZero: true
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
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
          datasets: [{
            label: 'Total Pembayaran (Juta)',
            data: [0.8, 1.2, 1.0, 1.5, 1.1, 1.3, 1.6, 1.4, 1.2, 1.1, 1.3, 1.0],
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
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    initializeReportCharts();
  });
</script>
@endpush
@endsection
