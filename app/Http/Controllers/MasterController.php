<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
    public function get_data_customer()
    {
        $data = Customer::select();

        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    public function get_data_penjualan_produk()
    {
        $data = PenjualanProduk::select();

        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
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
                'tipe' => 'required|unique:produk',

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
    public function create_customer(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama' => 'required|unique:customer',

            ],
            [
                'nama.required|unique:customer' => 'Nama Customer harus di isi',
            ]
        );
        Customer::create([
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'npwp' => '43443',
            'ket' => $request->ket,
        ]);
    }
    public function create_penjualan_produk(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama' => 'required|unique:penjualan_produk',
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
    public function update_customer(Request $request)
    {
        $id = $request->id;
        $produk = Customer::find($id);
        $produk->jenis = $request->jenis;
        $produk->nama = $request->nama;
        $produk->telp = $request->telp;
        $produk->alamat = $request->alamat;
        $produk->ket = $request->ket;
        $produk->save();
    }

    public function update_produk(Request $request)
    {
        $id = $request->id;
        $produk = Produk::find($id);
        $produk->kelompok_produk_id = $request->kelompok_produk_id;
        $produk->merk = $request->merk;
        $produk->tipe = $request->tipe;
        $produk->nama = $request->nama;
        $produk->nama_coo = $request->nama_coo;
        $produk->satuan = $request->satuan;
        $produk->no_akd = $request->no_akd;
        $produk->ket = $request->ket;
        $produk->status = $request->status;
        $produk->save();
    }

    //Delete
    public function delete_customer($id)
    {
        $produk = Customer::findOrFail($id);
        $produk->delete();
    }
    public function delete_produk($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
    }
    public function delete_penjualan_produk($id)
    {
        $produk = PenjualanProduk::findOrFail($id);
        $produk->delete();
    }
    public function delete_detail_penjualan_produk($id)
    {
        $produk = DetailPenjualanProduk::findOrFail($id);
        $produk->delete();
    }
    //Other

    //Check
    public function check_produk($value)
    {
        $data = Produk::where('tipe', $value)->get();
        echo json_encode($data);
    }
    public function check_customer($value)
    {
        $data = Customer::where('nama', $value)->get();
        echo json_encode($data);
    }
    public function check_penjualan_produk($value)
    {
        $data = PenjualanProduk::where('nama', $value)->get();
        echo json_encode($data);
    }
}
