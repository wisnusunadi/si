<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeknikController extends Controller
{
    public function bom_detail($id)
    {
        return view('page.teknik.bom.detail', ['id' => $id]);
    }

    public function bom_data_produk($id)
    {
        return view('page.teknik.bom.data.produk', ['id' => $id]);
    }
}
