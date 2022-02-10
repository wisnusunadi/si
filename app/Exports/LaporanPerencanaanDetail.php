<?php

namespace App\Exports;

use App\Exports\Sheets\SheetRealisasiPenjualan;
use App\Exports\Sheets\SheetRencanaPenjualan;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailRencanaPenjualan;
use App\Models\Ekatalog;
use App\Models\RencanaPenjualan;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class LaporanPerencanaanDetail implements FromView, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
{
    //  WithStyles, WithColumnFormatting
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(string $distributor, string $tahun, string $row, string $row_rencana)
    {
        $this->row = $row;
        $this->row_rencana = $row_rencana;
        $this->tahun = $tahun;
        $this->distributor = $distributor;
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->row == 0) {
            $r = $this->row_rencana + 2;
        } else {
            $r = 5;
        }

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        // $sheet->getStyle('A3:J3')->getFont()->setBold(true);

        $sheet->getStyle('A' . $r)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f1fd1b');
        // $sheet->getStyle('E' . $r)->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('f1fd1b');
        // $sheet->getStyle('F' . $r)->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('f1fd1b');
        // $sheet->getStyle('J' . $r)->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('f1fd1b');


        $sheet->getStyle('C2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('a99ece');
        $sheet->getStyle('C3:E3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('a99ece');
        $sheet->getStyle('F2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('96d094');
        $sheet->getStyle('F3:J3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('96d094');


        //     $sheet->getStyle('f2:g2')->getFill()
        //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //         ->getStartColor()->setRGB('51adb9');
        //     $sheet->getStyle('m2:r2')->getFill()
        //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //         ->getStartColor()->setRGB('89d0b4');
        //     $sheet->getStyle('g2:j2')->getFill()
        //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //         ->getStartColor()->setRGB('00ff7f');
        //     $sheet->getStyle('a2')->getFill()
        //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //         ->getStartColor()->setRGB('00ff7f');
        //     $sheet->getStyle('k2:l2')->getFill()
        //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //         ->getStartColor()->setRGB('00b359');
        //     $sheet->getStyle('s2:u2')->getFill()
        //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //         ->getStartColor()->setRGB('f99c83');

        return [

            $r    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
            3    => ['font' => ['bold' => true]],

        ];
    }

    public function view(): View
    {
        $dsb = $this->distributor;
        $thn = $this->tahun;
        $c =  $this->row_rencana;

        $data = RencanaPenjualan::where(['customer_id' => $dsb, 'tahun' => $thn])->get();
        $detail = DetailPesanan::WhereHas('DetailRencanaPenjualan.RencanaPenjualan', function ($q) use ($dsb) {
            $q->where('customer_id', $dsb);
        })->get();

        return view('page.penjualan.penjualan.LaporanPerencanaandetailEx', ['data' => $data, 'detail' => $detail, 'c' => $c]);
    }

    public function title(): string
    {
        return 'DETAIL';
    }
}
