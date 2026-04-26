<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        $query = Penjualan::with('details')->orderByDesc('created_at');

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
            'Nama Pembeli',
            'Nopol',
            'Jumlah Item',
            'Total Penjualan',
            'Tanggal',
            'Keterangan',
        ];
    }

    public function map($penjualan): array
    {
        return [
            $penjualan->id,
            $penjualan->nama_customer,
            $penjualan->nopol ?? '-',
            $penjualan->details->count(),
            $penjualan->total_pembelian,
            $penjualan->created_at->format('Y-m-d H:i:s'),
            $penjualan->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'DCE6F1'],
                ],
            ],
        ];
    }
}
