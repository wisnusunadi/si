<?php

namespace App\Exports;

use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenjualan implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // use Exportable;

    // public function tgl_mulai(string $tgl_mulai)
    // {
    //     $this->tgl_mulai = $tgl_mulai;
    //     return $this;
    // }

    // public function tgl_selesai(string $tgl_selesai)
    // {
    //     $this->tgl_selesai = $tgl_selesai;
    //     return $this;
    // }

    // public function jenis_penjualan(string $jenis_penjualan)
    // {
    //     $this->jenis_penjualan = $jenis_penjualan;
    //     return $this;
    // }

    // public function customer_id(string $customer_id)
    // {
    //     $this->customer_id = $customer_id;
    //     return $this;
    // }

    public function tgl_mulai()
    {
        return $this->tgl_mulai;
    }

    public function tgl_selesai()
    {
        return $this->tgl_selesai;
    }

    public function jenis_penjualan()
    {
        return $this->jenis_penjualan;
    }

    public function customer_id()
    {
        return $this->customer_id;
    }

    // public function query()
    // {
    //     if ($this->jenis = 'ekatalog') {
    //         if ($this->customer_id != null) {
    //             return Ekatalog::query()->where(['customer_id' => $this->customer_id])->whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
    //         } else if ($this->customer_id == null) {
    //             return Ekatalog::query()->where(['customer_id' => $this->customer_id])->whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
    //         }
    //     }
    // }

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
        if ($this->jenis_penjualan == 'semua') {
            if ($this->customer_id != null) {
                $q = Ekatalog::where('customer_id', $this->customer_id)->whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
            } else {
                $q = Ekatalog::whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
            }
            return view('page.penjualan.laporan.semua', [
                'semua' => $q
            ]);
        } else if ($this->jenis_penjualan == 'ekatalog') {
            if ($this->customer_id != null) {
                $q = Ekatalog::where('customer_id', $this->customer_id)->whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
            } else {
                $q = Ekatalog::whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
            }
            return view('page.penjualan.laporan.ekatalog', [
                'ekatalog' => $q
            ]);
        } else if ($this->jenis_penjualan == 'spa') {
            if ($this->customer_id != null) {
                $q = Spa::where('customer_id', $this->customer_id)->whereBetween('created_at', [$this->tgl_mulai . ' 00:00:00', $this->tgl_selesai . ' 23:59:59'])->get();
            } else {
                $q = Spa::whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
            }
            return view('page.penjualan.laporan.spa', [
                'spa' => $q
            ]);
        } else if ($this->jenis_penjualan == 'spb') {
            if ($this->customer_id != null) {
                $q = Spb::where('customer_id', $this->customer_id)->whereBetween('created_at', [$this->tgl_mulai . ' 00:00:00', $this->tgl_selesai . ' 23:59:59'])->get();
            } else {
                $q = Spb::whereBetween('tgl_buat', [$this->tgl_mulai, $this->tgl_selesai])->get();
            }
            return view('page.penjualan.laporan.spb', [
                'spb' => $q
            ]);
        }
    }
}
