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
use Maatwebsite\Excel\Events\AfterSheet;
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
    public function tampilan()
    {
        return $this->tampilan;
    }

    public function __construct(string $jenis_penjualan, string $distributor, string $tgl_awal,  string $tgl_akhir, string $seri, string $tampilan)
    {
        $this->jenis_penjualan = $jenis_penjualan;
        $this->distributor = $distributor;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->seri = $seri;
        $this->tampilan = $tampilan;
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {


        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:w2')->getFont()->setBold(true);

        $sheet->getStyle('b2:f2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('f2:g2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');

        $sheet->getStyle('g2:k2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('l2:m2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b359');

        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('K')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('N')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);

        if ($this->seri != "kosong") {
            $sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(70);
            $sheet->getStyle('P')->getAlignment()->setWrapText(true);
            $sheet->getStyle('n2:t2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('89d0b4');
            $sheet->getStyle('u2:w2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('f99c83');
            $sheet->getStyle('U:V')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
            $sheet->getStyle('n2:s2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('89d0b4');
            $sheet->getStyle('t2:v2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('f99c83');
            $sheet->getStyle('T:U')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getStyle('A:W')->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A:H')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L:M')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function view(): View
    {
        $tampilan = $this->tampilan;
        $seri = $this->seri;
        $dsb = $this->distributor;
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $x = explode(',', $this->jenis_penjualan);
        $data = "";


        if ($tampilan == 'merge') {
            if ($dsb == 'semua') {
                $ekatalog = Pesanan::has('Ekatalog')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $spa = Pesanan::has('Spa')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                $spb = Pesanan::has('Spb')->wherenotnull('no_po')
                    ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                    ->orderby('so', 'ASC')
                    ->get();
                if ($x == ['ekatalog', 'spa', 'spb']) {
                    $data = $ekatalog->merge($spa)->merge($spb)->sortBy('created_at');
                    $header = 'Laporan Penjualan Semua';
                } else if ($x == ['ekatalog', 'spa']) {
                    $data = Pesanan::has('Spb', 'Ekatalog')->wherenotnull('no_po')
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->orderby('so', 'ASC')
                        ->get();
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
                    $header = 'Laporan Penjualan SPA';
                } else if ($this->jenis_penjualan == 'spb') {
                    $data = $spb;
                    $header = 'Laporan Penjualan SPB';
                } else {
                    $data = $ekatalog->merge($spa)->merge($spb)->sortBy('created_at');
                    $header = 'Laporan Penjualan Semua';
                }
            } else {
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

                if ($x == ['ekatalog', 'spa', 'spb']) {
                    $data = $Ekatalog->merge($Spa)->merge($Spb);
                    $header = 'Laporan Penjualan Semua';
                } else if ($x == ['ekatalog', 'spa']) {
                    $data = $Ekatalog->merge($Spa);
                    $header = 'Laporan Penjualan Ekatalog dan SPA';
                } else if ($x == ['ekatalog', 'spb']) {
                    $data = $Ekatalog->merge($Spb);
                    $header = 'Laporan Penjualan Ekatalog dan SPB';
                } else if ($x == ['spa', 'spb']) {
                    $data = $Spa->merge($Spb);
                    $header = 'Laporan Penjualan SPA dan SPB';
                } else if ($this->jenis_penjualan == 'ekatalog') {
                    $data = $Ekatalog;
                    $header = 'Laporan Penjualan Ekatalog ';
                } else if ($this->jenis_penjualan == 'spa') {
                    $data = $Spa;
                    $header = 'Laporan Penjualan SPA';
                } else if ($this->jenis_penjualan == 'spb') {
                    $data = $Spb;
                    $header = 'Laporan Penjualan SPB';
                } else {
                    $data = $Ekatalog->merge($Spa)->merge($Spb);
                    $header = 'Laporan Penjualan Semua';
                }
            }
        } else {
            if ($dsb == 'semua') {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%EKAT%');
                })->get();
                $Spa  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%SPA%');
                })->get());

                $Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%SPB%');
                })->get());

                $Ekatalog_Spa  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%EKAT%')
                        ->orwhere('so', 'LIKE', '%SPA%');
                })->get());
                $Ekatalog_Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%EKAT%')
                        ->orwhere('so', 'LIKE', '%SPB%');
                })->get());
                $Spa_Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%SPA%')
                        ->orwhere('so', 'LIKE', '%SPB%');
                })->get());
                $Ekatalog_Spa_Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%SPA%')
                        ->orwhere('so', 'LIKE', '%SPB%')
                        ->orwhere('so', 'LIKE', '%EKAT%');
                })->get());


                $Part_Spa  = collect(DetailPesananPart::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%SPA%');
                })->get());
                $Part_Spb  = collect(DetailPesananPart::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('so', 'LIKE', '%SPB%');
                })->get());
            } else {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $dsb);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $dsb);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $dsb);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $dsb);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $dsb);
                })->get();
            }

            if ($x == ['ekatalog', 'spa', 'spb']) {
                $part = $Part_Spa->merge($Part_Spb);
                $data = $Ekatalog_Spa_Spb->merge($part);
                $header = 'Laporan Penjualan Semua';
            } else if ($x == ['ekatalog', 'spa']) {
                $data = $Ekatalog_Spa->merge($Part_Spa);
                $header = 'Laporan Penjualan Ekatalog dan SPA';
            } else if ($x == ['ekatalog', 'spb']) {
                $data = $Ekatalog_Spb->merge($Part_Spb);
                $header = 'Laporan Penjualan Ekatalog dan SPB';
            } else if ($x == ['spa', 'spb']) {
                $part = $Part_Spa->merge($Part_Spb);
                $data = $Spa_Spb->merge($part);
                $header = 'Laporan Penjualan SPA dan SPB';
            } else if ($this->jenis_penjualan == 'ekatalog') {
                $data = $Ekatalog;
                $header = 'Laporan Penjualan Ekatalog ';
            } else if ($this->jenis_penjualan == 'spa') {
                $data = $Part_Spa->merge($Spa);
                $header = 'Laporan Penjualan SPA';
            } else if ($this->jenis_penjualan == 'spb') {
                $data = $Spb->merge($Part_Spb);
                $header = 'Laporan Penjualan SPB';
            }
        }
        return view('page.penjualan.penjualan.LaporanPenjualanEx', ['data' => $data, 'header' => $header, 'seri' => $seri, 'tampilan' => $tampilan]);
    }
    public function title(): string
    {
        return 'Sudah PO (Variasi)';
    }
}
