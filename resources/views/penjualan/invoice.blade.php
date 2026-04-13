<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Penjualan - {{ $penjualan->id }}</title>
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
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-info h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .company-info p {
            font-size: 12px;
            color: #666;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            font-size: 20px;
            color: #0066cc;
            margin-bottom: 10px;
        }
        .invoice-info p {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .invoice-info .invoice-id {
            font-weight: bold;
            font-size: 14px;
        }

        .text-right{
            text-align: right;
        }

        .info-grid {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
        }
        .info-box h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-box p {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .info-box p strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #f5f5f5;
        }
        table th {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 12px;
            font-weight: bold;
        }
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        table tr:nth-child(even) {
            background-color: #fafafa;
        }
        table td.text-right {
            text-align: right;
        }
        table td.text-center {
            text-align: center;
        }

        .summary {
            width: 50%;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 12px;
            border-bottom: 1px solid #ddd;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #333;
            padding: 12px 0;
        }
        .summary-row.total .amount {
            color: #0066cc;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 12px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>TRANSJB</h1>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p class="invoice-id">ID: {{ $penjualan->id }}</p>
                <p>Tanggal: {{ $penjualan->created_at->format('d M Y') }}</p>
                <p>Jam: {{ $penjualan->created_at->format('H:i') }}</p>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="info-grid">
            <div class="info-box">
                <h3>Informasi Penjualan Kepada</h3>
                @if($penjualan->nasabah)
                    <p><strong>Nama:</strong> {{ $penjualan->nasabah->nama }}</p>
                    <p><strong>Telepon:</strong> {{ $penjualan->nasabah->no_telp }}</p>
                    <p><strong>Alamat:</strong> {{ $penjualan->nasabah->alamat }}</p>
                @else
                    <p><strong>Nama:</strong> {{ $penjualan->nama_customer }}</p>
                @endif
                @if($penjualan->nopol)
                    <p><strong>Nopol:</strong> {{ $penjualan->nopol }}</p>
                @endif
            </div>
            <div class="info-box">
                <h3>Keterangan</h3>
                @if($penjualan->keterangan)
                    <p>{{ $penjualan->keterangan }}</p>
                @else
                    <p>-</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Nama Produk</th>
                    <th style="width: 15%;" class="text-right">Harga Satuan</th>
                    <th style="width: 10%;" class="text-center">Satuan</th>
                    <th style="width: 15%;" class="text-right">Jumlah</th>
                    <th style="width: 15%;" class="text-right">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->nama_produk }}</td>
                    <td class="text-right">{{ formatCurrencyRound($detail->harga_satuan) }}</td>
                    <td class="text-center">{{ $detail->satuan }}</td>
                    <td class="text-right">{{ formatDecimal($detail->jumlah) }}</td>
                    <td class="text-right">{{ formatCurrencyRound($detail->harga_total) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada detail penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>Total Item:</span>
                <span class="text-right">{{ $penjualan->details->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Total Jumlah:</span>
                <span class="text-right">{{ formatDecimal($penjualan->details->sum('jumlah')) }}</span>
            </div>
            <div class="summary-row total">
                <span>TOTAL PENJUALAN:</span>
                <span class="amount text-right">{{ formatCurrencyRound($penjualan->total_pembelian) }}</span>
            </div>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="signature-box">
                <p>Pembuat Invoice</p>
                <div class="signature-line">{{ auth()->user()->name ?? 'Staff' }}</div>
            </div>
            <div class="signature-box">
                <p>Penerima Barang</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>Pihak TransJB</p>
                <div class="signature-line"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ date('d M Y H:i:s') }} | Invoice ini dibuat secara otomatis oleh Sistem TransJB</p>
            <p>Terima kasih atas transaksi Anda.</p>
        </div>
    </div>
</body>
</html>
