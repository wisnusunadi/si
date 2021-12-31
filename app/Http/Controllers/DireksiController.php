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
        $penj = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNull('so');
        })->where('status', 'sepakat')->count();

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

        $qc = Pesanan::has('TFProduksi')->doesntHave('DetailPesanan.DetailPesananProduk.NoSeriDetailPesanan')->count();

        $log = Pesanan::has('DetailPesanan.DetailPesananProduk.NoSeriDetailPesanan')->doesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik')->count();

        $dc = Pesanan::has('DetailPesanan.DetailPesananProduk.DetailLogistik')->doesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik.NoseriDetailLogistik.NoseriCoo')->count();

        return view('page.direksi.dashboard', ['penj' => $penj, 'gudang' => $gudang, 'qc' => $qc, 'log' => $log, 'dc' => $dc]);
    }
}
