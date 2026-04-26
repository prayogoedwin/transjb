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

    <div class="section-title">Rekap Bisnis Keseluruhan</div>
    <table class="small">
        <thead>
            <tr>
                <th>Indikator</th>
                <th style="text-align: right;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Total Nasabah</td><td style="text-align: right;">{{ $stats['total_nasabah'] }}</td></tr>
            <tr><td>Total Produk</td><td style="text-align: right;">{{ $stats['total_produk'] }}</td></tr>
            <tr><td>Total Sisa Hutang</td><td style="text-align: right;">{{ formatCurrencyRound($stats['total_sisa_hutang']) }}</td></tr>
            <tr><td>Total Sisa Simpanan</td><td style="text-align: right;">{{ formatCurrencyRound($stats['total_sisa_simpanan']) }}</td></tr>
            <tr><td>Total Pembelian</td><td style="text-align: right;">{{ formatCurrencyRound($stats['total_pembelian']) }}</td></tr>
            <tr><td>Total Penjualan</td><td style="text-align: right;">{{ formatCurrencyRound($stats['total_penjualan']) }}</td></tr>
            <tr><td>Jumlah Transaksi Pembelian</td><td style="text-align: right;">{{ $stats['total_transaksi_pembelian'] }}x</td></tr>
            <tr><td>Jumlah Transaksi Penjualan</td><td style="text-align: right;">{{ $stats['total_transaksi_penjualan'] }}x</td></tr>
            <tr><td>Total Biaya Admin</td><td style="text-align: right;">{{ formatCurrencyRound($stats['total_biaya_admin']) }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">Total Stok Per Produk</div>
    <table class="small">
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align: right;">Total Stok</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokPerProduk as $item)
                <tr>
                    <td>{{ $item->product?->nama_produk ?? '-' }}</td>
                    <td style="text-align: right;">{{ formatDecimalSmart($item->total_stok) }}</td>
                    <td>{{ $item->product?->satuan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pembelian Detail -->
    @if($pembelianDetail->count() > 0)
        <div class="section-title">Detail Pembelian</div>
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
                @foreach($pembelianDetail->take(10) as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->nasabah?->nama ?? '-' }}</td>
                        <td>{{ $item->product?->nama_produk ?? '-' }}</td>
                        <td style="text-align: right;">{{ number_format($item->total_berat, 2, ',', '.') }} {{ $item->satuan }}</td>
                        <td style="text-align: right;">{{ formatCurrencyRound($item->harga_satuan_beli) }}</td>
                        <td style="text-align: right;">{{ formatCurrencyRound($item->harga_akhir) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($penjualanDetail->count() > 0)
        <div class="section-title">Detail Penjualan</div>
        <table class="small">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th style="text-align: right;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualanDetail->take(10) as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->nama_customer ?? '-' }}</td>
                        <td style="text-align: right;">{{ formatCurrencyRound($item->total_pembelian) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
