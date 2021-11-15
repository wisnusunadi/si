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
            // insert one
            $gdg = GudangBarangJadi::find($request->gdg_brg_jadi_id);
            if ($gdg->stok < $request->qty) {
                return response()->json(['msg' => 'Stok Tidak Mencukupi']);
            } else {
                // insert one
                // $gdg->stok = $gdg->stok - $request->qty;
                // $gdg->save();

                $tf_prod = new TFProduksi();
                $tf_prod->ke = $request->ke;
                $tf_prod->deskripsi = $request->deskripsi;
                $tf_prod->created_at = Carbon::now();
                $tf_prod->save();

                $tf_prod_det = new TFProduksiDetail();
                $tf_prod_det->tfbj_id = $tf_prod->id;
                $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
                $tf_prod_det->qty = $request->qty;
                $tf_prod_det->created_at = Carbon::now();
                $tf_prod_det->save();

                $tf_prod_his = new TFProduksiHis();
                $tf_prod_his->tfbj_id = $tf_prod->id;
                $tf_prod_his->gdg_brg_jadi_id = $request->gdg_brg_jadi_id;
                $tf_prod_his->qty = $request->qty;
                $tf_prod_his->noseri = $request->noseri;
                $tf_prod_his->created_at = Carbon::now();
                $tf_prod_his->save();

                return response()->json(['msg' => 'Successfully']);
            }
        }
    }

    function getTFnon() {
        $data = TFProduksiDetail::with('header')->get();
        return datatables()->of($data)
            // ->addColumn('produk', function($data) {
            //     return $data->detail;
            // })
            ->make(true);
    }

    // check
    function checkStok(Request $request) {
        $gdg = GudangBarangJadi::where('id',$request->gdg_brg_jadi_id)->first();
        return $gdg;
        // if ($gdg->stok < $request->qty) {
        //     return response()->json(['msg' => 'Stok Tidak Mencukupi']);
        // } else {
        //     return response()->json(['msg' => 'Successfully']);
        // }
    }
}
