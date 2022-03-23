<?php

namespace App\Exports\Sheets;

use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
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
use App\Models\Pesanan;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetBelumPO implements WithTitle, FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(string $jenis_penjualan, string $distributor, string $tgl_awal,  string $tgl_akhir)
    {
        $this->jenis_penjualan = $jenis_penjualan;
        $this->distributor = $distributor;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // return [

        //     1    => ['font' => ['bold' => true, 'size' => 16]],

        // ];
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:u2')->getFont()->setBold(true);


        $sheet->getStyle('b2:f2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('g2:h2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b359');
        $sheet->getStyle('i2:n2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('89d0b4');
        $sheet->getStyle('o2:q2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f99c83');

        $sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);


        $sheet->getStyle('A:R')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A:C')->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:H')->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O:P')->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }



    public function view(): View
    {
        $dsb = $this->distributor;
        $x = explode(',', $this->jenis_penjualan);
        $ekatalog = "";
        $spa = "";
        $spb = "";
        $data = "";

        if ($dsb == 'semua') {
            $ekatalog = Pesanan::whereHas('Ekatalog', function ($q) {
                $q->wherenotIN('status', ['batal']);
            })->wherenull('no_po')
                ->orderby('so', 'ASC')
                ->limit(41)
                ->get();

            $spa = Pesanan::Has('Spa')->wherenull('no_po')
                ->orderby('so', 'ASC')
                ->get();

            $spb = Pesanan::Has('Spb')->wherenull('no_po')
                ->orderby('so', 'ASC')
                ->get();

            // $ekatalog = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) {
            //     $q->whereNUll('no_po')
            //         ->wherenotIN('status', ['batal']);
            // })->get();
        } else {
            $ekatalog = Pesanan::whereHas('Ekatalog', function ($q) use ($dsb) {
                $q->wherenotIN('status', ['batal'])
                    ->where('customer_id', $dsb);
                })->wherenull('no_po')
                    ->orderby('so', 'ASC')
                    ->get();

            $spa = Pesanan::whereHas('Spa', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenull('no_po')
                    ->orderby('so', 'ASC')
                    ->get();

            $spb = Pesanan::whereHas('Spb', function ($q) use ($dsb) {
                        $q->where('customer_id', $dsb);
                    })->wherenull('no_po')
                        ->orderby('so', 'ASC')
                        ->get();
            // $ekatalog = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb) {
            //     $q->whereNUll('no_po')
            //         ->where('customer_id', $dsb)
            //         ->wherenotIN('status', ['batal']);
            // })->get();
        }


        if ($x == ['ekatalog', 'spa', 'spb']) {
            $data = $ekatalog->merge($spa)->merge($spb)->sortBy('created_at');
            $header = 'Laporan Penjualan Semua';
        } else if ($x == ['ekatalog', 'spa']) {
            $data = $ekatalog->merge($spa)->sortBy('created_at');
            $header = 'Laporan Penjualan Ekatalog dan SPA';
        } else if ($x == ['ekatalog', 'spb']) {
            $data = $ekatalog->merge($spb)->sortBy('created_at');
            $header = 'Laporan Penjualan Ekatalog dan SPB';
        } else if ($x == ['spa', 'spb']) {
            $data = $spa->merge($spb)->sortBy('created_at');
            $header = 'Laporan Penjualan SPA dan SPB';
        } else if ($this->jenis_penjualan == 'ekatalog') {
            $data = $ekatalog;
            $header = 'Laporan Penjualan Ekatalog ';
        } else if ($this->jenis_penjualan == 'spa') {
            $data = $spa;
            $header = 'Laporan Penjualan SPA ';
        } else if ($this->jenis_penjualan == 'spb') {
            $data = $spb;
            $header = 'Laporan Penjualan SPB ';
        }
        else {
            $data = $ekatalog->merge($spa)->merge($spb)->sortBy('created_at');
            $header = 'Laporan Penjualan Semua';
        }
        return view('page.penjualan.penjualan.LaporanPenjualanBelumPOEx', ['header' => $header, 'data' => $data]);
    }

    public function title(): string
    {
        return 'Belum PO';
    }
}
