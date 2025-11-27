<?php

namespace App\Exports;

use App\Models\Barang_keluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class OutBarangExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $start_date, $end_date, $search, $lokawisata_id;

    public function __construct($start_date, $end_date, $search, $lokawisata_id)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->search = $search;
        $this->lokawisata_id = $lokawisata_id;
    }

    public function collection()
    {
        $query = Barang_keluar::with(['lokawisata', 'barang']);

        // Filter Search
        if (!empty($this->search)) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($sub) use ($search) {
                    $sub->where('nama_barang', 'like', '%' . $search . '%');
                })
                ->orWhereHas('lokawisata', function ($sub) use ($search) {
                    $sub->where('nama_lokawisata', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter Tanggal
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $start = Carbon::parse($this->start_date)->startOfDay();
            $end = Carbon::parse($this->end_date)->endOfDay();
            $query->whereBetween('tanggal_keluar', [$start, $end]);
        } elseif (!empty($this->start_date)) {
            $query->whereDate('tanggal_keluar', $this->start_date);
        }

        // Filter Lokawisata
        if (!empty($this->lokawisata_id)) {
            $query->where('lokawisata_id', $this->lokawisata_id);
        }

        return $query->orderBy('id', 'desc')->get()->map(function ($item) {
            return [
                'tanggal_keluar' => $item->tanggal_keluar,
                'lokawisata' => $item->lokawisata->nama_lokawisata ?? '-',
                'barang' => $item->barang->nama_barang ?? '-',
                'jumlah_keluar' => $item->jumlah_keluar,
                'harga_satuan' => $item->harga_satuan,
                'harga_total' => $item->harga_total,
                'keterangan' => $item->keterangan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal Keluar',
            'Lokawisata',
            'Nama Barang',
            'Jumlah Keluar',
            'Harga Satuan',
            'Harga Total',
            'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:G{$lastRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
                'wrapText' => true, 
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        for ($row = 1; $row <= $lastRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(20);
        }
        return [];
    }

    public function title(): string
    {
        return 'Data Stok Barang';
    }
}
