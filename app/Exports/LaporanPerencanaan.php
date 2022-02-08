<?php

namespace App\Exports;

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



class LaporanPerencanaan implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */



    public function tahun()
    {
        return $this->tahun;
    }
    public function distributor()
    {
        return $this->distributor;
    }
    public function row()
    {
        return $this->row;
    }

    public function __construct(string $distributor, string $tahun, string $row)
    {
        $this->row = $row;
        $this->tahun = $tahun;
        $this->distributor = $distributor;
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $r = $this->row + 4;
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);

        $sheet->getStyle('A' . $r)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f1fd1b');
        return [

            $r    => ['font' => ['bold' => true]],

        ];

        // $sheet->getStyle('A3:E3')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('FFFF0000');


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
    }

    public function view(): View
    {



        $dsb = $this->distributor;
        $thn = $this->tahun;

        $data = RencanaPenjualan::where(['customer_id' => $dsb, 'tahun' => $thn])->get();
        $detail = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($dsb, $thn) {
            $q->where('customer_id', $dsb)
                ->where('tahun', $thn);
        })->get();
        return view('page.penjualan.penjualan.LaporanPerencanaanEx', ['data' => $data, 'detail' => $detail]);
    }

    public function title(): string
    {
        return 'RENCANA ';
    }
}
