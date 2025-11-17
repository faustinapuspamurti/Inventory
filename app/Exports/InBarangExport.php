<?php

namespace App\Exports;

use App\Models\Barang_masuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class InBarangExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function collection()
    {
        return Barang_masuk::select('barang_id', 'jumlah_masuk', 'tanggal_masuk', 'harga', 'deskripsi', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID Barang',
            'Jumlah Masuk',
            'Tanggal Masuk',
            'Harga',
            'Keterangan',
            'Dibuat Pada'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
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
        $sheet->getStyle("A2:D{$lastRow}")->applyFromArray([
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
