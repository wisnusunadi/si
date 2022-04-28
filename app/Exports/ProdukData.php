<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Ekspedisi;
use App\Models\PenjualanProduk;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukData implements WithTitle, FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:h2')->getFont()->setBold(true);
    }

    public function view(): View
    {
        $data = PenjualanProduk::orderBy('nama')->get();
        return view('page.penjualan.produk.ProdukData', ['data' => $data]);
    }
    public function title(): string
    {
        return 'Data Produk';
    }
}
