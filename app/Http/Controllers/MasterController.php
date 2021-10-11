<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualanProduk;
use App\Models\PenjualanProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterController extends Controller
{
    //Get Data Table
    public function get_data_produk()
    {
        return datatables()->of(Produk::with('KelompokProduk'))->toJson();
    }
    public function get_data_penjualan_produk()
    {
        return datatables()->of(PenjualanProduk::select())->toJson();
    }
    public function get_data_detail_penjualan_produk($id)
    {
        return datatables()->of(DetailPenjualanProduk::with('Produk', 'PenjualanProduk')
            ->where('penjualan_produk_id', $id))->toJson();
    }

    //Create
    public function create_produk(Request $request)
    {
        $this->validate(
            $request,
            [
                'kelompok_produk_id' => 'required',
                'merk' => 'required',
                'tipe' => 'required',

            ],
            [
                'kelompok_produk_id.required' => 'Kelompok Produk harus di isi',
                'merk.required' => 'Merk Produk harus di isi',
                'tipe.required' => 'Tipe Produk harus di isi',
            ]
        );
        Produk::create([
            'kelompok_produk_id' => $request->kelompok_produk_id,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'nama' => $request->nama,
            'nama_coo' => $request->nama_coo,
            'satuan' => $request->satuan,
            'no_akd' => $request->no_akd,
            'ket' => $request->ket,
            'status' => $request->kelompok_produk_id
        ]);
    }

    public function create_penjualan_produk(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama' => 'required',
                'harga' => 'required',
                'produk_id.*' => 'required',
                'jumlah.*' => 'required'
            ],
            [
                'nama.required' => 'Nama Produk harus di isi',
                'harga.required' => 'Harga Produk harus di isi',
                'produk_id.required' => 'Produk harus di pilih',
                'jumlah.required' => 'Jumlah Produk harus di isi',
            ]
        );
        $PenjualanProduk = PenjualanProduk::create([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);


        for ($i = 0; $i < count($request->produk_id); $i++) {
            DetailPenjualanProduk::create([
                'produk_id' => $request->produk_id[$i],
                'penjualan_produk_id' => $PenjualanProduk->id,
                'jumlah' => $request->jumlah[$i],
            ]);
        }
    }
    //Update

    //Delete
}
