<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nasabah - {{ $nasabah->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.35;
            color: #333;
            font-size: 12px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 10px 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 8px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 20px;
            margin-bottom: 2px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .print-date {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 14px;
        }
        .section-title {
            background-color: #1e40af;
            color: white;
            padding: 7px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        .info-card {
            max-width: 520px;
            border: 1px solid #dbe4ff;
            border-radius: 4px;
            padding: 8px 10px;
            background: #fbfdff;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            border: 0;
            background: transparent !important;
            font-size: 12px;
        }
        .info-table .label {
            font-weight: bold;
            color: #1e40af;
            width: 130px;
        }
        .info-table .sep {
            width: 12px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            page-break-inside: avoid;
        }
        table thead {
            background-color: #dbeafe;
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
        }
        table th {
            padding: 7px 8px;
            text-align: left;
            font-weight: bold;
            color: #1e40af;
            font-size: 12px;
        }
        table td {
            padding: 7px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-simpan {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-pinjam {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .empty-message {
            font-style: italic;
            color: #999;
            text-align: center;
            padding: 10px;
        }
        .summary {
            background-color: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 8px 10px;
            margin-top: 8px;
            font-size: 12px;
        }
        .summary-item {
            margin-bottom: 4px;
        }
        .summary-label {
            font-weight: bold;
            color: #0284c7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN DETAIL NASABAH</h1>
            <!-- <p></p> -->
        </div>

        <div class="print-date">
            Dicetak pada: {{ now()->format('d F Y H:i:s') }}
        </div>

        <!-- Section: Informasi Nasabah -->
        <div class="section">
            <div class="section-title">INFORMASI NASABAH</div>
            <div class="info-card">
                <table class="info-table">
                    <tr>
                        <td class="label">Nama Nasabah</td>
                        <td class="sep">:</td>
                        <td>{{ $nasabah->nama }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. Telepon</td>
                        <td class="sep">:</td>
                        <td>{{ $nasabah->no_telp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="sep">:</td>
                        <td>{{ $nasabah->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">ID Nasabah</td>
                        <td class="sep">:</td>
                        <td>#{{ $nasabah->id }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Terdaftar</td>
                        <td class="sep">:</td>
                        <td>{{ $nasabah->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Terakhir Diperbarui</td>
                        <td class="sep">:</td>
                        <td>{{ $nasabah->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Section: Riwayat Bayar & Hutang -->
        <div class="section">
            <div class="section-title">RIWAYAT SIMPAN PINJAM</div>
            @if($nasabah->simpanPinjam->count() > 0)
                <table style="table-layout: fixed; width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th >Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabah->simpanPinjam as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($item->tipe === 'bayar')
                                        <span class="badge badge-simpan">Bayar</span>
                                    @elseif($item->tipe === 'bayar_cash')
                                        <span class="badge" style="background:#d9f99d;color:#365314;">Bayar Cash</span>
                                    @elseif($item->tipe === 'bayar_simpanan')
                                         <span class="badge" style="background:#d1fae5;color:#065f46;">Bayar Dari Simpanan</span>
                                    @elseif($item->tipe === 'hutang')
                                         <span class="badge badge-pinjam">Hutang</span>
                                    @elseif($item->tipe === 'transaksi')
                                         <span class="badge" style="background:#dbeafe;color:#1e40af;">Transaksi</span>
                                    @elseif($item->tipe === 'ambil')
                                         <span class="badge" style="background:#fef9c3;color:#854d0e;">Ambil</span>
                                    @elseif($item->tipe === 'ambil_simpanan')
                                         <span class="badge" style="background:#ffedd5;color:#9a3412;">Ambil Simpanan</span>
                                    @elseif($item->tipe === 'simpan')
                                         <span class="badge" style="background:#f3e8ff;color:#6b21a8;">Simpan</span>
                                    @else
                                        <span class="badge" style="background:#f3f4f6;color:#374151;">{{ ucfirst($item->tipe) }}</span>
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d F Y H:i') }}</td>
                                <td>{{ $item->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="summary">
                    @php
                        $totalBayarManual = $nasabah->simpanPinjam->whereIn('tipe', ['bayar', 'bayar_cash'])->sum('nominal');
                        $totalBayarSimpanan = $nasabah->simpanPinjam->where('tipe', 'bayar_simpanan')->sum('nominal');
                        $totalBayar = $totalBayarManual + $totalBayarSimpanan;
                        $totalHutang = $nasabah->simpanPinjam->where('tipe', 'hutang')->sum('nominal');
                        $totalTransaksi = $nasabah->simpanPinjam->where('tipe', 'transaksi')->sum('nominal');
                        $totalAmbil = $nasabah->simpanPinjam->where('tipe', 'ambil')->sum('nominal');
                        $totalAmbilSimpanan = $nasabah->simpanPinjam->where('tipe', 'ambil_simpanan')->sum('nominal');
                        $totalSimpan = $nasabah->simpanPinjam->where('tipe', 'simpan')->sum('nominal');
                        $sisaHutang = max(0, $totalHutang - $totalBayar);
                        $sisaSaldo = max(0, $totalSimpan - $totalAmbilSimpanan - $totalBayarSimpanan);
                    @endphp
                    <div class="summary-item">
                        <span class="summary-label">Total Bayar:</span> {{ formatCurrency($totalBayar, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Bayar Dari Simpanan:</span> {{ formatCurrency($totalBayarSimpanan, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Hutang:</span> {{ formatCurrency($totalHutang, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Transaksi:</span> {{ formatCurrency($totalTransaksi, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Ambil:</span> {{ formatCurrency($totalAmbil, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Ambil Simpanan:</span> {{ formatCurrency($totalAmbilSimpanan, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Simpan:</span> {{ formatCurrency($totalSimpan, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Sisa Hutang:</span> {{ formatCurrency($sisaHutang, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Sisa Saldo Simpanan:</span> {{ formatCurrency($sisaSaldo, 0, ',', '.') }}
                    </div>
                </div>
            @else
                <div class="empty-message">Tidak ada data riwayat Bayar & Hutang</div>
            @endif
        </div>

        <!-- Section: Riwayat Pembelian -->
        <div class="section">
            <div class="section-title">RIWAYAT PEMBELIAN (PENJUALAN)</div>
            @if($nasabah->pembelian->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Produk</th>
                            <th>Harga / Kg</th>
                            <th>Jumlah (Kg)</th>
                            <th>Harga Akhir</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabah->pembelian as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product->nama_produk ?? '-' }}</td>
                                <td class="text-right">{{ number_format($item->harga_satuan_beli, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($item->total_berat, 2, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($item->harga_akhir, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="summary">
                    @php
                        $totalBerat = $nasabah->pembelian->sum('total_berat');
                        $totalHargaAkhir = $nasabah->pembelian->sum('harga_akhir');
                    @endphp
                    <div class="summary-item">
                        <span class="summary-label">Total Berat/Jumlah:</span> {{ number_format($totalBerat, 2, ',', '.') }} Kg
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Harga Akhir:</span> Rp {{ number_format($totalHargaAkhir, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Rata-rata Harga:</span> Rp {{ number_format($totalHargaAkhir / $nasabah->pembelian->count(), 0, ',', '.') }}
                    </div>
                </div>
            @else
                <div class="empty-message">Tidak ada data riwayat pembelian</div>
            @endif
        </div>
    </div>
</body>
</html>
