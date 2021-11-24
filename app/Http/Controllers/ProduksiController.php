<?php

namespace App\Http\Controllers;

use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\TFProduksiHis;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
