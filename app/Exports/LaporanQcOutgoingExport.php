<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanQcOutgoingExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(string $customer_id, string $jenis_penjualan, string $tgl_mulai, string $tgl_selesai)
    {
        $this->customer_id = $customer_id;
        $this->jenis_penjualan = $jenis_penjualan;
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
    }

    public function view(): View
    {
        $q = "";

        return view('page.penjualan.laporan.spa', [
            'spa' => $q
        ]);
    }
}
