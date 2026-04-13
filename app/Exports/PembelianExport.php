<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembelianExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $dateFrom;
    private $dateTo;

    public function __construct($dateFrom = null, $dateTo = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = Pembelian::with('product');

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Produk',
            'Harga Satuan Beli',
            'Satuan',
            'Total Berat',
            'Total Harga',
            'Biaya Admin (%)',
            'Biaya Admin (Rp)',
            'Harga Akhir',
            'Created At',
            'Keterangan',
        ];
    }

    public function map($pembelian): array
    {
        return [
            $pembelian->id,
            $pembelian->product->nama_produk ?? '-',
            $pembelian->harga_satuan_beli,
            $pembelian->satuan,
            $pembelian->total_berat,
            $pembelian->total_harga,
            $pembelian->biaya_admin_persen,
            $pembelian->biaya_admin,
            $pembelian->harga_akhir,
            $pembelian->created_at->format('Y-m-d H:i:s'),
            $pembelian->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCE6F1']]],
        ];
    }
}
