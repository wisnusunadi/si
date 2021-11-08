<?php

namespace App\Http\Controllers;

use App\Models\GudangBarangJadi;
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
        $tf_prod = new TFProduksi();
        $tf_prod_det = new TFProduksiDetail();
        $tf_prod_his = new TFProduksiHis();
        try {
            $tf_prod->ke = $request->ke;
            $tf_prod->deskripsi = $request->deskripsi;
            $tf_prod->created_at = Carbon::now();
            $tf_prod->save();

            $tf_prod_det->tfbj_id = $tf_prod->id;
            $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
            $tf_prod_det->qty = 1;
            $tf_prod_det->noseri = $request->noseri;
            $tf_prod_det->created_at = Carbon::now();
            $tf_prod_det->save();

            $tf_prod_his->tfbj_id = $tf_prod->id;
            $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
            $tf_prod_his->qty = 1;
            $tf_prod_his->noseri = $request->noseri;
            $tf_prod_his->created_at = Carbon::now();
            $tf_prod_his->save();

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            if (empty($tf_prod->ke)) {
                return response()->json(['msg' => 'Data Tujuan Harus Di isi.']);
            }
            if (empty($tf_prod_det->gdg_brg_jadi_id)) {
                return response()->json(['msg' => 'Data Barang Harus Di isi.']);
            }
        }
    }

    function Tf_so(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                // 'produk_id' => 'required',
                'ke' => 'required',
                // 'stok' => 'required|numeric',
                // 'ke' => 'required',
            ],
            [
                // 'produk_id.required' => 'Produk harus diisi',
                'ke.required' => 'Tujuan harus diisi',
                // 'stok.numeric' => 'Stok harus diisi angka',
                // 'stok.required' => 'Stok harus diisi',
                // 'ke.required' => 'Tujuan harus diisi',
            ]
        );

        if($validator->fails()) {
            return response()->json(['error' =>  $validator->errors()]);
        } else {
            $tf_prod = new TFProduksi();
            $tf_prod->ke = $request->ke;
            $tf_prod->deskripsi = $request->deskripsi;
            $tf_prod->created_at = Carbon::now();
            $tf_prod->save();

            $tf_prod_det = new TFProduksiDetail();
            $tf_prod_det->tfbj_id = $tf_prod->id;
            $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
            $tf_prod_det->qty = $request->qty;
            // $tf_prod_det->noseri = $request->noseri;
            $tf_prod_det->created_at = Carbon::now();
            $tf_prod_det->save();

            $tf_prod_his = new TFProduksiHis();
            $tf_prod_his->tfbj_id = $tf_prod->id;
            $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
            $tf_prod_his->qty = $request->qty;
            // $tf_prod_his->noseri = $request->noseri;
            $tf_prod_his->created_at = Carbon::now();
            $tf_prod_his->save();

            return response()->json(['msg' => 'Successfully']);
        }
    }

    function TFNonSO(Request $request) {

        $tf_prod = new TFProduksi();
        $tf_prod_det = new TFProduksiDetail();
        $tf_prod_his = new TFProduksiHis();
        try {
            $tf_prod->ke = $request->ke;
            $tf_prod->deskripsi = $request->deskripsi;
            $tf_prod->created_at = Carbon::now();
            $tf_prod->save();

            $tf_prod_det->tfbj_id = $tf_prod->id;
            $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id ? $request->gdg_brg_jadi_id : $request->gdg_brg_jadi_id;
            $tf_prod_det->qty = $request->qty;
            $tf_prod_det->noseri = $request->noseri;
            $tf_prod_det->created_at = Carbon::now();
            $tf_prod_det->save();

            $tf_prod_his->tfbj_id = $tf_prod->id;
            $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id ? $request->gdg_brg_jadi_id : $request->gdg_brg_jadi_id;
            $tf_prod_his->qty = $request->qty;
            $tf_prod_his->noseri = $request->noseri;
            $tf_prod_his->created_at = Carbon::now();
            $tf_prod_his->save();

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            if (empty($tf_prod->ke)) {
                return response()->json(['msg' => 'Data Tujuan Harus Di isi.']);
            }
            if (empty($tf_prod_det->gdg_brg_jadi_id)) {
                return response()->json(['msg' => 'Data Barang Harus Di isi.']);
            }
        }
    }

    function getTFBJ() {
        $data = TFProduksi::with('divisi', 'detail_tf')->get();
        // $data = TFProduksiDetail::with('produk')->get();
        return $data;
    }


    // data perakitan
    function get_produksi() {
        $data = TFProduksiDetail::with('produk')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->produk->nama;
            })
            ->addColumn('stok', function ($data) {
                return $data->qty .' '.$data->produk->Satuan->nama;
            })
            ->addColumn('tgl_masuk', function ($data) {
                return date_format($data->created_at, 'd F Y');
            })
            ->addColumn('action', function ($data) {
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-edit"></i>&nbsp;Terima
                            </button>
                        </a>

                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function detail_dalam_perakitan($id) {
        // $data = TFProduksiDetail::where('tfbj_id')

        $html = '<div class="modal-header">
                    <h5 class="modal-title"><b>Detail Produk AMBULATORY BLOOD PRESSURE MONITOR</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No Seri</th>
                                <th>Layout</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>654165654</td>
                                <td>Layout 1</td>
                            </tr>
                            <tr>
                            <td>654165654</td>
                            <td>Layout 1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>';

        return response()->json($html);
    }

    function get_selain_produksi() {

    }
}
