<?php

namespace App\Exports;

use App\Models\Nasabah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NasabahExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Nasabah::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Address',
            'User',
            'Created At',
        ];
    }

    public function map($nasabah): array
    {
        return [
            $nasabah->id,
            $nasabah->nama,
            $nasabah->no_telp,
            $nasabah->alamat,
            $nasabah->user?->name,
            $nasabah->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
