<?php

namespace App\Exports;

use App\Models\Barang_keluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class OutBarangExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function collection()
    {
        return Barang_keluar::with(['lokawisata', 'barang'])
        ->get()
        ->map(function($item){
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
