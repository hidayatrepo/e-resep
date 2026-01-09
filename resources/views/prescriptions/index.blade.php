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
      class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-lg text-foreground font-medium transition-all duration-200 cursor-pointer">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Ekspor Laporan</span>
    </button>
    <button onclick="openModal('add')"
      class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-full font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Resep Baru</span>
    </button>
  </div>
</div>

<!-- Search & Filter -->
<div class="rounded-2xl border border-border p-4 bg-white mb-6 md:mb-8">
  <div class="flex flex-col md:flex-row gap-3 items-end">
    <!-- Search Input - 60% -->
    <div class="flex-1 relative md:basis-3/5">
      <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary w-5 h-5"></i>
      <input type="text" id="searchInput" placeholder="Cari resep berdasarkan nama pasien atau no. resep..." 
        class="w-full pl-12 pr-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
    </div>

    <!-- Date Range - 30% -->
    <div class="flex gap-2 flex-1 md:basis-3/10">
      <input type="date" id="startDateFilter" placeholder="Dari" class="flex-1 px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
      <input type="date" id="endDateFilter" placeholder="Sampai" class="flex-1 px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
    </div>

    <!-- Search Button - 10% -->
    <button onclick="filterTableData()" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-2 ring-1 ring-primary text-primary rounded-full font-medium hover:bg-primary hover:text-white transition-all md:basis-1/10">
      <i data-lucide="search" class="w-4 h-4"></i>
      <span>Cari</span>
    </button>
  </div>
</div>

<!-- Prescriptions Table -->
<div class="rounded-2xl border border-border overflow-hidden bg-white">
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
        <!-- Sample Row -->
        <tr class="border-b border-border hover:bg-muted/50 transition-all" data-date="2026-01-10" data-status="proses">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-001</td>
          <td class="px-6 py-4 text-sm text-secondary">Budi Santoso</td>
          <td class="px-6 py-4 text-sm text-secondary">Dr. Siti Nurhaliza</td>
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
              <button onclick="openModal('view', 1)" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button onclick="openModal('edit', 1)" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button onclick="deleteItem(1)" class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <tr class="border-b border-border hover:bg-muted/50 transition-all" data-date="2026-01-09" data-status="selesai">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-002</td>
          <td class="px-6 py-4 text-sm text-secondary">Siti Nurhaliza</td>
          <td class="px-6 py-4 text-sm text-secondary">Dr. Ahmad Wijaya</td>
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
              <button onclick="openModal('view', 2)" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button onclick="openModal('edit', 2)" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button onclick="deleteItem(2)" class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4 text-error"></i>
              </button>
            </div>
          </td>
        </tr>

        <tr class="border-b border-border hover:bg-muted/50 transition-all" data-date="2026-01-08" data-status="dibatalkan">
          <td class="px-6 py-4 text-sm text-foreground font-medium">RX-2024-003</td>
          <td class="px-6 py-4 text-sm text-secondary">Ahmad Wijaya</td>
          <td class="px-6 py-4 text-sm text-secondary">Dr. Budi Santoso</td>
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
              <button onclick="openModal('view', 3)" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Lihat">
                <i data-lucide="eye" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button onclick="openModal('edit', 3)" class="p-2 hover:bg-muted rounded-lg transition-all" aria-label="Edit">
                <i data-lucide="edit-2" class="w-4 h-4 text-secondary hover:text-primary"></i>
              </button>
              <button onclick="deleteItem(3)" class="p-2 hover:bg-error-lighter rounded-lg transition-all" aria-label="Hapus">
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
    <p class="text-sm text-secondary">Menampilkan <span id="showing">1</span> sampai <span id="total">3</span> dari 24 resep</p>
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
      <!-- Patient Info -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Nama Pasien</label>
          <input type="text" id="patientName" placeholder="Masukkan nama pasien..." class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground mb-2">Tanggal Pemeriksaan</label>
          <input type="datetime-local" id="examinationDate" class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
      </div>

      <!-- Vital Signs -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Tanda-Tanda Vital</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Tinggi Badan (cm)</label>
            <input type="number" placeholder="170" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Berat Badan (kg)</label>
            <input type="number" placeholder="70" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Systole (mmHg)</label>
            <input type="number" placeholder="120" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Diastole (mmHg)</label>
            <input type="number" placeholder="80" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Heart Rate (bpm)</label>
            <input type="number" placeholder="72" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Resp Rate (per min)</label>
            <input type="number" placeholder="16" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
          <div>
            <label class="block text-xs font-medium text-secondary mb-2">Suhu Tubuh (°C)</label>
            <input type="number" placeholder="37" step="0.1" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
          </div>
        </div>
      </div>

      <!-- Examination Result -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Hasil Pemeriksaan</h3>
        <textarea placeholder="Tuliskan hasil pemeriksaan dokter..." rows="2" class="w-full px-4 py-3 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm"></textarea>
      </div>

      <!-- Medicine Selection -->
      <div class="border-t border-border pt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Obat yang Diberikan</h3>
        <div class="space-y-4" id="medicineList">
          <div class="flex gap-3 items-end">
            <div class="flex-1">
              <label class="block text-xs font-medium text-secondary mb-2">Nama Obat</label>
              <select class="medicineSelect w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm" style="width: 100%">
                <option value="">-- Cari dan Pilih Obat --</option>
              </select>
            </div>
            <div class="w-24">
              <label class="block text-xs font-medium text-secondary mb-2">Jumlah</label>
              <input type="number" placeholder="10" min="1" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
            </div>
            <button type="button" onclick="addMedicine()" class="p-2 bg-primary hover:bg-primary-hover text-white rounded-full transition-all">
              <i data-lucide="plus" class="w-4 h-4"></i>
            </button>
          </div>
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
            <input type="file" id="fileUpload" class="hidden" accept=".png,.jpg,.jpeg,.pdf">
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
        <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary-hover transition-all">
          <i data-lucide="save" class="w-4 h-4"></i>
          Simpan Resep
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.0/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.0/vfs_fonts.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
</style>
<script>
  let currentModalMode = 'add';
  let medicinesData = [
    { id: '1', name: 'Amoxicillin 500mg Tablet (AMOXSAN)' },
    { id: '2', name: 'Paracetamol 500mg Tablet (SANMOL)' },
    { id: '3', name: 'Metformin 500mg Tablet (DIABETA)' }
  ];

  // Fetch medicines from API
  async function loadMedicines() {
    try {
      const response = await fetch('/api/medicines');
      const data = await response.json();
      medicinesData = data.medicines || medicinesData;
      initializeSelect2();
    } catch (error) {
      console.error('Error loading medicines:', error);
      // Use default dummy data
      initializeSelect2();
    }
  }

  function initializeSelect2() {
    document.querySelectorAll('.medicineSelect').forEach(select => {
      if (select.classList.contains('select2-hidden-accessible')) {
        // Already initialized, destroy and reinitialize
        $(select).select2('destroy');
      }
      $(select).select2({
        placeholder: '-- Cari dan Pilih Obat --',
        allowClear: false,
        width: '100%',
        data: medicinesData.map(m => ({ id: m.id, text: m.name }))
      });
    });
  }

  function openModal(mode = 'add', id = null) {
    currentModalMode = mode;
    const modal = document.getElementById('prescriptionModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('prescriptionForm');
    const examinationDate = document.getElementById('examinationDate');

    if (mode === 'add') {
      title.textContent = 'Resep Baru';
      form.reset();
      // Auto-fill current date and time
      const now = new Date();
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const date = String(now.getDate()).padStart(2, '0');
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      examinationDate.value = `${year}-${month}-${date}T${hours}:${minutes}`;
    } else if (mode === 'edit') {
      title.textContent = 'Edit Resep';
      // Populate form dengan data
    } else if (mode === 'view') {
      title.textContent = 'Detail Resep';
      form.querySelectorAll('input, textarea, select').forEach(el => el.disabled = true);
    }

    modal.classList.remove('hidden');
    lucide.createIcons();
    setTimeout(() => {
      initializeSelect2();
    }, 100);
  }

  function closeModal() {
    const modal = document.getElementById('prescriptionModal');
    const form = document.getElementById('prescriptionForm');
    form.querySelectorAll('input, textarea, select').forEach(el => el.disabled = false);
    modal.classList.add('hidden');
  }

  function addMedicine() {
    const medicineList = document.getElementById('medicineList');
    const newMedicine = document.createElement('div');
    newMedicine.className = 'flex gap-3 items-end';
    newMedicine.innerHTML = `
      <div class="flex-1">
        <select class="medicineSelect w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm" style="width: 100%">
          <option value="">-- Cari dan Pilih Obat --</option>
        </select>
      </div>
      <div class="w-24">
        <input type="number" placeholder="10" min="1" class="w-full px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary text-sm">
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="p-2 bg-error hover:bg-error-dark text-white rounded-full transition-all">
        <i data-lucide="trash-2" class="w-4 h-4"></i>
      </button>
    `;
    medicineList.appendChild(newMedicine);
    lucide.createIcons();
    
    // Initialize Select2 for the new select
    const newSelect = newMedicine.querySelector('.medicineSelect');
    $(newSelect).select2({
      placeholder: '-- Cari dan Pilih Obat --',
      allowClear: false,
      width: '100%',
      data: medicinesData.map(m => ({ id: m.id, text: m.name }))
    });
  }

  function deleteItem(id) {
    if (confirm('Apakah Anda yakin ingin menghapus resep ini?')) {
      console.log('Hapus resep ID:', id);
      // API call untuk hapus
    }
  }

  function exportReport() {
    // Export functionality is now handled by DataTables buttons (Excel/PDF)
    console.log('Export handled by DataTables buttons');
  }

  // Filter functionality
  document.getElementById('startDateFilter').addEventListener('change', filterTableData);
  document.getElementById('endDateFilter').addEventListener('change', filterTableData);

  function filterTableData() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const startDate = document.getElementById('startDateFilter').value;
    const endDate = document.getElementById('endDateFilter').value;
    const rows = document.querySelectorAll('#prescriptionsTable tr');
    let visibleCount = 0;

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const rowDate = row.getAttribute('data-date');

      const matchesSearch = !searchValue || text.includes(searchValue);
      const matchesStartDate = !startDate || rowDate >= startDate;
      const matchesEndDate = !endDate || rowDate <= endDate;

      if (matchesSearch && matchesStartDate && matchesEndDate) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    document.getElementById('showing').textContent = Math.min(visibleCount, visibleCount);
    document.getElementById('total').textContent = visibleCount;
  }

  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    loadMedicines();
    
    // Set default date range - 1st of current month to today
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    const formatDate = (date) => {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };
    
    document.getElementById('startDateFilter').value = formatDate(firstDayOfMonth);
    document.getElementById('endDateFilter').value = formatDate(today);
    
    // File upload handling
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');

    if (fileUpload) {
      fileUpload.addEventListener('change', handleFileUpload);

      // Drag and drop
      const uploadArea = fileUpload.parentElement;
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

    // Initialize Select2 when DOM is ready
    setTimeout(() => {
      initializeSelect2();
    }, 200);
  });

  function handleFileUpload() {
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const files = fileUpload.files;

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
          <div class="p-2 bg-info-light rounded-lg">
            <i data-lucide="file" class="w-5 h-5 text-info-dark"></i>
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium text-foreground">${fileName}</p>
            <p class="text-xs text-secondary">${fileSize} MB</p>
          </div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-2 hover:bg-error-lighter rounded-lg transition-all">
          <i data-lucide="x" class="w-4 h-4 text-error"></i>
        </button>
      `;
      filePreview.appendChild(previewItem);
    });

    lucide.createIcons();
  }
</script>
@endpush
@endsection
