<?php

namespace App\Exports\Sheets;

use App\Models\DetailPesanan;
use App\Models\DetailRencanaPenjualan;
use App\Models\RencanaPenjualan;
use App\Models\TblSiswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetRealisasiPenjualan implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */


    public function __construct(string $distributor, string $tahun, string $row_realisasi)
    {
        $this->row_realisasi = $row_realisasi;
        $this->tahun = $tahun;
        $this->distributor = $distributor;
    }

    public function styles(Worksheet $sheet)
    {
        $r = $this->row_realisasi + 4;
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);

        $sheet->getStyle('A' . $r)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f1fd1b');
        return [

            $r    => ['font' => ['bold' => true]],

        ];
    }
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    public function view(): View
    {
        $dsb = $this->distributor;
        $thn = $this->tahun;

        $data = RencanaPenjualan::where(['customer_id' => $dsb, 'tahun' => $thn])->get();
        $detail = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb) {
            $q->where('customer_id', $dsb);
        })->get();

        // $dsb = $this->distributor;
        // $thn = $this->tahun;

        // $data = RencanaPenjualan::where(['customer_id' => $dsb, 'tahun' => $thn])->get();
        // $detail = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($dsb, $thn) {
        //     $q->where('customer_id', $dsb)
        //         ->where('tahun', $thn);
        // })->get();

        return view('page.penjualan.penjualan.LaporanRealisasiEx', ['data' => $data, 'detail' => $detail]);
    }

    public function title(): string
    {
        return 'REALISASI';
    }
}
