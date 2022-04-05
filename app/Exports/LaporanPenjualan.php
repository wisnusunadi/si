<?php

namespace App\Exports;

use App\Exports\Sheets\SheetBelumPO;
use App\Exports\Sheets\SheetSudahPO;
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

    public function sheets(): array
    {
        $sheets = [];
        $x = explode(',', $this->jenis_penjualan);
        $sheets[] = new SheetSudahPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir, $this->seri, $this->tampilan);

        if ($x == ['ekatalog', 'spa', 'spb']) {
            $sheets[] = new SheetBelumPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir);
        } else if ($x == ['ekatalog', 'spa']) {
            $sheets[] = new SheetBelumPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir);
        } else if ($x == ['ekatalog', 'spb']) {
            $sheets[] = new SheetBelumPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir);
        } else if ($this->jenis_penjualan == 'ekatalog') {
            $sheets[] = new SheetBelumPO($this->jenis_penjualan, $this->distributor, $this->tgl_awal, $this->tgl_akhir);
        }


        return $sheets;
    }
}
