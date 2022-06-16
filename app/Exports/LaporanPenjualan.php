<?php

namespace App\Exports;

use App\Exports\Sheets\DetailProduk;
use App\Exports\Sheets\SheetBelumPO;
use App\Exports\Sheets\SheetBerdasarkanDetailProduk;
use App\Exports\Sheets\SheetBerdasarkanPaket;
use App\Exports\Sheets\SheetBerdasarkanPO;
use App\Exports\Sheets\SheetSudahPO;
use App\Exports\Sheets\SheetSudahPOPaket;
use Dotenv\Util\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanPenjualan implements WithMultipleSheets
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
    public function jenis_laporan()
    {
        return $this->jenis_laporan;
    }

    public function __construct(string $jenis_penjualan, string $distributor, string $tgl_awal,  string $tgl_akhir, string $seri, string $jenis_laporan)
    {
        $this->jenis_penjualan = $jenis_penjualan;
        $this->distributor = $distributor;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->seri = $seri;
        $this->jenis_laporan = $jenis_laporan;
    }

    public function sheets(): array
    {
        $jenis_laporan = $this->jenis_laporan;
        $sheets = [];
        $x = explode(',', $this->jenis_penjualan);



        if ($jenis_laporan == 'detail_produk') {
            $sheets[] = new SheetBerdasarkanDetailProduk($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        } else if ($jenis_laporan == 'paket_produk') {
            $sheets[] = new SheetBerdasarkanPaket($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        } else if ($jenis_laporan == 'no_po') {
            $sheets[] = new SheetBerdasarkanPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        } else {
            $sheets[] = new SheetBerdasarkanSJ($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        }
        // $sheets[] = new SheetSudahPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->tampilan);



        if ($jenis_laporan == 'detail_produk') {
            $sheets[] = new SheetBerdasarkanDetailProduk($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        } else if ($jenis_laporan == 'paket_produk') {
            $sheets[] = new SheetBerdasarkanPaket($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        } else if ($jenis_laporan == 'no_po') {
            $sheets[] = new SheetBerdasarkanPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        } else {
            $sheets[] = new SheetBerdasarkanSJ($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->jenis_laporan);
        }
        // $sheets[] = new SheetSudahPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->tampilan);

        return $sheets;
    }
}
