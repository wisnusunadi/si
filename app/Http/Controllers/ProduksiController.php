<?php

namespace App\Http\Controllers;

use App\Models\DetailEkatalog;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\NoseriBarangJadi;
use App\Models\Pesanan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\TFProduksiHis;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduksiController extends Controller
{
    function CreateTFItem(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'produk_id' => 'required',
                // 'nama' => 'required',
                // 'stok' => 'required|numeric',
                // 'ke' => 'required',
            ],
            [
                // 'produk_id.required' => 'Produk harus diisi',
                // 'nama.required' => 'Nama harus diisi',
                // 'stok.numeric' => 'Stok harus diisi angka',
                // 'stok.required' => 'Stok harus diisi',
                // 'ke.required' => 'Tujuan harus diisi',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            foreach ($request->ke as $key => $value) {
                $tf_prod = new TFProduksi();
                $tf_prod->ke = $value;
                $tf_prod->deskripsi = $request->deskripsi[$key];
                $tf_prod->created_at = Carbon::now();
                $tf_prod->save();

                $tf_prod_det = new TFProduksiDetail();
                $tf_prod_det->tfbj_id = $tf_prod->id;
                $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id[$key];
                $tf_prod_det->qty = $request->qty[$key];
                $tf_prod_det->created_at = Carbon::now();
                $tf_prod_det->save();

                $tf_prod_his = new TFProduksiHis();
                $tf_prod_his->tfbj_id = $tf_prod->id;
                $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id[$key];
                $tf_prod_his->qty = $request->qty[$key];
                // $tf_prod_his->noseri = $request->noseri[$key];
                $tf_prod_his->created_at = Carbon::now();
                $tf_prod_his->save();

                // $gdg = GudangBarangJadi::where('id', $request->gdg_brg_jadi_id)->get();
                // $gdg->stok = $gdg->stok - $request->qty;
                // $gdg->save();
            }

            return response()->json(['msg' => 'Successfully']);
        }
    }

    // get
    function getNoseri(Request $request, $id) {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id',$id)->get();
        return datatables()->of($data)
            ->addColumn('checkbox', function($data) {
                return '<input type="checkbox" name="" id="">';
            })
            ->addColumn('noseri', function($data) {
                return $data->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function getOutSO() {
        $Ekatalog = collect(Ekatalog::with('Pesanan')->get());
        $Spa = collect(Spa::with('Pesanan')->get());
        $Spb = collect(Spb::with('Pesanan')->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($data) {
                $name =  $data->getTable();
                if ($name == 'ekatalog') {
                    return 'E-Catalogue';
                } else if ($name == 'spa') {
                    return 'SPA';
                } else {
                    return 'SPB';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('no_paket', function ($data) {
                if (isset($data->no_paket)) {
                    return $data->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_order', function ($data) {
                if (isset($data->tgl_buat)) {
                    return $data->tgl_buat;
                } else {
                    return $data->tgl_po;;
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                if (isset($data->tgl_kontrak)) {
                    return $data->tgl_kontrak;
                } else {
                    return '';
                }
            })
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->so;
                } else {
                    return '';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-danger">Produk belum disiapkan</span>';
            })
            ->addColumn('button', function ($data) {
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="btn btn-primary dropdown-item" type="button">
                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                            </button>
                        </a>
                        </div>';
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }

    function getDetailSO(Request $request, $id) {
        $data = DetailEkatalog::with('Ekatalog', 'PenjualanProduk')->where('ekatalog_id', $id)->get();
        return datatables()->of($data)
                ->addColumn('produk', function ($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('qty', function($data) {
                    return $data->jumlah;
                })
                ->addColumn('tipe', function($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('merk', function($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('action', function($data) {
                    return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                            class="fas fa-qrcode"></i> Scan Produk</button>
                            </a>';
                })
                ->rawColumns(['action'])
            ->make(true);
    }

    function headerSo($id) {
        $data = Pesanan::with('Ekatalog')->find($id);
        return $data;
    }

    // check
    function checkStok(Request $request) {
        $gdg = GudangBarangJadi::where('id',$request->gdg_brg_jadi_id)->first();
        return $gdg;
    }
}
