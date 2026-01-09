@extends('layouts.main')

@section('title', 'Pemeriksaan Pasien - E-Resep')
@section('page_title', 'Pemeriksaan Pasien')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
  <div>
    <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Data Pemeriksaan Pasien</h1>
    <p class="text-secondary text-sm md:text-base">Kelola hasil pemeriksaan dan tanda-tanda vital pasien.</p>
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
      <span>Pemeriksaan Baru</span>
    </button>
  </div>
</div>

<!-- Search & Filter -->
<div class="flex flex-col md:flex-row gap-3 mb-6 md:mb-8">
  <div class="flex-1 relative">
    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary w-5 h-5"></i>
    <input type="text" placeholder="Cari pasien berdasarkan nama atau no. identitas..." 
      class="w-full pl-12 pr-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
  </div>
  <select class="px-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
    <option>Semua Tanggal</option>
    <option>Hari Ini</option>
    <option>Minggu Ini</option>
    <option>Bulan Ini</option>
  </select>
</div>

<!-- Examinations Table -->
<div class="rounded-2xl border border-border overflow-hidden bg-white">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-border bg-muted">
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Nama Pasien</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal Pemeriksaan</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Berat/Tinggi</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tekanan Darah</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Berkas</th>
          <th class="px-6 py-4 text-center text-sm font-semibold text-foreground">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- Sample Row 1 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">Budi Santoso</td>
          <td class="px-6 py-4 text-sm text-secondary">10 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">75 kg / 175 cm</td>
          <td class="px-6 py-4 text-sm text-secondary">120/80 mmHg</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              1 Berkas
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
              <button class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Sample Row 2 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">Siti Nurhaliza</td>
          <td class="px-6 py-4 text-sm text-secondary">09 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">62 kg / 163 cm</td>
          <td class="px-6 py-4 text-sm text-secondary">118/78 mmHg</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-muted text-secondary text-xs font-medium">
              <i data-lucide="x-circle" class="w-4 h-4"></i>
              Tidak ada
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
              <button class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Sample Row 3 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">Ahmad Wijaya</td>
          <td class="px-6 py-4 text-sm text-secondary">08 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">88 kg / 180 cm</td>
          <td class="px-6 py-4 text-sm text-secondary">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-warning-light text-warning-dark text-xs font-medium">
              <i data-lucide="alert-circle" class="w-4 h-4"></i>
              140/90 mmHg
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              2 Berkas
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
              <button class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- Sample Row 4 -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">Rini Wijayanti</td>
          <td class="px-6 py-4 text-sm text-secondary">07 Januari 2026</td>
          <td class="px-6 py-4 text-sm text-secondary">58 kg / 160 cm</td>
          <td class="px-6 py-4 text-sm text-secondary">115/76 mmHg</td>
          <td class="px-6 py-4 text-sm">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-success-light text-success-dark text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              1 Berkas
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
    <p class="text-sm text-secondary">Menampilkan 1 sampai 4 dari 142 pemeriksaan</p>
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

<!-- Info Alert -->
<div class="mt-6 p-4 bg-info-light rounded-card border-l-4 border-info">
  <div class="flex items-start gap-3">
    <i data-lucide="info" class="w-5 h-5 text-info-dark flex-shrink-0 mt-0.5"></i>
    <div>
      <h4 class="text-foreground text-sm font-medium">Catatan Pemeriksaan</h4>
      <p class="text-gray-500 text-xs mt-1">Pemeriksaan pasien mencakup: tanda-tanda vital (berat badan, tinggi badan, tekanan darah, detak jantung, laju respirasi, suhu tubuh), hasil pemeriksaan bebas, dan upload berkas pendukung dari pemeriksaan luar.</p>
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
