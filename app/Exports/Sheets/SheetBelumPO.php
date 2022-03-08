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
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // return [

        //     1    => ['font' => ['bold' => true, 'size' => 16]],

        // ];
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:u2')->getFont()->setBold(true);


        $sheet->getStyle('b2:e2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('f2:g2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b359');
        $sheet->getStyle('h2:m2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('89d0b4');
        $sheet->getStyle('n2:p2')->getFill()
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
            $ekatalog = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) {
                $q->whereNUll('no_po')
                    ->wherenotIN('status', ['batal']);
            })->get();
        } else {
            $ekatalog = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb) {
                $q->whereNUll('no_po')
                    ->where('customer_id', $dsb)
                    ->wherenotIN('status', ['batal']);
            })->get();
        }


        if ($x == ['ekatalog', 'spa', 'spb']) {
            $data = $ekatalog;
            $header = 'Laporan Penjualan Semua';
        } else if ($x == ['ekatalog', 'spa']) {
            $data = $ekatalog;
            $header = 'Laporan Penjualan Ekatalog dan SPA';
        } else if ($x == ['ekatalog', 'spb']) {
            $data = $ekatalog;
            $header = 'Laporan Penjualan Ekatalog dan SPB';
        } else if ($this->jenis_penjualan == 'ekatalog') {
            $data = $ekatalog;
            $header = 'Laporan Penjualan Ekatalog ';
        }
        return view('page.penjualan.penjualan.LaporanPenjualanBelumPOEx', ['header' => $header, 'data' => $data]);
    }

    public function title(): string
    {
        return 'Belum PO';
    }
}
