<?php

namespace App\Exports;

use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class LaporanPenjualanAll implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */



    public function jenis_penjualan()
    {
        return $this->jenis_penjualan;
    }
    public function distributor()
    {
        return $this->distributor;
    }
    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }
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
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
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
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('f2:g2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('m2:r2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('89d0b4');
        $sheet->getStyle('g2:j2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('k2:l2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b359');
        $sheet->getStyle('s2:u2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f99c83');
    }

    public function view(): View
    {
        $dsb = $this->distributor;
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $x = explode(',', $this->jenis_penjualan);


        if ($dsb == 'semua') {
            $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
            })->orderBy('pesanan_id', 'ASC')->get();
        } else {
            $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->where('customer_id', $dsb);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->where('customer_id', $dsb);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->where('customer_id', $dsb);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->where('customer_id', $dsb);
            })->orderBy('pesanan_id', 'ASC')->get();
            $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->where('customer_id', $dsb);
            })->orderBy('pesanan_id', 'ASC')->get();
        }


        if ($x == ['ekatalog', 'spa', 'spb']) {
            $prd = $Ekatalog->merge($Spa)->merge($Spb);
            $part = $Part_Spa->merge($Part_Spb);
            $data = $prd->merge($part);
            $header = 'Laporan Penjualan Semua';
        } else if ($x == ['ekatalog', 'spa']) {
            $prd = $Ekatalog->merge($Spa);
            $data = $prd->merge($Part_Spa);
            $header = 'Laporan Penjualan Ekatalog dan SPA';
        } else if ($x == ['ekatalog', 'spb']) {
            $prd = $Ekatalog->merge($Spb);
            $data = $prd->merge($Part_Spb);
            $header = 'Laporan Penjualan Ekatalog dan SPB';
        } else if ($x == ['spa', 'spb']) {
            $prd = $Spa->merge($Spb);
            $part = $Part_Spa->merge($Part_Spb);
            $data = $prd->merge($part);
            $header = 'Laporan Penjualan SPA dan SPB';
        } else if ($this->jenis_penjualan == 'ekatalog') {
            $data = $Ekatalog;
            $header = 'Laporan Penjualan Ekatalog ';
        } else if ($this->jenis_penjualan == 'spa') {
            $data = $Spa->merge($Part_Spa);
            $header = 'Laporan Penjualan SPA';
        } else if ($this->jenis_penjualan == 'spb') {
            $data = $Spb->merge($Part_Spb);
            $header = 'Laporan Penjualan SPB';
        }

        return view('page.penjualan.penjualan.LaporanPenjualanEx', ['data' => $data, 'header' => $header]);
    }
}
