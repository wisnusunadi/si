<?php

namespace App\Http\Controllers;

use App\Models\Ekatalog;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DireksiController extends Controller
{
    public function dashboard()
    {
        $penj = Pesanan::whereNull('so')->where('log_id', '7')->count();

        $gudangekat = Pesanan::doesntHave('TFProduksi')->whereHas('Ekatalog', function ($q) {
            $q->where('log', 'po');
        })->count();

        $gudangspa = Pesanan::doesntHave('TFProduksi')->whereHas('Spa', function ($q) {
            $q->where('log', 'po');
        })->orWhereHas('Spb', function ($q) {
            $q->where('log', 'po');
        })->count();

        $gudangspb = Pesanan::doesntHave('TFProduksi')->whereHas('Spb', function ($q) {
            $q->where('log', 'po');
        })->count();

        $gudang = $gudangekat + $gudangspa + $gudangspb;
        $qc = 0;
        $log = 0;
        $dc = 0;
        $pes = Pesanan::select()->get();
        foreach ($pes as $i) {
            if (isset($i->DetailPesanan)) {
                if ($i->getJumlahCek() < $i->getJumlahPesanan()) {
                    $qc = $qc + 1;
                }
            }

            if (isset($i->DetailPesanan)) {
                if ($i->getJumlahKirim() < $i->getJumlahPesanan()) {
                    $log = $log + 1;
                }
            }

            if (isset($i->DetailPesananPart)) {
                if ($i->getJumlahKirimPart() < $i->getJumlahPesananPart()) {
                    $log = $log + 1;
                }
            }

            if (isset($i->DetailPesananPart)) {
                if ($i->getJumlahCoo() < $i->getJumlahPesanan()) {
                    $dc = $dc + 1;
                }
            }
        }

        // $qc = Pesanan::has('TFProduksi')->doesntHave('DetailPesanan.DetailPesananProduk.NoSeriDetailPesanan')->count();

        // $log = Pesanan::has('DetailPesanan.DetailPesananProduk.NoSeriDetailPesanan')->doesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik')->count();

        // $dc = Pesanan::has('DetailPesanan.DetailPesananProduk.DetailLogistik')->doesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik.NoseriDetailLogistik.NoseriCoo')->count();


        return view('page.direksi.dashboard', ['penj' => $penj, 'gudang' => $gudang, 'qc' => $qc, 'log' => $log, 'dc' => $dc]);
    }
}
