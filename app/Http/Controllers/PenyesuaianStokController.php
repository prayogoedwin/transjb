<?php

namespace App\Http\Controllers;

use App\Models\PenyesuaianStok;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PenyesuaianStokController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PenyesuaianStok::with('product', 'user')->select('penyesuaian_stok.*');

            return DataTables::of($data)
                ->addColumn('produk', function ($item) {
                    return $item->product->nama_produk ?? '-';
                })
                ->addColumn('oleh', function ($item) {
                    return $item->user->name ?? '-';
                })
                ->addColumn('status_badge', function ($item) {
                    if ($item->status === 'in') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Masuk</span>';
                    }
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Keluar</span>';
                })
                ->addColumn('jumlah_satuan', function ($item) {
                    return formatDecimalSmart($item->jumlah) . ' ' . $item->satuan;
                })
                ->addColumn('actions', function ($item) {
                    return '<a href="' . route('penyesuaian_stok.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline">Detail</a>';
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at_id;
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        return view('penyesuaian_stok.index');
    }

    public function create(): View
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('penyesuaian_stok.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'exists:products,id'],
            'status'    => ['required', 'in:in,out'],
            'jumlah'    => ['required', 'numeric', 'min:0.0001'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

    //    dd($request->all()); 
        try {
            DB::transaction(function () use ($validated) {
                $product = Product::findOrFail($validated['produk_id']);

                $validated['satuan']  = $product->satuan;
                $validated['user_id'] = auth()->id();

                $penyesuaianStok = PenyesuaianStok::create($validated);
                $penyesuaianStok->updateStokProduk();
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan penyesuaian stok. ' . $e->getMessage());
        }

        return to_route('penyesuaian_stok.index')->with('status', 'Penyesuaian stok berhasil disimpan.');
    }

    public function show(PenyesuaianStok $penyesuaianStok): View
    {
        $penyesuaianStok->load('product', 'user');
        return view('penyesuaian_stok.show', compact('penyesuaianStok'));
    }
}
