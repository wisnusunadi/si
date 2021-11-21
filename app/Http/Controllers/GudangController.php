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
            ->addColumn('product', function($d) {
                return $d->produk->produk->nama.' '.$d->produk->nama;
            })
            ->addColumn('action', function($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action'])
            ->make(true);

        return $g;
    }

    function getDetailAll($id) {
        $data = NoseriTGbj::with('layout', 'detail')->where('t_gbj_detail_id',$id)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('layout', function($d) {
                return $d->layout->ruang;
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

    function getDraftPerakitan(Request $request) {
        if ($request->id) {
            $data = TFProduksiDetail::with('header', 'produk', 'noseri')->where('t_gbj_id', $request->id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function($d) {
                    return $d->produk->produk->nama.' '.$d->produk->nama;
                })
                ->addColumn('jml', function($d) {
                    return $d->qty.' '.$d->produk->satuan->nama;
                })
                ->addColumn('action', function($d) {
                    return '<a data-toggle="modal" data-target="#detail" class="detail" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-info"><i
                                class="far fa-eye"></i> Detail</button>
                            </a>';
                })
                ->addColumn('in', function($d) {
                    return date('d F Y', strtotime($d->header->tgl_masuk));
                })
                ->addColumn('from', function($d) {
                    return $d->header->darii->nama;
                })
                ->addColumn('tujuan', function($d) {
                    return $d->header->deskripsi;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            $data = TFProduksi::with('detail', 'darii')->where(['jenis' => 'masuk','status_id' => 1])->get();
            return datatables()->of($data)
                ->addColumn('in', function($d) {
                    return date('d-m-Y', strtotime($d->tgl_masuk));
                })
                ->addColumn('from', function($d) {
                    return $d->darii->nama;
                })
                ->addColumn('tujuan', function($d) {
                    return $d->deskripsi;
                })
                ->addColumn('action', function($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-info"><i
                                class="far fa-eye"></i> Detail</button>
                            </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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
        $h = new TFProduksi();
        $h->tgl_masuk = Carbon::now();
        $h->dari = $request->dari;
        $h->deskripsi = $request->deskripsi;
        $h->status_id = 1;
        $h->jenis = 'masuk';
        $h->created_at = Carbon::now();
        $h->save();

        foreach($request->gdg_brg_jadi_id as $key => $value) {
            $d = new TFProduksiDetail();
            $d->t_gbj_id = $h->id;
            $d->gdg_brg_jadi_id = $value;
            $d->qty = $request->qty[$key];
            $d->status_id = 1;
            $d->jenis = 'masuk';
            $d->created_at = Carbon::now();
            $d->save();
        }

        return response()->json(['msg' => 'Successfully']);
    }

    function storeFinalRancang(Request $request) {
        $h = TFProduksi::find($request->id);
        // $h->status_id = 2;
        // $h->updated_at = Carbon::now();
        // $h->save();
        $d = TFProduksiDetail::whereIn('t_gbj_id', $h->id)->get();
        return $d;
        // foreach($d as $v) {
        //    echo $v['qty'];
        // }
        // return $h;
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

    // dashboard
    // produk
    function h1() {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10,20])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h2() {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5,9])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h3() {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1,4])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h4() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->startOfMonth()->subMonths(3), Carbon::now()->startOfMonth()] )
                ->orwhereBetween('tgl_masuk', [Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->startOfMonth()] );
        })->get();

        return count($data);
    }

    function h5() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->startOfMonth()] )
                ->orwhereBetween('tgl_masuk', [Carbon::now()->startOfYear()->subYears(1), Carbon::now()->startOfYear()] );
        })->get();

        return count($data);
    }

    function h6() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->startOfYear()->subYears(1), Carbon::now()->startOfYear()] )
                ->orwhereBetween('tgl_masuk', [Carbon::now()->startOfYear()->subYears(3), Carbon::now()->startOfYear()] );
        })->get();

        return count($data);
    }

    function h7() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfYear()->subYear(3) );
        })->get();

        return count($data);
    }

    function getProdukstok1020() {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10,20])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function($d) {
                return $d->produk->nama.' '.$d->nama;
            })
            ->addColumn('jml', function($d) {
                return $d->stok.' '.$d->satuan->nama;
            })
            ->make(true);
    }

    function getProdukstok59() {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5,9])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function($d) {
                return $d->produk->nama.' '.$d->nama;
            })
            ->addColumn('jml', function($d) {
                return $d->stok.' '.$d->satuan->nama;
            })
            ->make(true);
    }

    function getProdukstok14(){
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1,4])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function($d) {
                return $d->produk->nama.' '.$d->nama;
            })
            ->addColumn('jml', function($d) {
                return $d->stok.' '.$d->satuan->nama;
            })
            ->make(true);
    }

    function getProdukIn36() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->startOfMonth()->subMonths(3), Carbon::now()->startOfMonth()] )
                ->orwhereBetween('tgl_masuk', [Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->startOfMonth()] );
        })->get();
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
            ->make(true);
    }

    function getProdukIn612() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->startOfMonth()] )
                ->orwhereBetween('tgl_masuk', [Carbon::now()->startOfYear()->subYears(1), Carbon::now()->startOfYear()] );
        })->get();
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
            ->make(true);
    }

    function getProduk1236() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->startOfYear()->subYears(1), Carbon::now()->startOfYear()] )
                ->orwhereBetween('tgl_masuk', [Carbon::now()->startOfYear()->subYears(3), Carbon::now()->startOfYear()] );
        })->get();
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
            ->make(true);
    }

    function getProduk36Plus() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfYear()->subYear(3) );
        })->get();
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
            ->make(true);
    }

    function getProdukByLayout() {
        $data = GudangBarangJadi::with('produk', 'layout')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function($d) {
                return $d->produk->nama.' '.$d->nama;
            })
            ->addColumn('jml', function($d) {
                return $d->stok.' '.$d->satuan->nama;
            })
            // ->addColumn('layout', function($d) {
            //     return $d->layout->ruang;
            // })
            ->make(true);
    }

    // penerimaan
    function hh1() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfDay()->subDays(1) );
            // $q->whereBetween('tgl_masuk', [Carbon::now()->startOfDay()->subDays(1), Carbon::now()->startOfDay()] );
        })->get();
        return count($data);
    }

    function hh2() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfDay()->subDays(2) );
            // $q->whereBetween('tgl_masuk', [Carbon::now()->startOfDay()->subDays(2), Carbon::now()->startOfDay()] );
        })->get();
        return count($data);
    }

    function hh3() {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfDay()->subDays(3) );
            // $q->whereBetween('tgl_masuk', [Carbon::now()->startOfDay()->subDays(3), Carbon::now()->startOfDay()] );
        })->get();
        return count($data);
    }

    function getPenerimaanProduk1(Request $request) {
        // $data = TFProduksiDetail::with('produk', 'header')->where('jenis', 'masuk')->get();
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfDay()->subDays(1) );
            // $q->whereBetween('tgl_masuk', [Carbon::now()->startOfDay()->subDays(1), Carbon::now()->startOfDay()] );
        })->get();
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
                return  '<a href="'.url("gbj/dp").'" class="btn btn-outline-primary"><i
                class="fas fa-paper-plane"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getPenerimaanProduk2(Request $request) {
        // $data = TFProduksiDetail::with('produk', 'header')->where('jenis', 'masuk')->get();
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfDay()->subDays(2) );
            // $q->whereBetween('tgl_masuk', [Carbon::now()->startOfDay()->subDays(2), Carbon::now()->startOfDay()] );
        })->get();
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
                return  '<a href="'.url("gbj/dp").'" class="btn btn-outline-primary"><i
                class="fas fa-paper-plane"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getPenerimaanProduk3(Request $request) {
        // $data = TFProduksiDetail::with('produk', 'header')->where('jenis', 'masuk')->get();
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->startOfDay()->subDays(3) );
        })->get();
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
                return  '<a href="'.url("gbj/dp").'" class="btn btn-outline-primary"><i
                class="fas fa-paper-plane"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getPenerimaanAll() {
        $data = TFProduksiDetail::with('produk', 'header')->where('jenis', 'masuk')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function($d) {
                if (isset($d->header->tgl_masuk)) {
                    switch ($d->header->tgl_masuk) {
                        case '1':
                            return date('d-m-Y', strtotime($d->header->tgl_masuk));
                            break;

                        case '2':
                            return date('d-m-Y', strtotime($d->header->tgl_masuk));
                            break;

                        case '3':
                            return date('d-m-Y', strtotime($d->header->tgl_masuk));
                            break;
                        default:
                            # code...
                            break;
                    }
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
                return  '<a href="{{ url("gbj/dp") }}" class="btn btn-outline-primary"><i
                class="fas fa-paper-plane"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


}
