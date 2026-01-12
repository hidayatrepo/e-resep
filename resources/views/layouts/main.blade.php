<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'E-Resep - Sistem E-Resep Dokter')</title>
  <meta name="description" content="E-Resep dashboard untuk mengelola resep elektronik dan data pasien.">
  <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />
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
  @stack('styles')
</head>
<body class="font-sans bg-gray-50 min-h-screen overflow-x-hidden">
  <div id="main-app">
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
              <!-- Dashboard - Semua role bisa akses -->
              <a href="{{ route('dashboard') }}" class="group @if(request()->routeIs('dashboard')) active @endif cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="layout-dashboard"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Dashboard</span>
                </div>
              </a>
              
              <!-- E-Resep (Hanya dokter dan admin) -->
              @php
                  $userRole = session('user.role') ?? '';
              @endphp
              
              @if($userRole === 'doctor' || $userRole === 'admin')
              <a href="{{ route('prescriptions.index') }}" class="group @if(request()->routeIs('prescriptions.*')) active @endif cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="file-text"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">E-Resep</span>
                </div>
              </a>
              @endif
              
              <!-- Pasien (Sembunyikan dulu) -->
              <a href="{{ route('patients.index') }}" class="group @if(request()->routeIs('patients.*')) active @endif cursor-pointer" style="display: none">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="users"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pasien</span>
                </div>
              </a>
              
              <!-- Obat (Sembunyikan dulu) -->
              <a href="{{ route('medications.index') }}" class="group @if(request()->routeIs('medications.*')) active @endif cursor-pointer" style="display: none">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="pill"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Obat</span>
                </div>
              </a>
              
              <!-- Pembayaran (Hanya apoteker dan admin) -->
              @if($userRole === 'pharmacist' || $userRole === 'admin')
              <a href="{{ route('payments.index') }}" class="group @if(request()->routeIs('payments.*')) active @endif cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="credit-card"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pembayaran</span>
                </div>
              </a>
              @endif
              
              <!-- Janji Temu (Sembunyikan dulu) -->
              <a href="#" class="group cursor-pointer" style="display: none">
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
              <!-- Laporan (Semua role bisa akses) -->
              <a href="{{ route('reports.index') }}" class="group @if(request()->routeIs('reports.*')) active @endif cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="bar-chart-3"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Laporan</span>
                </div>
              </a>
              
              <!-- Pengaturan (Hanya admin) -->
              @if($userRole === 'admin')
              <a href="{{ route('settings.index') }}" class="group @if(request()->routeIs('settings.*')) active @endif cursor-pointer">
                <div
                  class="flex items-center rounded-xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
                  <i data-lucide="settings"
                    class="size-6 text-secondary group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300"></i>
                  <span
                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Pengaturan</span>
                </div>
              </a>
              @endif
            </div>
          </div>
        </div>

        <!-- User Profile & Logout -->
        <div class="absolute bottom-0 left-0 w-[280px]">
          <div class="flex items-center justify-between border-t bg-white border-border p-5 gap-3">
            <div class="min-w-0 flex-1">
              <p class="font-semibold text-foreground">{{ session('user.name') ?? 'User' }}</p>
              <p class="text-sm text-secondary">
                @php
                    $role = session('user.role') ?? '';
                    $roleNames = [
                        'doctor' => 'Dokter',
                        'pharmacist' => 'Apoteker',
                        'admin' => 'Administrator'
                    ];
                @endphp
                {{ $roleNames[$role] ?? 'User' }}
              </p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline" id="logoutForm">
              @csrf
              <button type="button"
                class="size-11 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0 hover:bg-red-100 transition-all cursor-pointer"
                aria-label="Keluar"
                onclick="confirmLogout()">
                <i data-lucide="log-out" class="size-6 text-red-600"></i>
              </button>
            </form>
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
          <h2 class="hidden lg:block font-bold text-2xl text-foreground">@yield('page_title', 'Dashboard')</h2>
          <!-- Right actions -->
          <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-3 pl-3 border-l border-border">
              <div class="text-right">
                <p class="font-semibold text-foreground text-sm">{{ session('user.name') ?? 'User' }}</p>
                <p class="text-secondary text-xs">
                  @php
                      $role = session('user.role') ?? '';
                      $roleNames = [
                          'doctor' => 'Dokter',
                          'pharmacist' => 'Apoteker',
                          'admin' => 'Administrator'
                      ];
                  @endphp
                  {{ $roleNames[$role] ?? 'User' }}
                </p>
              </div>
              <div class="size-11 rounded-full object-cover ring-2 ring-border bg-primary/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user size-6 text-primary"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Page Content Area -->
        <div class="flex-1 overflow-y-auto p-5 md:p-8">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <script>
    function confirmLogout() {
      if (confirm('Apakah Anda yakin ingin keluar?')) {
        document.getElementById('logoutForm').submit();
      }
    }

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });

    // Expose functions to global scope
    window.toggleSidebar = toggleSidebar;
    window.confirmLogout = confirmLogout;
  </script>
  
  @stack('scripts')
</body>
</html>