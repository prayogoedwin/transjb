<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $penjualan = Penjualan::with('details')->select('penjualan.*')->orderby('created_at', 'desc');
            
            return DataTables::of($penjualan)
                ->addColumn('total_pembelian', function ($item) {
                    return formatCurrencyRound($item->total_pembelian);
                })
                ->addColumn('nopol', function ($item) {
                    return $item->nopol ?? '-';
                })
                ->addColumn('items_count', function ($item) {
                    return $item->details->count() . ' item(s)';
                })
                ->addColumn('actions', function ($item) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-penjualan')) {
                        $actions .= '<a href="' . route('penjualan.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                        $actions .= '<a href="' . route('penjualan.printInvoice', $item) . '" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Print</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-penjualan')) {
                        $actions .= '<a href="' . route('penjualan.edit', $item) . '" class="text-yellow-600 dark:text-yellow-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-penjualan')) {
                        $actions .= '<form action="' . route('penjualan.destroy', $item) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('M d, Y');
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('penjualan.index');
    }

    public function create(): View
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('penjualan.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'customer_id' => ['nullable'],
            'nama_customer' => ['required', 'string', 'max:255'],
            'nopol' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.produk_id' => ['required', 'exists:products,id'],
            'details.*.harga_satuan' => ['required', 'numeric', 'min:0'],
            'details.*.satuan' => ['required', 'string', 'max:50'],
            'details.*.jumlah' => ['required', 'numeric', 'min:0'],
            'print_invoice' => ['sometimes', 'boolean'],
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                // Calculate total penjualan
                $totalPembelian = 0;
                foreach ($validated['details'] as $detail) {
                    $totalPembelian += $detail['harga_satuan'] * $detail['jumlah'];
                }

                $validated['total_pembelian'] = $totalPembelian;

                $penjualan = Penjualan::create($validated);
                // Create detail items
                foreach ($validated['details'] as $detail) {
                    $product = Product::findOrFail($detail['produk_id']);
                    
                    $penjualanDetail = PenjualanDetail::create([
                        'penjualan_id' => $penjualan->id,
                        'produk_id' => $detail['produk_id'],
                        'nama_produk' => $product->nama_produk,
                        'harga_satuan' => $detail['harga_satuan'],
                        'satuan' => $detail['satuan'],
                        'jumlah' => $detail['jumlah'],
                        'harga_total' => $detail['harga_satuan'] * $detail['jumlah'],
                    ]);

                    $penjualanDetail->decreaseStock();
                }

                // Jika user memilih untuk cetak invoice
                if ($request->has('print_invoice') && $request->print_invoice && $request->wantsJson()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Penjualan created successfully.',
                        'print_url' => route('penjualan.printInvoice', $penjualan),
                        'redirect_url' => route('penjualan.index'),
                        'id' => $penjualan->id,
                    ]);
                }

                if ($request->has('print_invoice') && $request->print_invoice) {
                    return redirect()->route('penjualan.printInvoice', $penjualan)->with('status', 'Penjualan created successfully.');
                }

                return to_route('penjualan.index')->with('status', 'Penjualan created successfully.');
            });
        } catch (\Exception $e) {
            $errorMessage = 'Gagal menyimpan penjualan. ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                ], 422);
            }
            
            return back()->withInput()->with('error', $errorMessage);
        }
    }

    public function show(Penjualan $penjualan): View
    {
        $penjualan->load('details');
        return view('penjualan.show', compact('penjualan'));
    }

    public function edit(Penjualan $penjualan): View
    {
        $products = Product::orderBy('nama_produk')->get();
        $penjualan->load('details');
        return view('penjualan.edit', compact('penjualan', 'products'));
    }

    public function update(Request $request, Penjualan $penjualan): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['nullable'],
            'nama_customer' => ['required', 'string', 'max:255'],
            'nopol' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.id' => ['nullable', 'exists:penjualan_detail,id'],
            'details.*.produk_id' => ['required', 'exists:products,id'],
            'details.*.harga_satuan' => ['required', 'numeric', 'min:0'],
            'details.*.satuan' => ['required', 'string', 'max:50'],
            'details.*.jumlah' => ['required', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($penjualan, $validated) {
            // Calculate total penjualan
            $totalPembelian = 0;
            foreach ($validated['details'] as $detail) {
                $totalPembelian += $detail['harga_satuan'] * $detail['jumlah'];
            }
            $validated['total_pembelian'] = $totalPembelian;

            // Update main record
            $penjualan->update($validated);

            // Get existing detail IDs
            $existingIds = $penjualan->details->pluck('id')->toArray();
            $updatedIds = [];

            // Update or create details
            foreach ($validated['details'] as $detail) {
                $product = Product::findOrFail($detail['produk_id']);
                
                if (isset($detail['id']) && $detail['id']) {
                    // Update existing
                    $detailItem = PenjualanDetail::find($detail['id']);
                    $detailItem->update([
                        'produk_id' => $detail['produk_id'],
                        'nama_produk' => $product->nama_produk,
                        'harga_satuan' => $detail['harga_satuan'],
                        'satuan' => $detail['satuan'],
                        'jumlah' => $detail['jumlah'],
                        'harga_total' => $detail['harga_satuan'] * $detail['jumlah'],
                    ]);
                    $updatedIds[] = $detail['id'];
                } else {
                    // Create new
                    $newDetail = PenjualanDetail::create([
                        'penjualan_id' => $penjualan->id,
                        'produk_id' => $detail['produk_id'],
                        'nama_produk' => $product->nama_produk,
                        'harga_satuan' => $detail['harga_satuan'],
                        'satuan' => $detail['satuan'],
                        'jumlah' => $detail['jumlah'],
                        'harga_total' => $detail['harga_satuan'] * $detail['jumlah'],
                    ]);
                    $updatedIds[] = $newDetail->id;
                }
            }

            // Delete removed details
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                PenjualanDetail::whereIn('id', $toDelete)->delete();
            }

            return true;
        });

        return to_route('penjualan.index')->with('status', 'Penjualan updated successfully.');
    }

    public function destroy(Penjualan $penjualan): RedirectResponse
    {
        DB::transaction(function () use ($penjualan) {
            // Delete all details first
            $penjualan->details()->delete();
            $penjualan->delete();
        });

        return to_route('penjualan.index')->with('status', 'Penjualan deleted successfully.');
    }

    /**
     * Print invoice for penjualan transaction
     */
    public function printInvoice(Penjualan $penjualan)
    {
        $penjualan->load('details');
        $pdf = Pdf::loadView('penjualan.invoice', ['penjualan' => $penjualan]);
        // return \View::make('penjualan.invoice', ['penjualan' => $penjualan])->render();
        return $pdf->download('Penjualan-' . $penjualan->id . '-' . date('YmdHis') . '.pdf');
    }
}
