<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice Pembayaran - {{ $prescription->prescription_number ?? '-' }}</title>

<style>
    * {
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
    }

    body {
        margin: 0;
        background: #f1f5f9;
        padding: 24px;
        color: #0f172a;
    }

    .invoice {
        max-width: 760px;
        margin: auto;
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
    }

    /* HEADER */
    .header {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #ffffff;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 15px;
    }

    .header .title {
        font-size: 22px;
        font-weight: 600;
    }

    .header .subtitle {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .header .meta {
        text-align: right;
        font-size: 13px;
        opacity: 0.95;
    }

    .content {
        padding: 24px;
    }

    /* INFO GRID */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .info-grid b {
        display: block;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 2px;
    }

    /* CARD */
    .card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 10px;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    th {
        text-align: left;
        font-weight: 500;
        color: #64748b;
        border-bottom: 1px solid #e5e7eb;
        padding: 8px 0;
    }

    td {
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    th:last-child,
    td:last-child {
        text-align: right;
    }

    .total {
        display: flex;
        justify-content: flex-end;
        margin-top: 12px;
        font-weight: 600;
        font-size: 15px;
    }

    .total span {
        margin-left: 8px;
        color: #2563eb;
    }

    /* KEY VALUE */
    .kv {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        font-size: 13px;
    }

    .kv b {
        display: block;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 2px;
    }

    /* EXAM */
    .exam {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        font-size: 13px;
    }

    .exam div {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px;
    }

    /* PRINT */
    @media print {
        body {
            background: #ffffff;
            padding: 0;
        }

        .invoice {
            border-radius: 0;
        }

        .header {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
</head>

<body>

<div class="invoice">

    {{-- HEADER --}}
    <div class="header">
        <div>
            <div class="title">Invoice Pembayaran</div>
            <div class="subtitle">E-Resep System</div>
        </div>
        <div class="meta">
            No. Resi: <b>{{ $prescription->receipt_number ?? '-' }}</b><br>
            {{ $prescription->prescription_number ?? '-' }}
        </div>
    </div>

    <div class="content">

        {{-- INFO --}}
        <div class="info-grid">
            <div>
                <b>Nama Pasien</b>
                {{ $prescription->patient_name ?? '-' }}
            </div>
            <div>
                <b>Dokter</b>
                {{ $prescription->doctor_name ?? '-' }}
            </div>
            <div>
                <b>Apoteker</b>
                {{ $prescription->pharmacist_name ?? '-' }}
            </div>
            <div>   </div>
            <div>
                <b>Tanggal Pemeriksaan</b>
                {{ $prescription->examination_date ? date('d F Y H:i', strtotime($prescription->examination_date)) : '-' }}
            </div>
            <div>
                <b>Tanggal Pembayaran</b>
                {{ $prescription->served_at ? date('d F Y H:i', strtotime($prescription->served_at)) : '-' }}
            </div>
        </div>

        {{-- OBAT --}}
        <div class="card">
            <div class="card-title">Daftar Obat</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @forelse($prescription->items ?? [] as $i => $item)
                        @php
                            $itemTotal = $item->quantity * $item->unit_price;
                            $subtotal += $itemTotal;
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->medicine_name }}</td>
                            <td>{{ $item->quantity }} {{ $item->unit ?? 'pcs' }}</td>
                            <td>Rp {{ number_format($item->unit_price,0,',','.') }}</td>
                            <td><b>Rp {{ number_format($itemTotal,0,',','.') }}</b></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="total">
                Total Tagihan:
                <span>Rp {{ number_format($prescription->total_price ?? $subtotal,0,',','.') }}</span>
            </div>
        </div>

        {{-- PEMBAYARAN --}}
        <div class="card">
            <div class="card-title">Detail Pembayaran</div>
            <div class="kv">
                <div>
                    <b>Status Pembayaran</b>
                    {{ ucfirst($prescription->payment_status ?? '-') }}
                </div>
                <div>
                    <b>Metode Pembayaran</b>
                    {{ strtoupper($prescription->payment_method ?? '-') }}
                </div>
                <div>
                    <b>Jumlah Dibayar</b>
                    Rp {{ number_format($prescription->payment_amount ?? 0,0,',','.') }}
                </div>
                <div>
                    <b>No. Referensi</b>
                    {{ $prescription->payment_reference ?? '-' }}
                </div>
                @if($prescription->payment_notes)
                <div>
                    <b>Catatan</b>
                    {{ $prescription->payment_notes }}
                </div>
                @endif
            </div>
        </div>

        {{-- PEMERIKSAAN --}}
        @if($prescription->height || $prescription->weight)
        <div class="card">
            <div class="card-title">Data Pemeriksaan</div>
            <div class="exam">
                @if($prescription->height)
                    <div><b>Tinggi Badan</b> <span style="float: right">{{ $prescription->height }} cm </span></div>
                @endif
                @if($prescription->weight)
                    <div><b>Berat Badan</b> <span style="float: right">{{ $prescription->weight }} kg </span></div>
                @endif
                @if($prescription->systole && $prescription->diastole)
                    <div><b>Tekanan Darah</b> <span style="float: right">{{ $prescription->systole }}/{{ $prescription->diastole }} </span></div>
                @endif
                @if($prescription->heart_rate)
                    <div><b>Denyut Jantung</b> <span style="float: right">{{ $prescription->heart_rate }} bpm </span></div>
                @endif
                @if($prescription->respiration_rate)
                    <div><b>Laju Pernapasan</b> <span style="float: right">{{ $prescription->respiration_rate }} rpm </span></div>
                @endif
                @if($prescription->temperature)
                    <div><b>Suhu Tubuh</b> <span style="float: right">{{ $prescription->temperature }} °C </span></div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

<script>
    // Auto print
    window.onload = function () {
        setTimeout(() => window.print(), 300);
    };

    // Auto close tab setelah print
    window.onafterprint = function () {
        window.close();
    };
</script>

</body>
</html>
