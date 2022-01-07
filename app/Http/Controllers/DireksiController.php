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
        $gudang = 0;
        $qc = 0;
        $log = 0;
        $dc = 0;
        $pes = Pesanan::whereNotIn('log_id', ['7', '10'])->get();
        foreach ($pes as $i) {
            if (isset($i->DetailPesanan)) {
                if ($i->getJumlahSeri() < $i->getJumlahPesanan()) {
                    $gudang = $gudang + 1;
                }
            }

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
        return view('page.direksi.dashboard', ['penj' => $penj, 'gudang' => $gudang, 'qc' => $qc, 'log' => $log, 'dc' => $dc]);
    }
}
