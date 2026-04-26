<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bisnis</title>
    @php
        $appName = config('app.name', 'App');
        $initials = collect(explode(' ', $appName))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(3)
            ->implode('');
    @endphp
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,
        %3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E
            %3Crect width='100' height='100' rx='20' fill='%232563eb'/%3E
            %3Ctext x='50' y='50' text-anchor='middle' dy='0.35em' font-family='Arial, sans-serif' font-size='45' font-weight='bold' fill='white'%3E{{ $initials }}%3C/text%3E
        %3C/svg%3E">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 5px;
        }
        header p {
            color: #6b7280;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        thead {
            background: #f3f4f6;
            border-bottom: 2px solid #d1d5db;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            color: #1f2937;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .table-section {
            margin-bottom: 30px;
        }
        .table-section h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .print-button {
            text-align: center;
            margin: 20px 0;
        }
        .print-button button {
            padding: 10px 30px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            body {
                background: white;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Laporan Bisnis</h1>
            <p>Periode {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
            <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
        </header>

        <div class="table-section">
            <h3>Rekap Bisnis Keseluruhan</h3>
            <table>
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
        </div>

        <div class="table-section">
            <h3>Total Stok Per Produk</h3>
            <table>
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
                        <tr><td colspan="3" style="text-align: center;">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembelianDetail->count() > 0)
            <div class="table-section">
                <h3>Detail Pembelian</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nasabah</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembelianDetail->take(10) as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->nasabah?->nama ?? '-' }}</td>
                                <td>{{ $item->product?->nama_produk ?? '-' }}</td>
                                <td style="text-align: right;">{{ formatDecimalSmart($item->total_berat) }} {{ $item->satuan }}</td>
                                <td style="text-align: right;">{{ formatCurrencyRound($item->harga_satuan_beli) }}</td>
                                <td style="text-align: right;">{{ formatCurrencyRound($item->harga_akhir) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($penjualanDetail->count() > 0)
            <div class="table-section">
                <h3>Detail Penjualan</h3>
                <table>
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
            </div>
        @endif
    </div>

    <div class="print-button">
        <button onclick="window.print()">Print Laporan</button>
    </div>
</body>
</html>
