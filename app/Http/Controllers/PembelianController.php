<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pembelian = Pembelian::with('product')->select('pembelian.*');
            
            return DataTables::of($pembelian)
                ->addColumn('product_name', function ($item) {
                    return $item->product->nama_produk ?? '-';
                })
                ->addColumn('harga_satuan_beli', function ($item) {
                    return 'Rp ' . number_format($item->harga_satuan_beli, 0, ',', '.');
                })
                ->addColumn('total_harga', function ($item) {
                    return 'Rp ' . number_format($item->total_harga, 0, ',', '.');
                })
                ->addColumn('biaya_admin', function ($item) {
                    return 'Rp ' . number_format($item->biaya_admin, 0, ',', '.') . ' (' . number_format($item->biaya_admin_persen, 2) . '%)';
                })
                ->addColumn('harga_akhir', function ($item) {
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Rp ' . number_format($item->harga_akhir, 0, ',', '.') . '</span>';
                })
                ->addColumn('actions', function ($item) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-pembelian')) {
                        $actions .= '<a href="' . route('pembelian.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-pembelian')) {
                        $actions .= '<a href="' . route('pembelian.edit', $item) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-pembelian')) {
                        $actions .= '<form action="' . route('pembelian.destroy', $item) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('M d, Y');
                })
                ->rawColumns(['harga_akhir', 'actions'])
                ->make(true);
        }

        return view('pembelian.index');
    }

    public function create(): View
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('pembelian.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'exists:products,id'],
            'harga_satuan_beli' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'total_berat' => ['required', 'numeric', 'min:0'],
            'biaya_admin_persen' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Calculate totals
        $validated['total_harga'] = $validated['harga_satuan_beli'] * $validated['total_berat'];
        $validated['biaya_admin'] = ($validated['total_harga'] * $validated['biaya_admin_persen']) / 100;
        $validated['harga_akhir'] = $validated['total_harga'] - $validated['biaya_admin'];

        Pembelian::create($validated);

        return to_route('pembelian.index')->with('status', 'Pembelian created successfully.');
    }

    public function show(Pembelian $pembelian): View
    {
        $pembelian->load('product');
        return view('pembelian.show', compact('pembelian'));
    }

    public function edit(Pembelian $pembelian): View
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('pembelian.edit', compact('pembelian', 'products'));
    }

    public function update(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'exists:products,id'],
            'harga_satuan_beli' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'total_berat' => ['required', 'numeric', 'min:0'],
            'biaya_admin_persen' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Calculate totals
        $validated['total_harga'] = $validated['harga_satuan_beli'] * $validated['total_berat'];
        $validated['biaya_admin'] = ($validated['total_harga'] * $validated['biaya_admin_persen']) / 100;
        $validated['harga_akhir'] = $validated['total_harga'] - $validated['biaya_admin'];

        $pembelian->update($validated);

        return to_route('pembelian.index')->with('status', 'Pembelian updated successfully.');
    }

    public function destroy(Pembelian $pembelian): RedirectResponse
    {
        $pembelian->delete();

        return to_route('pembelian.index')->with('status', 'Pembelian deleted successfully.');
    }
}
