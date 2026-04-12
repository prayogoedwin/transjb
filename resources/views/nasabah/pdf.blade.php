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
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .print-date {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #1e40af;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            page-break-inside: avoid;
        }
        .info-label {
            font-weight: bold;
            color: #1e40af;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
            word-wrap: break-word;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        table thead {
            background-color: #dbeafe;
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #1e40af;
            font-size: 13px;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
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
            padding: 20px;
        }
        .summary {
            background-color: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin-top: 10px;
            font-size: 13px;
        }
        .summary-item {
            margin-bottom: 8px;
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
            <p>Transjaya Koperasi</p>
        </div>

        <div class="print-date">
            Dicetak pada: {{ now()->format('d F Y H:i:s') }}
        </div>

        <!-- Section: Informasi Nasabah -->
        <div class="section">
            <div class="section-title">📋 INFORMASI NASABAH</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Nasabah</div>
                    <div class="info-value">{{ $nasabah->nama }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value">{{ $nasabah->no_telp ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Alamat</div>
                    <div class="info-value" style="grid-column: 1 / -1;">{{ $nasabah->alamat ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">ID Nasabah</div>
                    <div class="info-value">#{{ $nasabah->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Terdaftar</div>
                    <div class="info-value">{{ $nasabah->created_at->format('d F Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Terakhir Diperbarui</div>
                    <div class="info-value">{{ $nasabah->updated_at->format('d F Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Section: Riwayat Simpan Pinjam -->
        <div class="section">
            <div class="section-title">💰 RIWAYAT SIMPAN PINJAM</div>
            @if($nasabah->simpanPinjam->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasabah->simpanPinjam as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($item->tipe === 'simpan')
                                        <span class="badge badge-simpan">Simpan</span>
                                    @else
                                        <span class="badge badge-pinjam">Pinjam</span>
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="summary">
                    @php
                        $totalSimpan = $nasabah->simpanPinjam->where('tipe', 'simpan')->sum('nominal');
                        $totalPinjam = $nasabah->simpanPinjam->where('tipe', 'pinjam')->sum('nominal');
                    @endphp
                    <div class="summary-item">
                        <span class="summary-label">Total Simpan:</span> Rp {{ number_format($totalSimpan, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Pinjam:</span> Rp {{ number_format($totalPinjam, 0, ',', '.') }}
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Saldo Netto:</span> Rp {{ number_format($totalSimpan - $totalPinjam, 0, ',', '.') }}
                    </div>
                </div>
            @else
                <div class="empty-message">Tidak ada data riwayat simpan pinjam</div>
            @endif
        </div>

        <!-- Section: Riwayat Pembelian -->
        <div class="section">
            <div class="section-title">🛒 RIWAYAT PEMBELIAN (PENJUALAN)</div>
            @if($nasabah->pembelian->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Produk</th>
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
                        <span class="summary-label">Total Harga:</span> Rp {{ number_format($totalHargaAkhir, 0, ',', '.') }}
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
