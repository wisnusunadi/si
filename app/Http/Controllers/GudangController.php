<?php

namespace App\Http\Controllers;

use App\Models\DetailEkatalog;
use App\Models\DetailEkatalogProduk;
use App\Models\Divisi;
use App\Models\DraftGBJ;
use App\Models\DraftGbjDetail;
use App\Models\DraftGbjNoSeri;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\Layout;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GudangController extends Controller
{
    // get
    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk', 'satuan')->get();
        // return response()->json($data);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->produk->nama .' '. $data->nama;
            })
            ->addColumn('kode_produk', function ($data) {
                return $data->produk->product->kode .''. $data->produk->kode;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->stok .' '.$data->satuan->nama;
            })
            ->addColumn('kelompok', function ($data) {
                return $data->produk->KelompokProduk->nama;
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

                        <a data-toggle="modal" data-target="#stokmodal" class="stokmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="fas fa-cubes"></i>&nbsp;Daftar Stok
                            </button>
                        </a>

                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function GetBarangJadiByID(Request $request)
    {
        $data = GudangBarangJadi::with('produk', 'satuan')->where('id', $request->id)->get();
        $dataid = $data->pluck('produk_id');
        $datas = Produk::with('product')->where('id', $dataid)->get();
        return response()->json([
            'data' => $data,
            'nama_produk' => $datas
        ]);
    }

    function getNoseri(Request $request, $id) {
        $data = GudangBarangJadi::with('noseri')->where('id', $id)->get();
        // $data = NoseriBarangJadi::with('gudang', 'from', 'to')->where('gdg_barang_jadi_id', $id)->get();
        return response()->json($data);
    }

    function getHistory($id) {
        $data = NoseriBarangJadi::with('from', 'to')->where('id', $id)->get();
        return response()->json($data);
    }

    function getRancangDraft() {
        // $data = DraftGBJ::with('divisi', 'gbj', 'status')->get();
        // return datatables()->of($data)
        //     ->make(true);
    }

    function getHistorybyProduk() {
        $data = GudangBarangJadi::with('produk', 'satuan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('stock', function($d) {
                return $d->stok. ' '.$d->satuan->nama;
            })
            ->addColumn('kelompok', function($d) {
                return $d->produk->kelompokproduk->nama;
            })
            ->addColumn('product', function($d) {
                return $d->produk->nama. ' '.$d->nama;
            })
            ->addColumn('kode_produk', function($d) {
                return $d->produk->product->kode .''. $d->produk->kode;
            })
            ->addColumn('action', function($d) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                            <button class="btn btn-info" data-toggle="modal" data-target=".modal-detail"><i
                            class="far fa-eye"></i> Detail</button>
                            </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getAllTransaksi() {
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->get();
        $g = datatables()->of($data1)
            ->addIndexColumn()
            ->addColumn('so', function($d) {
                if (isset($d->header->pesanan_id)) {
                    return $d->header->pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('date_in', function($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function($d) {
                if (isset($d->header->tgl_keluar)) {
                    return date('d-m-Y', strtotime($d->header->tgl_keluar));
                } else {
                    return "-";
                }
            })
            ->addColumn('divisi', function($d) {
                if ($d->header->jenis == 'keluar') {
                    return '<span class="badge badge-info">'.$d->header->divisi->nama.'</span>';
                } else {
                    return '<span class="badge badge-success">'.$d->header->dari.'</span>';
                }
            })
            ->addColumn('tujuan', function($d) {
                return $d->header->deskripsi;
            })
            ->addColumn('jumlah', function($d) {
                return $d->qty.' '.$d->produk->satuan->nama;
            })
            ->addColumn('action', function($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info" onclick="detailProduk()"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action'])
            ->make(true);

        return $g;
    }

    function getDetailHistory($id) {
        $data = GudangBarangJadi::with('produk')->where('id',$id)->get();
        $d = [];
        foreach($data as $dd) {
            $d[] = [
                'id' => $dd->id,
                'kode' => $dd->produk->product->kode.''.$dd->produk->kode ? $dd->produk->product->kode.''.$dd->produk->kode : '-',
                'nama' => $dd->produk->nama.' '.$dd->nama,
                'deskripsi' => $dd->deskripsi,
                'panjang' => $dd->dim_p .' mm',
                'lebar' => $dd->dim_l. ' mm',
                'tinggi' => $dd->dim_t. ' mm',
            ];
        }

        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();
        $g = datatables()->of($data1)
            ->addIndexColumn()
            ->addColumn('so', function($d) {
                if (isset($d->header->pesanan_id)) {
                    return $d->header->pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('date_in', function($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function($d) {
                if (isset($d->header->tgl_keluar)) {
                    return date('d-m-Y', strtotime($d->header->tgl_keluar));
                } else {
                    return "-";
                }
            })
            ->addColumn('divisi', function($d) {
                if ($d->header->jenis == 'keluar') {
                    return '<span class="badge badge-info">'.$d->header->divisi->nama.'</span>';
                } else {
                    return '<span class="badge badge-success">'.$d->header->dari.'</span>';
                }
            })
            ->addColumn('tujuan', function($d) {
                return $d->header->deskripsi;
            })
            ->addColumn('jumlah', function($d) {
                return $d->qty.' '.$d->produk->satuan->nama;
            })
            ->addColumn('action', function($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info" onclick="detailProduk()"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action'])
            ->make(true);
        return response()->json([
            'header' => $d,
            'detail' => $g
        ]);
    }

    function getRakit() {
        $data = TFProduksiDetail::with('produk', 'header')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function($d) {
                return $d->produk->produk->nama.' '.$d->produk->nama;
            })
            ->addColumn('jumlah', function($d) {
                return $d->qty.' '.$d->produk->satuan->nama;
            })
            ->addColumn('action', function($d) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-edit"></i>&nbsp;Terima
                            </button>
                        </a>

                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>

                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getRakitNoseri($id) {
        $data = NoseriTGbj::with('layout', 'detail')->where('t_gbj_detail_id',$id)->get();
        return datatables()->of($data)
            ->addColumn('layout', function($d) {
                return $d->layout->ruang;
            })
            ->addColumn('seri', function($d) {
                return $d->noseri;
            })
            ->addColumn('title', function($d) {
                return $d->detail->produk->produk->nama.' '.$d->detail->produk->nama;
            })
            ->make(true);
    }

    function getTerimaRakit($id) {
        $data = NoseriTGbj::with('layout', 'detail')->where('t_gbj_detail_id',$id)->get();

        return datatables()->of($data)
            ->addColumn('layout', function($d) {
                return '<select name="layout_id" id="layout_id" class="form-control">

                        </select>';
            })
            ->addColumn('seri', function($d) {
                return $d->noseri;
            })
            ->addColumn('checkbox', function($d) {
                return '<input type="checkbox" class="cb-child" value="'.$d->id.'">';
            })
            ->addColumn('title', function($d) {
                return $d->detail->produk->produk->nama.' '.$d->detail->produk->nama;
            })
            ->rawColumns(['checkbox', 'layout'])
            ->make(true);
    }

    // store
    function storeNoseri(Request $request, $id) {
        dd($request->all());
        // $Gud = GudangBarangJadi::find($id);
        // $Gud->layout_id = $request->layout_id;
        // $Gud->save();
        // return response()->json('ok');
    }

    function StoreBarangJadi(Request $request)
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
            $id = $request->id;
            if ($id) {
                $brg_jadi = GudangBarangJadi::find($id);
                $brg_his = new GudangBarangJadiHis();

                if (empty($brg_jadi->id)) {
                    return response()->json(['msg' => 'Data not found']);
                }

                $brg_jadi->produk_id = $request->produk_id;
                $brg_jadi->satuan_id = $request->satuan_id;
                $brg_jadi->nama = $request->nama;
                $brg_jadi->deskripsi = $request->deskripsi;
                $brg_jadi->stok = 0;
                $image = $request->file('gambar');
                if ($image) {
                    $path = 'upload/gbj/';
                    $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
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
                $brg_his->satuan_id = $request->satuan_id;
                $brg_his->nama = $request->nama;
                $brg_his->deskripsi = $request->deskripsi;
                $brg_his->stok = 0;
                $brg_his->status = $request->status;
                $brg_his->created_at = Carbon::now();
                $brg_his->save();
            } else {
                $brg_jadi = new GudangBarangJadi();
                $brg_jadi->produk_id = $request->produk_id;
                $brg_jadi->satuan_id = $request->satuan_id;
                $brg_jadi->nama = $request->nama;
                $brg_jadi->stok = 0;
                $brg_jadi->deskripsi = $request->deskripsi;
                $image = $request->file('gambar');
                if ($image) {
                    $path = 'upload/gbj/';
                    $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($path, $nameImage);
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
                $brg_his->satuan_id = $request->satuan_id;
                $brg_his->produk_id = $request->produk_id;
                $brg_his->nama = $request->nama;
                $brg_his->stok = 0;
                $brg_his->deskripsi = $request->deskripsi;
                $brg_his->status = $request->status;
                $brg_his->created_at = Carbon::now();
                $brg_his->save();
            }
            return response()->json(['msg' => 'Successfully']);
        }
    }

    function storeDraftRancang(Request $request) {
        // dd($request->all());
        // $validator = Validator::make(
        //     $request->all(),
        //     [
        //         // 'produk_id' => 'required',
        //         // 'nama' => 'required',
        //         // 'stok' => 'required|numeric',
        //         // 'ke' => 'required',
        //     ],
        //     [
        //         // 'produk_id.required' => 'Produk harus diisi',
        //         // 'nama.required' => 'Nama harus diisi',
        //         // 'stok.numeric' => 'Stok harus diisi angka',
        //         // 'stok.required' => 'Stok harus diisi',
        //         // 'ke.required' => 'Tujuan harus diisi',
        //     ]
        // );

        // if ($validator->fails()) {
        //     return $validator->errors();
        // } else {
        //     foreach ($request->dari as $key => $value) {
        //         $draft = new DraftGBJ();
        //         // $draft->gbj_id = $request->gbj_id[$key];
        //         $draft->tgl_masuk = $request->tgl_masuk[$key];
        //         $draft->dari = $value;
        //         $draft->tujuan = $request->tujuan[$key];
        //         // $draft->qty = $request->qty[$key];
        //         $draft->status_id = 1;
        //         $draft->created_at = Carbon::now();
        //         $draft->save();

        //         foreach($request->gbj_id as $i => $v) {
        //             $detail = new DraftGbjDetail();
        //             $detail->draft_gbj_id = $draft->id;
        //             $detail->gbj_id = $request->gbj_id[$key];
        //             $detail->qty = $request->qty[$key];
        //             $detail->status_id = 1;
        //             $detail->created_at = Carbon::now();
        //             $detail->save();
        //         }

        //         // $noseri = new DraftGbjNoSeri();
        //         // $noseri->draft_gbj_id = $draft->id;
        //         // $noseri->noseri = $request->noseri[$key];
        //         // $noseri->layout_id = $request->layout_id[$key];
        //         // $noseri->status = $draft->status_id[$key];
        //         // $noseri->created_at = Carbon::now();
        //         // $noseri->save();
        //     }

        //     return response()->json(['msg' => 'Successfully']);
        // }
    }

    // select
    function select_layout() {
        $data = Layout::where('jenis_id',1)->get();
        return response()->json($data);
    }

    function select_product() {
        $data = Produk::with('product')->get();
        return response()->json($data);
    }

    function select_product_by_id($id) {
        $data = Produk::with('product')->find($id);
        return response()->json($data);
    }

    function select_satuan() {
        $data = Satuan::all();
        return response()->json($data);
    }

    function select_divisi() {
        $data = Divisi::all();
        return response()->json($data);
    }

    function select_gbj()
    {
        $data = GudangBarangJadi::with('produk')->get();
        return response()->json($data);
    }

    // test
    function test($id) {
        $data = DetailEkatalog::where('ekatalog_id', $id)->with('GudangBarangJadi', 'GudangBarangJadi.Produk')->get();
        return $data;
    }
}
