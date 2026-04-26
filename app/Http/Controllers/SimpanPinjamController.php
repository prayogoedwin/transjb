<?php

namespace App\Http\Controllers;

use App\Models\SimpanPinjam;
use App\Models\Nasabah;
use App\Exports\SimpanPinjamExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class SimpanPinjamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $simpanPinjam = SimpanPinjam::with('nasabah')->select('simpan_pinjam.*');

            if ($request->filled('date_from')) {
                $simpanPinjam->whereDate('created_at', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $simpanPinjam->whereDate('created_at', '<=', $request->input('date_to'));
            }
            if ($request->filled('nasabah_id')) {
                $simpanPinjam->where('nasabah_id', $request->integer('nasabah_id'));
            }
            
            return DataTables::of($simpanPinjam)
                ->addColumn('nasabah_name', function ($item) {
                    return $item->nasabah->nama ?? '-';
                })
                ->addColumn('nominal', function ($item) {
                    return 'Rp ' . number_format($item->nominal, 0, ',', '.');
                })
                ->addColumn('tipe_badge', function ($item) {
                    if ($item->tipe === 'bayar') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Bayar</span>';
                    } else if ($item->tipe === 'bayar_simpanan') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">Bayar Dari Simpanan</span>';
                    } else if ($item->tipe === 'hutang') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Hutang</span>';
                    } else if ($item->tipe === 'transaksi') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Transaksi</span>';
                    } else if ($item->tipe === 'ambil') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Ambil</span>';
                    } else if ($item->tipe === 'ambil_simpanan') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">Ambil Simpanan</span>';
                    } else if ($item->tipe === 'simpan') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Simpan</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">' . ucfirst($item->tipe) . '</span>';
                    }
                })
                ->addColumn('actions', function ($item) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-simpan-pinjam')) {
                        $actions .= '<a href="' . route('simpan_pinjam.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">Detail</a>';
                        $actions .= '<a href="' . route('simpan_pinjam.printReceipt', $item) . '" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Cetak</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-simpan-pinjam')) {
                        $actions .= '<a href="' . route('simpan_pinjam.edit', $item) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Ubah</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-simpan-pinjam')) {
                        $actions .= '<form action="' . route('simpan_pinjam.destroy', $item) . '" method="POST" class="inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d M Y H:i');
                })
                ->rawColumns(['tipe_badge', 'actions'])
                ->make(true);
        }

        $nasabah = Nasabah::orderBy('nama')->get();
        return view('simpan_pinjam.index', compact('nasabah'));
    }

    public function create(): View
    {
        $nasabah = Nasabah::with('simpanPinjam')->orderBy('nama')->get()->map(function ($item) {
            $totalSimpan = $item->simpanPinjam->where('tipe', 'simpan')->sum('nominal');
            $totalKeluarSimpanan = $item->simpanPinjam
                ->whereIn('tipe', ['ambil_simpanan', 'bayar_simpanan'])
                ->sum('nominal');
            $item->sisa_simpanan = max(0, $totalSimpan - $totalKeluarSimpanan);
            return $item;
        });
        return view('simpan_pinjam.create', compact('nasabah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabah,id'],
            'tipe' => ['required', 'in:bayar,bayar_simpanan,hutang,transaksi,ambil,simpan,ambil_simpanan'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'print_receipt' => ['sometimes', 'boolean'],
        ]);

        if (in_array($validated['tipe'], ['bayar_simpanan', 'ambil_simpanan'], true)) {
            $sisaSimpanan = $this->getSisaSimpanan((int) $validated['nasabah_id']);
            if ((float) $validated['nominal'] > $sisaSimpanan) {
                return back()
                    ->withInput()
                    ->withErrors(['nominal' => 'Nominal melebihi sisa simpanan (maksimal Rp ' . number_format((int) round($sisaSimpanan), 0, ',', '.') . ').']);
            }
        }

        $simpanPinjam = SimpanPinjam::create($validated);

        // Jika user memilih untuk cetak langsung dan request AJAX
        if ($request->has('print_receipt') && $request->print_receipt && $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data Bayar & Hutang berhasil dibuat.',
                'print_url' => route('simpan_pinjam.printReceipt', $simpanPinjam),
                'redirect_url' => route('simpan_pinjam.index'),
                'id' => $simpanPinjam->id,
            ]);
        }

        // Jika regular request (non-AJAX)
        if ($request->has('print_receipt') && $request->print_receipt) {
            return to_route('simpan_pinjam.printReceipt', $simpanPinjam)->with('status', 'Data Bayar & Hutang berhasil dibuat.');
        }

        return to_route('simpan_pinjam.index')->with('status', 'Data Bayar & Hutang berhasil dibuat.');
    }

    public function show(SimpanPinjam $simpanPinjam): View
    {
        $simpanPinjam->load('nasabah');
        return view('simpan_pinjam.show', compact('simpanPinjam'));
    }

    public function edit(SimpanPinjam $simpanPinjam): View
    {
        $nasabah = Nasabah::orderBy('nama')->get();
        return view('simpan_pinjam.edit', compact('simpanPinjam', 'nasabah'));
    }

    public function update(Request $request, SimpanPinjam $simpanPinjam): RedirectResponse
    {
        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabah,id'],
            'tipe' => ['required', 'in:bayar,bayar_simpanan,hutang,transaksi,ambil,simpan,ambil_simpanan'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        $simpanPinjam->update($validated);

        return to_route('simpan_pinjam.index')->with('status', 'Data Bayar & Hutang berhasil diperbarui.');
    }

    public function destroy(SimpanPinjam $simpanPinjam): RedirectResponse
    {
        $simpanPinjam->delete();

        return to_route('simpan_pinjam.index')->with('status', 'Data Bayar & Hutang berhasil dihapus.');
    }

    /**
     * Print receipt for Bayar & Hutang transaction
     */
    public function printReceipt(SimpanPinjam $simpanPinjam)
    {
        $simpanPinjam->load('nasabah');
        $pdf = Pdf::loadView('simpan_pinjam.receipt', ['simpanPinjam' => $simpanPinjam]);
        return $pdf->download('Struk-' . $simpanPinjam->id . '-' . date('YmdHis') . '.pdf');
    }

    /**
     * Export Bayar & Hutang to Excel with date filter
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'nasabah_id' => ['nullable', 'integer', 'exists:nasabah,id'],
        ]);

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $nasabahId = $request->nasabah_id;

        $filename = 'BayarHutang-' . date('YmdHis') . '.xlsx';
        
        return Excel::download(
            new SimpanPinjamExport($dateFrom, $dateTo, $nasabahId),
            $filename
        );
    }

    private function getSisaSimpanan(int $nasabahId): float
    {
        $totalSimpan = SimpanPinjam::where('nasabah_id', $nasabahId)
            ->where('tipe', 'simpan')
            ->sum('nominal');

        $totalKeluarSimpanan = SimpanPinjam::where('nasabah_id', $nasabahId)
            ->whereIn('tipe', ['ambil_simpanan', 'bayar_simpanan'])
            ->sum('nominal');

        return max(0, (float) $totalSimpan - (float) $totalKeluarSimpanan);
    }
}
