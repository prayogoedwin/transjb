<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bisnis</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 11px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 3px;
        }
        .header p {
            color: #6b7280;
            font-size: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .stat-box {
            border: 1px solid #d1d5db;
            padding: 12px;
            background: #f9fafb;
            border-radius: 4px;
        }
        .stat-box label {
            display: block;
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-box .value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .summary-box {
            border: 1px solid #d1d5db;
            padding: 12px;
            page-break-inside: avoid;
        }
        .summary-box h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        table.small {
            font-size: 9px;
        }
        thead {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }
        th {
            padding: 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #d1d5db;
        }
        td {
            padding: 6px;
            border: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            border-bottom: 2px solid #d1d5db;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Bisnis</h1>
        <p>Periode {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-box">
            <label>Total Nasabah</label>
            <div class="value">{{ $stats['total_nasabah'] }}</div>
        </div>
        <div class="stat-box">
            <label>Total Produk</label>
            <div class="value">{{ $stats['total_produk'] }}</div>
        </div>
        <div class="stat-box">
            <label>Total Pembelian</label>
            <div class="value"> {{ formatCurrencyRound($stats['total_pembelian_nominal']) }}</div>
        </div>
        <div class="stat-box">
            <label>Total Transaksi</label>
            <div class="value">{{ $stats['total_pembelian'] }}</div>
        </div>
    </div>

    <!-- Summary -->
    <div class="summary-grid">
        <div class="summary-box">
            <h3>Penjualan</h3>
            <div class="summary-item">
                <span>Transaksi</span>
                <span>{{ $stats['total_pembelian'] }} x</span>
            </div>
            <div class="summary-item">
                <span>Total Penjualan</span>
                <span>Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Biaya Admin</span>
                <span>Rp {{ number_format($stats['total_biaya_admin'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-item" style="border-top: 1px solid #d1d5db; padding-top: 5px; margin-top: 5px; font-weight: bold;">
                <span>Pendapatan Bersih</span>
                <span>Rp {{ number_format($stats['total_penjualan'] - $stats['total_biaya_admin'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="summary-box">
            <h3>Simpan Pinjam</h3>
            <div class="summary-item">
                <span>Total Simpan</span>
                <span>Rp {{ number_format($stats['total_simpan'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Total Pinjam</span>
                <span>Rp {{ number_format($stats['total_pinjam'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-item" style="border-top: 1px solid #d1d5db; padding-top: 5px; margin-top: 5px; font-weight: bold;">
                <span>Selisih</span>
                <span>Rp {{ number_format($stats['total_simpan'] - $stats['total_pinjam'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="summary-box">
            <h3>Performa</h3>
            <div class="summary-item">
                <span>Bilangan Hari</span>
                <span>{{ \Carbon\Carbon::parse($dateTo)->diffInDays(\Carbon\Carbon::parse($dateFrom)) + 1 }} hari</span>
            </div>
            <div class="summary-item">
                <span>Rata-rata Harian</span>
                <span>Rp {{ number_format($stats['total_penjualan'] / (\Carbon\Carbon::parse($dateTo)->diffInDays(\Carbon\Carbon::parse($dateFrom)) + 1), 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Rata-rata/Transaksi</span>
                <span>Rp {{ number_format($stats['total_pembelian'] > 0 ? $stats['total_penjualan'] / $stats['total_pembelian'] : 0, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Pembelian Detail -->
    @if($pembelianDetail->count() > 0)
        <div class="section-title">Detail Penjualan</div>
        <table class="small">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelianDetail as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->nasabah?->nama ?? '-' }}</td>
                        <td>{{ $item->product->nama_produk }}</td>
                        <td style="text-align: right;">{{ number_format($item->total_berat, 2, ',', '.') }} {{ $item->satuan }}</td>
                        <td style="text-align: right;">{{ number_format($item->harga_satuan_beli, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($item->harga_akhir, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Simpan Pinjam Detail -->
    @if($simpanPinjamDetail->count() > 0)
        <div class="section-title">Detail Simpan Pinjam</div>
        <table class="small">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Tipe</th>
                    <th style="text-align: right;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($simpanPinjamDetail as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->nasabah?->nama ?? '-' }}</td>
                        <td>{{ ucfirst($item->tipe) }}</td>
                        <td style="text-align: right;">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
