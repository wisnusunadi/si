<?php

namespace App\Http\Controllers;

use App\Models\DetailEkatalog;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\NoseriBarangJadi;
use App\Models\PenjualanProduk;
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
                $tf_prod->tgl_keluar = Carbon::now();
                $tf_prod->ke = $value;
                $tf_prod->deskripsi = $request->deskripsi[$key];
                $tf_prod->jenis = 'keluar';
                $tf_prod->created_at = Carbon::now();
                $tf_prod->save();

                $tf_prod_det = new TFProduksiDetail();
                $tf_prod_det->t_gbj_id = $tf_prod->id;
                $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id[$key];
                $tf_prod_det->qty = $request->qty[$key];
                $tf_prod_det->jenis = 'keluar';
                $tf_prod_det->created_at = Carbon::now();
                $tf_prod_det->save();

                // $tf_prod_his = new TFProduksiHis();
                // $tf_prod_his->tfbj_id = $tf_prod->id;
                // $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id[$key];
                // $tf_prod_his->qty = $request->qty[$key];
                // // $tf_prod_his->noseri = $request->noseri[$key];
                // $tf_prod_his->created_at = Carbon::now();
                // $tf_prod_his->save();
            }

            $gdg = GudangBarangJadi::whereIn('id', $request->gdg_brg_jadi_id)->get()->toArray();
            $i = 0;
            foreach ($gdg as $vv) {
                $i++;
                $vv['stok'] = $vv['stok'] - $request->qty[$i];
                GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
            }

            return response()->json(['msg' => 'Successfully',]);
        }
    }

    function TfbySO(Request $request)
    {
        $data = TFProduksi::where('pesanan_id', $request->pesanan_id)->get();
        if (count($data) > 0) {
            // foreach($data as $v) {
            //     $v->pesanan_id = $request->pesanan_id;
            //     $v->tgl_keluar = Carbon::now();
            //     $v->ke = 23;
            //     $v->jenis = 'keluar';
            //     $v->status_id = 1;
            //     $v->created_at = Carbon::now();
            //     $v->save();
            // }

            return response()->json(['msg' => 'Data Sudah Ada']);
        } else {
            $d = new TFProduksi();
            $d->pesanan_id = $request->pesanan_id;
            $d->tgl_keluar = Carbon::now();
            $d->ke = 23;
            $d->jenis = 'keluar';
            $d->status_id = 1;
            $d->state_id = 2;
            $d->created_at = Carbon::now();
            $d->save();

            foreach ($request->gdg_brg_jadi_id as $key => $value) {
                $dd = new TFProduksiDetail();
                $dd->t_gbj_id = $d->id;
                $dd->gdg_brg_jadi_id = $value;
                $dd->qty = $request->qty[$key];
                $dd->jenis = 'keluar';
                $dd->status_id = 1;
                $dd->state_id = 2;
                $dd->created_at = Carbon::now();
                $dd->save();
            }

            return response()->json(['msg' => 'Data Tersimpan ke Rancangan']);
        }
    }

    // get
    function getNoseri(Request $request, $id)
    {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $id)->get();
        return datatables()->of($data)
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" name="" id="">';
            })
            ->addColumn('noseri', function ($data) {
                return $data->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function getOutSO()
    {
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
                    return date('d-m-Y', strtotime($data->tgl_kontrak));
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
            ->addColumn('status1', function ($data) {
                return '<span class="badge badge-danger">Belum Dicek</span>';
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
            ->addColumn('action', function ($data) {
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                            </button>
                        </a>

                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button">
                                <i class="far fa-eye"></i>&nbsp;View
                            </button>
                        </a>
                        </div>';
            })
            ->rawColumns(['button', 'status', 'action', 'status1'])
            ->make(true);
    }

    function getDetailSO(Request $request, $id)
    {
        $data = DetailEkatalog::where('ekatalog_id', $id)->with('GudangBarangJadi', 'GudangBarangJadi.Produk')->get();
        $l = [];
        $v = 0;
        $i = 0;
        foreach ($data as $s) {
            foreach ($s->GudangBarangJadi as $k) {
                $l[$v]['id'] = $k->pivot->gudang_barang_jadi_id;
                $l[$v]['nama_produk'] = $k->produk->nama;
                $l[$v]['merk'] = $k->produk->merk;
                $l[$v]['jumlah'] = $k->pivot->jumlah;
                $v++;
            }
        }
        // $i++;
        return datatables()->of($l)
            ->addColumn('produk', function ($data) {

                return $data['nama_produk'].'<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id[]" value="'.$data['id'].'">';
            })
            ->addColumn('ids', function ($d) {
                return $d['id'];
            })
            ->addColumn('qty', function ($data) {
                return $data['jumlah'].'<input type="hidden" class="jumlah" name="qty[]" id="qty" value="'.$data['jumlah'].'">';
            })
            ->addColumn('merk', function ($data) {
                return $data['merk'];
            })
            ->addColumn('tipe', function ($data) {
                return $data['merk'];
            })
            ->addColumn('action', function ($data) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data['id'] . '">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                            class="fas fa-qrcode"></i> Scan Produk</button>
                            </a>';
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-danger">Belum Diinput</span>';
            })
            ->rawColumns(['action', 'status', 'produk', 'qty'])
            ->make(true);
        // return $data;
    }

    function headerSo($id)
    {
        $data = Pesanan::with('Ekatalog')->find($id);
        return $data;
    }

    function getHistorybyProduk()
    {
        $data = GudangBarangJadi::with('produk', 'satuan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('stock', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('kelompok', function ($d) {
                return $d->produk->kelompokproduk->nama;
            })
            ->addColumn('product', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('kode_produk', function ($d) {
                return $d->produk->product->kode . '' . $d->produk->kode;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                            <button class="btn btn-info" data-toggle="modal" data-target=".modal-detail"><i
                            class="far fa-eye"></i> Detail</button>
                            </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getNoseriSO(Request $request) {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $request->gdg_barang_jadi_id)->get();
        return datatables()->of($data)
            ->addColumn('seri', function($d) {
                return $d->noseri;
            })
            ->addColumn('checkbox', function($d) {
                return $d->id;
            })
            ->make(true);
    }

    // check
    function checkStok(Request $request)
    {
        $gdg = GudangBarangJadi::where('id', $request->gdg_brg_jadi_id)->first();
        return $gdg;
    }

    function test()
    {
        $data = TFProduksi::where('pesanan_id', 2)->get();
        if (count($data) > 0) {
            return 'Data Sudah ada';
        } else {
            return 'Data belum ada';
        }
        // return $data;
    }
}
