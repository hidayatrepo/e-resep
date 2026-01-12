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
    <button onclick="exportReport()"
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Data</span>
    </button>
  </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i>
      <p class="text-sm text-secondary">Total Pembayaran Hari Ini</p>
    </div>
    <p class="text-2xl font-bold text-foreground" id="todayAmount">Rp 0</p>
  </div>
  
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="clock" class="w-5 h-5 text-warning"></i>
      <p class="text-sm text-secondary">Menunggu Pembayaran</p>
    </div>
    <p class="text-2xl font-bold text-warning-dark" id="pendingCount">0 Transaksi</p>
  </div>
  
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
      <p class="text-sm text-secondary">Sudah Dibayar</p>
    </div>
    <p class="text-2xl font-bold text-success" id="paidCount">0 Transaksi</p>
  </div>
  
  <div class="rounded-2xl border border-border p-6 bg-white">
    <div class="flex items-center gap-3 mb-2">
      <i data-lucide="user-check" class="w-5 h-5 text-info"></i>
      <p class="text-sm text-secondary">Apoteker Aktif</p>
    </div>
    <p class="text-2xl font-bold text-info-dark" id="pharmacistName">{{ session('user.name') ?? 'Apoteker' }}</p>
  </div>
</div>

<!-- Search & Filter -->
<div class="flex flex-col md:flex-row gap-3 mb-6 md:mb-8">
  <div class="flex-1 relative">
    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary w-5 h-5"></i>
    <input type="text" id="searchInput" placeholder="Cari pembayaran berdasarkan nama pasien atau no. resep..." 
      class="w-full pl-12 pr-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
  </div>
  <select id="paymentStatusFilter" class="px-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
    <option value="">Semua Status Bayar</option>
    <option value="pending">Belum Dibayar</option>
    <option value="paid">Sudah Dibayar</option>
    <option value="cancelled">Dibatalkan</option>
  </select>
  <select id="prescriptionStatusFilter" class="px-4 py-3 rounded-button border border-border focus:outline-none focus:ring-2 focus:ring-primary">
    <option value="">Semua Status Resep</option>
    <option value="draft">Draf</option>
    <option value="process">Diproses</option>
    <option value="completed">Selesai</option>
    <option value="cancelled">Dibatalkan</option>
  </select>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="hidden text-center py-8">
  <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
  <p class="mt-2 text-secondary">Memuat data...</p>
</div>

<!-- No Data Message -->
<div id="noDataMessage" class="hidden text-center py-12">
  <i data-lucide="file-text" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
  <p class="text-gray-500">Tidak ada data pembayaran ditemukan</p>
</div>

<!-- Payments Table -->
<div id="paymentsTableContainer" class="rounded-2xl border border-border overflow-hidden bg-white">
  <div class="overflow-x-auto">
    <table id="paymentsTable" class="w-full">
      <thead>
        <tr class="border-b border-border bg-muted">
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">No. Resep</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Nama Pasien</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Dokter</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Apoteker</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status Resep</th>
          <th class="px-6 py-4 text-right text-sm font-semibold text-foreground">Total Biaya</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status Bayar</th>
          <th class="px-6 py-4 text-center text-sm font-semibold text-foreground">Aksi</th>
        </tr>
      </thead>
      <tbody id="paymentsTableBody">
        <!-- Data akan diisi oleh JavaScript -->
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex items-center justify-between px-6 py-4 border-t border-border">
    <p class="text-sm text-secondary">Menampilkan <span id="showing">0</span> sampai <span id="total">0</span> dari <span id="totalRecords">0</span> pembayaran</p>
    <div class="flex items-center gap-2">
      <button id="prevPageBtn" onclick="changePage(currentPage - 1)" class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all disabled:opacity-50" disabled>
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
      </button>
      <div id="pageNumbers" class="flex items-center gap-1">
        <!-- Page numbers akan diisi oleh JavaScript -->
      </div>
      <button id="nextPageBtn" onclick="changePage(currentPage + 1)" class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
      </button>
    </div>
  </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <!-- Modal Header -->
    <div class="flex items-center justify-between p-6 border-b border-border sticky top-0 bg-white">
      <h2 id="modalTitle" class="text-2xl font-bold text-foreground">Proses Pembayaran</h2>
      <button onclick="closeModal()" class="text-secondary hover:text-foreground transition-all">
        <i data-lucide="x" class="w-6 h-6"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <form id="paymentForm" class="p-6 space-y-6">
      @csrf
      <input type="hidden" id="paymentId">
      <input type="hidden" id="pharmacistName" value="{{ session('user.name') ?? 'Apoteker' }}">
      <input type="hidden" id="pharmacistId" value="{{ session('user.id') ?? '' }}">
      
      <!-- Prescription Info -->
      <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Informasi Resep</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-blue-700 mb-1">No. Resep</p>
            <p class="text-base font-medium text-blue-900" id="modalPrescriptionNumber">-</p>
          </div>
          <div>
            <p class="text-sm text-blue-700 mb-1">Nama Pasien</p>
            <p class="text-base font-medium text-blue-900" id="modalPatientName">-</p>
          </div>
          <div>
            <p class="text-sm text-blue-700 mb-1">Dokter</p>
            <p class="text-base font-medium text-blue-900" id="modalDoctorName">-</p>
          </div>
          <div>
            <p class="text-sm text-blue-700 mb-1">Tanggal</p>
            <p class="text-base font-medium text-blue-900" id="modalExaminationDate">-</p>
          </div>
          <div>
            <p class="text-sm text-blue-700 mb-1">Apoteker</p>
            <p class="text-base font-medium text-blue-900" id="modalPharmacistName">{{ session('user.name') ?? 'Apoteker' }}</p>
          </div>
        </div>
      </div>

      <!-- Medicine Items -->
      <div class="border border-border rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b border-border">
          <h3 class="text-lg font-semibold text-foreground">Daftar Obat</h3>
        </div>
        <div class="p-4">
          <table class="w-full">
            <thead>
              <tr class="border-b border-border">
                <th class="text-left text-sm font-medium text-secondary pb-2">Nama Obat</th>
                <th class="text-right text-sm font-medium text-secondary pb-2">Jumlah</th>
                <th class="text-right text-sm font-medium text-secondary pb-2">Harga</th>
                <th class="text-right text-sm font-medium text-secondary pb-2">Total</th>
              </tr>
            </thead>
            <tbody id="modalMedicineList">
              <!-- Medicine items will be populated -->
            </tbody>
            <tfoot>
              <tr class="border-t border-border">
                <td colspan="3" class="text-right text-sm font-medium text-foreground pt-3">Total Tagihan:</td>
                <td class="text-right text-lg font-bold text-primary pt-3" id="modalTotalPrice">Rp 0</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Payment Details -->
      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-foreground">Detail Pembayaran</h3>
        
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Jumlah Dibayar *</label>
          <input type="number" id="paymentAmount" name="payment_amount" placeholder="0" min="0"
            class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-right text-lg font-medium">
          <p class="text-xs text-secondary mt-1">Tagihan: <span id="billAmount" class="font-medium">Rp 0</span></p>
          <p class="text-xs" id="changeAmount">Kembalian: <span class="font-medium">Rp 0</span></p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Metode Pembayaran *</label>
          <select id="paymentMethod" name="payment_method" class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">-- Pilih Metode --</option>
            <option value="cash">Tunai</option>
            <option value="debit_card">Kartu Debit</option>
            <option value="credit_card">Kartu Kredit</option>
            <option value="qris">QRIS</option>
            <option value="transfer">Transfer Bank</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">No. Referensi (Opsional)</label>
          <input type="text" id="paymentReference" name="payment_reference" placeholder="Contoh: INV-20241231-001"
            class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Catatan (Opsional)</label>
          <textarea id="paymentNotes" name="payment_notes" rows="2" placeholder="Tambahkan catatan jika diperlukan..."
            class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm"></textarea>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-6 border-t border-border sticky bottom-0 bg-white">
        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-2.5 ring-1 ring-border text-foreground rounded-lg font-medium hover:bg-muted transition-all">
          Batal
        </button>
        <button type="submit" id="submitPaymentButton" class="flex-1 flex items-center justify-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-all">
          <i data-lucide="credit-card" class="w-4 h-4"></i>
          <span>Proses Pembayaran</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- View Payment Detail Modal -->
<div id="viewPaymentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
    <!-- Modal Header -->
    <div class="flex items-center justify-between p-6 border-b border-border sticky top-0 bg-white">
      <h2 id="viewModalTitle" class="text-2xl font-bold text-foreground">Detail Pembayaran</h2>
      <div class="flex items-center gap-2">
        <button onclick="printInvoice(currentViewPaymentId)" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all">
          <i data-lucide="printer" class="w-4 h-4"></i>
          <span>Cetak</span>
        </button>
        <button onclick="closeViewModal()" class="text-secondary hover:text-foreground transition-all ml-2">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
    </div>

    <!-- Modal Body -->
    <div class="p-6 space-y-6">
      <!-- Header Info -->
      <div class="flex justify-between items-start border-b border-border pb-4">
        <div>
          <h3 class="text-lg font-bold text-foreground mb-1" id="viewPatientName">-</h3>
          <p class="text-sm text-secondary" id="viewPrescriptionNumber">-</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-secondary">No. Resi</p>
          <p class="text-lg font-bold text-primary" id="viewReceiptNumber">-</p>
        </div>
      </div>

      <!-- Prescription Info -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="text-sm text-secondary mb-1">Dokter</p>
          <p class="text-base font-medium text-foreground" id="viewDoctorName">-</p>
        </div>
        <div>
          <p class="text-sm text-secondary mb-1">Tanggal Pemeriksaan</p>
          <p class="text-base font-medium text-foreground" id="viewExaminationDate">-</p>
        </div>
        <div>
          <p class="text-sm text-secondary mb-1">Apoteker</p>
          <p class="text-base font-medium text-foreground" id="viewPharmacistName">-</p>
        </div>
        <div>
          <p class="text-sm text-secondary mb-1">Tanggal Dilayani</p>
          <p class="text-base font-medium text-foreground" id="viewServedAt">-</p>
        </div>
      </div>

      <!-- Medicine Items -->
      <div class="border border-border rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b border-border">
          <h3 class="text-lg font-semibold text-foreground">Daftar Obat</h3>
        </div>
        <div class="p-4">
          <table class="w-full">
            <thead>
              <tr class="border-b border-border">
                <th class="text-left text-sm font-medium text-secondary pb-2">No.</th>
                <th class="text-left text-sm font-medium text-secondary pb-2">Nama Obat</th>
                <th class="text-right text-sm font-medium text-secondary pb-2">Jumlah</th>
                <th class="text-right text-sm font-medium text-secondary pb-2">Harga</th>
                <th class="text-right text-sm font-medium text-secondary pb-2">Total</th>
              </tr>
            </thead>
            <tbody id="viewMedicineList">
              <!-- Medicine items will be populated -->
            </tbody>
            <tfoot>
              <tr class="border-t border-border">
                <td colspan="4" class="text-right text-sm font-medium text-foreground pt-3">Total Tagihan:</td>
                <td class="text-right text-lg font-bold text-primary pt-3" id="viewTotalPrice">Rp 0</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Payment Details -->
      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-foreground">Detail Pembayaran</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-secondary mb-1">Status Pembayaran</p>
            <div id="viewPaymentStatus">-</div>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Metode Pembayaran</p>
            <p class="text-base font-medium text-foreground" id="viewPaymentMethod">-</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Jumlah Dibayar</p>
            <p class="text-base font-medium text-foreground" id="viewPaymentAmount">Rp 0</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">No. Referensi</p>
            <p class="text-base font-medium text-foreground" id="viewPaymentReference">-</p>
          </div>
          <div class="md:col-span-2">
            <p class="text-sm text-secondary mb-1">Catatan Pembayaran</p>
            <p class="text-base font-medium text-foreground" id="viewPaymentNotes">-</p>
          </div>
        </div>
      </div>

      <!-- Examination Details -->
      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-foreground">Data Pemeriksaan</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div>
            <p class="text-sm text-secondary mb-1">Tinggi Badan</p>
            <p class="text-base font-medium text-foreground" id="viewHeight">-</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Berat Badan</p>
            <p class="text-base font-medium text-foreground" id="viewWeight">-</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Tekanan Darah</p>
            <p class="text-base font-medium text-foreground" id="viewBloodPressure">-</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Denyut Jantung</p>
            <p class="text-base font-medium text-foreground" id="viewHeartRate">-</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Laju Pernapasan</p>
            <p class="text-base font-medium text-foreground" id="viewRespirationRate">-</p>
          </div>
          <div>
            <p class="text-sm text-secondary mb-1">Suhu Tubuh</p>
            <p class="text-base font-medium text-foreground" id="viewTemperature">-</p>
          </div>
        </div>
        <div>
          <p class="text-sm text-secondary mb-1">Hasil Pemeriksaan</p>
          <p class="text-base font-medium text-foreground" id="viewExaminationResult">-</p>
        </div>
        <div>
          <p class="text-sm text-secondary mb-1">Catatan Dokter</p>
          <p class="text-base font-medium text-foreground" id="viewDoctorNotes">-</p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
  /* Payment Status Badges */
  .payment-badge-pending {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium;
  }
  
  .payment-badge-paid {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium;
  }
  
  .payment-badge-cancelled {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium;
  }

  /* Prescription Status Badges */
  .prescription-badge-draft {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-medium;
  }
  
  .prescription-badge-process {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium;
  }
  
  .prescription-badge-completed {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium;
  }
  
  .prescription-badge-cancelled {
    @apply inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium;
  }

  /* Custom scrollbar */
  #paymentModal .overflow-y-auto,
  #viewPaymentModal .overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E1 #F1F5F9;
  }
  
  #paymentModal .overflow-y-auto::-webkit-scrollbar,
  #viewPaymentModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
  }
  
  #paymentModal .overflow-y-auto::-webkit-scrollbar-track,
  #viewPaymentModal .overflow-y-auto::-webkit-scrollbar-track {
    background: #F1F5F9;
  }
  
  #paymentModal .overflow-y-auto::-webkit-scrollbar-thumb,
  #viewPaymentModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #CBD5E1;
    border-radius: 3px;
  }
</style>
@endpush

@push('scripts')
<script>
  // =============== GLOBAL VARIABLES ===============
  let currentPage = 1;
  let totalPages = 1;
  let totalRecords = 0;
  let currentPaymentId = null;
  let currentViewPaymentId = null;
  
  // Session user data from PHP
  const sessionUser = @json(session('user') ?? []);
  const currentUserId = sessionUser?.id || 0;
  const currentUserRole = sessionUser?.role || '';
  const currentUserName = sessionUser?.name || 'Apoteker';

  // =============== INITIALIZATION ===============
  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    initializePayments();
  });

  async function initializePayments() {
    try {
      // Update pharmacist name in statistics card
      document.getElementById('pharmacistName').textContent = currentUserName;
      
      // Load statistics
      await loadStatistics();
      
      // Load initial payments data
      await loadPayments();
      
      // Setup form submission
      setupPaymentForm();
      
      // Setup search functionality
      setupPaymentSearch();
      
      // Setup payment amount calculation
      setupPaymentCalculation();
      
    } catch (error) {
      console.error('Error initializing payments:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat halaman pembayaran');
    }
  }

  // =============== PAYMENTS MANAGEMENT ===============
  async function loadPayments() {
    showLoading(true);
    
    try {
      const search = document.getElementById('searchInput').value;
      const paymentStatus = document.getElementById('paymentStatusFilter').value;
      const prescriptionStatus = document.getElementById('prescriptionStatusFilter').value;
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/payments/get', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          search: search,
          payment_status: paymentStatus,
          status: prescriptionStatus,
          page: currentPage,
          per_page: 10
        })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        updatePaymentsTable(result.data);
      } else {
        showAlert('error', result.message || 'Gagal memuat data pembayaran');
      }
    } catch (error) {
      console.error('Error loading payments:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat data: ' + error.message);
    } finally {
      showLoading(false);
    }
  }

  function updatePaymentsTable(data) {
    const tableBody = document.getElementById('paymentsTableBody');
    const container = document.getElementById('paymentsTableContainer');
    const noData = document.getElementById('noDataMessage');
    
    // Update total records
    totalRecords = data.total || 0;
    
    if (!data.data || data.data.length === 0) {
      container.classList.add('hidden');
      noData.classList.remove('hidden');
      updatePaginationInfo(0, 0, 0);
      return;
    }
    
    container.classList.remove('hidden');
    noData.classList.add('hidden');
    
    let html = '';
    data.data.forEach((payment, index) => {
      // Format date
      const examDate = new Date(payment.examination_date);
      const formattedDate = examDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      });
      
      // Format total price
      const totalPrice = payment.total_price ? 
        `Rp ${parseInt(payment.total_price).toLocaleString('id-ID')}` : 'Rp 0';
      
      // Prescription status badge
      let prescriptionStatusBadge = '';
      let prescriptionStatusClass = '';
      let prescriptionStatusIcon = '';
      let prescriptionStatusLabel = '';
      
      switch(payment.status) {
        case 'draft':
          prescriptionStatusClass = 'prescription-badge-draft';
          prescriptionStatusIcon = 'file-edit';
          prescriptionStatusLabel = 'Draf';
          break;
        case 'process':
          prescriptionStatusClass = 'prescription-badge-process';
          prescriptionStatusIcon = 'refresh-cw';
          prescriptionStatusLabel = 'Diproses';
          break;
        case 'completed':
          prescriptionStatusClass = 'prescription-badge-completed';
          prescriptionStatusIcon = 'check-circle';
          prescriptionStatusLabel = 'Selesai';
          break;
        case 'cancelled':
          prescriptionStatusClass = 'prescription-badge-cancelled';
          prescriptionStatusIcon = 'x-circle';
          prescriptionStatusLabel = 'Dibatalkan';
          break;
        default:
          prescriptionStatusClass = 'prescription-badge-draft';
          prescriptionStatusIcon = 'help-circle';
          prescriptionStatusLabel = payment.status || '-';
      }
      
      prescriptionStatusBadge = `
        <span class="${prescriptionStatusClass}" style="display:flex">
          <i data-lucide="${prescriptionStatusIcon}" class="w-4 h-4 mr-1"></i>
          ${prescriptionStatusLabel}
        </span>
      `;
      
      // Payment status badge
      let paymentStatusBadge = '';
      let canProcess = false;
      let canView = false;
      let canPrint = false;
      let canCancel = false;
      
      // Cek permission berdasarkan role
      const isPharmacist = currentUserRole === 'pharmacist';
      const isAdmin = currentUserRole === 'admin';
      
      switch(payment.payment_status) {
        case 'pending':
          paymentStatusBadge = `
            <span class="payment-badge-pending" style="display:flex">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              Belum Dibayar
            </span>
          `;
          // Hanya apoteker dan admin yang bisa proses pembayaran
          canProcess = (isPharmacist || isAdmin) && (payment.status === 'draft' || payment.status === 'process');
          canCancel = isPharmacist || isAdmin;
          break;
        case 'paid':
          paymentStatusBadge = `
            <span class="payment-badge-paid" style="display:flex">
              <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
              Sudah Dibayar
            </span>
          `;
          canView = true; // Semua bisa lihat detail pembayaran yang sudah selesai
          canPrint = isPharmacist || isAdmin;
          break;
        case 'cancelled':
          paymentStatusBadge = `
            <span class="payment-badge-cancelled" style="display:flex">
              <i data-lucide="x-circle" class="w-4 h-4 mr-1"></i>
              Dibatalkan
            </span>
          `;
          canView = isPharmacist || isAdmin;
          break;
        default:
          paymentStatusBadge = `
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-medium" style="display:flex">
              <i data-lucide="help-circle" class="w-4 h-4 mr-1"></i>
              ${payment.payment_status || '-'}
            </span>
          `;
      }
      
      // Tampilkan nama apoteker jika sudah ada
      const pharmacistName = payment.pharmacist_name ? 
        `<span class="text-sm text-secondary">${payment.pharmacist_name}</span>` : 
        `<span class="text-sm text-gray-400">Belum diproses</span>`;
      
      html += `
        <tr class="border-b border-border hover:bg-muted/50 transition-all">
          <td class="px-6 py-4 text-sm text-foreground font-medium">${payment.prescription_number || '-'}</td>
          <td class="px-6 py-4 text-sm text-secondary">${payment.patient_name || '-'}</td>
          <td class="px-6 py-4 text-sm text-secondary">${payment.doctor_name || '-'}</td>
          <td class="px-6 py-4 text-sm">${pharmacistName}</td>
          <td class="px-6 py-4 text-sm text-secondary">${formattedDate}</td>
          <td class="px-6 py-4 text-sm">${prescriptionStatusBadge}</td>
          <td class="px-6 py-4 text-sm text-foreground font-medium text-right">${totalPrice}</td>
          <td class="px-6 py-4 text-sm">${paymentStatusBadge}</td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              ${canProcess ? `
                <button onclick="openPaymentModal(${payment.id})" class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all" aria-label="Proses Pembayaran">
                  <i data-lucide="credit-card" class="w-4 h-4"></i>
                </button>
              ` : ''}
              ${canView ? `
                <button onclick="viewPayment(${payment.id})" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat Detail">
                  <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
                </button>
              ` : ''}
              ${canPrint ? `
                <button onclick="printInvoice(${payment.id})" class="p-2 hover:bg-blue-100 rounded-lg transition-all" aria-label="Cetak Invoice">
                  <i data-lucide="printer" class="w-4 h-4 text-blue-600"></i>
                </button>
              ` : ''}
              ${canCancel ? `
                <button onclick="cancelPayment(${payment.id})" class="p-2 hover:bg-red-100 rounded-lg transition-all" aria-label="Batalkan">
                  <i data-lucide="x-circle" class="w-4 h-4 text-red-600"></i>
                </button>
              ` : ''}
            </div>
          </td>
        </tr>
      `;
    });
    
    tableBody.innerHTML = html;
    lucide.createIcons();
    
    // Update pagination info
    const from = (currentPage - 1) * 10 + 1;
    const to = Math.min(currentPage * 10, totalRecords);
    updatePaginationInfo(from, to, totalRecords);
    
    // Update pagination controls
    updatePaginationControls(data.last_page || 1);
  }

  function updatePaginationInfo(showing, to, total) {
    document.getElementById('showing').textContent = showing;
    document.getElementById('total').textContent = to;
    document.getElementById('totalRecords').textContent = total;
  }

  function updatePaginationControls(lastPage) {
    totalPages = lastPage;
    const pageNumbers = document.getElementById('pageNumbers');
    const prevBtn = document.getElementById('prevPageBtn');
    const nextBtn = document.getElementById('nextPageBtn');
    
    // Update prev/next buttons
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
    
    // Update page numbers
    let html = '';
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
    
    if (endPage - startPage + 1 < maxPagesToShow) {
      startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }
    
    if (startPage > 1) {
      html += `<button onclick="changePage(1)" class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">1</button>`;
      if (startPage > 2) {
        html += `<span class="px-2">...</span>`;
      }
    }
    
    for (let i = startPage; i <= endPage; i++) {
      if (i === currentPage) {
        html += `<button class="px-3 py-2 rounded-lg bg-primary text-white">${i}</button>`;
      } else {
        html += `<button onclick="changePage(${i})" class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">${i}</button>`;
      }
    }
    
    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        html += `<span class="px-2">...</span>`;
      }
      html += `<button onclick="changePage(${totalPages})" class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-all">${totalPages}</button>`;
    }
    
    pageNumbers.innerHTML = html;
  }

  function changePage(page) {
    if (page >= 1 && page <= totalPages) {
      currentPage = page;
      loadPayments();
    }
  }

  // =============== PAYMENT MODAL ===============
  async function openPaymentModal(id) {
    try {
      // Cek permission untuk apoteker dan admin
      if (!['pharmacist', 'admin'].includes(currentUserRole)) {
        showAlert('error', 'Anda tidak memiliki izin untuk memproses pembayaran');
        return;
      }
      
      currentPaymentId = id;
      
      // Load payment data
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/payments/get', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ id: id })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        // Cek status harus draft atau process
        if (!['draft', 'process'].includes(result.data.status)) {
          showAlert('error', 'Hanya resep dengan status "Draf" atau "Diproses" yang bisa dibayar');
          return;
        }
        
        // Cek apakah sudah dibayar
        if (result.data.payment_status === 'paid') {
          showAlert('error', 'Resep ini sudah dibayar');
          return;
        }
        
        populatePaymentModal(result.data);
        
        // Show modal
        const modal = document.getElementById('paymentModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        lucide.createIcons();
      } else {
        showAlert('error', 'Gagal memuat data pembayaran');
      }
    } catch (error) {
      console.error('Error opening payment modal:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat data');
    }
  }

  async function populatePaymentModal(data) {
    // Set payment ID
    document.getElementById('paymentId').value = data.id;
    
    // Set prescription info
    document.getElementById('modalPrescriptionNumber').textContent = data.prescription_number || '-';
    document.getElementById('modalPatientName').textContent = data.patient_name || '-';
    document.getElementById('modalDoctorName').textContent = data.doctor_name || '-';
    document.getElementById('modalPharmacistName').textContent = currentUserName;
    
    // Format examination date
    if (data.examination_date) {
      const examDate = new Date(data.examination_date);
      const formattedDate = examDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      document.getElementById('modalExaminationDate').textContent = formattedDate;
    } else {
      document.getElementById('modalExaminationDate').textContent = '-';
    }
    
    // Load medicine items
    const medicineList = document.getElementById('modalMedicineList');
    medicineList.innerHTML = '';
    
    if (data.items && data.items.length > 0) {
      let total = 0;
      data.items.forEach((item, index) => {
        const itemTotal = item.quantity * item.unit_price;
        total += itemTotal;
        
        medicineList.innerHTML += `
          <tr class="${index < data.items.length - 1 ? 'border-b border-border' : ''}">
            <td class="py-2 text-sm text-secondary">${item.medicine_name || '-'}</td>
            <td class="py-2 text-sm text-secondary text-right">${item.quantity || 0} ${item.unit || 'pcs'}</td>
            <td class="py-2 text-sm text-secondary text-right">Rp ${parseInt(item.unit_price || 0).toLocaleString('id-ID')}</td>
            <td class="py-2 text-sm font-medium text-foreground text-right">Rp ${parseInt(itemTotal).toLocaleString('id-ID')}</td>
          </tr>
        `;
      });
      
      // Set total price
      document.getElementById('modalTotalPrice').textContent = `Rp ${parseInt(total).toLocaleString('id-ID')}`;
      document.getElementById('billAmount').textContent = `Rp ${parseInt(total).toLocaleString('id-ID')}`;
      
      // Set payment amount to total
      document.getElementById('paymentAmount').value = total;
      calculateChange();
    } else {
      medicineList.innerHTML = `
        <tr>
          <td colspan="4" class="py-4 text-center text-sm text-secondary">Tidak ada obat</td>
        </tr>
      `;
      document.getElementById('modalTotalPrice').textContent = 'Rp 0';
      document.getElementById('billAmount').textContent = 'Rp 0';
    }
  }

  function setupPaymentCalculation() {
    const paymentAmount = document.getElementById('paymentAmount');
    if (paymentAmount) {
      paymentAmount.addEventListener('input', calculateChange);
    }
  }

  function calculateChange() {
    const billAmount = parseFloat(document.getElementById('billAmount').textContent.replace('Rp ', '').replace(/\./g, '')) || 0;
    const paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
    const change = paymentAmount - billAmount;
    
    const changeElement = document.getElementById('changeAmount');
    if (change >= 0) {
      changeElement.innerHTML = `Kembalian: <span class="font-medium text-green-600">Rp ${Math.round(change).toLocaleString('id-ID')}</span>`;
    } else {
      changeElement.innerHTML = `Kurang: <span class="font-medium text-red-600">Rp ${Math.abs(Math.round(change)).toLocaleString('id-ID')}</span>`;
    }
  }

  function closeModal() {
    const modal = document.getElementById('paymentModal');
    const form = document.getElementById('paymentForm');
    
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    form.reset();
  }

  // =============== VIEW PAYMENT DETAIL ===============
  async function viewPayment(id) {
    try {
      currentViewPaymentId = id;
      
      // Load payment data
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/payments/get', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ id: id })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        populateViewModal(result.data);
        
        // Show modal
        const modal = document.getElementById('viewPaymentModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        lucide.createIcons();
      } else {
        showAlert('error', 'Gagal memuat data pembayaran');
      }
    } catch (error) {
      console.error('Error viewing payment:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat data');
    }
  }

  function populateViewModal(data) {
    // Set basic info
    document.getElementById('viewPatientName').textContent = data.patient_name || '-';
    document.getElementById('viewPrescriptionNumber').textContent = data.prescription_number || '-';
    document.getElementById('viewReceiptNumber').textContent = data.receipt_number || '-';
    document.getElementById('viewDoctorName').textContent = data.doctor_name || '-';
    document.getElementById('viewPharmacistName').textContent = data.pharmacist_name || currentUserName;
    
    // Format dates
    if (data.examination_date) {
      const examDate = new Date(data.examination_date);
      const formattedExamDate = examDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      document.getElementById('viewExaminationDate').textContent = formattedExamDate;
    } else {
      document.getElementById('viewExaminationDate').textContent = '-';
    }
    
    if (data.served_at) {
      const servedDate = new Date(data.served_at);
      const formattedServedDate = servedDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      document.getElementById('viewServedAt').textContent = formattedServedDate;
    } else {
      document.getElementById('viewServedAt').textContent = '-';
    }
    
    // Set payment status badge
    let paymentStatusBadge = '';
    switch(data.payment_status) {
      case 'pending':
        paymentStatusBadge = '<span class="payment-badge-pending"><i data-lucide="clock" class="w-4 h-4"></i> Belum Dibayar</span>';
        break;
      case 'paid':
        paymentStatusBadge = '<span class="payment-badge-paid"><i data-lucide="check-circle" class="w-4 h-4"></i> Sudah Dibayar</span>';
        break;
      case 'cancelled':
        paymentStatusBadge = '<span class="payment-badge-cancelled"><i data-lucide="x-circle" class="w-4 h-4"></i> Dibatalkan</span>';
        break;
      default:
        paymentStatusBadge = data.payment_status || '-';
    }
    document.getElementById('viewPaymentStatus').innerHTML = paymentStatusBadge;
    
    // Set payment details
    document.getElementById('viewPaymentMethod').textContent = formatPaymentMethod(data.payment_method);
    document.getElementById('viewPaymentAmount').textContent = data.payment_amount ? 
      `Rp ${parseInt(data.payment_amount).toLocaleString('id-ID')}` : 'Rp 0';
    document.getElementById('viewPaymentReference').textContent = data.payment_reference || '-';
    document.getElementById('viewPaymentNotes').textContent = data.payment_notes || '-';
    
    // Set examination details
    document.getElementById('viewHeight').textContent = data.height ? `${data.height} cm` : '-';
    document.getElementById('viewWeight').textContent = data.weight ? `${data.weight} kg` : '-';
    document.getElementById('viewBloodPressure').textContent = data.systole && data.diastole ? 
      `${data.systole}/${data.diastole} mmHg` : '-';
    document.getElementById('viewHeartRate').textContent = data.heart_rate ? `${data.heart_rate} bpm` : '-';
    document.getElementById('viewRespirationRate').textContent = data.respiration_rate ? `${data.respiration_rate} rpm` : '-';
    document.getElementById('viewTemperature').textContent = data.temperature ? `${data.temperature} °C` : '-';
    document.getElementById('viewExaminationResult').textContent = data.examination_result || '-';
    document.getElementById('viewDoctorNotes').textContent = data.notes || '-';
    
    // Load medicine items
    const medicineList = document.getElementById('viewMedicineList');
    medicineList.innerHTML = '';
    
    if (data.items && data.items.length > 0) {
      let total = 0;
      data.items.forEach((item, index) => {
        const itemTotal = item.quantity * item.unit_price;
        total += itemTotal;
        
        medicineList.innerHTML += `
          <tr class="${index < data.items.length - 1 ? 'border-b border-border' : ''}">
            <td class="py-2 text-sm text-secondary">${index + 1}</td>
            <td class="py-2 text-sm text-secondary">${item.medicine_name || '-'}</td>
            <td class="py-2 text-sm text-secondary text-right">${item.quantity || 0} ${item.unit || 'pcs'}</td>
            <td class="py-2 text-sm text-secondary text-right">Rp ${parseInt(item.unit_price || 0).toLocaleString('id-ID')}</td>
            <td class="py-2 text-sm font-medium text-foreground text-right">Rp ${parseInt(itemTotal).toLocaleString('id-ID')}</td>
          </tr>
        `;
      });
      
      // Set total price
      document.getElementById('viewTotalPrice').textContent = `Rp ${parseInt(total).toLocaleString('id-ID')}`;
    } else {
      medicineList.innerHTML = `
        <tr>
          <td colspan="5" class="py-4 text-center text-sm text-secondary">Tidak ada obat</td>
        </tr>
      `;
      document.getElementById('viewTotalPrice').textContent = 'Rp 0';
    }
  }

  function formatPaymentMethod(method) {
    const methods = {
      'cash': 'Tunai',
      'debit_card': 'Kartu Debit',
      'credit_card': 'Kartu Kredit',
      'qris': 'QRIS',
      'transfer': 'Transfer Bank'
    };
    return methods[method] || method || '-';
  }

  function closeViewModal() {
    const modal = document.getElementById('viewPaymentModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }

  // =============== PAYMENT FORM ===============
  function setupPaymentForm() {
    const form = document.getElementById('paymentForm');
    if (form) {
      form.addEventListener('submit', handlePaymentSubmit);
    }
  }

  async function handlePaymentSubmit(event) {
    event.preventDefault();
    
    // Validate form
    const paymentAmount = document.getElementById('paymentAmount').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    
    if (!paymentAmount || parseFloat(paymentAmount) <= 0) {
      showAlert('error', 'Jumlah pembayaran harus diisi');
      return;
    }
    
    if (!paymentMethod) {
      showAlert('error', 'Metode pembayaran harus dipilih');
      return;
    }
    
    // Collect form data
    const formData = {
      id: currentPaymentId,
      payment_amount: paymentAmount,
      payment_method: paymentMethod,
      payment_reference: document.getElementById('paymentReference').value,
      payment_notes: document.getElementById('paymentNotes').value,
      pharmacist_name: currentUserName,
      pharmacist_id: currentUserId
    };
    
    // Disable submit button
    const submitButton = document.getElementById('submitPaymentButton');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...';
    
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/payments/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        showAlert('success', 'Pembayaran berhasil diproses oleh ' + currentUserName);
        
        // Offer to print invoice
        if (confirm('Pembayaran berhasil! Apakah Anda ingin mencetak invoice?')) {
          printInvoice(currentPaymentId);
        }
        
        closeModal();
        loadPayments();
        loadStatistics(); // Refresh statistics
      } else {
        if (result.errors) {
          showAlert('error', result.errors.join(', '));
        } else {
          showAlert('error', result.message || 'Gagal memproses pembayaran');
        }
      }
    } catch (error) {
      console.error('Error processing payment:', error);
      showAlert('error', 'Terjadi kesalahan saat memproses pembayaran');
    } finally {
      // Re-enable submit button
      submitButton.disabled = false;
      submitButton.innerHTML = originalText;
      lucide.createIcons();
    }
  }

  // =============== PAYMENT ACTIONS ===============
  async function printInvoice(id) {
    try {
      // Cek permission untuk apoteker dan admin
      if (!['pharmacist', 'admin'].includes(currentUserRole)) {
        showAlert('error', 'Anda tidak memiliki izin untuk mencetak invoice');
        return;
      }
      
      // Open PDF in new tab
      window.open(`/api/payments/invoice/${id}/pdf`, '_blank');
    } catch (error) {
      console.error('Error printing invoice:', error);
      showAlert('error', 'Terjadi kesalahan saat mencetak invoice');
    }
  }

  async function cancelPayment(id) {
    if (!confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')) {
      return;
    }
    
    // Cek permission untuk apoteker dan admin
    if (!['pharmacist', 'admin'].includes(currentUserRole)) {
      showAlert('error', 'Anda tidak memiliki izin untuk membatalkan pembayaran');
      return;
    }
    
    const notes = prompt('Alasan pembatalan (opsional):', '');
    
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/payments/cancel', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
          id: id,
          notes: notes,
          pharmacist_name: currentUserName,
          pharmacist_id: currentUserId
        })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        showAlert('success', 'Pembayaran berhasil dibatalkan oleh ' + currentUserName);
        loadPayments();
        loadStatistics();
      } else {
        showAlert('error', result.message || 'Gagal membatalkan pembayaran');
      }
    } catch (error) {
      console.error('Error cancelling payment:', error);
      showAlert('error', 'Terjadi kesalahan saat membatalkan pembayaran');
    }
  }

  // =============== STATISTICS ===============
  async function loadStatistics() {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/payments/statistics', {
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        }
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        updateStatistics(result.data);
      }
    } catch (error) {
      console.error('Error loading statistics:', error);
    }
  }

  function updateStatistics(data) {
    // Update dashboard cards
    document.getElementById('todayAmount').textContent = `Rp ${parseInt(data.today.total_amount || 0).toLocaleString('id-ID')}`;
    document.getElementById('pendingCount').textContent = `${data.pending || 0} Transaksi`;
    
    // Calculate paid count
    const paidCount = (data.month.total_payments || 0) - (data.today.total_payments || 0);
    document.getElementById('paidCount').textContent = `${paidCount} Transaksi`;
  }

  // =============== UTILITY FUNCTIONS ===============
  function showLoading(show) {
    const indicator = document.getElementById('loadingIndicator');
    if (show) {
      indicator.classList.remove('hidden');
    } else {
      indicator.classList.add('hidden');
    }
  }

  function showAlert(type, message) {
    // Remove existing alerts
    document.querySelectorAll('.alert').forEach(alert => alert.remove());
    
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
      <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info'}" class="w-5 h-5"></i>
      <span>${message}</span>
    `;
    
    document.body.appendChild(alertDiv);
    lucide.createIcons();
    
    // Remove after 5 seconds
    setTimeout(() => {
      alertDiv.style.animation = 'slideOut 0.3s ease-out';
      setTimeout(() => {
        if (alertDiv.parentNode) {
          alertDiv.parentNode.removeChild(alertDiv);
        }
      }, 300);
    }, 5000);
  }

  function setupPaymentSearch() {
    const searchInput = document.getElementById('searchInput');
    const paymentStatusFilter = document.getElementById('paymentStatusFilter');
    const prescriptionStatusFilter = document.getElementById('prescriptionStatusFilter');
    
    // Search on enter key
    searchInput?.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        currentPage = 1;
        loadPayments();
      }
    });
    
    // Filter on change
    paymentStatusFilter?.addEventListener('change', function() {
      currentPage = 1;
      loadPayments();
    });
    
    prescriptionStatusFilter?.addEventListener('change', function() {
      currentPage = 1;
      loadPayments();
    });
  }

  function exportReport() {
    showAlert('info', 'Fitur ekspor data akan segera tersedia');
  }

  // =============== GLOBAL FUNCTIONS ===============
  window.loadPayments = loadPayments;
  window.changePage = changePage;
  window.openPaymentModal = openPaymentModal;
  window.closeModal = closeModal;
  window.viewPayment = viewPayment;
  window.closeViewModal = closeViewModal;
  window.printInvoice = printInvoice;
  window.cancelPayment = cancelPayment;
  window.exportReport = exportReport;
</script>
@endpush
@endsection