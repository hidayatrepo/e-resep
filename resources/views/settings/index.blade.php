@extends('layouts.main')

@section('title', 'Pengaturan - E-Resep')
@section('page_title', 'Pengaturan')

@section('content')
<!-- Page Header -->
<div class="flex flex-col gap-2 mb-6 md:mb-8">
  <h1 class="text-foreground text-2xl md:text-3xl font-bold">Pengaturan Aplikasi</h1>
  <p class="text-secondary text-sm md:text-base">Kelola konfigurasi dasar aplikasi E-Resep.</p>
</div>

<!-- Settings Form -->
<div class="rounded-2xl border border-border p-8 bg-white">
  <form class="space-y-0">
    <!-- Header with Save Button -->
    <div class="flex items-start justify-between mb-8 pb-6 border-b border-border">
      <h2 class="text-2xl font-bold text-foreground">Pengaturan Aplikasi</h2>
      <div class="flex gap-3">
        <button type="reset" class="rounded-2xl flex items-center gap-2 px-6 py-2.5 ring-1 ring-border text-foreground font-medium hover:bg-muted transition-all whitespace-nowrap">
          Batal
        </button>
        <button type="submit" class="rounded-2xl flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-medium hover:bg-primary-hover transition-all whitespace-nowrap">
          <i data-lucide="save" class="w-4 h-4"></i>
          Simpan
        </button>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Nama Rumah Sakit -->
        <div>
          <h3 class="text-lg font-semibold text-foreground mb-6 pb-4 border-b border-border">Informasi Rumah Sakit</h3>
          
          <div class="space-y-5">
            <div>
              <label class="block text-sm font-medium text-foreground mb-2">Nama Rumah Sakit</label>
              <input type="text" value="RS Delta Surya" 
                class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
              <p class="text-xs text-secondary mt-1">Nama resmi rumah sakit yang ditampilkan di aplikasi</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-foreground mb-2">Alamat</label>
              <input type="text" value="Jl. Sudirman No. 123, Jakarta" 
                class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
              <label class="block text-sm font-medium text-foreground mb-2">Nomor Telepon</label>
              <input type="tel" value="+62 21 123 4567" 
                class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
              <label class="block text-sm font-medium text-foreground mb-2">Email</label>
              <input type="email" value="info@rsdeltasurya.com" 
                class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
          </div>
        </div>

        <!-- Integrasi API -->
        <div>
          <h3 class="text-lg font-semibold text-foreground mb-6 pb-4 border-b border-border">Integrasi API</h3>
          
          <div class="space-y-5">
            <div>
              <label class="block text-sm font-medium text-foreground mb-2">Email API</label>
              <input type="email" value="hidayathack@gmail.com" 
                class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
              <p class="text-xs text-secondary mt-1">Email untuk authentikasi dengan API eksternal</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-foreground mb-2">Password API</label>
              <div class="flex gap-2">
                <input type="password" value="087856420950" 
                  class="flex-1 px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary"
                  id="apiPassword">
                <button type="button" class="px-4 py-3 rounded-lg border border-border hover:bg-muted transition-all" aria-label="Lihat Password" id="togglePassword">
                  <i data-lucide="eye" class="w-4 h-4 text-secondary"></i>
                </button>
              </div>
              <p class="text-xs text-secondary mt-1">Password/Nomor HP untuk authentikasi API</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-foreground mb-2">API Base URL</label>
              <input type="url" value="http://recruitment.rsdeltasurya.com/api/v1" 
                class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
              <p class="text-xs text-secondary mt-1">URL endpoint untuk API eksternal</p>
            </div>

            <button type="button" class="flex items-center gap-2 px-6 py-2.5 bg-info text-white rounded-lg font-medium hover:bg-info-dark transition-all w-full justify-center">
              <i data-lucide="link" class="w-4 h-4"></i>
              Uji Koneksi API
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    
    // Toggle Password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('apiPassword');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4 text-secondary"></i>';
      } else {
        passwordInput.type = 'password';
        this.innerHTML = '<i data-lucide="eye" class="w-4 h-4 text-secondary"></i>';
      }
      lucide.createIcons();
    });
  });
</script>
@endpush
@endsection
