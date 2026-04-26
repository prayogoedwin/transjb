<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\NasabahExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $nasabah = Nasabah::with('user')->select('nasabah.*');
            
            return DataTables::of($nasabah)
                // ->addColumn('user_name', function ($item) {
                //     return $item->user?->name ?? '-';
                // })
                ->addColumn('actions', function ($item) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-nasabah')) {
                        $actions .= '<a href="' . route('nasabah.show', $item) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">Detail</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-nasabah')) {
                        $actions .= '<a href="' . route('nasabah.edit', $item) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-nasabah')) {
                        $actions .= '<form action="' . route('nasabah.destroy', $item) . '" method="POST" class="inline" onsubmit="return confirm(\'Apakah Anda yakin?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d F Y H:i');
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('nasabah.index');
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('nasabah.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            // 'user_id' => ['nullable', 'exists:users,id'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        Nasabah::create($validated);

        return to_route('nasabah.index')->with('status', 'Nasabah berhasil ditambahkan.');
    }

    public function show(Nasabah $nasabah): View
    {
        $nasabah->load('user', 'simpanPinjam', 'pembelian.product');
        return view('nasabah.show', compact('nasabah'));
    }

    public function edit(Nasabah $nasabah): View
    {
        $users = User::orderBy('name')->get();
        return view('nasabah.edit', compact('nasabah', 'users'));
    }

    public function update(Request $request, Nasabah $nasabah): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            // 'user_id' => ['nullable', 'exists:users,id'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $nasabah->update($validated);

        return to_route('nasabah.index')->with('status', 'Nasabah berhasil diperbarui.');
    }

    public function destroy(Nasabah $nasabah): RedirectResponse
    {
        $nasabah->delete();

        return to_route('nasabah.index')->with('status', 'Nasabah berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new NasabahExport, 'nasabah-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Nasabah $nasabah)
    {
        $nasabah->load('user', 'simpanPinjam', 'pembelian.product');
        
        $pdf = Pdf::loadView('nasabah.pdf', compact('nasabah'))
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $namaNasabah = Str::of($nasabah->nama)
            ->ascii()
            ->replaceMatches('/[^A-Za-z0-9]+/', '_')
            ->trim('_')
            ->toString();

        $tanggalCetak = now()->format('Y-m-d');
        $jamCetak = now()->format('H-i-s');
        $filename = "{$namaNasabah}-ID{$nasabah->id}-{$tanggalCetak}-{$jamCetak}.pdf";

        return $pdf->download($filename);
    }

    public function exportSummaryPdf(Nasabah $nasabah)
    {
        $nasabah->load('user', 'simpanPinjam', 'pembelian.product');

        $pdf = Pdf::loadView('nasabah.summary_pdf', compact('nasabah'))
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $namaNasabah = Str::of($nasabah->nama)
            ->ascii()
            ->replaceMatches('/[^A-Za-z0-9]+/', '_')
            ->trim('_')
            ->toString();

        $tanggalCetak = now()->format('Y-m-d');
        $jamCetak = now()->format('H-i-s');
        $filename = "Ringkasan-{$namaNasabah}-ID{$nasabah->id}-{$tanggalCetak}-{$jamCetak}.pdf";

        return $pdf->download($filename);
    }
}
