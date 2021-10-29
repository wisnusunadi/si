<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailSpa;
use App\Models\DetailSpb;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class PenjualanController extends Controller
{
    //Get Data Table
    public function get_data_detail_ekatalog($value)
    {
        $data  = DetailEkatalog::with('Penjualan_produk', 'Ekatalog')
            ->where('ekatalog', $value)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    public function get_data_detail_spa($value)
    {
        $data  = DetailSpa::with('Penjualan_produk', 'Spa')
            ->where('spa_id', $value)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    public function get_data_detail_spb($value)
    {
        $data  = DetailSpa::with('Penjualan_produk', 'Spb')
            ->where('spb_id', $value)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    public function get_data_ekatalog_pengiriman()
    {
        $data  = Ekatalog::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('batas_kontrak', function ($data) {
                $x = 'sd';

                return  ' <hgroup>' . $data->tgl_kontrak .  '<small id="warning">' . $x . '</small> </hgroup>';
            })
            ->rawColumns(['batas_kontrak',])
            ->make(true);
    }
    public function get_data_ekatalog()
    {
        $data  = Ekatalog::with('pesanan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                if ($data->status == "sepakat") {
                    $status = '<span class="green-text badge">Sepakat</span>';
                } else if ($data->status == "negosiasi") {
                    $status =  '<span class="yellow-text badge">Negosiasi</span>';
                } else {
                    $status =  '<span class="red-text badge">Batal</span>';
                }

                return $status;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="">
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Detail
                    </button>
                </a>
                <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">                         
                    <button class="dropdown-item" type="button" >
                      <i class="fas fa-pencil-alt"></i>
                      Edit
                    </button>
                </a>
                </div>';
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_filter_data_ekatalog($value)
    {
        $x = explode(',', $value);
        $y = ['sepakat', 'negosisasi', 'batal'];
        $data  = Ekatalog::with('pesanan')->whereIN('status', $x)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                if ($data->status == "sepakat") {
                    $status = '<span class="green-text badge">Sepakat</span>';
                } else if ($data->status == "negosiasi") {
                    $status =  '<span class="yellow-text badge">Negosiasi</span>';
                } else {
                    $status =  '<span class="red-text badge">Batal</span>';
                }

                return $status;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="">
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Detail
                    </button>
                </a>
                <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">                         
                    <button class="dropdown-item" type="button" >
                      <i class="fas fa-pencil-alt"></i>
                      Edit
                    </button>
                </a>
                </div>';
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_data_spa()
    {
        $data  = Spa::select();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    public function get_data_spb()
    {
        $data  = Spb::select();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }


    //Create


    public function create_penjualan(Request $request)
    {
        if ($request->jenis_penjualan == 'ekatalog') {
            // $this->validate(
            //     $request,
            //     [
            //         'no_paket' => 'required',
            //         'customer_id' => 'required',
            //         'status' => 'required',
            //         'tgl_kontrak' => 'required',
            //         'jumlah.*' => 'required',
            //         'penjualan_produk_id.*' => 'required'
            //     ],
            //     [
            //         'no_paket.required' => 'No Paket harus di isi',
            //         'customer_id.required' => 'Customer harus di isi',
            //         'status.required' => 'Status harus di pilih',
            //         'tgl_kontrak.required' => 'Tg; Kontrak harus di isi',
            //         'jumlah.required' => 'Jumlah Produk harus di isi',
            //         'penjualan_produk_id.required' => 'Produk harus di pilih',
            //     ]
            // );


            //Konversi No SO
            // $x = Ekatalog::max('id') + 1;
            // $y = Carbon::now()->format('Y');
            // $m = Carbon::now()->format('m');
            // $filter = new IntToRoman();

            $Ekatalog = Ekatalog::create([
                'customer_id' => $request->customer_id,
                'no_paket' => 'AK1-' . $request->no_paket,
                'deskripsi' => $request->deskripsi,
                'instansi' => $request->instansi,
                'satuan' => $request->satuan_kerja,
                'status' => $request->status,
                'tgl_kontrak' => $request->batas_kontrak,
                'tgl_buat' => $request->tanggal_pemesanan,
                'ket' => $request->keterangan
            ]);
            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                DetailEkatalog::create([
                    'ekatalog_id' => $Ekatalog->id,
                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                    'jumlah' => $request->produk_jumlah[$i],
                    'harga' => $request->produk_harga[$i],
                    'ongkir' => 0,
                ]);
            }
        } elseif ($request->jenis_penjualan == 'spa') {
        } else {
        }
    }
    public function create_ekatalog(Request $request)
    {
        $this->validate(
            $request,
            [
                'no_paket' => 'required',
                'customer_id' => 'required',
                'status' => 'required',
                'tgl_kontrak' => 'required',
                'jumlah.*' => 'required',
                'penjualan_produk_id.*' => 'required'
            ],
            [
                'no_paket.required' => 'No Paket harus di isi',
                'customer_id.required' => 'Customer harus di isi',
                'status.required' => 'Status harus di pilih',
                'tgl_kontrak.required' => 'Tg; Kontrak harus di isi',
                'jumlah.required' => 'Jumlah Produk harus di isi',
                'penjualan_produk_id.required' => 'Produk harus di pilih',
            ]
        );
        $Ekatalog = Ekatalog::create([
            'customer_id' => $request->customer_id,
            'no_paket' => 'AK1-' . $request->no_paket,
            'deskripsi' => $request->deskripsi,
            'instansi' => $request->instansi,
            'satuan' => $request->satuan,
            'status' => $request->status,
            'tgl_kontrak' => $request->tgl_kontrak,
            'tgl_buat' => $request->tgl_buat,
            'ket' => $request->ket
        ]);
        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
            DetailEkatalog::create([
                'ekatalog_id' => $Ekatalog->id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i],
                'ongkir' => $request->ongkir[$i],
            ]);
        }
    }
    public function create_spa(Request $request)
    {
        $this->validate(
            $request,
            [
                'customer_id' => 'required',
                'status' => 'required',
                'jumlah.*' => 'required',
                'penjualan_produk_id.*' => 'required'
            ],
            [
                'customer_id.required' => 'Customer harus di isi',
                'status.required' => 'Status harus di pilih',
                'jumlah.required' => 'Jumlah Produk harus di isi',
                'penjualan_produk_id.required' => 'Produk harus di pilih',
            ]
        );
        $Spa = Spa::create([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
            'ket' => $request->ket
        ]);
        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
            DetailSpa::create([
                'spa_id' => $Spa->id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i],
                'ongkir' => $request->ongkir[$i],
            ]);
        }
    }
    public function create_spb(Request $request)
    {
        $this->validate(
            $request,
            [
                'customer_id' => 'required',
                'status' => 'required',
                'jumlah.*' => 'required',
                'penjualan_produk_id.*' => 'required'
            ],
            [
                'customer_id.required' => 'Customer harus di isi',
                'status.required' => 'Status harus di pilih',
                'jumlah.required' => 'Jumlah Produk harus di isi',
                'penjualan_produk_id.required' => 'Produk harus di pilih',
            ]
        );
        $Spb = Spb::create([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
            'ket' => $request->ket
        ]);
        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
            DetailSpb::create([
                'spb_id' => $Spb->id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i],
                'ongkir' => $request->ongkir[$i],
            ]);
        }
    }
    //Update
    // public function update_ekatalog(Request $request)
    // {
    //     $id = $request->id;
    //     $produk = Ekatalog::find($id);
    //     $produk->save();
    // }

    //Delete
    public function delete_detail_ekatalog($id)
    {
        $detail_ekatalog = DetailEkatalog::findOrFail($id);
        $detail_ekatalog->delete();
    }
    public function delete_detail_spa($id)
    {
        $detail_spa = DetailSpa::findOrFail($id);
        $detail_spa->delete();
    }
    public function delete_detail_spb($id)
    {
        $detail_spb = DetailSpb::findOrFail($id);
        $detail_spb->delete();
    }
    public function delete_ekatalog($id)
    {
        $ekatalog = Ekatalog::findOrFail($id);
        $ekatalog->delete();
    }
    public function delete_spa($id)
    {
        $ekatalog = Spa::findOrFail($id);
        $ekatalog->delete();
    }
    public function delete_spb($id)
    {
        $ekatalog = Spb::findOrFail($id);
        $ekatalog->delete();
    }
}
