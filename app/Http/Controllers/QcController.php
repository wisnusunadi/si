<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
