<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Pembelian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .invoice-header h1 {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .invoice-header p {
            font-size: 14px;
            color: #666;
        }
        .invoice-no {
            margin-top: 10px;
            font-size: 12px;
            color: #999;
        }
        .invoice-content {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .label {
            color: #666;
            width: 150px;
            font-weight: normal;
        }
        .value {
            color: #333;
            text-align: right;
            flex: 1;
            font-weight: 500;
        }
        .divider {
            border-top: 1px dashed #ddd;
            margin: 20px 0;
        }
        .summary-section {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .summary-row.total {
            /* border-top: 2px solid #333; */
            padding-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #2ecc71;
        }
        .summary-label {
            color: #333;
        }
        .summary-value {
            color: #333;
            font-weight: 500;
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
            color: #999;
            border-top: 1px dashed #ddd;
            padding-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 13px;
        }
        table th {
            background: #f0f0f0;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #333;
            color: #333;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table tr:last-child td {
            border-bottom: 2px solid #333;
        }
        .text-right {
            text-align: right;
        }
        .highlight {
            background: #ffeb3b;
            padding: 2px 4px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>INVOICE PEMBELIAN</h1>
            <p>Bukti Transaksi Pembelian Produk</p>
            <div class="invoice-no">
                No. Invoice: {{ $pembelian->id }} | Tanggal: {{ $pembelian->created_at_id }}
            </div>
        </div>

        <div class="invoice-content">
            <div class="section-title">Detail Produk</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th width="20%" class="text-right">Harga Satuan</th>
                        <th width="20%" class="text-right">Berat Total</th>
                        <th width="20%" class="text-right">Potongan</th>
                        <th width="20%" class="text-right">Harga Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>{{ $pembelian->product->nama_produk }}</strong></td>
                        <td class="text-right">Rp {{ formatCurrencyRound($pembelian->harga_satuan_beli) }}</td>
                        <td class="text-right">{{ formatDecimalSmart($pembelian->total_berat) }} {{ $pembelian->satuan }}</td>
                        <td class="text-right">{{ formatDecimalSmart($pembelian->potongan) }} %</td>
                        <td class="text-right"><strong>Rp {{ formatCurrencyRound($pembelian->harga_akhir) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="divider"></div>

            <div class="section-title">Ringkasan Pembayaran</div>
            
            <div class="summary-section">
                <div class="summary-row total">
                    <span class="summary-label">HARGA AKHIR:</span>
                    <span class="summary-value">Rp {{ formatCurrencyRound($pembelian->harga_akhir) }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <div class="section-title">Keterangan</div>
            
            @if($pembelian->keterangan)
                <div class="row">
                    <div class="value" style="text-align: left; margin-top: 5px; color: #555;">
                        {{ $pembelian->keterangan }}
                    </div>
                </div>
            @endif
            <!-- <div class="row">
                <span class="label">Status Stok:</span>
                <span class="value">
                    @if($pembelian->stok)
                        <strong style="color: #2ecc71;">✓ Sudah ditambahkan ke stok</strong>
                    @else
                        <strong style="color: #e74c3c;">✗ Belum ditambahkan ke stok</strong>
                    @endif
                </span>
            </div> -->
        </div>

        <div class="footer">
            <p>Invoice ini adalah bukti sah dari transaksi pembelian. Simpan invoice ini sebagai referensi Anda.</p>
            <p style="margin-top: 10px;">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
