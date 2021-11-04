<?php

namespace App\Http\Controllers;

use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\Layout;
use App\Models\NoseriBarangJadi;
use App\Models\Produk;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GudangController extends Controller
{
    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk', 'noseri')->select();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kelompok', function ($data) {
                return $data->produk->kelompokproduk->nama;
            })
            ->addColumn('merk', function ($data) {
                return $data->produk->merk;
            })
            ->addColumn('satuan', function ($data) {
                return $data->stok . ' ' . $data->produk->Satuan->nama;
            })
            ->addColumn('satuan1', function ($data) {
                return $data->stok - 20 . ' ' . $data->produk->Satuan->nama;
            })
            ->addColumn('layout', function ($data) {
                return $data->Layout->ruang . ';' . $data->Layout->lantai . '-' . $data->Layout->rak;
            })
            ->addColumn('nama', function ($data) {
                return $data->nama;
            })
            ->addColumn('kode', function ($data) {
                return $data->produk->kode ? $data->produk->kode : '-';
            })
            ->addColumn('action', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-edit"></i>&nbsp;Edit
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

        //return datatables()->of(GudangBarangJadi::select())->toJson();
    }

    function StoreBarangJadi(Request $request)
    {
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

        if ($validator->fails()) {
            return response()->json(['error' =>  $validator->errors()]);
        } else {
            $id = $request->id;
            if ($id) {
                $brg_jadi = GudangBarangJadi::find($id);
                $brg_his = new GudangBarangJadiHis();

                if (empty($brg_jadi->id)) {
                    return response()->json(['msg' => 'Data not found']);
                }

                $brg_jadi->produk_id = $request->produk_id;
                $brg_jadi->nama = $request->nama;
                $brg_jadi->deskripsi = $request->deskripsi;
                // if ($request->jenis === 'MASUK') {
                //     $brg_jadi->stok = $request->stok + $brg_jadi->stok;
                // } else {
                //     $brg_jadi->stok = $brg_jadi->stok - $request->stok;
                // }
                $brg_jadi->stok = $request->stok;
                $brg_jadi->layout_id = $request->layout_id;
                $image = $request->file('gambar');
                if ($image) {
                    $path = 'upload/gbj/';
                    $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($path, $nameImage);
                    // $nameImage = base64_encode(file_get_contents($image));
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
                // $brg_his->jenis = $request->jenis;
                // $brg_his->dari = $request->dari;
                // $brg_his->ke = $request->ke;
                $brg_his->status = $request->status;
                $brg_his->layout_id = $request->layout_id;
                $brg_his->created_at = Carbon::now();
                $brg_his->save();
                // $noseri = new NoseriBarangJadi();
                // if ($request->jenis === 'MASUK') {
                //     $noseri->gdg_barang_jadi_id = $brg_jadi->id;
                //     $noseri->dari = $request->dari;
                //     $noseri->ke = $request->ke;
                //     $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
                //     $noseri->jenis = 'MASUK';
                //     $noseri->is_aktif = 1;
                //     $noseri->created_at = Carbon::now();
                // } else {
                //     $noseri->gdg_barang_jadi_id = $brg_jadi->id;
                //     $noseri->dari = $request->dari;
                //     $noseri->ke = $request->ke;
                //     $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
                //     $noseri->jenis = 'KELUAR';
                //     $noseri->is_aktif = 1;
                //     $noseri->created_at = Carbon::now();
                // }
                // $noseri->save();
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
                    $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($path, $nameImage);
                    // $nameImage = base64_encode(file_get_contents($image));
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
            }
            return response()->json(['msg' => 'Successfully']);
        }
    }

    function UpdateBarangJadi(Request $request, $id)
    {
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
            // if ($request->jenis === 'MASUK') {
            //     $brg_jadi->stok = $request->stok + $brg_jadi->stok;
            // } else {
            //     $brg_jadi->stok = $brg_jadi->stok - $request->stok;
            // }
            $brg_jadi->stok = $request->stok;
            $brg_jadi->layout_id = $request->layout_id;
            $image = $request->file('gambar');
            if ($image) {
                $path = 'upload/gbj/';
                $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($path, $nameImage);
                // $nameImage = base64_encode(file_get_contents($image));
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
            // $brg_his->jenis = $request->jenis;
            // $brg_his->dari = $request->dari;
            // $brg_his->ke = $request->ke;
            $brg_his->status = $request->status;
            $brg_his->layout_id = $request->layout_id;
            $brg_his->created_at = Carbon::now();
            $brg_his->save();
            // $noseri = new NoseriBarangJadi();
            // if ($request->jenis === 'MASUK') {
            //     $noseri->gdg_barang_jadi_id = $brg_jadi->id;
            //     $noseri->dari = $request->dari;
            //     $noseri->ke = $request->ke;
            //     $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
            //     $noseri->jenis = 'MASUK';
            //     $noseri->is_aktif = 1;
            //     $noseri->created_at = Carbon::now();
            // } else {
            //     $noseri->gdg_barang_jadi_id = $brg_jadi->id;
            //     $noseri->dari = $request->dari;
            //     $noseri->ke = $request->ke;
            //     $noseri->noseri = date('Ymd') . '-' . mt_rand(100, 999);
            //     $noseri->jenis = 'KELUAR';
            //     $noseri->is_aktif = 1;
            //     $noseri->created_at = Carbon::now();
            // }
            // $noseri->save();

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
                    'Layout' => $b->Layout->ruang . ';' . $b->Layout->lantai . '/' . $b->Layout->rak,
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

            // decode base64 to image
            // $source_data = $brg_jadi->gambar;
            // $source_data = base64_decode($source_data);
            // $source_img = imagecreatefromstring($source_data);
            // $rotated_img = imagerotate($source_img, 0, 0);
            // $data_file = 'upload/gbj/' . $brg_jadi->id . '.png';
            // if (!file_exists($brg_jadi->id)) {
            //     $data_savefile = imagejpeg($rotated_img, $data_file, 10);
            // }
            // imagedestroy($source_img);

            $res_data = [
                'produk' => $brg_jadi->produk->tipe,
                'nama' => $brg_jadi->nama,
                'stok' => $brg_jadi->stok,
                'Layout' => $brg_jadi->Layout->ruang . '-' . $brg_jadi->Layout->lantai . '/' . $brg_jadi->Layout->rak,
                'Gambar' => url('upload/gbj/' . $brg_jadi->gambar),
                // 'Gambar' => url($data_file),
                'created_at' => date_format($brg_jadi->created_at, 'd-m-Y H:i:s'),
                'updated_at' => date_format($brg_jadi->updated_at, 'd-m-Y H:i:s'),
            ];


            if (!empty($brg_jadi)) {
                return response()->json([
                    'Data' => [
                        'Produk' => $res_data,
                        'History Stok' => $his_stock,
                        'No Seri' => $his_noseri,
                    ]
                ]);
            } else {
                return response()->json(['msg' => 'Data not found']);
            }
        } catch (\Exception $e) {
            if (empty($brg_jadi->id)) {
                # code...
                return response()->json(['msg' => 'Data not found']);
            }
        }
    }

    function show($id)
    {
        $data = GudangBarangJadi::with('noseri', 'history', 'Layout', 'produk')->where('id', $id)->get();
        $lay = Layout::all();
        $prd = Produk::all();
        $res = [];
        foreach ($prd as $r) {
            $res[] = $r;
        }
        $res_lay = [];
        foreach ($lay as $l) {
            $res_lay[] = $l;
        }
        foreach ($data as $d) {
            $html = '<div class="modal-header">
                            <h5 class="modal-title">Produk ' . $d->nama . '</h5>

                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <img src="http://localhost:8000/upload/gbj/' . $d->gambar . '"
                                        alt="">
                                </div>
                                <div class="col-6">
                                    <p><b>Nama Produk</b></p>
                                    <p>' . $d->nama . '</p>
                                    <p><b>Deskripsi Produk</b></p>
                                    <p>' . $d->deskripsi . '</p>
                                    <p><b>Dimensi</b></p>
                                    <div class="d-flex">
                                        <p>Panjang: ' . $d->dim_p . '</p>
                                        <p>Lebar: ' . $d->dim_l . '</p>
                                        <p>Tinggi: ' . $d->dim_t . '</p>
                                    </div>
                                    <p><b>Produk</b></p>
                                    <p>' . $d->produk->nama . '</p>
                                    <p><b>Layout</b></p>
                                    <p>' . $d->Layout->ruang . ';' . $d->Layout->lantai . '-' . $d->Layout->rak . '</p>
                                </div>
                            </div>
                        </div>';
        }

        return response()->json(['html' => $html]);
    }

    function edit(Request $request)
    {
        $data = GudangBarangJadi::with('noseri', 'history', 'Layout', 'produk')->where('id', $request->id)->get();
        // $lay = Layout::all();
        // $prd = Produk::all();
        // $res = [];
        // foreach($prd as $r) {
        //     $res[] = $r;
        // }
        // $res_lay = [];
        // foreach($lay as $l) {
        //     $res_lay[] = $l;
        // }
        // foreach($data as $d) {
        //     $html = ' <!-- Modal Header -->
        //     <div class="modal-header">
        //         <h5 class="modal-title" id="exampleModalLabel">Edit Produk '.$d->nama.'</h5>
        //         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        //             <span aria-hidden="true">&times;</span>
        //         </button>
        //     </div>
        //     <!-- Modal body -->
        //     <div class="modal-body">
        //         <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
        //             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //                 <span aria-hidden="true">&times;</span>
        //             </button>
        //         </div>
        //         <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
        //             <strong>Success!</strong>Article was added successfully.
        //             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //                 <span aria-hidden="true">&times;</span>
        //             </button>
        //         </div>
        //         <div class="row">
        //             <div class="col">
        //                 <label for="">Nama Produk</label>
        //                 <input type="text" name="nama" id="editnama" class="form-control" placeholder="Nama Produk" value="'.$d->nama.'">
        //             </div>
        //             <div class="col">
        //                 <label for="">Stok</label>
        //                 <input type="text" name="stok" id="editstok" class="form-control" placeholder="Stok" value="'.$d->stok.'">
        //             </div>
        //         </div>
        //         <div class="form-group">
        //             <label for="">Deskripsi</label>
        //             <textarea class="form-control" name="editdeskripsi" id="deskripsi" cols="5" rows="5">'.$d->deskripsi.'</textarea>
        //         </div>
        //         <div class="form-group">
        //             <label for="">Dimensi</label>
        //             <div class="d-flex justify-content-between">
        //                 <input type="text" class="form-control" name="dim_p" id="editdim_p" placeholder="Panjang" value="'.$d->dim_p.'">&nbsp;
        //                 <input type="text" class="form-control" name="dim_l" id="editdim_l" value="'.$d->dim_l.'" placeholder="Lebar">&nbsp;
        //                 <input type="text" class="form-control" name="dim_t" id="editdim_t" value="'.$d->dim_t.'" placeholder="Tinggi">&nbsp;
        //             </div>
        //         </div>
        //         <div class="row">
        //             <div class="col">
        //                 <div class="form-group">
        //                     <label for="">Produk</label>
        //                     <select name="produk_id" id="editproduk_id" class="form-control produk-edit">
        //                         <option value="'.$d->produk_id.'">'.$d->produk->nama.'</option>
        //                     </select>
        //                 </div>
        //             </div>
        //             <div class="col">
        //                 <label for="">Layout</label>
        //                 <select name="layout_id" id="editlayout_id" class="form-control layout-edit">
        //                     <option value="'.$d->layout_id.'">'.$d->Layout->ruang.'</option>
        //                 </select>
        //             </div>
        //         </div>
        //         <div class="form-group">
        //             <div class="custom-file">
        //                 <input type="file" name="gambar" id="editgambar" class="custom-file-input" id="inputGroupFile02" />
        //                 <label class="custom-file-label" for="inputGroupFile02">Pilih File</label>
        //             </div>
        //         </div>

        //     </div>';
        // }


        // return response()->json(['html' => $html]);
        return response()->json($data);
    }
}
