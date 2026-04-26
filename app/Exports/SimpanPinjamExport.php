<?php

namespace App\Exports;

use App\Models\SimpanPinjam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SimpanPinjamExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private const TIPE_LABELS = [
        'bayar' => 'Bayar',
        'bayar_simpanan' => 'Bayar dari Simpanan',
        'hutang' => 'Hutang',
        'transaksi' => 'Transaksi',
        'ambil' => 'Ambil',
        'ambil_simpanan' => 'Ambil Simpanan',
        'simpan' => 'Simpan',
    ];

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
        $query = SimpanPinjam::with('nasabah');

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
            self::TIPE_LABELS[$simpanPinjam->tipe] ?? ucfirst((string) $simpanPinjam->tipe),
            $simpanPinjam->nominal,
            $simpanPinjam->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCE6F1']]],
        ];
    }
}
