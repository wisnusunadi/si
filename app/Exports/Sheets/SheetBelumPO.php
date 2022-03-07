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

class SheetBelumPO implements WithTitle, FromView, ShouldAutoSize
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



    public function view(): View
    {
        $dsb = $this->distributor;
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $x = explode(',', $this->jenis_penjualan);


        if ($dsb == 'semua') {
            $ekatalog = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) {
                $q->whereNUll('no_po');
            })->get();
        } else {
            $ekatalog = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb) {
                $q->whereNUll('no_po')
                    ->where('customer_id', $dsb);
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
