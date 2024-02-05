<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;

class TeknikController extends Controller
{

    public function bom_data_produk($id)
    {
        return view('page.teknik.bom.data.produk', ['id' => $id]);
    }
    public function cetak_certificate()
    {
        $pdf = PDF::loadView('page.lab.sertifikat.index')->setOptions(['defaultFont' => 'sans-serif'])->setPaper('A4', 'potrait');
        return $pdf->stream('');
    }
    public function cetak_certificate_with_ttd()
    {
        // pdf with image
        $pdf = PDF::loadView('page.lab.sertifikat.ttd')->setOptions(['defaultFont' => 'sans-serif'])->setPaper('A4', 'potrait');
        return $pdf->stream('');
    }
}
