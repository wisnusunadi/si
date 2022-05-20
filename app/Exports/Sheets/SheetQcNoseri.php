<?php

namespace App\Exports\Sheets;

use App\Models\NoseriDetailPesanan;
use App\Models\OutgoingPesananPart;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetQcNoseri implements WithTitle, FromView, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function jenis()
    {
        return $this->jenis;
    }
    public function produk()
    {
        return $this->produk;
    }
    public function so()
    {
        return $this->so;
    }
    public function hasil()
    {
        return $this->hasil;
    }
    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }

    public function __construct(string $jenis, string $produk, string $so, string $hasil, string $tgl_awal, string $tgl_akhir)
    {
        $this->jenis = $jenis;
        $this->produk = $produk;
        $this->so = $so;
        $this->hasil = $hasil;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
    //         'I' => NumberFormat::FORMAT_TEXT
    //     ];
    // }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:N2')->getFont()->setBold(true);


        $sheet->getStyle('A:N')->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // $sheet->getStyle('a2')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('00ff7f');
        // $sheet->getStyle('b2:c2')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('51adb9');
        // $sheet->getStyle('d2:f2')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('51adb9');
        // $sheet->getStyle('g2:j2')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('89d0b4');
        // $sheet->getStyle('k2:n2')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('00ff7f');
        // $sheet->getStyle('o2')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('89d0b4');
        $sheet->getStyle('A:E')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L:N')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        if($this->jenis == "produk"){
            $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(30);
            $sheet->getStyle('K')->getAlignment()->setWrapText(true);
        }
    }

    public function view(): View
    {
        $jenis = $this->jenis;
        $produk = $this->produk;
        $hasil = $this->hasil;
        $so = $this->so;
        $tgl_awal = $this->tgl_awal;
        $tgl_akhir = $this->tgl_akhir;
        $res = "";

        if($jenis == "produk"){
            if($produk != "0" && $so == "0"){
                if ($hasil != "semua") {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($produk){
                        $q->where('penjualan_produk_id', $produk);
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                } else {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($produk){
                        $q->where('penjualan_produk_id', $produk);
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                }
            } else if($produk == "0" && $so != "0"){
                if ($hasil != "semua") {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function($q) use($so){
                        $q->where('so', 'LIKE', '%'.$so.'%');
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                } else {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function($q) use($so){
                        $q->where('so', 'LIKE', '%'.$so.'%');
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                }
            } else if($produk != "0" && $so != "0"){
                if ($hasil != "semua") {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($produk){
                        $q->where('penjualan_produk_id', $produk);
                    })->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function($q) use($so){
                        $q->where('so', 'LIKE', '%'.$so.'%');
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                } else {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($produk){
                        $q->where('penjualan_produk_id', $produk);
                    })->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function($q) use($so){
                        $q->where('so', 'LIKE', '%'.$so.'%');
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                }
            } else if($produk == "0" && $so == "0"){
                if ($hasil != "semua") {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                } else {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                }
            }
        } else if($jenis == "part"){
            if ($produk != "0" && $so == '0') {
                $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananPart', function($q) use($produk){
                    $q->where('m_sparepart_id', $produk);
                })->orderBy('detail_pesanan_part_id', 'ASC')->get();
            }
            else if ($produk == "0" && $so != '0') {
                $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananPart.Pesanan', function($q) use($so){
                    $q->where('so', 'LIKE', '%'.$so.'%');
                })->orderBy('detail_pesanan_part_id', 'ASC')->get();
            }
            else if ($produk != "0" && $so != '0') {
                $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananPart', function($q) use($produk){
                    $q->where('m_sparepart_id', $produk);
                })->whereHas('DetailPesananPart.Pesanan', function($q) use($so){
                    $q->where('so', 'LIKE', '%'.$so.'%');
                })->orderBy('detail_pesanan_part_id', 'ASC')->get();
            }else if ($produk == "0" && $so == '0'){
                $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            }
        }

        return view('page.qc.laporan.LaporanQcNoseri', ['jenis' => $jenis, 'produk' => $produk, 'so' => $so, 'hasil' => $hasil, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir, 'data' => $res]);
    }

    public function title(): string
    {
        return 'Per Nomor Seri';
    }
}
