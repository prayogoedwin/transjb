<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Product;
use App\Models\Nasabah;
use App\Exports\PembelianExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pembelian = Pembelian::with('product', 'nasabah')->select('pembelian.*')->orderby('created_at', 'desc');
            
            return DataTables::of($pembelian)
                ->addColumn('nasabah_name', function ($item) {
                    return $item->nasabah?->nama ?? '-';
                })
                ->addColumn('product_name', function ($item) {
                    return $item->product->nama_produk ?? '-';
                })
                ->addColumn('harga_satuan_beli', function ($item) {
                    return formatCurrencyRound($item->harga_satuan_beli);
                })
                ->addColumn('total_harga', function ($item) {
                    return formatCurrencyRound($item->total_harga);
                })
                ->addColumn('biaya_admin', function ($item) {
                    return formatCurrencyRound($item->biaya_admin) . ' (' . number_format($item->biaya_admin_persen, 2) . '%)';
                })
                ->addColumn('harga_akhir', function ($item) {
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">'. formatCurrencyRound($item->harga_akhir) . '</span>';
                })
                ->addColumn('total_berat', function ($item) {
                    return formatDecimalSmart($item->total_berat) . ' ' . $item->satuan;
                })
                ->addColumn('actions', function ($item) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-pembelian')) {
                        $actions .= '<a href="' . route('pembelian.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                        $actions .= '<a href="' . route('pembelian.printInvoice', $item) . '" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Print</a>';
                    }
                    
                    // if (auth()->user()->hasPermission('edit-pembelian')) {
                    //     $actions .= '<a href="' . route('pembelian.edit', $item) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    // }
                    
                    // if (auth()->user()->hasPermission('delete-pembelian')) {
                    //     $actions .= '<form action="' . route('pembelian.destroy', $item) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                    //         ' . csrf_field() . method_field('DELETE') . '
                    //         <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                    //     </form>';
                    // }
                    
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
        $nasabah = Nasabah::orderBy('nama')->get();
        return view('pembelian.create', compact('products', 'nasabah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabah,id'],
            'produk_id' => ['required', 'exists:products,id'],
            'harga_satuan_beli' => ['required', 'numeric', 'min:0'],
            'total_berat' => ['required', 'numeric', 'min:0'],
            'biaya_admin_persen' => ['required', 'numeric', 'min:0', 'max:100'],
            'keterangan' => ['nullable', 'string'],
            'print_invoice' => ['sometimes', 'boolean'],
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                // Calculate totals
                $validated['total_harga'] = $validated['harga_satuan_beli'] * $validated['total_berat'];
                $validated['biaya_admin'] = ($validated['total_harga'] * $validated['biaya_admin_persen']) / 100;
                $validated['harga_akhir'] = $validated['total_harga'] - $validated['biaya_admin'];

                $product = Product::findOrFail($validated['produk_id']);
                $validated['satuan'] = $product->satuan; // Set satuan dari produk terkait
                
                $pembelian = Pembelian::create($validated);
                $pembelian->addToStok();


                // Jika user memilih untuk cetak invoice dan request AJAX
                if ($request->has('print_invoice') && $request->print_invoice && $request->wantsJson()) {
                    // Add to stok
                    
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pembelian created successfully.',
                        'print_url' => route('pembelian.printInvoice', $pembelian),
                        'redirect_url' => route('pembelian.index'),
                        'id' => $pembelian->id,
                    ]);
                }

                // Jika regular request (non-AJAX)
                if ($request->has('print_invoice') && $request->print_invoice) {
                    return redirect()->route('pembelian.printInvoice', $pembelian)->with('status', 'Pembelian created successfully.');
                }

                return to_route('pembelian.index')->with('status', 'Pembelian created successfully.');
            });
        } catch (\Exception $e) {
            $errorMessage = 'Gagal menyimpan pembelian. ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                ], 422);
            }
            
            return back()->withInput()->with('error', $errorMessage);
        }
    }

    public function show(Pembelian $pembelian): View
    {
        $pembelian->load('product', 'nasabah');
        return view('pembelian.show', compact('pembelian'));
    }

    public function edit(Pembelian $pembelian): View
    {
        $products = Product::orderBy('nama_produk')->get();
        $nasabah = Nasabah::orderBy('nama')->get();
        return view('pembelian.edit', compact('pembelian', 'products', 'nasabah'));
    }

    public function update(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabah,id'],
            'produk_id' => ['required', 'exists:products,id'],
            'harga_satuan_beli' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'total_berat' => ['required', 'numeric', 'min:0'],
            'biaya_admin_persen' => ['required', 'numeric', 'min:0', 'max:100'],
            'keterangan' => ['nullable', 'string'],
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

    /**
     * Print invoice for pembelian transaction
     */
    public function printInvoice(Pembelian $pembelian)
    {
        $pembelian->load('product', 'stok');
        $pdf = Pdf::loadView('pembelian.invoice', ['pembelian' => $pembelian]);
        return $pdf->download('Invoice-' . $pembelian->id . '-' . date('YmdHis') . '.pdf');
    }

    /**
     * Export pembelian to Excel with date filter
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $filename = 'Pembelian-' . date('YmdHis') . '.xlsx';
        
        return Excel::download(
            new PembelianExport($dateFrom, $dateTo),
            $filename
        );
    }
}
