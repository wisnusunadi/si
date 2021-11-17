<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class DcController extends Controller
{
    public function pdf_coo()
    {
        $pdf = PDF::loadView('page.dc.coo.pdf')->setPaper('A4');
        return $pdf->stream('');
    }
}
