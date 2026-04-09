<?php

namespace App\Http\Controllers;

use App\Models\SimpanPinjam;
use App\Models\Nasabah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SimpanPinjamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $simpanPinjam = SimpanPinjam::with('nasabah')->select('simpan_pinjam.*');
            
            return DataTables::of($simpanPinjam)
                ->addColumn('nasabah_name', function ($item) {
                    return $item->nasabah->nama ?? '-';
                })
                ->addColumn('nominal', function ($item) {
                    return 'Rp ' . number_format($item->nominal, 0, ',', '.');
                })
                ->addColumn('tipe_badge', function ($item) {
                    if ($item->tipe === 'simpan') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Simpan</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Pinjam</span>';
                    }
                })
                ->addColumn('actions', function ($item) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-simpan-pinjam')) {
                        $actions .= '<a href="' . route('simpan_pinjam.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-simpan-pinjam')) {
                        $actions .= '<a href="' . route('simpan_pinjam.edit', $item) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-simpan-pinjam')) {
                        $actions .= '<form action="' . route('simpan_pinjam.destroy', $item) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('M d, Y');
                })
                ->rawColumns(['tipe_badge', 'actions'])
                ->make(true);
        }

        return view('simpan_pinjam.index');
    }

    public function create(): View
    {
        $nasabah = Nasabah::orderBy('nama')->get();
        return view('simpan_pinjam.create', compact('nasabah'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabah,id'],
            'tipe' => ['required', 'in:simpan,pinjam'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        SimpanPinjam::create($validated);

        return to_route('simpan_pinjam.index')->with('status', 'Simpan Pinjam record created successfully.');
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
            'tipe' => ['required', 'in:simpan,pinjam'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        $simpanPinjam->update($validated);

        return to_route('simpan_pinjam.index')->with('status', 'Simpan Pinjam record updated successfully.');
    }

    public function destroy(SimpanPinjam $simpanPinjam): RedirectResponse
    {
        $simpanPinjam->delete();

        return to_route('simpan_pinjam.index')->with('status', 'Simpan Pinjam record deleted successfully.');
    }
}
