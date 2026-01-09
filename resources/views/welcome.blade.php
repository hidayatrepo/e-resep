<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Resep - Sistem E-Resep Dokter</title>
  <meta name="description" content="E-Resep dashboard untuk mengelola resep elektronik dan data pasien.">
  <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style type="text/tailwindcss">
    @theme inline {
    --color-primary: var(--primary);
    --color-primary-hover: var(--primary-hover);
    --color-foreground: var(--foreground);
    --color-secondary: var(--secondary);
    --color-muted: var(--muted);
    --color-border: var(--border);
    --color-card-grey: var(--card-grey);
    --color-card-message: var(--card-message);
    --color-accent-blue: var(--accent-blue);
    --color-accent-teal: var(--accent-teal);
    --color-accent-sky: var(--accent-sky);
    --color-success: var(--success);
    --color-success-light: var(--success-light);
    --color-success-dark: var(--success-dark);
    --color-error: var(--error);
    --color-error-light: var(--error-light);
    --color-error-lighter: var(--error-lighter);
    --color-error-dark: var(--error-dark);
    --color-warning: var(--warning);
    --color-warning-light: var(--warning-light);
    --color-warning-dark: var(--warning-dark);
    --color-info: var(--info);
    --color-info-light: var(--info-light);
    --color-info-dark: var(--info-dark);
    --color-alert: var(--alert);
    --color-alert-light: var(--alert-light);
    --color-alert-dark: var(--alert-dark);
    --color-gray-50: var(--gray-50);
    --color-gray-100: var(--gray-100);
    --color-gray-200: var(--gray-200);
    --color-gray-500: var(--gray-500);
    --color-gray-600: var(--gray-600);
    --color-gray-700: var(--gray-700);
    --font-sans: var(--font-sans);
    --radius-card: 24px;
    --radius-button: 50px;
    --radius-icon: 12px;
    --radius-xl: 16px;
    --radius-2xl: 20px;
    --radius-3xl: 24px;
  }
  :root {
    --primary: #0443A8;
    --primary-hover: #03358A;
    --foreground: #111827;
    --secondary: #6B7280;
    --muted: #F3F4F6;
    --border: #E5E7EB;
    --card-grey: #F9FAFB;
    --card-message: #DBEAFE;
    --accent-blue: #93C5FD;
    --accent-teal: #5EEAD4;
    --accent-sky: #E0F2FE;
    --success: #10B981;
    --success-light: #D1FAE5;
    --success-dark: #065F46;
    --error: #EF4444;
    --error-light: #FEE2E2;
    --error-lighter: #FEF2F2;
    --error-dark: #991B1B;
    --warning: #F59E0B;
    --warning-light: #FEF3C7;
    --warning-dark: #92400E;
    --info: #3B82F6;
    --info-light: #DBEAFE;
    --info-dark: #1E40AF;
    --alert: #F97316;
    --alert-light: #FFEDD5;
    --alert-dark: #9A3412;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --font-sans: 'Lexend Deca', sans-serif;
  }
  select {
    @apply appearance-none bg-no-repeat cursor-pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-position: right 10px center;
    padding-right: 40px;
  }
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
</head>

<body class="font-sans bg-gray-50 min-h-screen overflow-x-hidden">

  <!-- Login Screen -->
  <div id="login-screen" class="fixed inset-0 bg-white z-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
          <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i data-lucide="stethoscope" class="w-10 h-10 text-primary"></i>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">E-Resep</h1>
          <p class="text-gray-600">Sistem E-Resep Digital
        </div>

        <div id="login-message" class="mb-4 hidden p-4 rounded-xl"></div>

        <form id="login-form" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email / Username</label>
            <input type="text" id="username" required
              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="dokter@email.com">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" id="password" required
              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="••••••••">
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
              <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
            </label>
            <a href="#" class="text-sm text-primary hover:underline">Lupa password?</a>
          </div>

          <button type="submit" id="login-btn"
            class="w-full mt-6 px-4 py-3 bg-primary text-white rounded-xl font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer flex items-center justify-center">
            <span>Masuk</span>
            <div id="login-spinner"
              class="hidden animate-spin ml-2 h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
          </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200">
          <p class="text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="#" class="text-primary font-medium hover:underline">Hubungi administrator</a>
          </p>
        </div>

        <div class="mt-6 text-center">
          <p class="text-xs text-gray-500">© 2026 E-Resep. undang-undang.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Main App (Hidden Initially) -->
  <div id="main-app" class="hidden">
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/80 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen max-h-screen flex-1 bg-muted overflow-hidden">
      <!-- SIDEBAR -->
      <aside id="sidebar"
        class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-hidden">
        <!-- Top Bar with logo and title -->
        <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3">
          <div class="flex items-center gap-3">

            <div
              class="w-12 h-12 bg-gradient-to-br from-blue-700 to-blue-800 rounded-xl flex items-center justify-center shadow-md">
              <i data-lucide="stethoscope" class="w-7 h-7 text-white"></i>
            </div>
            <h1 class="font-semibold text-xl">E-Resep</h1>
          </div>
          <div class="flex gap-2">
            <button onclick="toggleSidebar()" aria-label="Tutup sidebar"
              class="lg:hidden size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
              <i data-lucide="x" class="size-6 text-secondary"></i>
            </button>
          </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex flex-col p-5 pb-28 gap-6 overflow-y-auto flex-1">
          <!-- Menu Utama Section -->
          <div class="flex flex-col gap-4">
            <h3 class="font-medium text-sm text-secondary">Menu Utama</h3>
            <div class="flex flex-col gap-1">
              <!-- Active menu item -->
              <a href="#" class="group active cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="layout-dashboard"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Dashboard</span>
                </div>
              </a>
              <!-- Regular menu items -->
              <a href="#" class="group cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="users"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pasien</span>
                </div>
              </a>
              <a href="#" class="group cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="file-text"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">E-Resep</span>
                </div>
              </a>
              <a href="#" class="group cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="pill"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Obat</span>
                </div>
              </a>
              <a href="#" class="group cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="calendar-check"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Janji
                    Temu</span>
                </div>
              </a>
            </div>
          </div>

          <!-- Manajemen Section -->
          <div class="flex flex-col gap-4">
            <h3 class="font-medium text-sm text-secondary">Manajemen</h3>
            <div class="flex flex-col gap-1">
              <a href="#" class="group cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="bar-chart-3"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Laporan</span>
                </div>
              </a>
              <a href="#" class="group cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="settings"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pengaturan</span>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- User Profile & Logout -->
        <div class="absolute bottom-0 left-0 w-[280px]">
          <div class="flex items-center justify-between border-t bg-white border-border p-5 gap-3">
            <div class="min-w-0 flex-1">
              <p class="font-semibold text-foreground" id="logged-in-user">Dr. Sarah Johnson</p>
              <p class="text-sm text-secondary">Dokter Umum</p>
            </div>
            <button onclick="logout()"
              class="size-11 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0 hover:bg-red-100 transition-all cursor-pointer"
              aria-label="Keluar">
              <i data-lucide="log-out" class="size-6 text-red-600"></i>
            </button>
          </div>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 lg:ml-[280px] flex flex-col bg-white min-h-screen overflow-x-hidden">
        <!-- Top Header Bar -->
        <div
          class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-border bg-white px-5 md:px-8">
          <!-- Mobile hamburger -->
          <button onclick="toggleSidebar()" aria-label="Buka menu"
            class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
            <i data-lucide="menu" class="size-6 text-foreground"></i>
          </button>
          <!-- Page title (shown on desktop) -->
          <h2 class="hidden lg:block font-bold text-2xl text-foreground">Dashboard</h2>
          <!-- Right actions -->
          <div class="flex items-center gap-3">
            <button
              class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative"
              aria-label="Notifikasi">
              <i data-lucide="bell" class="size-6 text-secondary"></i>
              <span
                class="absolute -top-1 -right-1 h-5 px-1.5 rounded-full bg-error text-white text-xs font-medium flex items-center justify-center">5</span>
            </button>
            <div class="hidden md:flex items-center gap-3 pl-3 border-l border-border">
              <div class="text-right">
                <p class="font-semibold text-foreground text-sm" id="header-username">Dr. Sarah Johnson</p>
                <p class="text-secondary text-xs">Dokter Umum</p>
              </div>
              <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=100&h=100&fit=crop" alt="Profile"
                class="size-11 rounded-full object-cover ring-2 ring-border">
            </div>
          </div>
        </div>

        <!-- Page Content Area -->
        <div class="flex-1 overflow-y-auto p-5 md:p-8">
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
              <button onclick="newPrescription()"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Resep Baru</span>
              </button>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Janji Temu Hari Ini -->
            <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
              <div class="flex items-center gap-[6px]">
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                  <i data-lucide="calendar" class="size-6 text-primary"></i>
                </div>
                <p class="font-medium text-secondary">Janji Temu Hari Ini</p>
              </div>
              <div class="flex items-center gap-3">
                <p class="font-bold text-[32px] leading-10">18</p>
                <span class="text-success text-sm font-semibold">+3</span>
              </div>
            </div>

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
                <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                  <i data-lucide="users" class="size-6 text-success"></i>
                </div>
                <p class="font-medium text-secondary">Pasien Aktif</p>
              </div>
              <p class="font-bold text-[32px] leading-10">142</p>
            </div>

            <!-- Stok Obat Rendah -->
            <div class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white">
              <div class="flex items-center gap-[6px]">
                <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
                  <i data-lucide="package" class="size-6 text-warning-dark"></i>
                </div>
                <p class="font-medium text-secondary">Stok Obat</p>
              </div>
              <div class="flex items-center gap-3">
                <p class="font-bold text-[32px] leading-10">7</p>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Tren Resep -->
            <div class="flex flex-col rounded-2xl border border-border p-6 gap-6 bg-white">
              <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col gap-3">
                  <div class="flex items-center gap-[6px]">
                    <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center shrink-0">
                      <i data-lucide="trending-up" class="size-6 text-info"></i>
                    </div>
                    <p class="font-medium text-secondary">Tren Resep</p>
                  </div>
                  <p class="font-bold text-[32px] leading-10">24.5</p>
                </div>
                <button
                  class="flex items-center rounded-3xl border border-border py-3 px-4 gap-2 bg-primary/10 w-fit cursor-pointer hover:bg-primary/20 transition-all duration-300">
                  <i data-lucide="calendar" class="size-5 text-primary"></i>
                  <p class="font-medium text-sm text-primary">7 Hari Terakhir</p>
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

          <!-- Additional Sections -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Resep Terbaru -->
            <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <h3 class="font-bold text-lg text-foreground">Resep Terbaru</h3>
                <a href="#" class="cursor-pointer"><span
                    class="text-sm text-primary font-semibold hover:underline">Lihat Semua</span></a>
              </div>
              <div class="space-y-4">
                <div
                  class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop"
                      class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="Foto pasien">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">John Smith - Amoxicillin 500mg</h4>
                      <p class="text-gray-500 text-xs truncate">Resep #RX-2024-001 • Infeksi saluran pernapasan atas</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 pl-13 sm:pl-0 sm:flex-shrink-0">
                    <span class="text-gray-500 text-xs whitespace-nowrap">2 jam lalu</span>
                    <span
                      class="bg-info-light text-info-dark text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">Aktif</span>
                  </div>
                </div>
                <div
                  class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop"
                      class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="Foto pasien">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Emma Wilson - Lisinopril 10mg</h4>
                      <p class="text-gray-500 text-xs truncate">Resep #RX-2024-002 • Manajemen hipertensi</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 pl-13 sm:pl-0 sm:flex-shrink-0">
                    <span class="text-gray-500 text-xs whitespace-nowrap">4 jam lalu</span>
                    <span
                      class="bg-success-light text-success-dark text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">Selesai</span>
                  </div>
                </div>
                <div
                  class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop"
                      class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="Foto pasien">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Michael Brown - Metformin 850mg</h4>
                      <p class="text-gray-500 text-xs truncate">Resep #RX-2024-003 • Diabetes Tipe 2</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 pl-13 sm:pl-0 sm:flex-shrink-0">
                    <span class="text-gray-500 text-xs whitespace-nowrap">6 jam lalu</span>
                    <span
                      class="bg-info-light text-info-dark text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">Aktif</span>
                  </div>
                </div>
                <div
                  class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop"
                      class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="Foto pasien">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Robert Davis - Ibuprofen 400mg</h4>
                      <p class="text-gray-500 text-xs truncate">Resep #RX-2024-004 • Nyeri pasca operasi</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 pl-13 sm:pl-0 sm:flex-shrink-0">
                    <span class="text-gray-500 text-xs whitespace-nowrap">8 jam lalu</span>
                    <span
                      class="bg-warning-light text-warning-dark text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">Tertunda</span>
                  </div>
                </div>
                <div
                  class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b786d4d3?w=100&h=100&fit=crop"
                      class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="Foto pasien">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Lisa Anderson - Atorvastatin 20mg</h4>
                      <p class="text-gray-500 text-xs truncate">Resep #RX-2024-005 • Hiperlipidemia</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 pl-13 sm:pl-0 sm:flex-shrink-0">
                    <span class="text-gray-500 text-xs whitespace-nowrap">1 hari lalu</span>
                    <span
                      class="bg-success-light text-success-dark text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">Selesai</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Janji Temu Mendatang -->
            <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <h3 class="font-bold text-lg text-foreground">Janji Temu Mendatang</h3>
                <a href="#" class="cursor-pointer"><span
                    class="text-sm text-primary font-semibold hover:underline">Lihat Semua</span></a>
              </div>
              <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                      <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">John Smith - Kontrol</h4>
                      <p class="text-gray-500 text-xs truncate">Infeksi saluran pernapasan atas • Ruang 101</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-foreground text-sm font-semibold">09:30</p>
                    <span class="bg-success-light text-success-dark text-xs font-medium px-2 py-1 rounded-full">Hari
                      Ini</span>
                  </div>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-10 h-10 bg-info/10 rounded-xl flex items-center justify-center flex-shrink-0">
                      <i data-lucide="user" class="w-5 h-5 text-info"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Emma Wilson - Pemeriksaan</h4>
                      <p class="text-gray-500 text-xs truncate">Review hipertensi • Ruang 102</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-foreground text-sm font-semibold">11:00</p>
                    <span class="bg-success-light text-success-dark text-xs font-medium px-2 py-1 rounded-full">Hari
                      Ini</span>
                  </div>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-10 h-10 bg-warning/10 rounded-xl flex items-center justify-center flex-shrink-0">
                      <i data-lucide="user" class="w-5 h-5 text-warning-dark"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Michael Brown - Pasien Baru</h4>
                      <p class="text-gray-500 text-xs truncate">Konsultasi diabetes • Ruang 103</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-foreground text-sm font-semibold">14:15</p>
                    <span class="bg-success-light text-success-dark text-xs font-medium px-2 py-1 rounded-full">Hari
                      Ini</span>
                  </div>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center flex-shrink-0">
                      <i data-lucide="user" class="w-5 h-5 text-success"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Lisa Anderson - Rutin</h4>
                      <p class="text-gray-500 text-xs truncate">Pengecekan kolesterol • Ruang 104</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-foreground text-sm font-semibold">10:45</p>
                    <span
                      class="bg-warning-light text-warning-dark text-xs font-medium px-2 py-1 rounded-full">Besok</span>
                  </div>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                  <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-10 h-10 bg-alert/10 rounded-xl flex items-center justify-center flex-shrink-0">
                      <i data-lucide="user" class="w-5 h-5 text-alert"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h4 class="text-foreground text-sm font-medium truncate">Robert Davis - Darurat</h4>
                      <p class="text-gray-500 text-xs truncate">Evaluasi pasca operasi • IGD</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-foreground text-sm font-semibold">16:00</p>
                    <span class="bg-alert-light text-alert-dark text-xs font-medium px-2 py-1 rounded-full">Besok</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Alerts Section -->
          <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white">
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
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Resep Baru -->
  <div id="prescription-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-card p-6 max-w-md w-full">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-foreground text-xl font-bold">Buat Resep Baru</h3>
        <button onclick="closePrescriptionModal()"
          class="size-8 flex items-center justify-center rounded-lg hover:bg-gray-100 cursor-pointer">
          <i data-lucide="x" class="size-5 text-secondary"></i>
        </button>
      </div>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pasien</label>
          <select class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white">
            <option>Pilih Pasien</option>
            <option>John Smith</option>
            <option>Emma Wilson</option>
            <option>Michael Brown</option>
            <option>Lisa Anderson</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Obat</label>
          <select class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white">
            <option>Pilih Obat</option>
            <option>Amoxicillin 500mg</option>
            <option>Lisinopril 10mg</option>
            <option>Metformin 850mg</option>
            <option>Ibuprofen 400mg</option>
            <option>Atorvastatin 20mg</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Instruksi Dosis</label>
          <textarea class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white h-24"
            placeholder="Contoh: Minum 1 tablet 2x sehari setelah makan"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (Hari)</label>
            <input type="number" class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white" value="7">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Ulang</label>
            <select class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white">
              <option>0</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
            </select>
          </div>
        </div>

        <button onclick="submitPrescription()"
          class="w-full mt-6 px-4 py-3 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
          Buat Resep
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Halaman Tidak Tersedia -->
  <div id="page-not-found-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-card p-6 max-w-sm w-full text-center">
      <div class="w-16 h-16 bg-warning-light rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-lucide="alert-triangle" class="w-8 h-8 text-warning-dark"></i>
      </div>
      <h3 class="text-foreground text-xl font-bold mb-2">Halaman Tidak Tersedia</h3>
      <p class="text-gray-500 text-sm mb-6">Halaman ini belum dibuat. Silakan generate menggunakan chat!</p>
      <button onclick="closePageNotFoundModal()"
        class="w-full px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
        Mengerti
      </button>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      lucide.createIcons();

      // Setup login form
      document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Simple validation
        if (username && password) {
          login(username);
        } else {
          alert('Harap isi username dan password!');
        }
      });

      // Show modal when clicking any link (except login/prescription buttons)
      document.querySelectorAll('#main-app a').forEach(link => {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          document.getElementById('page-not-found-modal').classList.remove('hidden');
        });
      });
    });

    function login(username) {
      // Simulate login process
      document.getElementById('login-screen').classList.add('hidden');
      document.getElementById('main-app').classList.remove('hidden');

      // Update user display name
      const displayName = username.includes('@') ? username.split('@')[0] : username;
      document.getElementById('logged-in-user').textContent = `Dr. ${displayName}`;
      document.getElementById('header-username').textContent = `Dr. ${displayName}`;

      // Initialize charts after login
      initializeCharts();
    }

    function logout() {
      if (confirm('Apakah Anda yakin ingin keluar?')) {
        document.getElementById('login-screen').classList.remove('hidden');
        document.getElementById('main-app').classList.add('hidden');
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
      }
    }

    function newPrescription() {
      document.getElementById('prescription-modal').classList.remove('hidden');
    }

    function closePrescriptionModal() {
      document.getElementById('prescription-modal').classList.add('hidden');
    }

    function submitPrescription() {
      alert('Resep berhasil dibuat!');
      closePrescriptionModal();
    }

    function closePageNotFoundModal() {
      document.getElementById('page-not-found-modal').classList.add('hidden');
    }

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    }

    function initializeCharts() {
      // Prescription Trend Chart
      const prescriptionCtx = document.getElementById('prescriptionChart');
      if (prescriptionCtx) {
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
      if (medicationCtx) {
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
  </script>
</body>

</html>