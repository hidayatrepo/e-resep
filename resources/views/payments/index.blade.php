@extends('layouts.main')

@section('title', 'Pembayaran - E-Resep')
@section('page_title', 'Manajemen Pembayaran')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
  <div>
    <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Data Pembayaran</h1>
    <p class="text-secondary text-sm md:text-base">Kelola pembayaran resep dan cetak resi pembayaran.</p>
  </div>
  <div class="flex items-center gap-2 md:gap-3 ml-auto md:ml-0">
    <button
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Data</span>
    </button>
    <button
      class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Pembayaran Baru</span>
    </button>
  </div>
</div>

<!-- Search & Filter -->
<div class="flex flex-col md:flex-row gap-3 mb-6 md:mb-8">
  <div class="flex-1 relative">
    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary w-5 h-5"></i>
    <input type="text" placeholder="Cari pembayaran berdasarkan nama pasien atau no. resep..." 
      class="w-full pl-12 pr-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
  </div>
  <select class="px-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
    <option>Semua Status</option>
    <option>Belum Dibayar</option>
    <option>Sudah Dibayar</option>
    <option>Dibatalkan</option>
  </select>
</div>

<!-- Payments Table -->
<div class="rounded-2xl border border-border overflow-hidden bg-white">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-border bg-muted">
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">No. Resep</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Nama Pasien</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal</th>
          <th class="px-6 py-4 text-right text-sm font-semibold text-foreground">Total Biaya</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status</th>
          <th class="px-6 py-4 text-center text-sm font-semibold text-foreground">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- Sample Row 1 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-001</td>
          <td class="px-6 py-4 text-sm text-secondary">Budi Santoso</td>
          <td class="px-6 py-4 text-sm text-secondary">10 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-foreground font-medium text-right">Rp 125.000</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              Sudah Dibayar
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat detail">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Cetak Resi PDF">
                <i data-lucide="file-pdf" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Sample Row 2 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-002</td>
          <td class="px-6 py-4 text-sm text-secondary">Siti Nurhaliza</td>
          <td class="px-6 py-4 text-sm text-secondary">09 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-foreground font-medium text-right">Rp 85.000</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warning-light text-warning-dark text-xs font-medium">
              <i data-lucide="clock" class="w-4 h-4"></i>
              Belum Dibayar
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat detail">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Sample Row 3 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-003</td>
          <td class="px-6 py-4 text-sm text-secondary">Ahmad Wijaya</td>
          <td class="px-6 py-4 text-sm text-secondary">08 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-foreground font-medium text-right">Rp 215.500</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              Sudah Dibayar
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat detail">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Cetak Resi PDF">
                <i data-lucide="file-pdf" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Sample Row 4 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-004</td>
          <td class="px-6 py-4 text-sm text-secondary">Rini Wijayanti</td>
          <td class="px-6 py-4 text-sm text-secondary">07 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-foreground font-medium text-right">Rp 95.000</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-error-light text-error-dark text-xs font-medium">
              <i data-lucide="x-circle" class="w-4 h-4"></i>
              Dibatalkan
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat detail">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex items-center justify-between px-6 py-4 border-t border-border">
    <p class="text-sm text-secondary">Menampilkan 1 sampai 4 dari 24 pembayaran</p>
    <div class="flex items-center gap-2">
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all disabled:opacity-50" disabled>
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
      </button>
      <button class="px-3 py-2 rounded-lg bg-primary text-white">1</button>
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">2</button>
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">3</button>
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
      </button>
    </div>
  </div>
</div>

<!-- Summary Card -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i>
      <p class="text-sm text-secondary">Total Pembayaran Hari Ini</p>
    </div>
    <p class="text-2xl font-bold text-foreground">Rp 425.500</p>
  </div>
  
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="clock" class="w-5 h-5 text-warning"></i>
      <p class="text-sm text-secondary">Menunggu Pembayaran</p>
    </div>
    <p class="text-2xl font-bold text-warning-dark">3 Transaksi</p>
  </div>
  
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
      <p class="text-sm text-secondary">Sudah Dibayar</p>
    </div>
    <p class="text-2xl font-bold text-success">18 Transaksi</p>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
  });
</script>
@endpush
@endsection
