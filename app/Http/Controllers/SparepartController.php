<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\SparepartGudang;
use App\Models\SparepartHis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SparepartController extends Controller
{
    function get() {
        $spr = SparepartGudang::with('Spare', 'his')->paginate(10);
        return $spr;
    }

    function getId($id) {
        $data = Sparepart::find($id);
        $head = SparepartGudang::whereIn('sparepart_id', array($data->id))->get();
        $his = SparepartHis::whereIn('sparepart_id', array($data->id))->get();
        try {
            $res_head = [];
            foreach($head as $h) {
                $res_head[] = [
                    'nama' => $h->nama,
                    'deskripsi' => $h->deskripsi,
                    'stok' => $h->stok,
                    'layout_id' => $h->layout_id ? $h->layout_id : '-',
                    'gambar' => url('/upload/sparepart/'. $h->gambar),
                    'dimensi' => $h->dim_p * $h->dim_l * $h->dim_t,
                    'created_at' => date_format($h->created_at, 'd-m-Y H:i:s'),
                    'updated_at' => date_format($h->updated_at, 'd-m-Y H:i:s')
                ];
            }
            $res_his = [];
            foreach($his as $hh) {
                $res_his[] = [
                    'nama' => $hh->nama,
                    'deskripsi' => $hh->deskripsi,
                    'stok' => $hh->stok,
                    'layout_id' => $hh->layout_id ? $hh->layout_id : '-',
                    'jenis' => $hh->jenis,
                    'created_at' => date_format($hh->created_at, 'd-m-Y H:i:s'),
                    'updated_at' => date_format($hh->updated_at, 'd-m-Y H:i:s')
                ];
            }
            return response()->json([
                'data' => [
                    'head' => $data,
                    'gudang' => $res_head,
                    'history' => $res_his
                ]
            ]);
        } catch (\Throwable $th) {
            if (empty($data)) {
                return response()->json(['msg' => 'Data not found']);
            }
        }
    }

    function store(Request $request) {
        $spr = new Sparepart();
        $spr_gdg = new SparepartGudang();
        $spr_his = new SparepartHis();

        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'stok' => 'required|numeric',
            'kelompok_produk_id' => 'required',
        ],
        [
            'nama.required' => 'Nama Sparepart harus diisi',
            'stok.required' => 'Stok harus diisi',
            'stok.numeric' => 'Stok harus berisi angka',
            'kelompok_produk_id.required' => 'Kategori Sparepart harus diisi'
        ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            );
        } else {
            $spr->kelompok_produk_id = $request->kelompok_produk_id;
            $spr->kode = $request->kode;
            $spr->nama = $request->nama;
            $spr->created_at = Carbon::now();
            $spr->save();

            $spr_gdg->sparepart_id = $spr->id;
            $spr_gdg->nama = $request->nama;
            $spr_gdg->deskripsi = $request->deskripsi;
            $spr_gdg->stok = $request->stok;
            $spr_gdg->layout_id = $request->layout_id;
            $image = $request->file('gambar');
            if ($image) {
                $path = 'upload/sparepart/';
                $nameImage = date('YmdHis') . ".". $image->getClientOriginalExtension();
                $image->move($path, $nameImage);
                $spr_gdg->gambar = $nameImage;
            }
            $spr_gdg->dim_p = $request->dim_p;
            $spr_gdg->dim_l = $request->dim_l;
            $spr_gdg->dim_t = $request->dim_t;
            $spr_gdg->status = $request->status;
            $spr_gdg->created_at = Carbon::now();
            $spr_gdg->save();

            $spr_his->gs_id = $spr_gdg->id;
            $spr_his->sparepart_id = $spr->id;
            $spr_his->nama = $request->nama;
            $spr_his->deskripsi = $request->deskripsi;
            $spr_his->stok = $request->stok;
            $spr_his->layout_id = $request->layout_id;
            $spr_his->status = $request->status;
            $spr_his->jenis = 'MASUK';
            $spr_his->created_at = Carbon::now();
            $spr_his->save();

            return response()->json(['msg' => 'Successfully']);
        }
    }

    function update(Request $request, $id) {
        $spr = Sparepart::find($id);
        $spr_gdg = SparepartGudang::where('sparepart_id', $spr->id)->first();
        $spr_his = new SparepartHis();

        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'stok' => 'required|numeric',
            'kelompok_produk_id' => 'required',
        ],
        [
            'nama.required' => 'Nama Sparepart harus diisi',
            'stok.required' => 'Stok harus diisi',
            'stok.numeric' => 'Stok harus berisi angka',
            'kelompok_produk_id.required' => 'Kategori Sparepart harus diisi'
        ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            $spr->kelompok_produk_id = $request->kelompok_produk_id;
            $spr->kode = $request->kode;
            $spr->nama = $request->nama;
            $spr->updated_at = Carbon::now();
            $spr->save();

            $spr_gdg->sparepart_id = $spr->id;
            $spr_gdg->nama = $request->nama;
            $spr_gdg->deskripsi = $request->deskripsi;
            if ($request->jenis === 'MASUK') {
                $spr_gdg->stok = $spr_gdg->stok + $request->stok;
            } else {
                $spr_gdg->stok = $spr_gdg->stok - $request->stok;
            }
            $spr_gdg->layout_id = $request->layout_id;
            // unlink('upload/sparepart/'. $spr_gdg->gambar);
            $image = $request->file('gambar');
            if ($image) {
                $path = 'upload/sparepart/';
                $nameImage = date('YmdHis') . ".". $image->getClientOriginalExtension();
                $image->move($path, $nameImage);
                $spr_gdg->gambar = $nameImage;
            }
            $spr_gdg->dim_p = $request->dim_p;
            $spr_gdg->dim_l = $request->dim_l;
            $spr_gdg->dim_t = $request->dim_t;
            $spr_gdg->status = $request->status;
            $spr_gdg->updated_at = Carbon::now();
            $spr_gdg->save();

            $spr_his->gs_id = $spr_gdg->id;
            $spr_his->sparepart_id = $spr->id;
            $spr_his->nama = $request->nama;
            $spr_his->deskripsi = $request->deskripsi;
            $spr_his->stok = $request->stok;
            $spr_his->layout_id = $request->layout_id;
            $spr_his->status = $request->status;
            $spr_his->jenis = $request->jenis;
            $spr_his->updated_at = Carbon::now();
            $spr_his->save();

            return response()->json(['msg' => 'Successfully']);
        }
    }

    function delete($id) {

        try {
            $spr = Sparepart::find($id);
            $spr_gdg = SparepartGudang::where('sparepart_id', $spr->id);
            $spr_his = SparepartHis::whereIn('sparepart_id', array($spr->id));
            if (!empty($spr)) {
                $spr_his->delete();
                $spr_gdg->delete();
                $spr->delete();

                return response()->json(['msg' => 'Successfully']);
            }
        } catch (\Exception $e) {
            //throw $th;
            if (empty($spr)) {
                return response()->json(['msg' => 'Data not found']);
            }
        }
    }

    function deleteImage() {
        if(File::exists('upload/sparepart/20211029163528.png')){
            unlink('upload/sparepart/20211029163528.png');
            return 'ok';
        }else{
            dd('File does not exists.');
        }
    }

}
