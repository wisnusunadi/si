<?php

namespace App\Http\Controllers;

use App\Models\GudangBarangJadi;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function get_data_barang_jadi()
    {
        return datatables()->of(GudangBarangJadi::with('Produk'))->toJson();
    }
}
