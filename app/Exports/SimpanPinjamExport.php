<?php

namespace App\Exports;

use App\Models\SimpanPinjam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SimpanPinjamExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SimpanPinjam::with('nasabah')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nasabah',
            'Type',
            'Nominal',
            'Created At',
        ];
    }

    public function map($simpanPinjam): array
    {
        return [
            $simpanPinjam->id,
            $simpanPinjam->nasabah->nama,
            $simpanPinjam->tipe === 'simpan' ? 'Simpan' : 'Pinjam',
            $simpanPinjam->nominal,
            $simpanPinjam->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
