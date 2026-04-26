<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Nasabah - {{ $nasabah->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #222; font-size: 12px; line-height: 1.4; }
        .container { max-width: 800px; margin: 0 auto; padding: 12px; }
        .header { text-align: center; border-bottom: 2px solid #1e40af; padding-bottom: 8px; margin-bottom: 10px; }
        .header h1 { color: #1e40af; font-size: 18px; }
        .print-date { text-align: right; font-size: 11px; color: #666; margin-bottom: 10px; }
        .section-title { background: #1e40af; color: #fff; padding: 6px 10px; font-weight: bold; margin-bottom: 8px; border-radius: 4px; font-size: 13px; }
        .info-card { border: 1px solid #dbe4ff; border-radius: 4px; padding: 8px 10px; margin-bottom: 12px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 3px 0; font-size: 12px; }
        .label { font-weight: bold; color: #1e40af; width: 150px; }
        .sep { width: 12px; text-align: center; }
        .summary { border-left: 4px solid #0284c7; background: #f0f9ff; padding: 8px 10px; }
        .summary-item { margin-bottom: 4px; }
        .summary-label { font-weight: bold; color: #0284c7; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RINGKASAN NASABAH</h1>
        </div>

        <div class="print-date">Dicetak pada: {{ now()->format('d F Y H:i:s') }}</div>

        <div class="section-title">INFORMASI NASABAH</div>
        <div class="info-card">
            <table class="info-table">
                <tr><td class="label">Nama Nasabah</td><td class="sep">:</td><td>{{ $nasabah->nama }}</td></tr>
                <tr><td class="label">No. Telepon</td><td class="sep">:</td><td>{{ $nasabah->no_telp ?? '-' }}</td></tr>
                <tr><td class="label">Alamat</td><td class="sep">:</td><td>{{ $nasabah->alamat ?? '-' }}</td></tr>
                <tr><td class="label">ID Nasabah</td><td class="sep">:</td><td>#{{ $nasabah->id }}</td></tr>
            </table>
        </div>

        <div class="section-title">RINGKASAN RIWAYAT</div>
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
            <div class="summary-item"><span class="summary-label">Total Bayar:</span> {{ formatCurrency($totalBayar, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Total Bayar Dari Simpanan:</span> {{ formatCurrency($totalBayarSimpanan, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Total Hutang:</span> {{ formatCurrency($totalHutang, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Total Transaksi:</span> {{ formatCurrency($totalTransaksi, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Total Ambil:</span> {{ formatCurrency($totalAmbil, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Total Ambil Simpanan:</span> {{ formatCurrency($totalAmbilSimpanan, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Total Simpan:</span> {{ formatCurrency($totalSimpan, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Sisa Hutang:</span> {{ formatCurrency($sisaHutang, 0, ',', '.') }}</div>
            <div class="summary-item"><span class="summary-label">Sisa Saldo Simpanan:</span> {{ formatCurrency($sisaSaldo, 0, ',', '.') }}</div>
        </div>
    </div>
</body>
</html>
