<?php

namespace App\Http\Controllers;

use App\Exports\LaporanQcOutgoing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class QcController extends Controller
{
    public function update_modal_so()
    {
        return view('page.qc.so.edit');
    }

    public function detail_modal_riwayat_so()
    {
        return view('page.qc.so.riwayat.detail');
    }

    //Laporan
    public function laporan_outgoing(Request $request)
    {
        return Excel::download(new LaporanQcOutgoing($request->produk_id ?? '', $request->no_so ?? '', $request->hasil_uji  ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_qc_outgoing.xlsx');
    }
}
