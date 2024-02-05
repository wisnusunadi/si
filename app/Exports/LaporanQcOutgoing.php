<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanQcOutgoing implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function produk_id()
    {
        return $this->produk_id;
    }

    public function no_so()
    {
        return $this->no_so;
    }

    public function hasil_pengujian()
    {
        return $this->hasil_pengujian;
    }

    public function tgl_mulai()
    {
        return $this->tgl_mulai;
    }

    public function tgl_selesai()
    {
        return $this->tgl_selesai;
    }

    public function __construct(string $produk_id, string $no_so, string $hasil_pengujian, string $tgl_mulai, string $tgl_selesai)
    {
        $this->produk_id = $produk_id;
        $this->no_so = $no_so;
        $this->hasil_pengujian = $hasil_pengujian;
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
    }

    public function view(): View
    {
        $q = "";
        return view('page.qc.laporan.outgoing', [
            'result' => $q
        ]);
    }
}
