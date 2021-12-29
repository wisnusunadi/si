<?php

namespace App\Http\Controllers;

use App\Exports\GBJExportSPB;
use App\Models\DetailEkatalog;
use App\Models\DetailEkatalogProduk;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Divisi;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\Layout;
use App\Models\LogSurat;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class GudangController extends Controller
{
    // get
    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->produk->nama . ' ' . $data->nama;
            })
            ->addColumn('kode_produk', function ($data) {
                return $data->produk->product->kode . '' . $data->produk->kode;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->stok . ' ' . $data->satuan->nama;
            })
            ->addColumn('jumlah1', function ($data) {
                if ($data->id) {
                    $ss = DetailPesananProduk::with('detailpesanan')->where('gudang_barang_jadi_id', $data->id)->get();
                    return $data->stok - $ss->sum('detailpesanan.jumlah') . ' ' . $data->satuan->nama;
                } else {
                    return '-';
                }

            })
            ->addColumn('kelompok', function ($data) {
                return $data->produk->KelompokProduk->nama;
            })
            ->addColumn('action', function ($data) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="btn btn-outline-success btn-sm" type="button" >
                            <i class="far fa-edit"></i>&nbsp;Edit
                            </button>
                        </a>

                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
            })
            ->addColumn('action_direksi', function($data) {
                return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
            })
            ->rawColumns(['action', 'action_direksi'])
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

    function getNoseri(Request $request, $id)
    {
        $data = GudangBarangJadi::with('noseri')->where('id', $id)->get();
        return response()->json($data);
    }

    function getHistory($id)
    {
        $data = NoseriBarangJadi::with('from', 'to')->where('id', $id)->get();
        return response()->json($data);
    }

    function getHistorybyProduk()
    {
        $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('stock', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('stok_jual', function ($data) {
                if ($data->id) {
                    $ss = DetailPesananProduk::with('detailpesanan')->where('gudang_barang_jadi_id', $data->id)->get();
                    return $data->stok - $ss->sum('detailpesanan.jumlah') . ' ' . $data->satuan->nama;
                } else {
                    return '-';
                }

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
                return '<a class="btn btn-info" href="'.url('gbj/tp/'.$d->id.'').'"><i
                        class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getAllTransaksi()
    {
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->get();
        $g = datatables()->of($data1)
            ->addIndexColumn()
            ->addColumn('so', function ($d) {
                if (isset($d->header->pesanan_id)) {
                    return $d->header->pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('date_in', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function ($d) {
                if (isset($d->header->tgl_keluar)) {
                    return date('d-m-Y', strtotime($d->header->tgl_keluar));
                } else {
                    return "-";
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->header->jenis == 'keluar') {
                    return '<span class="badge badge-info">' . $d->header->divisi->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->darii->nama . '</span>';
                }
            })
            ->addColumn('tujuan', function ($d) {
                return $d->header->deskripsi;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                <button class="btn btn-info"><i
                class="far fa-eye"></i> Detail</button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action'])
            ->make(true);

        return $g;
    }

    function getDetailAll($id)
    {
        $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('layout', function ($d) {
                if(isset($d->layout->ruang)) {
                    return $d->layout->ruang;
                } else {
                    return '-';
                }

            })
            ->addColumn('seri', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
            })
            ->addColumn('title', function ($d) {
                return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
            })
            ->rawColumns(['checkbox', 'layout'])
            ->make(true);
    }

    function getDetailHistory($id)
    {
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();

        return datatables()->of($data1)
            ->addIndexColumn()
            ->addColumn('so', function ($d) {
                if (isset($d->header->pesanan_id)) {
                    return $d->header->pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('date_in', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function ($d) {
                if (isset($d->header->tgl_keluar)) {
                    return date('d-m-Y', strtotime($d->header->tgl_keluar));
                } else {
                    return "-";
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->header->jenis == 'keluar') {
                    return '<span class="badge badge-info">' . $d->header->divisi->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->darii->nama . '</span>';
                }
            })
            ->addColumn('tujuan', function ($d) {
                return $d->header->deskripsi;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action'])
            ->make(true);

    }

    function getDetailHistorySeri($id) {
        $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noser', function($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function($d) {
                return $d->layout->ruang;
            })
            ->make(true);
    }

    function getDetailHistory1($id) {
        $header = GudangBarangJadi::with('produk')->where('id', $id)->first();
        $data = GudangBarangJadi::with('produk')->where('id', $id)->get();
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();
        return view('page.gbj.tp.show', compact('data', 'data1', 'header'));
    }

    function getRakit()
    {

        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('dari', 17);
        })->with('produk', 'header')->get()->sortByDesc('header.tgl_masuk');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                $seri = NoseriTGbj::where('t_gbj_detail_id', $d->id)->get();
                $c = count($seri);
                return $c . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                $seri = NoseriTGbj::where('t_gbj_detail_id', $d->id)->get();
                $seri_final = NoseriTGbj::where('t_gbj_detail_id', $d->id)->where('status_id', 3)->get();
                $cc = count(($seri_final));
                $c = count($seri);
                if($cc == $c) {
                    return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                } else {
                    return  '
                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                            </a>

                           ';
                }

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getRakitNoseri($id)
    {
        $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->where('status_id', 3)->get();
        return datatables()->of($data)
            ->addColumn('layout', function ($d) {
                return $d->layout->ruang;
            })
            ->addColumn('noserii', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('title', function ($d) {
                return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
            })
            ->make(true);
    }

    function getTerimaRakit($id)
    {
        $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->where('status_id', null)->get();
        $layout = Layout::where('jenis_id', 1)->get();
        $a = 0;
        return datatables()->of($data)
            ->addColumn('layout', function ($d) use($layout, $a) {
                $opt = '';

                foreach($layout as $l) {
                    $opt .= '<option value="'.$l->id.'">'.$l->ruang.'</option>';
                }
                $a++;
                return '<select name="layout_id[]" id="layout_id" class="form-control layout">
                        ' . $opt . '
                        </select>';

            })
            ->addColumn('noserii', function ($d) {
                return $d->seri->noseri.'<input type="hidden" name="noseri[]" id="noseri[]" value="'.$d->seri->noseri.'">';
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
            })
            ->addColumn('title', function ($d) {
                return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
            })
            ->rawColumns(['checkbox', 'layout', 'noserii'])
            ->make(true);
    }

    function getDraftPerakitan(Request $request)
    {
        if ($request->id) {
            $data = TFProduksiDetail::with('header', 'produk', 'noseri')->where('t_gbj_id', $request->id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama.'<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="'.$d->gdg_brg_jadi_id.'">';
                })
                ->addColumn('jml', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama.'<input type="hidden" name="qty[]" id="qty" value="'.$d->qty.'">';
                })
                ->addColumn('kode_prd', function($d) {
                    return $d->gdg_brg_jadi_id;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#detail" class="detail" data-attr="" data-var="'.$d->produk->nama.'" data-nama="'.$d->produk->produk->nama.'" data-gbj='.$d->gdg_brg_jadi_id.' data-id="' . $d->id . '">
                                <button class="btn btn-info"><i
                                class="far fa-eye"></i> Detail</button>
                            </a>';
                })
                ->addColumn('in', function ($d) {
                    return date('d F Y', strtotime($d->header->tgl_masuk));
                })
                ->addColumn('from', function ($d) {
                    return $d->header->darii->nama;
                })
                ->addColumn('tujuan', function ($d) {
                    return $d->header->deskripsi;
                })
                ->rawColumns(['action', 'nama_produk', 'jml'])
                ->make(true);
        } else {
            $data = TFProduksi::with('detail', 'darii')->where(['jenis' => 'masuk', 'status_id' => 1])->get();
            return datatables()->of($data)
                ->addColumn('in', function ($d) {
                    return date('d-m-Y', strtotime($d->tgl_masuk));
                })
                ->addColumn('from', function ($d) {
                    return $d->darii->nama;
                })
                ->addColumn('tujuan', function ($d) {
                    return $d->deskripsi;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-info"><i
                                class="far fa-eye"></i> Detail</button>
                            </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function getNoseriDraftRakit(Request $request) {
        $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id',$request->t_gbj_detail_id)->get();
        $layout = Layout::where('jenis_id', 1)->get();
        return datatables()->of($data)
            ->addColumn('serii', function($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function($d) use($layout) {
                $opt = '';
                foreach($layout as $l) {
                    $opt .= '<option value="'.$l->id.'">'.$l->ruang.'</option>';
                }
                return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                        ' . $opt . '
                        </select>';
            })
            ->addColumn('checkbox', function($d) {
                return '<input type="checkbox" class="cb-child-rancang" value="'.$d->id.'" data-id="'.$d->noseri_id.'">';
            })
            ->rawColumns(['checkbox', 'posisi'])
            ->make(true);
    }

    function terimaRakit() {
        $layout = Layout::where('jenis_id', 1)->get();
        return view('page.gbj.dp', compact('layout'));
    }

    function ceknoseri(Request $request) {
        $cek = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get()->count();
        $data = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get();
        $arr_seri = [];

        if(count($data) == 0) {
            return response()->json(['msg' => 'Noseri tersimpan']);
        } else {
            foreach($data as $d) {
                array_push($arr_seri, $d->noseri);
            }
            return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_seri) . ' sudah terdaftar']);
        }
    }

    function allTp() {
        $data1 = TFProduksi::with('pesanan')->where([
            ['jenis', '=','keluar'],
            ['status_id', '=', 2],
        ])->whereNotNull('pesanan_id')->get();
        return view('page.gbj.tp.tp', compact('data1'));
    }

    function exportSpb($id) {
        if (!$id) {
            LogSurat::create([
                'pesanan_id' => $id,
                'transfer_by' => Auth::user()->id
            ]);
        }
        $tfby = LogSurat::where('pesanan_id', $id)->get();
        $data = TFProduksiDetail::whereHas('header', function($q) use($id) {
            $q->where('pesanan_id', $id);
        })->with('seri.seri', 'produk.produk')->get();
        return view('page.gbj.reports.spb',['data' => $data, 'tfby' => $tfby]);
    }

    function getListSODone() {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);
        return $data;
    }

    // store
    function storeNoseri(Request $request)
    {
        foreach($request->noseri as $key => $value) {
            $detail = TFProduksiDetail::find($request->t_gbj_detail_id);
            $detail->status_id = 3;
            $detail->updated_at = Carbon::now();
            $detail->save();

            $header = TFProduksi::find($detail->t_gbj_id);
            $header->tgl_masuk = Carbon::now();
            $header->status_id = 3;
            $header->updated_at = Carbon::now();
            $header->save();

            $noserigbj = new NoseriBarangJadi();
            $noserigbj->gdg_barang_jadi_id = $detail->gdg_brg_jadi_id;
            $noserigbj->ke = $header->ke;
            $noserigbj->noseri = $value;
            $noserigbj->jenis = 'MASUK';
            $noserigbj->created_at = Carbon::now();
            $noserigbj->save();
        }

        $gdg = GudangBarangJadi::find($detail->gdg_brg_jadi_id);
        $gdg->stok = $gdg->stok + $detail->qty;
        $gdg->save();

        return response()->json(['msg', 'Successfully']);
    }

    function StoreBarangJadi(Request $request)
    {
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
                $brg_jadi->updated_by = $request->userid;
                $brg_jadi->save();

                $brg_his->gdg_brg_jadi_id = $brg_jadi->id;
                $brg_his->produk_id = $request->produk_id;
                $brg_his->satuan_id = $request->satuan_id;
                $brg_his->nama = $request->nama;
                $brg_his->deskripsi = $request->deskripsi;
                $brg_his->status = $request->status;
                $brg_his->created_at = Carbon::now();
                $brg_his->created_by = $request->userid;
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
                $brg_jadi->created_by = $request->userid;
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
                $brg_his->created_by = $request->userid;
                $brg_his->save();
            }
            return response()->json(['msg' => 'Successfully']);
    }

    function storeDraftRancang(Request $request)
    {
        $h = new TFProduksi();
        $h->tgl_masuk = $request->tgl_masuk;
        $h->dari = $request->dari;
        $h->deskripsi = $request->deskripsi;
        $h->status_id = 1;
        $h->jenis = 'masuk';
        $h->created_at = Carbon::now();
        $h->created_by = $request->userid;
        $h->save();

        foreach($request->data as $key => $value) {
            $d = new TFProduksiDetail();
            $d->t_gbj_id = $h->id;
            $d->gdg_brg_jadi_id = $key;
            $d->qty = $value['jumlah'];
            $d->status_id = 1;
            $d->jenis = 'masuk';
            $d->created_at = Carbon::now();
            $d->created_by = $request->userid;
            $d->save();

            foreach($value['noseri'] as $key1 => $value1) {
                $nn = new NoseriBarangJadi();
                $nn->gdg_barang_jadi_id = $key;
                $nn->dari = $request->dari;
                $nn->noseri = $value1;
                $nn->layout_id = $value['layout'][$key1];
                $nn->jenis = 'MASUK';
                $nn->is_aktif = 0;
                $nn->created_by = $request->userid;
                $nn->save();

                $n = new NoseriTGbj();
                $n->t_gbj_detail_id = $d->id;
                $n->noseri_id = $nn->id;
                $n->layout_id = $value['layout'][$key1];
                $n->jenis = 'masuk';
                $n->status_id = 1;
                $n->state_id = 2;
                $n->created_by = $request->userid;
                $n->save();
            }
        }

        return response()->json(['msg' => 'Successfully']);
    }

    function storeFinalRancang(Request $request)
    {
        // dd($request->all());
        $h = new TFProduksi();
        $h->tgl_masuk = $request->tgl_masuk;
        $h->dari = $request->dari;
        $h->deskripsi = $request->deskripsi;
        $h->status_id = 2;
        $h->jenis = 'masuk';
        $h->created_at = Carbon::now();
        $h->created_by = $request->userid;
        $h->save();

        foreach($request->data as $key => $value) {
            $d = new TFProduksiDetail();
            $d->t_gbj_id = $h->id;
            $d->gdg_brg_jadi_id = $key;
            $d->qty = $value['jumlah'];
            $d->status_id = 2;
            $d->jenis = 'masuk';
            $d->created_at = Carbon::now();
            $d->created_by = $request->userid;
            $d->save();

            foreach($value['noseri'] as $key1 => $value1) {
                $nn = new NoseriBarangJadi();
                $nn->gdg_barang_jadi_id = $key;
                $nn->dari = $request->dari;
                $nn->noseri = $value1;
                $nn->layout_id = $value['layout'][$key1];
                $nn->jenis = 'MASUK';
                $nn->is_aktif = 1;
                $nn->created_by = $request->userid;
                $nn->save();

                $n = new NoseriTGbj();
                $n->t_gbj_detail_id = $d->id;
                $n->noseri_id = $nn->id;
                $n->layout_id = $value['layout'][$key1];
                $n->jenis = 'masuk';
                $n->status_id = 2;
                $n->state_id = 3;
                $n->created_by = $request->userid;
                $n->save();
            }

            $gdg = GudangBarangJadi::whereIn('id', [$key])->get()->toArray();

            foreach ($gdg as $vv) {
                $vv['stok'] = $vv['stok'] + $value['jumlah'];

                GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok'], 'updated_by' => $request->userid]);
                GudangBarangJadiHis::create([
                    'gdg_brg_jadi_id' => $vv['id'],
                    'stok' => $value['jumlah'],
                    'tgl_masuk' => $request->tgl_masuk,
                    'jenis' => 'MASUK',
                    'created_by' => $request->userid,
                    'created_at' => Carbon::now(),
                    'dari' => $request->dari,
                    'tujuan' => $request->deskripsi,
                ]);
            }
        }

        return response()->json(['msg' => 'Successfully']);
    }

    function finalDraftRakit(Request $request) {
        $header = TFProduksi::find($request->id);

        $dd = TFProduksiDetail::where('t_gbj_id', $request->id)->get()->toArray();

        foreach($dd as $dd) {
            for ($i=0; $i < count($request->seri[$dd['gdg_brg_jadi_id']]); $i++) {
                NoseriTGbj::where('id', $request->seri[$dd['gdg_brg_jadi_id']][$i])->update(['status_id' => 2]);
                $a = NoseriTGbj::where('id', $request->seri[$dd['gdg_brg_jadi_id']][$i])->get()->toArray();
                foreach ($a as $a) {
                    echo NoseriBarangJadi::where('id',$a['noseri_id'])->get();
                    NoseriBarangJadi::where('id',$a['noseri_id'])->update(['is_aktif' => 1]);
                    $b = NoseriBarangJadi::whereIn('id',[$a['noseri_id']])->get()->toArray();
                    foreach($b as $b) {
                        $ac = GudangBarangJadi::where('id', $b['gdg_barang_jadi_id'])->get()->toArray();
                        foreach($ac as $vv) {
                            $vv['stok'] = $vv['stok'] + count($ac);
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => count($ac),
                                'tgl_masuk' => $header->tgl_masuk,
                                'jenis' => 'MASUK',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'dari' => $request->dari,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }
                    }

                }
            }
        }

        $header->status_id = 2;
        $header->updated_at = Carbon::now();
        $header->save();

        return response()->json(['msg' => 'Data Berhasil Diterima']);
    }

    function storeCekSO(Request $request) {
        $check_array = $request->gbj_id;
        $id = $request->pesanan_id;
        $h = Pesanan::find($request->pesanan_id);
        $dt = DetailPesanan::where('pesanan_id', $h->id)->get()->pluck('id')->toArray();
        foreach($request->gbj_id as $key => $value) {
            if (in_array($request->gbj_id[$key], $check_array)) {
                DetailPesananProduk::whereIn('detail_pesanan_id', $dt)->WhereIn('gudang_barang_jadi_id', $check_array)
                    ->update(['status_cek' => 4, 'checked_by' => $request->userid]);
            }
        }

        $cek = DetailPesananProduk::whereIn('detail_pesanan_id', $dt)->WhereIn('gudang_barang_jadi_id', $check_array)->where('status_cek',4)->get()->count();
        $cek_prd = DetailPesananProduk::whereIn('detail_pesanan_id', $dt)->WhereIn('gudang_barang_jadi_id', $check_array)->get()->count();
        if ($cek == $cek_prd) {
            $h->status_cek = 4;
            $h->checked_by = $request->userid;
            $h->save();
        }

        return response()->json(['msg' => 'Successfully']);
    }

    // select
    function select_layout()
    {
        $data = Layout::where('jenis_id', 1)->get();
        return response()->json($data);
    }

    function select_product()
    {
        $data = Produk::with('product')->get();
        return response()->json($data);
    }

    function select_product_by_id($id)
    {
        $data = Produk::with('product')->find($id);
        return response()->json($data);
    }

    function select_satuan()
    {
        $data = Satuan::all();
        return response()->json($data);
    }

    function select_divisi()
    {
        $data = Divisi::all();
        return response()->json($data);
    }

    function select_gbj()
    {
        $data = GudangBarangJadi::with('produk')->get();
        return response()->json($data);
    }

    // dashboard

    function getNoseriTerima(Request $request, $id) {
        $data = NoseriTGbj::whereHas('detail', function($q) use($id) {
            $q->where('t_gbj_detail_id', $id);
        })->get();
        return datatables()->of($data)
            ->addColumn('noser', function($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function($d) {
                if(isset($d->layout->ruang)) {
                    return $d->layout->ruang;
                } else {
                    return '-';
                }

            })
            ->make(true);
    }
    // produk
    function h1()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h2()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h3()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h4()
    {

        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
                })->get();

        return count($data);
    }

    function h5()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();

        return count($data);
    }

    function h6()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();

        return count($data);
    }

    function h7()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->subYear(3));
        })->get();

        return count($data);
    }

    function getProdukstok1020()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getProdukstok59()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal2" data-attr=""  data-id="' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getProdukstok14()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->rawColumns(['action', 'tgl_masuk'])
            ->make(true);
    }

    function getProdukIn36()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {

                    $a = Carbon::now()->diffInDays($d->header->tgl_masuk);

                    if ($a == 1) {
                        return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                    } else if ($a == 2) {
                        return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                    } else if ($a >= 3) {
                        return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->make(true);
    }

    function getProdukIn612()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->make(true);
    }

    function getProduk1236()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->make(true);
    }

    function getProduk36Plus()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->subYear(3));
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->make(true);
    }

    function getProdukByLayout(Request $request)
    {
        $data = GudangBarangJadi::with('produk', 'layout')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('layout', function ($d) {
                if (isset($d->layout_id)) {
                    return $d->layout->ruang;
                } else {
                    return '-';
                }
            })
            ->make(true);
    }

    // penerimaan
    function hh1()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::yesterday(), Carbon::now()]);
        })->get();
        return count($data);
    }

    function hh2()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereDate('tgl_masuk', '=', Carbon::now()->subDays(2));
        })->get();
        return count($data);
    }

    function hh3()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereDate('tgl_masuk', '<=', Carbon::now()->subDays(3));
        })->get();
        return count($data);
    }

    function getPenerimaanProduk1(Request $request)
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::yesterday(), Carbon::now()]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="'.$d->produk->produk->nama .'" data-var="'.$d->produk->nama.'" data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getPenerimaanProduk2(Request $request)
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereDate('tgl_masuk', '=', Carbon::now()->subDays(2));
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="'.$d->produk->produk->nama .'" data-var="'.$d->produk->nama.'" data-attr=""  data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getPenerimaanProduk3(Request $request)
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereDate('tgl_masuk', '<=', Carbon::now()->subDays(3));
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    $c = Carbon::now()->diffInDays($d->header->tgl_masuk);
                    return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="'.$d->produk->produk->nama .'" data-var="'.$d->produk->nama.'" data-attr=""  data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['action', 'tgl_masuk'])
            ->make(true);
    }

    function getPenerimaanAll()
    {
        $data = TFProduksiDetail::with('produk', 'header')->where('jenis', 'masuk')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if ($d->header->tgl_masuk) {

                    $a = Carbon::now()->diffInDays($d->header->tgl_masuk);

                    if ($a == 1) {
                        return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                    } else if ($a == 2) {
                        return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                    } else if ($a >= 3) {
                        return date('d-m-Y', strtotime($d->header->tgl_masuk)) . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }
                } else {
                    return date('d-m-Y', strtotime($d->header->tgl_masuk));
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="'.$d->produk->produk->nama .'" data-var="'.$d->produk->nama.'"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['action', 'tgl_masuk'])
            ->make(true);
    }

    // penjualan
    function he1()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
        ->whereBetween('tgl_kontrak', [Carbon::yesterday(), Carbon::now()])
        ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function he2()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
        ->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(2))
        ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function he3()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
        ->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(3))
        ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function list_tf1() {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
        ->whereBetween('tgl_kontrak', [Carbon::yesterday(), Carbon::now()])
        ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->Pesanan->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('tgl_batas', function($d) {
                if(isset($d->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->tgl_kontrak);
                    return $d->tgl_kontrak.'<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function () {
                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
            })
            ->addColumn('action', function ($d) {
                $x = $d->getTable();
                return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value='.$x.'  data-id="' . $d->pesanan_id . '">
                             <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                             </button>
                         </a>';
            })
            ->rawColumns(['action', 'status', 'tgl_batas'])
            ->make(true);
    }

    function list_tf2() {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
        ->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(2))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->Pesanan->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('tgl_batas', function($d) {
                if(isset($d->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->tgl_kontrak);
                    return $d->tgl_kontrak.'<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function () {
                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
            })
            ->addColumn('action', function ($d) {
                $x = $d->getTable();
                return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value='.$x.' data-id="' . $d->pesanan_id . '">
                             <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                             </button>
                         </a>';
            })
            ->rawColumns(['action', 'status', 'tgl_batas'])
            ->make(true);
    }

    function list_tf3()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
        ->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(3))
        ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->Pesanan->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('tgl_batas', function($d) {
                if(isset($d->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->tgl_kontrak);
                    return $d->tgl_kontrak.'<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function () {
                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
            })
            ->addColumn('action', function ($d) {
                $x = $d->getTable();
                return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value='.$x.'  data-id="' . $d->pesanan_id . '">
                             <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                             </button>
                         </a>';
            })
            ->rawColumns(['action', 'status', 'tgl_batas'])
            ->make(true);
    }

    function detailsale($id, $value) {
        if ($value == "ekatalog") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spa") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spb") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        }

        return datatables()->of($g)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama;
                } else {
                    return $data->gudangbarangjadi->produk->nama.' '.$data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                return $data->detailpesanan->jumlah. ' '.$data->gudangbarangjadi->satuan->nama;
            })
            ->addColumn('tipe', function ($data) {
                return $data->gudangbarangjadi->produk->nama;
            })
            ->addColumn('merk', function ($data) {
                return $data->gudangbarangjadi->produk->merk;
            })
            ->addColumn('button', function ($data) {
                return '<a type="button" class="noserishow" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    function outSO()
    {
        $data = DB::table('view_dashboard_produk_tdk_so')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function($d) {
                return $d->prd;
            })
            ->addColumn('permintaan', function($d) {
                return $d->jumlah;
            })
            ->addColumn('current_stok', function($d) {
                return $d->stok;
            })
            ->make(true);
    }

    function test(Request $request)
    {
        // list all so
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('pesanan_id', 8);
        })->with('seri.seri', 'header.pesanan', 'produk.produk')->get();
        return $data;
    }
}
