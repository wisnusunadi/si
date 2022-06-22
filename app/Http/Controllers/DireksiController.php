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
        $pes = Pesanan::whereNotIn('log_id', ['7'])->get();
        foreach ($pes as $i) {
            if (isset($i->DetailPesanan) && !isset($i->DetailPesananPart)) {
                if ($i->getJumlahSeri() < $i->getJumlahPesanan()) {
                    $gudang = $gudang + 1;
                }

                if ($i->getJumlahCek() < $i->getJumlahSeri()) {
                    $qc = $qc + 1;
                }

                if ($i->getJumlahKirim() < $i->getJumlahCek()) {
                    $log = $log + 1;
                }

                if ($i->getJumlahCoo() < $i->getJumlahSeri()) {
                    $dc = $dc + 1;
                }
            } else if(!isset($i->DetailPesanan) && isset($i->DetailPesananPart)){
                if ($i->getJumlahCekPart("ok") < $i->getJumlahPesananPartNonJasa()) {
                    $qc = $qc + 1;
                }

                if ($i->getJumlahKirimPart() < $i->getJumlahCekPart("ok")) {
                    $log = $log + 1;
                }
            } else if(isset($i->DetailPesanan) && isset($i->DetailPesananPart)){
                if ($i->getJumlahSeri() < $i->getJumlahPesanan()) {
                    $gudang = $gudang + 1;
                }

                if ($i->getJumlahCek() < $i->getJumlahSeri() || $i->getJumlahCekPart("ok") < $i->getJumlahPesananPartNonJasa()) {
                    $qc = $qc + 1;
                }

                if ($i->getJumlahKirim() < $i->getJumlahCek() || $i->getJumlahKirimPart() < $i->getJumlahCekPart("ok")) {
                    $log = $log + 1;
                }

                if ($i->getJumlahCoo() < $i->getJumlahSeri()) {
                    $dc = $dc + 1;
                }
            }
        }
        return view('page.direksi.dashboard', ['penj' => $penj, 'gudang' => $gudang, 'qc' => $qc, 'log' => $log, 'dc' => $dc]);
    }
}
