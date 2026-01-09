@extends('layouts.main')

@section('title', 'Dashboard - E-Resep')
@section('page_title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
  <div>
    <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Dashboard E-Resep</h1>
    <p class="text-secondary text-sm md:text-base">Selamat datang! Berikut ringkasan aktivitas resep
      elektronik Anda hari ini.</p>
  </div>
  <div class="flex items-center gap-2 md:gap-3 ml-auto md:ml-0">
    <button
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Laporan</span>
    </button>
    <button onclick="alert('Fitur buat resep baru belum tersedia.')"
      class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Resep Baru</span>
    </button>
  </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
  
  <!-- Resep Hari Ini -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="file-text" class="size-6 text-info"></i>
      </div>
      <p class="font-medium text-secondary">Resep Hari Ini</p>
    </div>
    <p class="font-bold text-[32px] leading-10">24</p>
  </div>

  <!-- Pasien Aktif -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="users" class="size-6 text-warning"></i>
      </div>
      <p class="font-medium text-secondary">Pasien Aktif</p>
    </div>
    <p class="font-bold text-[32px] leading-10">142</p>
  </div>

  <!-- Stok Obat Rendah -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-[6px]">
      <!-- Versi dengan warna danger/red -->
      <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="package" class="size-6 text-primary/50"></i>
      </div>
      <p class="font-medium text-secondary">Stok Obat</p>
    </div>
    <div class="flex items-center gap-3">
      <p class="font-bold text-[32px] leading-10">7</p>
    </div>
  </div>

  <!-- Pembayaran Hari Ini -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
    <div class="flex items-center gap-[6px]">
      <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
        <i data-lucide="credit-card" class="size-6 text-success"></i>
      </div>
      <p class="font-medium text-secondary">Pembayaran Hari Ini</p>
    </div>
    <div class="flex items-center gap-3">
      <p class="font-bold text-[32px] leading-10">18</p>
      <span class="text-success text-sm font-semibold">+3</span>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
  <!-- Tren Resep -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-6 bg-white">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
      <h3 class="font-bold text-lg text-foreground">Grafik Resep</h3>
      <button
        class="flex items-center rounded-3xl border border-border py-2 px-4 gap-2 bg-primary/10 w-fit cursor-pointer">
        {{-- <i data-lucide="pie-chart" class="size-4 text-primary"></i> --}}
        <p class="font-medium text-sm text-primary">2026</p>
      </button>
    </div>
    <div class="w-full overflow-x-auto">
      <div class="min-w-[400px] h-[250px] md:h-[300px]">
        <canvas id="prescriptionChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Kategori Obat -->
  <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
      <h3 class="font-bold text-lg text-foreground">Kategori Obat</h3>
      <button
        class="flex items-center rounded-3xl border border-border py-2 px-4 gap-2 bg-primary/10 w-fit cursor-pointer">
        <i data-lucide="pie-chart" class="size-4 text-primary"></i>
        <p class="font-medium text-sm text-primary">Berdasarkan Kategori</p>
      </button>
    </div>
    <div class="w-full overflow-x-auto">
      <div class="min-w-[400px] h-[250px] md:h-[300px]">
        <canvas id="medicationChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Alerts Section -->
<div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white" style="display: none">
  <h3 class="font-bold text-lg text-foreground">Peringatan Medis</h3>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="p-4 bg-error-light rounded-card border-l-4 border-error">
      <div class="flex items-start gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-error-dark flex-shrink-0 mt-0.5"></i>
        <div>
          <h4 class="text-foreground text-sm font-medium">Interaksi Obat</h4>
          <p class="text-gray-500 text-xs mt-1">Interaksi Warfarin dan Amiodarone terdeteksi untuk pasien #124
          </p>
        </div>
      </div>
    </div>
    <div class="p-4 bg-warning-light rounded-card border-l-4 border-warning">
      <div class="flex items-start gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-warning-dark flex-shrink-0 mt-0.5"></i>
        <div>
          <h4 class="text-foreground text-sm font-medium">Peringatan Alergi</h4>
          <p class="text-gray-500 text-xs mt-1">Pasien #087 alergi penisilin - Amoxicillin diresepkan</p>
        </div>
      </div>
    </div>
    <div class="p-4 bg-info-light rounded-card border-l-4 border-info">
      <div class="flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 text-info-dark flex-shrink-0 mt-0.5"></i>
        <div>
          <h4 class="text-foreground text-sm font-medium">Update Stok</h4>
          <p class="text-gray-500 text-xs mt-1">Metformin 500mg hampir habis - Disarankan pesan ulang</p>
        </div>
      </div>
    </div>
    <div class="p-4 bg-success-light rounded-card border-l-4 border-success">
      <div class="flex items-start gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-success-dark flex-shrink-0 mt-0.5"></i>
        <div>
          <h4 class="text-foreground text-sm font-medium">Resep Terverifikasi</h4>
          <p class="text-gray-500 text-xs mt-1">Semua tanda tangan elektronik terverifikasi untuk resep hari
            ini</p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Initialize charts function
  function initializeCharts() {
    // Prescription Trend Chart
    const prescriptionCtx = document.getElementById('prescriptionChart');
    if (prescriptionCtx && typeof Chart !== 'undefined') {
      new Chart(prescriptionCtx, {
        type: 'line',
        data: {
          labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
          datasets: [
            {
              label: 'Minggu Ini',
              data: [18, 22, 20, 25, 24, 15, 10],
              borderColor: '#0443A8',
              backgroundColor: 'rgba(4, 67, 168, 0.1)',
              fill: true,
              tension: 0.4
            },
            {
              label: 'Minggu Lalu',
              data: [15, 20, 18, 22, 21, 12, 8],
              borderColor: '#93C5FD',
              backgroundColor: 'rgba(147, 197, 253, 0.1)',
              fill: true,
              tension: 0.4
            }
          ]
        },
        options: {
          animation: false,
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah Resep'
              }
            }
          }
        }
      });
    }

    // Medication Categories Chart
    const medicationCtx = document.getElementById('medicationChart');
    if (medicationCtx && typeof Chart !== 'undefined') {
      new Chart(medicationCtx, {
        type: 'doughnut',
        data: {
          labels: ['Antibiotik', 'Kardiovaskular', 'Diabetes', 'Analgesik'],
          datasets: [{
            data: [35, 28, 22, 15],
            backgroundColor: ['#0443A8', '#3B82F6', '#10B981', '#F59E0B']
          }]
        },
        options: {
          animation: false,
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    initializeCharts();
  });

  // Expose functions to global scope
  window.initializeCharts = initializeCharts;
</script>
@endpush
@endsection
