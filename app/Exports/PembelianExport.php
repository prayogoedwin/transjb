<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembelianExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Pembelian::with('product')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product',
            'Harga Satuan Beli',
            'Satuan',
            'Total Berat',
            'Total Harga',
            'Biaya Admin %',
            'Biaya Admin',
            'Harga Akhir',
            'Created At',
        ];
    }

    public function map($pembelian): array
    {
        return [
            $pembelian->id,
            $pembelian->product->nama_produk,
            $pembelian->harga_satuan_beli,
            $pembelian->satuan,
            $pembelian->total_berat,
            $pembelian->total_harga,
            $pembelian->biaya_admin_persen,
            $pembelian->biaya_admin,
            $pembelian->harga_akhir,
            $pembelian->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
