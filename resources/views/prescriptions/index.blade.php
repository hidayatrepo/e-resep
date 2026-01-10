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
  <div class="flex items-center gap-2 md:gap-3">
    <button onclick="exportReport()"
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Data</span>
    </button>
    <button onclick="openModal('add')"
      class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-full font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Resep Baru</span>
    </button>
  </div>
</div>

<!-- Search & Filter -->
<div class="mb-6">
  <div class="flex flex-col md:flex-row gap-3 items-end">
    <!-- Search Input - 60% -->
    <div class="flex-1 relative md:basis-3/5">
      <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary w-5 h-5"></i>
      <input type="text" id="searchInput" placeholder="Cari resep berdasarkan nama pasien atau no. resep..." 
        class="w-full pl-12 pr-4 py-2 rounded-2xl border border-border focus:outline-none focus:ring-2 focus:ring-primary" autocomplete="off">
    </div>

    <!-- Date Range - 30% -->
    <div class="flex gap-2 flex-1 md:basis-3/10">
      <input type="date" id="startDateFilter" placeholder="Dari" class="rounded-2xl flex-1 px-3 py-3 border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
      <input type="date" id="endDateFilter" placeholder="Sampai" class="rounded-2xl flex-1 px-3 py-3 border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
    </div>

    <!-- Search Button - 10% -->
    <button onclick="loadPrescriptions()" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-2 ring-1 ring-primary text-primary rounded-full font-medium hover:bg-primary hover:text-white transition-all md:basis-1/10">
      <i data-lucide="search" class="w-4 h-4"></i>
      <span>Cari</span>
    </button>
  </div>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="hidden text-center py-8">
  <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
  <p class="mt-2 text-secondary">Memuat data...</p>
</div>

<!-- No Data Message -->
<div id="noDataMessage" class="hidden text-center py-12">
  <i data-lucide="file-text" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
  <p class="text-gray-500">Tidak ada data resep ditemukan</p>
</div>

<!-- Prescriptions Table -->
<div id="prescriptionsTableContainer" class="rounded-2xl border border-border overflow-hidden bg-white">
  <div class="overflow-x-auto">
    <table id="prescriptionsDataTable" class="w-full">
      <thead>
        <tr class="border-b border-border bg-muted">
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">No. Resep</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Nama Pasien</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Dokter</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Obat</th>
          <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status</th>
          <th class="px-6 py-4 text-center text-sm font-semibold text-foreground">Aksi</th>
        </tr>
      </thead>
      <tbody id="prescriptionsTable">
        <!-- Data akan diisi oleh JavaScript -->
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex items-center justify-between px-6 py-4 border-t border-border">
    <p class="text-sm text-secondary">Menampilkan <span id="showing">0</span> sampai <span id="total">0</span> dari <span id="totalRecords">0</span> resep</p>
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

<!-- ADD/EDIT Modal -->
<div id="prescriptionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
    <!-- Modal Header -->
    <div class="flex items-center justify-between p-6 border-b border-border sticky top-0 bg-white">
      <h2 id="modalTitle" class="text-2xl font-bold text-foreground">Resep Baru</h2>
      <button onclick="closeModal()" class="text-secondary hover:text-foreground transition-all">
        <i data-lucide="x" class="w-6 h-6"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <form id="prescriptionForm" class="p-6 space-y-6">
      @csrf
      <input type="hidden" id="prescriptionId">
      
      <!-- Patient Info -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Nama Pasien *</label>
          <input type="text" id="patientName" name="patient_name" placeholder="Masukkan nama pasien..." 
            class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Tanggal Pemeriksaan *</label>
          <input type="datetime-local" id="examinationDate" name="examination_date" 
            class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary" required>
        </div>
      </div>

      <!-- Vital Signs -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Tanda-Tanda Vital</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Tinggi Badan (cm)</label>
            <input type="number" id="height" name="height" placeholder="170" step="0.1"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Berat Badan (kg)</label>
            <input type="number" id="weight" name="weight" placeholder="70" step="0.1"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Systole (mmHg)</label>
            <input type="number" id="systole" name="systole" placeholder="120"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Diastole (mmHg)</label>
            <input type="number" id="diastole" name="diastole" placeholder="80"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Heart Rate (bpm)</label>
            <input type="number" id="heartRate" name="heart_rate" placeholder="72"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Resp Rate (per min)</label>
            <input type="number" id="respirationRate" name="respiration_rate" placeholder="16"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Suhu Tubuh (°C)</label>
            <input type="number" id="temperature" name="temperature" placeholder="37" step="0.1"
              class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
        </div>
      </div>

      <!-- Examination Result -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Hasil Pemeriksaan</h3>
        <textarea id="examinationResult" name="examination_result" placeholder="Tuliskan hasil pemeriksaan dokter..." rows="2"
          class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm"></textarea>
      </div>

      <!-- Medicine Selection -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Obat yang Diberikan *</h3>
        <div class="space-y-4" id="medicineList">
          <!-- Medicine rows will be added here -->
        </div>
        <div class="mt-4">
          <button type="button" onclick="addMedicineRow()" class="flex items-center gap-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary-hover transition-all cursor-pointer" style="width: 100%;background:transparent;color:#0443A8;border:1px solid #0443A8">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Obat
          </button>
        </div>
      </div>

      <!-- Medical Files Upload -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Upload Berkas Pemeriksaan (Opsional)</h3>
        <div class="flex items-center justify-center w-full mb-4">
          <label class="w-full flex flex-col items-center justify-center border-2 border-dashed border-border rounded-lg p-6 cursor-pointer hover:border-primary hover:bg-muted/50 transition-all">
            <i data-lucide="upload-cloud" class="w-8 h-8 text-secondary mb-2"></i>
            <span class="text-sm font-medium text-foreground">Klik untuk upload atau drag file</span>
            <span class="text-xs text-secondary mt-1">PNG, JPG, PDF maksimal 5MB</span>
            <input type="file" id="fileUpload" class="hidden" accept=".png,.jpg,.jpeg,.pdf" multiple>
          </label>
        </div>
        <!-- File Preview -->
        <div id="filePreview" class="space-y-3">
          <!-- Preview items will be added here -->
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-6 border-t border-border sticky bottom-0 bg-white">
        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-2.5 ring-1 ring-border text-foreground rounded-lg font-medium hover:bg-muted transition-all">
          Batal
        </button>
        <button type="submit" id="submitButton" class="flex-1 flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary-hover transition-all">
          <i data-lucide="save" class="w-4 h-4"></i>
          <span id="submitButtonText">Simpan Resep</span>
        </button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  /* Select2 Custom Styling */
  .select2-container--default .select2-selection--single {
    border: 1px solid #E5E7EB;
    border-radius: 0.5rem;
    height: auto;
    padding: 0;
    min-height: 38px;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    padding: 0.5rem 0.75rem;
    line-height: 1.4;
    color: #111827;
    font-size: 0.875rem;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: auto;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
  }
  
  .select2-container--default.select2-container--focus .select2-selection--single,
  .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #0443A8;
    box-shadow: 0 0 0 3px rgba(4, 67, 168, 0.1);
  }
  
  .select2-container--default .select2-results__option {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.4;
  }
  
  .select2-container--default .select2-results__option--selected {
    background-color: #0443A8;
    color: white;
  }
  
  .select2-container--default .select2-results__option--highlighted.select2-results__option--selected {
    background-color: #03358A;
    color: white;
  }
  
  .select2-container--default .select2-results__option--highlighted:not(.select2-results__option--selected) {
    background-color: #F3F4F6;
    color: #111827;
  }
  
  .select2-dropdown {
    border: 1px solid #E5E7EB;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin-top: 4px;
  }
  
  .select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #E5E7EB;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
  }
  
  .select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #0443A8;
    outline: none;
    box-shadow: 0 0 0 3px rgba(4, 67, 168, 0.1);
  }
  
  .select2-results {
    margin: 0;
    padding: 0;
  }
  
  .select2-results__group {
    padding: 0;
    margin: 0;
  }
  
  .select2-results > .select2-results__options {
    max-height: 250px;
  }
  
  /* Remove extra spacing */
  .select2-container {
    width: 100% !important;
  }
  
  /* Hide clear button */
  .select2-container--default .select2-selection__clear {
    display: none !important;
  }

  /* Custom alert styles */
  .alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    max-width: 400px;
    animation: slideIn 0.3s ease-out;
  }

  .alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
  }

  .alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  .alert-info {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
  }

  @keyframes slideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(100%);
      opacity: 0;
    }
  }
</style>
@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  // =============== GLOBAL VARIABLES ===============
  let currentModalMode = 'add';
  let currentPrescriptionId = null;
  let currentPage = 1;
  let totalPages = 1;
  let totalRecords = 0;
  let medicinesData = [];
  
  // Dummy medicines data (in real app, fetch from API)
  const dummyMedicines = [
    { id: '1', name: 'Amoxicillin 500mg Tablet (AMOXSAN)', price: 5000 },
    { id: '2', name: 'Paracetamol 500mg Tablet (SANMOL)', price: 3000 },
    { id: '3', name: 'Metformin 500mg Tablet (DIABETA)', price: 4500 },
    { id: '4', name: 'CTM 4mg Tablet', price: 2000 },
    { id: '5', name: 'Ibuprofen 400mg Tablet', price: 3500 },
    { id: '6', name: 'Omeprazole 20mg Capsule', price: 6000 },
    { id: '7', name: 'Amlodipine 5mg Tablet', price: 5500 },
    { id: '8', name: 'Glibenclamide 5mg Tablet', price: 4000 }
  ];

  // =============== INITIALIZATION ===============
  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    initializeApp();
  });

  async function initializeApp() {
    try {
      // Load medicines data
      await loadMedicines();
      
      // Set default date range
      setDefaultDateRange();
      
      // Load initial prescriptions
      await loadPrescriptions();
      
      // Initialize Select2
      setTimeout(() => {
        initializeSelect2();
      }, 200);
      
      // Set up form submission
      setupFormSubmission();
      
      // Set up search functionality
      setupSearchFunctionality();
      
      // Setup file upload
      setupFileUpload();
      
    } catch (error) {
      console.error('Error initializing app:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat aplikasi');
    }
  }

  function setDefaultDateRange() {
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('startDateFilter').value = formatDate(firstDayOfMonth);
    document.getElementById('endDateFilter').value = formatDate(today);
  }

  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  // =============== MEDICINES MANAGEMENT ===============
  async function loadMedicines() {
    try {
      // In real app, fetch from API: /api/medicines
      medicinesData = dummyMedicines;
    } catch (error) {
      console.error('Error loading medicines:', error);
      medicinesData = dummyMedicines;
    }
  }

  function initializeSelect2() {
    document.querySelectorAll('.medicineSelect').forEach(select => {
      if ($(select).hasClass('select2-hidden-accessible')) {
        $(select).select2('destroy');
      }
      $(select).select2({
        placeholder: '-- Cari dan Pilih Obat --',
        allowClear: false,
        width: '100%',
        data: medicinesData.map(m => ({ 
          id: m.id, 
          text: `${m.name} - Rp ${m.price.toLocaleString('id-ID')}`,
          price: m.price 
        })),
        escapeMarkup: function (markup) { return markup; }
      }).on('select2:select', function (e) {
        // Set harga otomatis saat obat dipilih
        const selectedData = e.params.data;
        const row = $(this).closest('.flex.gap-3.items-end');
        const unitPriceInput = row.find('.unitPriceInput');
        if (selectedData.price && !unitPriceInput.val()) {
          unitPriceInput.val(selectedData.price);
        }
      });
    });
  }

  // =============== PRESCRIPTIONS CRUD ===============
  async function loadPrescriptions() {
    showLoading(true);
    
    try {
      const search = document.getElementById('searchInput').value;
      const startDate = document.getElementById('startDateFilter').value;
      const endDate = document.getElementById('endDateFilter').value;
      
      // Gunakan CSRF token yang ada di meta tag atau buat baru
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/prescriptions/get', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          search: search,
          start_date: startDate,
          end_date: endDate,
          page: currentPage,
          per_page: 10
        })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      
      if (result.success) {
        updatePrescriptionsTable(result.data);
      } else {
        showAlert('error', result.message || 'Gagal memuat data resep');
      }
    } catch (error) {
      console.error('Error loading prescriptions:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat data: ' + error.message);
    } finally {
      showLoading(false);
    }
  }

  function updatePrescriptionsTable(data) {
    const tableBody = document.getElementById('prescriptionsTable');
    const container = document.getElementById('prescriptionsTableContainer');
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
    data.data.forEach((prescription, index) => {
      // Format date
      const examDate = new Date(prescription.examination_date);
      const formattedDate = examDate.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
      
      // Get medicines list
      let medicinesList = '';
      if (prescription.items && prescription.items.length > 0) {
        medicinesList = prescription.items.map(item => item.medicine_name).slice(0, 2).join(', ');
        if (prescription.items.length > 2) {
          medicinesList += ` dan ${prescription.items.length - 2} lainnya`;
        }
      } else {
        medicinesList = '-';
      }
      
      // Status badge
      let statusBadge = '';
      let canEdit = false;
      let canDelete = false;
      
      switch(prescription.status) {
        case 'draft':
          statusBadge = `
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">
              <i data-lucide="file-text" class="w-4 h-4"></i>
              Draft
            </span>
          `;
          canEdit = true;
          canDelete = true;
          break;
        case 'process':
          statusBadge = `
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
              <i data-lucide="clock" class="w-4 h-4"></i>
              Proses
            </span>
          `;
          canEdit = true;
          canDelete = true;
          break;
        case 'completed':
          statusBadge = `
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              Selesai
            </span>
          `;
          canEdit = false;
          canDelete = false;
          break;
        case 'cancelled':
          statusBadge = `
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium">
              <i data-lucide="x-circle" class="w-4 h-4"></i>
              Dibatalkan
            </span>
          `;
          canEdit = false;
          canDelete = false;
          break;
        default:
          statusBadge = `
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">
              <i data-lucide="help-circle" class="w-4 h-4"></i>
              ${prescription.status}
            </span>
          `;
      }
      
      html += `
        <tr class="border-b border-border hover:bg-muted/50 transition-all" 
            data-date="${prescription.examination_date ? prescription.examination_date.split('T')[0] : ''}" 
            data-status="${prescription.status}">
          <td class="px-6 py-4 text-sm text-foreground font-medium">${prescription.prescription_number || '-'}</td>
          <td class="px-6 py-4 text-sm text-secondary">${prescription.patient_name || '-'}</td>
          <td class="px-6 py-4 text-sm text-secondary">${prescription.doctor_name || '-'}</td>
          <td class="px-6 py-4 text-sm text-secondary">${formattedDate}</td>
          <td class="px-6 py-4 text-sm text-secondary">${medicinesList}</td>
          <td class="px-6 py-4 text-sm">${statusBadge}</td>
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center justify-center gap-2">
              <button onclick="viewPrescription(${prescription.id})" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              ${canEdit ? `
                <button onclick="editPrescription(${prescription.id})" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                  <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
                </button>
              ` : ''}
              ${canDelete ? `
                <button onclick="deletePrescription(${prescription.id})" class="p-2 hover:bg-red-100 rounded-lg transition-all" aria-label="Hapus">
                  <i data-lucide="trash-2" class="w-4 h-4 text-red-600"></i>
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
      loadPrescriptions();
    }
  }

  // =============== MODAL FUNCTIONS ===============
  function openModal(mode = 'add', id = null) {
    currentModalMode = mode;
    currentPrescriptionId = id;
    
    const modal = document.getElementById('prescriptionModal');
    const title = document.getElementById('modalTitle');
    const submitButtonText = document.getElementById('submitButtonText');
    
    // Reset form and clear medicines
    document.getElementById('prescriptionForm').reset();
    document.getElementById('medicineList').innerHTML = '';
    document.getElementById('filePreview').innerHTML = '';
    
    // Re-enable all inputs
    document.querySelectorAll('#prescriptionForm input, #prescriptionForm textarea, #prescriptionForm select, #prescriptionForm button[type="button"]').forEach(el => {
      el.disabled = false;
    });
    
    if (mode === 'add') {
      title.textContent = 'Resep Baru';
      submitButtonText.textContent = 'Simpan Resep';
      setCurrentDateTime();
      addMedicineRow(); // Add initial medicine row
    } else if (mode === 'edit' && id) {
      title.textContent = 'Edit Resep';
      submitButtonText.textContent = 'Update Resep';
      loadPrescriptionData(id);
    } else if (mode === 'view' && id) {
      title.textContent = 'Detail Resep';
      submitButtonText.textContent = 'Tutup';
      loadPrescriptionData(id);
      // Disable all inputs for view mode
      document.querySelectorAll('#prescriptionForm input, #prescriptionForm textarea, #prescriptionForm select, #prescriptionForm button[type="button"]').forEach(el => {
        el.disabled = true;
      });
      // But keep the cancel button enabled
      const cancelBtn = document.querySelector('button[onclick="closeModal()"]');
      if (cancelBtn) cancelBtn.disabled = false;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    lucide.createIcons();
    setTimeout(() => {
      initializeSelect2();
    }, 100);
  }

  function setCurrentDateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const date = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    document.getElementById('examinationDate').value = `${year}-${month}-${date}T${hours}:${minutes}`;
  }

  async function loadPrescriptionData(id) {
    try {
      // Gunakan CSRF token yang ada di meta tag atau buat baru
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/prescriptions/get', {
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
        populateForm(result.data);
      } else {
        showAlert('error', 'Gagal memuat data resep');
        closeModal();
      }
    } catch (error) {
      console.error('Error loading prescription:', error);
      showAlert('error', 'Terjadi kesalahan saat memuat data');
      closeModal();
    }
  }

  function populateForm(data) {
    // Set prescription ID
    document.getElementById('prescriptionId').value = data.id || '';
    
    // Basic info
    document.getElementById('patientName').value = data.patient_name || '';
    document.getElementById('examinationDate').value = data.examination_date ? data.examination_date.slice(0, 16) : '';
    document.getElementById('examinationResult').value = data.examination_result || '';
    
    // Vital signs
    document.getElementById('height').value = data.height || '';
    document.getElementById('weight').value = data.weight || '';
    document.getElementById('systole').value = data.systole || '';
    document.getElementById('diastole').value = data.diastole || '';
    document.getElementById('heartRate').value = data.heart_rate || '';
    document.getElementById('respirationRate').value = data.respiration_rate || '';
    document.getElementById('temperature').value = data.temperature || '';
    
    // Clear and add medicines
    document.getElementById('medicineList').innerHTML = '';
    if (data.items && data.items.length > 0) {
      data.items.forEach((item, index) => {
        addMedicineRow(item, index === 0);
      });
    } else {
      addMedicineRow();
    }
  }

  function addMedicineRow(medicineData = null, isFirst = false) {
    const medicineList = document.getElementById('medicineList');
    const rowId = Date.now() + Math.random();
    
    const row = document.createElement('div');
    row.className = 'flex gap-3 items-end';
    row.id = `medicineRow-${rowId}`;
    
    row.innerHTML = `
      <div class="flex-1">
        <label class="block text-xs font-medium text-secondary mb-2">Nama Obat</label>
        <select class="medicineSelect w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm" style="width: 100%">
          <option value="">-- Cari dan Pilih Obat --</option>
          ${medicinesData.map(m => `
            <option value="${m.id}" data-price="${m.price}" ${medicineData && medicineData.medicine_id == m.id ? 'selected' : ''}>
              ${m.name} - Rp ${m.price.toLocaleString('id-ID')}
            </option>
          `).join('')}
        </select>
      </div>
      <div class="w-24">
        <label class="block text-xs font-medium text-secondary mb-2">Jumlah</label>
        <input type="number" class="quantityInput w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm"
          placeholder="1" min="1" value="${medicineData ? medicineData.quantity : '1'}">
      </div>
      <div class="w-32">
        <label class="block text-xs font-medium text-secondary mb-2">Harga Satuan</label>
        <input type="number" class="unitPriceInput w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm"
          placeholder="0" min="0" step="100" value="${medicineData ? medicineData.unit_price : '0'}">
      </div>
      ${isFirst ? `
        <button type="button" onclick="removeMedicineRow('${rowId}')" class="p-2 bg-error hover:bg-error-dark text-white rounded-full transition-all mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4"><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path><path d="M3 6h18"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        </button>
      ` : `
        <button type="button" onclick="removeMedicineRow('${rowId}')" class="p-2 bg-error hover:bg-error-dark text-white rounded-full transition-all mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4"><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path><path d="M3 6h18"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        </button>
      `}
    `;
    
    medicineList.appendChild(row);
    
    // Initialize Select2
    const select = row.querySelector('.medicineSelect');
    setTimeout(() => {
      $(select).select2({
        placeholder: '-- Cari dan Pilih Obat --',
        allowClear: false,
        width: '100%',
        data: medicinesData.map(m => ({ 
          id: m.id, 
          text: `${m.name} - Rp ${m.price.toLocaleString('id-ID')}`,
          price: m.price 
        })),
        escapeMarkup: function (markup) { return markup; }
      }).on('select2:select', function (e) {
        // Set harga otomatis saat obat dipilih
        const selectedData = e.params.data;
        const row = $(this).closest('.flex.gap-3.items-end');
        const unitPriceInput = row.find('.unitPriceInput');
        if (selectedData.price && !unitPriceInput.val()) {
          unitPriceInput.val(selectedData.price);
        }
      });
      
      // Set initial price if medicine data provided
      if (medicineData && medicineData.medicine_id) {
        $(select).val(medicineData.medicine_id).trigger('change');
        const unitPriceInput = row.querySelector('.unitPriceInput');
        if (medicineData.unit_price && !unitPriceInput.value) {
          unitPriceInput.value = medicineData.unit_price;
        }
      }
    }, 100);
  }

  function removeMedicineRow(rowId) {
    const row = document.getElementById(`medicineRow-${rowId}`);
    if (row) {
      // Destroy Select2 instance before removing
      const select = row.querySelector('.medicineSelect');
      if (select && $(select).hasClass('select2-hidden-accessible')) {
        $(select).select2('destroy');
      }
      row.remove();
    }
  }

  function closeModal() {
    const modal = document.getElementById('prescriptionModal');
    const form = document.getElementById('prescriptionForm');
    
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Re-enable all inputs
    form.querySelectorAll('input, textarea, select, button').forEach(el => {
      el.disabled = false;
    });
  }

  // =============== FORM SUBMISSION ===============
  function setupFormSubmission() {
    const form = document.getElementById('prescriptionForm');
    form.addEventListener('submit', handleFormSubmit);
  }

  async function handleFormSubmit(event) {
    event.preventDefault();
    
    if (currentModalMode === 'view') {
      closeModal();
      return;
    }
    
    // Collect form data
    const formData = collectFormData();
    
    // Validate
    if (!validateFormData(formData)) {
      return;
    }
    
    const endpoint = currentModalMode === 'add' 
      ? '/api/prescriptions/add' 
      : '/api/prescriptions/update';
    
    // Add ID for update
    if (currentModalMode === 'edit') {
      formData.id = currentPrescriptionId;
    }
    
    // Tambahkan field yang diperlukan untuk model
    formData.doctor_name = 'Dr. Dokter Umum'; // Default, bisa diganti dengan input dokter
    formData.status = 'draft'; // Default status
    
    // Disable submit button
    const submitButton = document.getElementById('submitButton');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...';
    
    try {
      // Gunakan CSRF token yang ada di meta tag atau buat baru
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch(endpoint, {
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
        showAlert('success', currentModalMode === 'add' ? 'Resep berhasil dibuat' : 'Resep berhasil diperbarui');
        closeModal();
        loadPrescriptions();
      } else {
        if (result.errors) {
          showAlert('error', result.errors.join(', '));
        } else {
          showAlert('error', result.message || 'Terjadi kesalahan');
        }
      }
    } catch (error) {
      console.error('Error submitting form:', error);
      showAlert('error', 'Terjadi kesalahan saat menyimpan data: ' + error.message);
    } finally {
      // Re-enable submit button
      submitButton.disabled = false;
      submitButton.innerHTML = originalText;
      lucide.createIcons();
    }
  }

  function collectFormData() {
    const formData = {
      patient_name: document.getElementById('patientName').value,
      examination_date: document.getElementById('examinationDate').value,
      examination_result: document.getElementById('examinationResult').value,
      height: document.getElementById('height').value || null,
      weight: document.getElementById('weight').value || null,
      systole: document.getElementById('systole').value || null,
      diastole: document.getElementById('diastole').value || null,
      heart_rate: document.getElementById('heartRate').value || null,
      respiration_rate: document.getElementById('respirationRate').value || null,
      temperature: document.getElementById('temperature').value || null,
      status: 'draft', // Default status
      items: []
    };
    
    // Collect medicines
    const medicineRows = document.querySelectorAll('#medicineList > div');
    medicineRows.forEach(row => {
      const select = row.querySelector('.medicineSelect');
      const selectedOption = select?.selectedOptions[0];
      const quantityInput = row.querySelector('.quantityInput');
      const unitPriceInput = row.querySelector('.unitPriceInput');
      
      if (selectedOption && selectedOption.value && quantityInput && quantityInput.value && unitPriceInput && unitPriceInput.value) {
        formData.items.push({
          medicine_id: selectedOption.value,
          medicine_name: selectedOption.text.split(' - ')[0], // Get name without price
          quantity: parseInt(quantityInput.value),
          unit_price: parseFloat(unitPriceInput.value),
          unit: 'tablet',
          instructions: '' // Default, bisa ditambahkan field instructions
        });
      }
    });
    
    return formData;
  }

  function validateFormData(formData) {
    if (!formData.patient_name.trim()) {
      showAlert('error', 'Nama pasien harus diisi');
      return false;
    }
    
    if (!formData.examination_date) {
      showAlert('error', 'Tanggal pemeriksaan harus diisi');
      return false;
    }
    
    if (formData.items.length === 0) {
      showAlert('error', 'Minimal satu obat harus ditambahkan');
      return false;
    }
    
    // Validate each medicine
    for (let i = 0; i < formData.items.length; i++) {
      const item = formData.items[i];
      if (!item.medicine_name.trim()) {
        showAlert('error', `Nama obat ke-${i + 1} harus diisi`);
        return false;
      }
      if (!item.quantity || item.quantity < 1) {
        showAlert('error', `Jumlah obat ke-${i + 1} harus diisi`);
        return false;
      }
      if (!item.unit_price || item.unit_price < 0) {
        showAlert('error', `Harga obat ke-${i + 1} harus diisi`);
        return false;
      }
    }
    
    return true;
  }

  // =============== CRUD OPERATIONS ===============
  async function viewPrescription(id) {
    openModal('view', id);
  }

  async function editPrescription(id) {
    openModal('edit', id);
  }

  async function deletePrescription(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus resep ini?')) {
      return;
    }
    
    try {
      // Gunakan CSRF token yang ada di meta tag atau buat baru
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value ||
                       '{{ csrf_token() }}';
      
      const response = await fetch('/api/prescriptions/delete', {
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
        showAlert('success', 'Resep berhasil dihapus');
        loadPrescriptions();
      } else {
        showAlert('error', result.message || 'Gagal menghapus resep');
      }
    } catch (error) {
      console.error('Error deleting prescription:', error);
      showAlert('error', 'Terjadi kesalahan saat menghapus data');
    }
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

  function setupSearchFunctionality() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.querySelector('button[onclick="loadPrescriptions()"]');
    
    // Search on enter key
    searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
        currentPage = 1;
        loadPrescriptions();
      }
    });
    
    // Update date filter event listeners
    document.getElementById('startDateFilter').addEventListener('change', function() {
      currentPage = 1;
      loadPrescriptions();
    });
    
    document.getElementById('endDateFilter').addEventListener('change', function() {
      currentPage = 1;
      loadPrescriptions();
    });
  }

  function setupFileUpload() {
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const uploadArea = fileUpload?.parentElement;

    if (!fileUpload || !uploadArea) return;

    fileUpload.addEventListener('change', handleFileUpload);

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('border-primary', 'bg-muted');
    });
    
    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('border-primary', 'bg-muted');
    });
    
    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('border-primary', 'bg-muted');
      fileUpload.files = e.dataTransfer.files;
      handleFileUpload();
    });
  }

  function handleFileUpload() {
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const files = fileUpload.files;

    if (!filePreview || !files) return;

    filePreview.innerHTML = '';

    if (files.length === 0) {
      return;
    }

    Array.from(files).forEach((file, index) => {
      const fileSize = (file.size / 1024 / 1024).toFixed(2);
      const fileType = file.type.split('/')[1] || file.name.split('.').pop();
      const fileName = file.name;

      const previewItem = document.createElement('div');
      previewItem.className = 'flex items-center justify-between p-4 border border-border rounded-lg bg-muted';
      previewItem.innerHTML = `
        <div class="flex items-center gap-3">
          <div class="p-2 bg-blue-100 rounded-lg">
            <i data-lucide="file" class="w-5 h-5 text-blue-600"></i>
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium text-foreground">${fileName}</p>
            <p class="text-xs text-secondary">${fileSize} MB</p>
          </div>
        </div>
        <button type="button" onclick="removeFile(${index})" class="p-2 hover:bg-red-100 rounded-lg transition-all">
          <i data-lucide="x" class="w-4 h-4 text-red-600"></i>
        </button>
      `;
      filePreview.appendChild(previewItem);
    });

    lucide.createIcons();
  }

  function removeFile(index) {
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    
    if (!fileUpload || !filePreview) return;
    
    // Create new FileList (cannot modify existing one)
    const dt = new DataTransfer();
    const files = Array.from(fileUpload.files);
    
    files.forEach((file, i) => {
      if (i !== index) {
        dt.items.add(file);
      }
    });
    
    fileUpload.files = dt.files;
    
    // Update preview
    handleFileUpload();
  }

  // =============== EXISTING FUNCTIONS (UPDATED) ===============
  function exportReport() {
    showAlert('info', 'Fitur ekspor laporan akan segera tersedia');
  }

  // Update the existing deleteItem function
  window.deleteItem = function(id) {
    deletePrescription(id);
  };

  // Make functions available globally
  window.openModal = openModal;
  window.closeModal = closeModal;
  window.loadPrescriptions = loadPrescriptions;
  window.changePage = changePage;
  window.addMedicineRow = addMedicineRow;
  window.removeMedicineRow = removeMedicineRow;
  window.viewPrescription = viewPrescription;
  window.editPrescription = editPrescription;
  window.deletePrescription = deletePrescription;
  window.exportReport = exportReport;
</script>
@endpush
@endsection