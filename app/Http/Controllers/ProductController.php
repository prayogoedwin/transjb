<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::select('products.*');
            
            return DataTables::of($products)
                ->addColumn('total_stock', function ($product) {
                    $inStock = $product->stocks()->where('transaksi', 'in')->sum('jumlah');
                    $outStock = $product->stocks()->where('transaksi', 'out')->sum('jumlah');
                    $total = $inStock - $outStock;
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">' . formatDecimalSmart($total) . ' ' . $product->satuan . '</span>';
                })
                ->addColumn('harga_beli', function ($product) {
                    return formatCurrencyRound($product->harga_beli);
                })
                ->addColumn('actions', function ($product) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-products')) {
                        $actions .= '<a href="' . route('products.show', $product) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">Detail</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-products')) {
                        $actions .= '<a href="' . route('products.edit', $product) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-products')) {
                        $actions .= '<form action="' . route('products.destroy', $product) . '" method="POST" class="inline" onsubmit="return confirm(\'Apa Anda yakin?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($product) {
                    return $product->created_at->format('d F Y H:i');
                })
                ->rawColumns(['total_stock', 'actions'])
                ->make(true);
        }

        return view('products.index');
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255', 'unique:products'],
            'satuan' => ['required', 'string', 'max:50'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['tanggal_update_harga_beli'] = now();

        Product::create($validated);

        return to_route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        $product->load('stocks.createdBy', 'priceHistories.createdBy');
        $inStock = $product->stocks()->where('transaksi', 'in')->sum('jumlah');
        $outStock = $product->stocks()->where('transaksi', 'out')->sum('jumlah');
        $totalStock = $inStock - $outStock;
        
        return view('products.show', compact('product', 'totalStock', 'inStock', 'outStock'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255', 'unique:products,nama_produk,'.$product->id],
            'satuan' => ['required', 'string', 'max:50'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
        ]);

        // Record price history if harga_beli changed
        if ($product->harga_beli != $validated['harga_beli']) {
            $product->priceHistories()->create([
                'harga_beli_before' => $product->harga_beli,
                'tanggal_update_harga_beli' => $product->tanggal_update_harga_beli,
                'harga_jual_before' => $product->harga_jual,
                'tanggal_update_harga_jual' => $product->tanggal_update_harga_jual,
                'created_by' => auth()->id(),
            ]);
        }

        $validated['tanggal_update_harga_beli'] = now();

        $product->update($validated);

        return to_route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index')->with('status', 'Produk berhasil dihapus.');
    }
}
