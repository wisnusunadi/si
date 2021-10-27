<?php

namespace App\Http\Controllers;

use App\Models\GudangBarangJadi;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk')->select();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kelompok', function ($data) {
                return $data->produk->kelompokproduk->nama;
            })
            ->addColumn('merk', function ($data) {
                return $data->produk->merk;
            })
            ->addColumn('satuan', function ($data) {
                return $data->produk->satuan;
            })
            ->addColumn('nama', function ($data) {
                if ($data->variasi != '') {
                    return $data->produk->tipe . ' - <b>' . $data->variasi . '</b>';
                } else {
                    return $data->produk->tipe;
                }
            })
            ->rawColumns(['nama'])
            ->make(true);

        //return datatables()->of(GudangBarangJadi::select())->toJson();
    }
}
