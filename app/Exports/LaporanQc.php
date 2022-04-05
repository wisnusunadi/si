<?php

namespace App\Exports;

use App\Exports\Sheets\SheetQcPenjualan;
use Dotenv\Util\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanQc implements WithMultipleSheets
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

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new SheetQcPenjualan($this->jenis, $this->produk, $this->so, $this->hasil, $this->tgl_awal, $this->tgl_akhir);
        return $sheets;
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
    //         'I' => NumberFormat::FORMAT_TEXT
    //     ];
    // }

    // public function styles(Worksheet $sheet)
    // {
    //     $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    //     $sheet->getStyle('A2:O2')->getFont()->setBold(true);
    //     $sheet->getStyle('A:O')->getAlignment()
    //     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    //     $sheet->getStyle('a2')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('00ff7f');
    //     $sheet->getStyle('b2:c2')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('51adb9');
    //     $sheet->getStyle('d2:f2')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('51adb9');
    //     $sheet->getStyle('g2:j2')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('89d0b4');
    //     $sheet->getStyle('k2:n2')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('00ff7f');
    //     $sheet->getStyle('o2')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('89d0b4');
    //     $sheet->getStyle('A:C')->getAlignment()
    //         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle('G:I')->getAlignment()
    //         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle('O')->getAlignment()
    //         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    //     $sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(35);
    //     $sheet->getStyle('D')->getAlignment()->setWrapText(true);
    //     $sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(45);
    //     $sheet->getStyle('E')->getAlignment()->setWrapText(true);
    //     $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(38);
    //     $sheet->getStyle('K')->getAlignment()->setWrapText(true);
    //     $sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(30);
    //     $sheet->getStyle('L')->getAlignment()->setWrapText(true);
    //     $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(45);
    //     $sheet->getStyle('N')->getAlignment()->setWrapText(true);
    // }

    // public function view(): View
    // {
    //     $jenis = $this->jenis;
    //     $produk = $this->produk;
    //     $hasil = $this->hasil;
    //     $so = $this->so;
    //     $tgl_awal = $this->tgl_awal;
    //     $tgl_akhir = $this->tgl_akhir;
    //     $res = "";

    //     if($jenis == "produk"){
    //         if($produk != "0" && $so == "0"){
    //             if ($hasil != "semua") {
    //                 $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk', function($q) use($produk){
    //                     $q->where('produk_penjualan_id', $produk);
    //                 })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir, $hasil){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
    //                 })->get();
    //             } else {
    //                 $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk', function($q) use($produk){
    //                     $q->where('produk_penjualan_id', $produk);
    //                 })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
    //                 })->get();
    //             }
    //         } else if($produk == "0" && $so != "0"){
    //             if ($hasil != "semua") {
    //                 $res = Pesanan::where('so', $so)->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir, $hasil){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
    //                 })->get();
    //             } else {
    //                 $res = Pesanan::where('so', $so)->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
    //                 })->get();
    //             }
    //         } else if($produk != "0" && $so != "0"){
    //             if ($hasil != "semua") {
    //                 $res = Pesanan::where('so', $so)->whereHas('DetailPesanan.DetailPesananProduk', function($q) use($produk){
    //                     $q->where('produk_penjualan_id', $produk);
    //                 })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir, $hasil){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
    //                 })->get();
    //             } else {
    //                 $res = Pesanan::where('so', $so)->whereHas('DetailPesanan.DetailPesananProduk', function($q) use($produk){
    //                     $q->where('produk_penjualan_id', $produk);
    //                 })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
    //                 })->get();
    //             }
    //         } else if($produk == "0" && $so == "0"){
    //             if ($hasil != "semua") {
    //                 $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir, $hasil){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
    //                 })->get();
    //             } else {
    //                 $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir){
    //                     $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
    //                 })->get();
    //             }
    //         }
    //     } else if($jenis == "part"){
    //         if ($produk != "0" && $so == '0') {
    //             $res = Pesanan::whereHas('DetailPesananPart', function($q) use($produk){
    //                 $q->where('m_sparepart_id', $produk);
    //             })->whereHas('DetailPesananPart.OutgoingPesananPart', function($q) use($tgl_awal, $tgl_akhir){
    //                 $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
    //             })->get();
    //         }
    //         else if ($produk == "0" && $so != '0') {
    //             $res = Pesanan::where('so', $so)->whereHas('DetailPesananPart.OutgoingPesananPart', function($q) use($tgl_awal, $tgl_akhir){
    //                 $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
    //             })->get();
    //         }
    //         else if ($produk != "0" && $so != '0') {
    //             $res = Pesanan::where('so', $so)->whereHas('DetailPesananPart', function($q) use($produk){
    //                 $q->where('m_sparepart_id', $produk);
    //             })->whereHas('DetailPesananPart.OutgoingPesananPart', function($q) use($tgl_awal, $tgl_akhir){
    //                 $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
    //             })->get();
    //         }else if ($produk == "0" && $so == '0'){
    //             $res = Pesanan::whereHas('DetailPesananPart.OutgoingPesananPart', function($q) use($tgl_awal, $tgl_akhir){
    //                 $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
    //             })->get();
    //         }
    //     }

    //     // if($header == "ekspedisi"){
    //     //     if($eks != '0'){
    //     //         $data = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->get();
    //     //     }
    //     //     else{
    //     //         $data = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->get();
    //     //     }
    //     // }
    //     // else if($header == "nonekspedisi"){
    //     //     $data = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->get();
    //     // }
    //     // else{
    //     //     $data = Logistik::whereBetween('tgl_kirim', [$awal, $akhir])->orderByRaw('logistik.detail_logistik.detail_pesanan_produk.detail_pesanan.pesanan.id ASC')->orderByRaw('logistik.detail_logistik_part.detail_pesanan_part.pesanan.id ASC')->get();
    //     // }

    //     return view('page.qc.laporan.LaporanQc', ['jenis' => $jenis, 'produk' => $produk, 'so' => $so, 'hasil' => $hasil, 'tgl_awal' => $tgl_awal, 'akhir' => $tgl_akhir, 'data' => $res]);
    // }
}
