<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'E-Resep - Sistem E-Resep Dokter')</title>
  <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />
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
  @stack('styles')
</head>
<body class="font-sans bg-gray-50 min-h-screen overflow-x-hidden">
  @yield('content')
  
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
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }

      function closePageNotFoundModal() {
        document.getElementById('page-not-found-modal').classList.add('hidden');
      }

      // Show modal when clicking any link (except login/prescription buttons)
      document.querySelectorAll('a[href="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          document.getElementById('page-not-found-modal').classList.remove('hidden');
        });
      });

      // Expose functions to global scope
      window.closePageNotFoundModal = closePageNotFoundModal;
    });

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    }

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

    // Expose functions to global scope
    window.toggleSidebar = toggleSidebar;
    window.initializeCharts = initializeCharts;
  </script>
  
  @stack('scripts')
</body>
</html>