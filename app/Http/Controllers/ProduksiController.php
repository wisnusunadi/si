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
                'nama' => 'required',
                // 'stok' => 'required|numeric',
                // 'ke' => 'required',
            ],
            [
                // 'produk_id.required' => 'Produk harus diisi',
                'nama.required' => 'Nama harus diisi',
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
            $tf_prod->ket = $request->ket;
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
            $tf_prod->ket = $request->ket;
            $tf_prod->created_at = Carbon::now();
            $tf_prod->save();

            $tf_prod_det->tfbj_id = $tf_prod->id;
            $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
            $tf_prod_det->qty = $request->qty;
            // if($tf_prod_det->is_aktif == 1) {
            //     $gbj->stok = $gbj->stok - $request->qty;
            // }
            $tf_prod_det->noseri = $request->noseri;
            $tf_prod_det->created_at = Carbon::now();
            $tf_prod_det->save();

            $tf_prod_his->tfbj_id = $tf_prod->id;
            $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
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
}
