@extends('layouts.main')

@section('title', 'E-Resep - E-Resep')
@section('page_title', 'Manajemen E-Resep')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
  <div>
    <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Data E-Resep</h1>
    <p class="text-secondary text-sm md:text-base">Kelola resep elektronik dan riwayat pengambilan obat.</p>
  </div>
  <div class="flex items-center gap-2 md:gap-3 ml-auto md:ml-0">
    <button
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Laporan</span>
    </button>
    <button
      class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Resep Baru</span>
    </button>
  </div>
</div>

<!-- Search & Filter -->
<div class="flex flex-col md:flex-row gap-3 mb-6 md:mb-8">
  <div class="flex-1 relative">
    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary w-5 h-5"></i>
    <input type="text" placeholder="Cari resep berdasarkan nama pasien atau no. resep..." 
      class="w-full pl-12 pr-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
  </div>
  <select class="px-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
    <option>Semua Status</option>
    <option>Proses</option>
    <option>Selesai</option>
    <option>Dibatalkan</option>
  </select>
</div>

<!-- Prescriptions Table -->
<div class="rounded-2xl border border-border overflow-hidden bg-white">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-border bg-muted">
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">No. Resep</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Nama Pasien</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Obat</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status</th>
          <th class="px-6 py-4 text-center text-sm font-semibold text-foreground">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- Sample Row -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-001</td>
          <td class="px-6 py-4 text-sm text-secondary">Budi Santoso</td>
          <td class="px-6 py-4 text-sm text-secondary">10 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">Amoxicillin 500mg, Paracetamol</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-info-light text-info-dark text-xs font-medium">
              <i data-lucide="clock" class="w-4 h-4"></i>
              Proses
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-002</td>
          <td class="px-6 py-4 text-sm text-secondary">Siti Nurhaliza</td>
          <td class="px-6 py-4 text-sm text-secondary">09 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">Metformin 500mg</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              Selesai
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-003</td>
          <td class="px-6 py-4 text-sm text-secondary">Ahmad Wijaya</td>
          <td class="px-6 py-4 text-sm text-secondary">08 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">Aspirin 100mg, Warfarin 5mg</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-error-light text-error-dark text-xs font-medium">
              <i data-lucide="x-circle" class="w-4 h-4"></i>
              Dibatalkan
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex items-center justify-between px-6 py-4 border-t border-border">
    <p class="text-sm text-secondary">Menampilkan 1 sampai 3 dari 24 resep</p>
    <div class="flex items-center gap-2">
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all disabled:opacity-50" disabled>
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
      </button>
      <button class="px-3 py-2 rounded-lg bg-primary text-white">1</button>
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">2</button>
      <button class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
      </button>
    </div>
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
