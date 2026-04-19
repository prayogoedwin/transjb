<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bisnis</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background: #f9fafb;
        }
        .stat-card h3 {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }
        .summary-card h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1f2937;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-item span:first-child {
            color: #6b7280;
        }
        .summary-item span:last-child {
            font-weight: bold;
            color: #1f2937;
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

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Nasabah</h3>
                <div class="value">{{ $stats['total_nasabah'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Produk</h3>
                <div class="value">{{ $stats['total_produk'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Pembelian</h3>
                <div class="value">{{ formatCurrencyRound($stats['total_pembelian_nominal']) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Transaksi</h3>
                <div class="value">{{ $stats['total_pembelian'] }}</div>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Pembelian</h3>
                <div class="summary-item">
                    <span>Transaksi</span>
                    <span>{{ $stats['total_pembelian'] }} x</span>
                </div>
                <div class="summary-item">
                    <span>Total Pembelian</span>
                    <span>{{ formatCurrencyRound($stats['total_pembelian_nominal']) }}</span>
                </div>
                <div class="summary-item">
                    <span>Biaya Admin</span>
                    <span>{{ formatCurrencyRound($stats['total_biaya_admin']) }}</span>
                </div>
                <!-- <div class="summary-item" style="border-top: 2px solid #d1d5db; padding-top: 10px; margin-top: 10px;">
                    <span><strong>Pendapatan Bersih</strong></span>
                    <span><strong>Rp {{ number_format($stats['total_pembelian'] - $stats['total_biaya_admin'], 0, ',', '.') }}</strong></span>
                </div> -->
            </div>

            <div class="summary-card">
                <h3>Bayar & Hutang</h3>
                <div class="summary-item">
                    <span>Total Simpan</span>
                    <span>{{ formatCurrencyRound($stats['total_simpan']) }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Pinjam</span>
                    <span>{{ formatCurrencyRound($stats['total_pinjam']) }}</span>
                </div>
                <div class="summary-item" style="border-top: 2px solid #d1d5db; padding-top: 10px; margin-top: 10px;">
                    <span><strong>Selisih</strong></span>
                    <span><strong>{{ formatCurrencyRound($stats['total_simpan'] - $stats['total_pinjam']) }}</strong></span>
                </div>
            </div>
<!-- 
            <div class="summary-card">
                <h3>Performa</h3>
                <div class="summary-item">
                    <span>Bilangan Hari</span>
                    <span>{{ \Carbon\Carbon::parse($dateTo)->diffInDays(\Carbon\Carbon::parse($dateFrom)) + 1 }} hari</span>
                </div>
                <div class="summary-item">
                    <span>Rata-rata Harian</span>
                    <span>{{ formatCurrencyRound($stats['total_pembelian'] / (\Carbon\Carbon::parse($dateTo)->diffInDays(\Carbon\Carbon::parse($dateFrom)) + 1)) }}</span>
                </div>
                <div class="summary-item">
                    <span>Rata-rata/Transaksi</span>
                    <span>{{ formatCurrencyRound($stats['total_pembelian'] > 0 ? $stats['total_pembelian'] / $stats['total_pembelian'] : 0) }}</span>
                </div>
            </div> -->
        </div>

        <!-- Pembelian Detail -->
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
                        @foreach($pembelianDetail as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->nasabah?->nama ?? '-' }}</td>
                                <td>{{ $item->product->nama_produk }}</td>
                                <td style="text-align: right;">{{ formatRound($item->total_berat, 2, ',', '.') }} {{ $item->satuan }}</td>
                                <td style="text-align: right;">{{ formatCurrencyRound($item->harga_satuan_beli) }}</td>
                                <td style="text-align: right;">{{ formatCurrencyRound($item->harga_akhir) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Bayar & Hutang Detail -->
        @if($simpanPinjamDetail->count() > 0)
            <div class="table-section">
                <h3>Detail Bayar & Hutang</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nasabah</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($simpanPinjamDetail as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->nasabah?->nama ?? '-' }}</td>
                                <td>{{ ucfirst($item->tipe) }}</td>
                                <td>{{ formatCurrencyRound($item->nominal) }}</td>
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
