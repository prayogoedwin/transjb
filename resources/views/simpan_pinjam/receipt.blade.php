<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Bayar & Hutang</title>
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
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .receipt-header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .receipt-header p {
            font-size: 12px;
            color: #666;
        }
        .receipt-content {
            margin-bottom: 20px;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .receipt-row.label {
            color: #666;
            font-weight: normal;
        }
        .receipt-row.value {
            color: #333;
        }
        .receipt-row.value strong {
            font-weight: bold;
        }
        .receipt-divider {
            border-top: 1px dashed #ddd;
            margin: 20px 0;
        }
        .receipt-total {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-top: 10px;
        }
        .receipt-total.amount {
            color: #2ecc71;
        }
        .receipt-type {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-type.bayar {
            background: #d4edda;
            color: #155724;
        }
        .receipt-type.bayar-simpanan {
            background: #d1fae5;
            color: #065f46;
        }
        .receipt-type.hutang {
            background: #f8d7da;
            color: #721c24;
        }
        .receipt-type.transaksi {
            background: #d1ecf1;
            color: #0c5460;
        }
        .receipt-type.ambil {
            background: #fff3cd;
            color: #856404;
        }
        .receipt-type.ambil-simpanan {
            background: #ffedd5;
            color: #9a3412;
        }
        .receipt-type.simpan {
            background: #f3e8ff;
            color: #6b21a8;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            border-top: 1px dashed #ddd;
            padding-top: 20px;
        }
        .receipt-number {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
        .receipt-details {
            font-size: 13px;
            line-height: 1.8;
        }
        .receipt-details .label {
            color: #666;
            display: inline-block;
            width: 120px;
        }
        .receipt-details .value {
            color: #333;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>STRUK</h1>
            <p>Transaksi Bayar & Hutang</p>
        </div>

        <div class="receipt-content">
            <div style="text-align: center; margin-bottom: 20px;">
                <span class="receipt-type {{ $simpanPinjam->tipe === 'bayar' ? 'bayar' : ($simpanPinjam->tipe === 'bayar_simpanan' ? 'bayar-simpanan' : ($simpanPinjam->tipe === 'hutang' ? 'hutang' : ($simpanPinjam->tipe === 'transaksi' ? 'transaksi' : ($simpanPinjam->tipe === 'ambil' ? 'ambil' : ($simpanPinjam->tipe === 'ambil_simpanan' ? 'ambil-simpanan' : ($simpanPinjam->tipe === 'simpan' ? 'simpan' : '')))))) }}">
                    {{ $simpanPinjam->tipe === 'bayar' ? 'Bayar' : ($simpanPinjam->tipe === 'bayar_simpanan' ? 'Bayar dari Simpanan' : ($simpanPinjam->tipe === 'hutang' ? 'Hutang' : ($simpanPinjam->tipe === 'transaksi' ? 'Transaksi' : ($simpanPinjam->tipe === 'ambil' ? 'Ambil' : ($simpanPinjam->tipe === 'ambil_simpanan' ? 'Ambil Simpanan' : ($simpanPinjam->tipe === 'simpan' ? 'Simpan' : '')))))) }}
                </span>
            </div>

            <div class="receipt-details">
                <div>
                    <span class="label">Nasabah:</span>
                    <span class="value">{{ $simpanPinjam->nasabah->nama }}</span>
                </div>
                
                <div>
                    <span class="label">Tanggal:</span>
                    <span class="value">{{ $simpanPinjam->created_at->format('d/m/Y') }}</span>
                </div>

                <div>
                    <span class="label">Waktu:</span>
                    <span class="value">{{ $simpanPinjam->created_at->format('H:i:s') }}</span>
                </div>
            </div>

            <div class="receipt-divider"></div>

            <div class="receipt-row">
                <span>Nominal:</span>
                <strong>Rp {{ number_format($simpanPinjam->nominal, 0, ',', '.') }}</strong>
            </div>

            <div class="receipt-divider"></div>

            <div class="receipt-row receipt-total amount">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($simpanPinjam->nominal, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Terima kasih telah menggunakan layanan kami</p>
            <p style="margin-top: 10px;">Simpan struk ini sebagai bukti transaksi</p>
            <div class="receipt-number">
                ID Transaksi: {{ $simpanPinjam->id }}
            </div>
        </div>
    </div>
</body>
</html>
