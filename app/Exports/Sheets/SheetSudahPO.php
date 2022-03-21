<?php

namespace App\Exports\Sheets;

use App\Models\Pesanan;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetSudahPO implements WithTitle, FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
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
    public function seri()
    {
        return $this->seri;
    }

    public function __construct(string $jenis_penjualan, string $distributor, string $tgl_awal,  string $tgl_akhir, string $seri)
    {
        $this->jenis_penjualan = $jenis_penjualan;
        $this->distributor = $distributor;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->seri = $seri;
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {


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
        $seri = $this->seri;
        $dsb = $this->distributor;
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $x = explode(',', $this->jenis_penjualan);

        if ($dsb == 'semua') {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $data = Pesanan::has('Spb', 'Ekatalog')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan Semua';
            } else if ($x == ['ekatalog', 'spa']) {
                $data = Pesanan::has('Spb', 'Ekatalog')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan Ekatalog dan SPA';
            } else if ($x == ['ekatalog', 'spb']) {
                $data = Pesanan::has('Spa', 'Ekatalog')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan Ekatalog dan SPB';
            } else if ($x == ['spa', 'spb']) {
                $data = Pesanan::has('Ekatalog', 'Spb')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan SPA dan SPB';
            } else if ($this->jenis_penjualan == 'ekatalog') {
                $data = Pesanan::has('Ekatalog')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan Ekatalog ';
            } else if ($this->jenis_penjualan == 'spa') {
                $data = Pesanan::has('Spa')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan SPA';
            } else if ($this->jenis_penjualan == 'spb') {
                $data = Pesanan::has('Spb')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan SPB';
            }
        } else {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog = Pesanan::wherehas('Ekatalog', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $Spa = Pesanan::wherehas('Spa', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $Spb = Pesanan::wherehas('Spb', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $data = $Ekatalog->merge($Spa)->merge($Spb);
                $header = 'Laporan Penjualan Semua';
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog = Pesanan::wherehas('Ekatalog', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $Spa = Pesanan::wherehas('Spa', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $data = $Ekatalog->merge($Spa);

                $header = 'Laporan Penjualan Ekatalog dan SPA';
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog = Pesanan::wherehas('Ekatalog', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $Spb = Pesanan::wherehas('Spb', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $data = $Ekatalog->merge($Spb);

                $header = 'Laporan Penjualan Ekatalog dan SPB';
            } else if ($x == ['spa', 'spb']) {
                $Spa = Pesanan::wherehas('Spa', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $Spb = Pesanan::wherehas('Spb', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $data = $Spa->merge($Spb);


                $header = 'Laporan Penjualan SPA dan SPB';
            } else if ($this->jenis_penjualan == 'ekatalog') {
                $data = Pesanan::wherehas('Ekatalog', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan Ekatalog ';
            } else if ($this->jenis_penjualan == 'spa') {
                $data = Pesanan::wherehas('Spa', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan SPA';
            } else if ($this->jenis_penjualan == 'spb') {
                $data = Pesanan::wherehas('Spb', function ($q) use ($dsb) {
                    $q->where('customer_id', $dsb);
                })->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $header = 'Laporan Penjualan SPB';
            }
        }


        return view('page.penjualan.penjualan.LaporanPenjualanEx', ['data' => $data, 'header' => $header, 'seri' => $seri]);
    }
    public function title(): string
    {
        return 'Sudah PO';
    }
}
