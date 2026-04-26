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
    private $nasabahId;

    public function __construct($dateFrom = null, $dateTo = null, $nasabahId = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->nasabahId = $nasabahId;
    }

    public function collection()
    {
        $query = Pembelian::with('product', 'nasabah');

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->nasabahId) {
            $query->where('nasabah_id', $this->nasabahId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nasabah',
            'Produk',
            'Harga Satuan Beli',
            'Satuan',
            'Total Berat',
            'Total Harga',
            'Potongan (%)',
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
            $pembelian->nasabah?->nama ?? '-',
            $pembelian->product->nama_produk ?? '-',
            $pembelian->harga_satuan_beli,
            $pembelian->satuan,
            $pembelian->total_berat,
            $pembelian->total_harga,
            $pembelian->potongan,
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
