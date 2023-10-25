<?php

namespace App\Exports;

use App\Models\DetailPesananProduk;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NoseriQC implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function id()
    {
        return $this->id;
    }
    public function __construct(string $id)
    {
        $this->id = $id;
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A5:D5')->getFont()->setBold(true);
        $sheet->getStyle('B2:C3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ])->getAlignment()->setWrapText(true);
    }

    public function registerEvents(): array
    {
        $id = $this->id;

        $p = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();

        $p_id = array();
        foreach ($p as $q) {
            $p_id[] = $q->id;
        }

        $count_row = NoseriTGbj::whereHas('detail', function ($q) use ($p_id) {
            $q->wherein('detail_pesanan_produk_id', $p_id);
        })->count();

        $total_row = $count_row + 5;
        $cellRange      = 'A5:D' . $total_row;

        return [
            AfterSheet::class    => function (AfterSheet $event) use ($cellRange) {
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setWrapText(true);
            },
        ];
    }


    public function view(): View
    {
        $id = $this->id;
        $header = Pesanan::find($id);
        $data = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();
        return view('page.qc.so.export_seri', ['data' => $data, 'header' => $header]);
    }

    public function title(): string
    {
        return 'Transfer GBJ';
    }
}
