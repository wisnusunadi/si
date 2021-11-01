<?php

namespace App\Http\Controllers;

use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\NoseriBarangJadi;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GudangController extends Controller
{
    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk')->select();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kelompok', function ($data) {
                return $data->produk->kelompokproduk->nama;
            })
            ->addColumn('merk', function ($data) {
                return $data->produk->merk;
            })
            ->addColumn('satuan', function ($data) {
                return $data->produk->Satuan->nama;
            })
            ->addColumn('layout', function ($data) {
                return $data->Layout->ruang . '-' . $data->Layout->lantai . '/' . $data->Layout->rak;
            })
            ->addColumn('nama', function ($data) {
                if ($data->variasi != '') {
                    return $data->produk->tipe . ' - <b>' . $data->variasi . '</b>';
                } else {
                    return $data->produk->tipe;
                }
            })
            ->rawColumns(['nama'])
            ->make(true);

        //return datatables()->of(GudangBarangJadi::select())->toJson();
    }

    function StoreBarangJadi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required',
            'nama' => 'required',
            'stok' => 'required|numeric',
            'ke' => 'required',
        ],
        [
            'produk_id.required' => 'Produk harus diisi',
            'nama.required' => 'Nama harus diisi',
            'stok.numeric' => 'Stok harus diisi angka',
            'stok.required' => 'Stok harus diisi',
            'ke.required' => 'Tujuan harus diisi',
        ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $brg_jadi = new GudangBarangJadi();
            $brg_jadi->produk_id = $request->produk_id;
            $brg_jadi->nama = $request->nama;
            $brg_jadi->deskripsi = $request->deskripsi;
            $brg_jadi->stok = $request->stok;
            $brg_jadi->layout_id = $request->layout_id;
            $image = $request->file('gambar');
            if ($image) {
                $path = 'upload/gbj/';
                // $nameImage = date('YmdHis') . ".". $image->getClientOriginalExtension();
                // $image->move($path, $nameImage);
                $nameImage = base64_encode(file_get_contents($image));
                $brg_jadi->gambar = $nameImage;
            }
            $brg_jadi->dim_p = $request->dim_p;
            $brg_jadi->dim_l = $request->dim_l;
            $brg_jadi->dim_t = $request->dim_t;
            $brg_jadi->status = $request->status;
            $brg_jadi->created_at = Carbon::now();
            $brg_jadi->save();

            $brg_his = new GudangBarangJadiHis();
            $brg_his->gdg_brg_jadi_id = $brg_jadi->id;
            $brg_his->produk_id = $request->produk_id;
            $brg_his->nama = $request->nama;
            $brg_his->deskripsi = $request->deskripsi;
            $brg_his->stok = $request->stok;
            $brg_his->jenis = 'MASUK';
            $brg_his->dari = $request->dari;
            $brg_his->ke = $request->ke;
            $brg_his->status = $request->status;
            $brg_his->layout_id = $request->layout_id;
            $brg_his->created_at = Carbon::now();
            $brg_his->save();

            $noseri = new NoseriBarangJadi();
            $noseri->gdg_barang_jadi_id = $brg_jadi->id;
            $noseri->dari = $request->dari;
            $noseri->ke = $request->ke;
            $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
            $noseri->jenis = 'MASUK';
            $noseri->is_aktif = 1;
            $noseri->created_at = Carbon::now();
            $noseri->save();

            return response()->json(['msg' => 'Successfully']);
        }
    }

    function UpdateBarangJadi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required',
            'nama' => 'required',
            'stok' => 'required|numeric',
            'ke' => 'required',
        ],
        [
            'produk_id.required' => 'Produk harus diisi',
            'nama.required' => 'Nama harus diisi',
            'stok.numeric' => 'Stok harus diisi angka',
            'stok.required' => 'Stok harus diisi',
            'ke.required' => 'Tujuan harus diisi',
        ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $brg_jadi = GudangBarangJadi::find($id);
            $brg_his = new GudangBarangJadiHis();

            if (empty($brg_jadi->id)) {
                return response()->json(['msg' => 'Data not found']);
            }

            $brg_jadi->produk_id = $request->produk_id;
            $brg_jadi->nama = $request->nama;
            $brg_jadi->deskripsi = $request->deskripsi;
            if ($request->jenis === 'MASUK') {
                $brg_jadi->stok = $request->stok + $brg_jadi->stok;
            } else {
                $brg_jadi->stok = $brg_jadi->stok - $request->stok;
            }
            $brg_jadi->layout_id = $request->layout_id;
            $image = $request->file('gambar');
            if ($image) {
                $path = 'upload/gbj/';
                $nameImage = date('YmdHis') . ".". $image->getClientOriginalExtension();
                $image->move($path, $nameImage);
                $brg_jadi->gambar = $nameImage;
            }
            $brg_jadi->dim_p = $request->dim_p;
            $brg_jadi->dim_l = $request->dim_l;
            $brg_jadi->dim_t = $request->dim_t;
            $brg_jadi->status = $request->status;
            $brg_jadi->updated_at = Carbon::now();
            $brg_jadi->save();


            $brg_his->gdg_brg_jadi_id = $brg_jadi->id;
            $brg_his->produk_id = $request->produk_id;
            $brg_his->nama = $request->nama;
            $brg_his->deskripsi = $request->deskripsi;
            $brg_his->stok = $request->stok;
            $brg_his->jenis = $request->jenis;
            $brg_his->dari = $request->dari;
            $brg_his->ke = $request->ke;
            $brg_his->status = $request->status;
            $brg_his->layout_id = $request->layout_id;
            $brg_his->created_at = Carbon::now();
            $brg_his->save();
            $noseri = new NoseriBarangJadi();
            if ($request->jenis === 'MASUK') {
                $noseri->gdg_barang_jadi_id = $brg_jadi->id;
                $noseri->dari = $request->dari;
                $noseri->ke = $request->ke;
                $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
                $noseri->jenis = 'MASUK';
                $noseri->is_aktif = 1;
                $noseri->created_at = Carbon::now();
            } else {
                $noseri->gdg_barang_jadi_id = $brg_jadi->id;
                $noseri->dari = $request->dari;
                $noseri->ke = $request->ke;
                $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
                $noseri->jenis = 'KELUAR';
                $noseri->is_aktif = 1;
                $noseri->created_at = Carbon::now();
            }
            $noseri->save();

            return response()->json(['msg' => 'Successfully']);
        }
    }

    function DestroyBarangJadi($id)
    {
        try {
            $brg_jadi = GudangBarangJadi::find($id);
            $brg_his = GudangBarangJadiHis::whereIn('gdg_brg_jadi_id', array($brg_jadi->id));
            $noseri = NoseriBarangJadi::whereIn('gdg_barang_jadi_id', array($brg_jadi->id));

            if (!empty($brg_jadi)) {
                $noseri->delete();
                $brg_his->delete();
                $brg_jadi->delete();
                return response()->json(['msg' => 'Successfully']);
            } else {
                return response()->json(['msg' => 'Data not found']);
            }
        } catch (\Exception $e) {
            if (empty($brg_jadi)) {
                return response()->json(['msg' => 'Data not found']);
            }
        }
    }

    public function base64toFile($img) {

    }

    function GetBarangJadiByID($id)
    {
        try {
            $brg_jadi = GudangBarangJadi::find($id);
            $brg_his = GudangBarangJadiHis::where('gdg_brg_jadi_id', $brg_jadi->id)->orderBy('created_at', 'DESC')->get();
            $noseri = NoseriBarangJadi::where('gdg_barang_jadi_id', $brg_jadi->id)->orderBy('created_at', 'DESC')->get();
            $his_stock = [];
            foreach ($brg_his as $b) {
                $his_stock[] = [
                    'nama' => $b->nama,
                    'stok' => $b->stok,
                    'deskripsi' => $b->deskripsi,
                    'dari' => $b->from->nama,
                    'ke' => $b->to->nama,
                    'jenis' => $b->jenis,
                    'Layout' => $b->Layout->ruang .';'. $b->Layout->lantai .'/'. $b->Layout->rak,
                    'created_at' => date_format($b->created_at, 'd-m-Y H:i:s'),
                ];
            }
            $his_noseri = [];
            foreach ($noseri as $c) {
                $his_noseri[] = [
                    'dari' => $c->from->nama,
                    'ke' => $c->to->nama,
                    'noseri' => $c->noseri,
                    'jenis' => $c->jenis,
                    'created_at' => date_format($c->created_at, 'd-m-Y H:i:s'),
                ];
            }
            $source_data = $brg_jadi->gambar;
            $source_data = base64_decode($source_data); //simple base64 any format decoded image source_data
            $source_img = imagecreatefromstring($source_data);
            $rotated_img = imagerotate($source_img, 0, 0); //simple images rotate with angle 90 degree here
            $data_file = 'upload/gbj/'. $brg_jadi->id . '.png';
            if (count($brg_jadi) < 0) {
                $data_savefile = imagejpeg($rotated_img, $data_file, 10);
            }
            imagedestroy($source_img);


            if (!empty($brg_jadi)) {
                return response()->json([
                    'produk' => $brg_jadi->produk->tipe,
                    'nama' => $brg_jadi->nama,
                    'stok' => $brg_jadi->stok,
                    'Layout' => $brg_jadi->Layout->ruang . '-' . $brg_jadi->Layout->lantai . '/' . $brg_jadi->Layout->rak,
                    'Gambar' => url('/upload/gbj/'. $data_file),
                    // 'Gambar' => imagejpeg($img),
                    'created_at' => date_format($brg_jadi->created_at, 'd-m-Y H:i:s'),
                    'updated_at' => date_format($brg_jadi->updated_at, 'd-m-Y H:i:s'),
                    'History Stok' => $his_stock,
                    'No Seri' => $his_noseri,
                ]);
            } else {
                return response()->json(['msg' => 'Data not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }


}
