<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\SimpanPinjam;
use App\Models\Nasabah;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        // Get date range from request
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Calculate statistics
        $stats = [
            'total_nasabah' => Nasabah::count(),
            'total_produk' => Product::count(),
            'total_pembelian' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count(),
            'total_penjualan' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('harga_akhir'),
            'total_biaya_admin' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('biaya_admin'),
            'total_simpan' => SimpanPinjam::where('tipe', 'simpan')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->sum('nominal'),
            'total_pinjam' => SimpanPinjam::where('tipe', 'pinjam')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->sum('nominal'),
        ];

        // Get monthly data for chart
        $pembelianPerBulan = Pembelian::selectRaw('MONTH(created_at) as bulan, SUM(harga_akhir) as total')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupByRaw('MONTH(created_at)')
            ->get();

        // Detail transactions
        $pembelianDetail = Pembelian::with('product', 'nasabah')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        $simpanPinjamDetail = SimpanPinjam::with('nasabah')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        return view('laporan.index', compact('stats', 'pembelianPerBulan', 'pembelianDetail', 'simpanPinjamDetail', 'dateFrom', 'dateTo'));
    }

    public function exportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Get same data as index
        $stats = [
            'total_nasabah' => Nasabah::count(),
            'total_produk' => Product::count(),
            'total_pembelian' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count(),
            'total_pembelian_nominal' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('harga_akhir'),
            'total_biaya_admin' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('biaya_admin'),
            'total_simpan' => SimpanPinjam::where('tipe', 'simpan')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->sum('nominal'),
            'total_pinjam' => SimpanPinjam::where('tipe', 'pinjam')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->sum('nominal'),
        ];

        $pembelianDetail = Pembelian::with('product', 'nasabah')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        $simpanPinjamDetail = SimpanPinjam::with('nasabah')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('stats', 'pembelianDetail', 'simpanPinjamDetail', 'dateFrom', 'dateTo'));
        return $pdf->download('laporan-bisnis-' . now()->format('Y-m-d') . '.pdf');
    }

    public function print(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $stats = [
            'total_nasabah' => Nasabah::count(),
            'total_produk' => Product::count(),
            'total_pembelian' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count(),
            'total_pembelian_nominal' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('harga_akhir'),
            'total_biaya_admin' => Pembelian::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('biaya_admin'),
            'total_simpan' => SimpanPinjam::where('tipe', 'simpan')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->sum('nominal'),
            'total_pinjam' => SimpanPinjam::where('tipe', 'pinjam')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->sum('nominal'),
        ];

        $pembelianDetail = Pembelian::with('product', 'nasabah')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        $simpanPinjamDetail = SimpanPinjam::with('nasabah')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        return view('laporan.print', compact('stats', 'pembelianDetail', 'simpanPinjamDetail', 'dateFrom', 'dateTo'));
    }
}
