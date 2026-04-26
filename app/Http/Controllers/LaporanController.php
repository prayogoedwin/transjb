<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\SimpanPinjam;
use App\Models\Nasabah;
use App\Models\Product;
use App\Models\Stok;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $data = $this->getLaporanData($dateFrom, $dateTo);

        return view('laporan.index', array_merge($data, compact('dateFrom', 'dateTo')));
    }

    public function exportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $data = $this->getLaporanData($dateFrom, $dateTo);

        $pdf = Pdf::loadView('laporan.pdf', array_merge($data, compact('dateFrom', 'dateTo')));
        return $pdf->download('laporan-bisnis-' . now()->format('Y-m-d') . '.pdf');
    }

    public function print(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $data = $this->getLaporanData($dateFrom, $dateTo);

        return view('laporan.print', array_merge($data, compact('dateFrom', 'dateTo')));
    }

    private function getLaporanData(string $dateFrom, string $dateTo): array
    {
        $dateRange = [$dateFrom, $dateTo . ' 23:59:59'];
        $totalHutang = SimpanPinjam::where('tipe', 'hutang')->sum('nominal');
        $totalBayar = SimpanPinjam::whereIn('tipe', ['bayar', 'bayar_simpanan'])->sum('nominal');
        $totalSimpan = SimpanPinjam::where('tipe', 'simpan')->sum('nominal');
        $totalKeluarSimpanan = SimpanPinjam::whereIn('tipe', ['ambil_simpanan', 'bayar_simpanan'])->sum('nominal');

        $stats = [
            'total_nasabah' => Nasabah::count(),
            'total_produk' => Product::count(),
            'total_transaksi_pembelian' => Pembelian::whereBetween('created_at', $dateRange)->count(),
            'total_pembelian' => Pembelian::whereBetween('created_at', $dateRange)->sum('harga_akhir'),
            'total_biaya_admin' => Pembelian::whereBetween('created_at', $dateRange)->sum('biaya_admin'),
            'total_transaksi_penjualan' => Penjualan::whereBetween('created_at', $dateRange)->count(),
            'total_penjualan' => Penjualan::whereBetween('created_at', $dateRange)->sum('total_pembelian'),
            'total_sisa_hutang' => max(0, $totalHutang - $totalBayar),
            'total_sisa_simpanan' => max(0, $totalSimpan - $totalKeluarSimpanan),
        ];

        $stokPerProduk = Stok::selectRaw('produk_id, SUM(CASE WHEN transaksi = "in" THEN jumlah ELSE -jumlah END) as total_stok')
            ->with('product')
            ->groupBy('produk_id')
            ->orderByDesc('total_stok')
            ->get();

        $pembelianDetail = Pembelian::with('product', 'nasabah')
            ->whereBetween('created_at', $dateRange)
            ->orderByDesc('created_at')
            ->get();

        $penjualanDetail = Penjualan::with('details')
            ->whereBetween('created_at', $dateRange)
            ->orderByDesc('created_at')
            ->get();

        return compact('stats', 'stokPerProduk', 'pembelianDetail', 'penjualanDetail');
    }
}
