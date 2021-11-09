<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class LogistikController extends Controller
{
    public function pdf_surat_jalan()
    {
        $pdf = PDF::loadView('page.logistik.pengiriman.print_sj')->setPaper('A4', 'landscape');
        return $pdf->stream('');
    }

    public function update_modal_surat_jalan($id, $status)
    {
        return view('page.logistik.pengiriman.edit', ['id' => $id, 'status' => $status]);
    }
}
