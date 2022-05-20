<?php

namespace App\Http\Controllers;

use App\Exports\GBJExportSPB;
use App\Exports\ImportNoseri;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GudangController extends Controller
{
    // get
    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get()->sortBy('produk.nama');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->produk->nama . ' ' . $data->nama;
            })
            ->addColumn('kode_produk', function ($data) {
                return $data->produk->product->kode . '' . $data->produk->kode;
            })
            ->addColumn('jumlah', function ($data) {
                $d = $data->get_sum_noseri();
                return $d . ' ' . $data->satuan->nama;
            })
            ->addColumn('jumlah1', function ($data) {
                $d = $data->get_sum_noseri();
                $ss = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                    return $d - $ss . ' ' . $data->satuan->nama;
            })
            ->addColumn('kelompok', function ($data) {
                return $data->produk->KelompokProduk->nama;
            })
            ->addColumn('merk', function($data){
                return $data->produk->merk;
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
                        </a>

                        <a data-toggle="modal" data-target="#stokmodal" class="stokmodal" data-attr=""  data-id="' . $data->id . '">
                            <button class="btn btn-outline-warning btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Daftar Stok
                            </button>
                        </a>';
            })
            ->addColumn('action_direksi', function ($data) {
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
        $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
            $d->where('id', $id);
        })->where('is_aktif', 1)->where('is_ready', 0)->get();
        $layout = Layout::where('jenis_id', 1)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('ids', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('Layout', function ($d) use ($layout) {
                $opt = '';
                foreach ($layout as $l) {
                    if ($d->layout_id == $l->id) {
                        $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                    } else {
                        $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                    }
                }
                return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                        ' . $opt . '
                        </select>';
            })
            ->addColumn('aksi', function ($d) {
                return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                        <button class="btn btn-outline-info btn-sm" type="button" >
                        <i class="far fa-eye"></i>&nbsp;Detail
                        </button>
                    </a>';
            })
            ->rawColumns(['ids', 'Layout', 'aksi'])
            ->make(true);
    }

    function getNoseriDone(Request $request, $id)
    {
        $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
            $d->where('id', $id);
        })->where('is_aktif', 1)->where('is_ready', 1)->get();
        $layout = Layout::where('jenis_id', 1)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('ids', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('Layout', function ($d) use ($layout) {
                $opt = '';
                foreach ($layout as $l) {
                    if ($d->layout_id == $l->id) {
                        $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                    } else {
                        $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                    }
                }
                return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                        ' . $opt . '
                        </select>';
            })
            ->addColumn('used', function($d) {
                return $d->pesanan->so;
            })
            ->addColumn('aksi', function ($d) {
                return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                        <button class="btn btn-outline-info btn-sm" type="button" >
                        <i class="far fa-eye"></i>&nbsp;Detail
                        </button>
                    </a>';
            })
            ->rawColumns(['ids', 'Layout', 'aksi'])
            ->make(true);
    }

    function getHistory($id)
    {
        $data = NoseriBarangJadi::with('from', 'to')->where('noseri', $id)->get()->unique(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($d) {
                return Carbon::parse($d->created_at)->isoFormat('dddd, D MMMM Y');
            })
            ->addColumn('dari', function ($d) {
                return $d->from->nama;
            })->make(true);
    }

    function getHistorybyProduk()
    {

        $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->has('TrxProduk')->get();
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
                return '<a class="btn btn-info" href="' . url('gbj/tp/' . $d->id . '') . '"><i
                        class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getAllTransaksi()
    {
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->get()->sortByDesc('header.tgl_keluar');
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMMM Y');
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function ($d) {
                if (isset($d->header->tgl_keluar)) {
                    return Carbon::parse($d->header->tgl_keluar)->isoFormat('D MMMM Y');
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
                if (isset($d->layout->ruang)) {
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
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

    function getDetailHistorySeri($id)
    {
        $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noser', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function ($d) {
                return $d->layout->ruang;
            })
            ->make(true);
    }

    function getDetailHistory1($id)
    {
        $header = GudangBarangJadi::with('produk')->where('id', $id)->first();
        $data = GudangBarangJadi::with('produk')->where('id', $id)->get();
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();
        return view('page.gbj.tp.show', compact('data', 'data1', 'header'));
    }

    function getRakit()
    {
        $data = TFProduksiDetail::with('header')
            ->leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
            ->select('*', DB::raw('sum(t_gbj_detail.qty) as total'))
            ->groupBy('tg.tgl_masuk')
            ->groupBy('t_gbj_detail.gdg_brg_jadi_id')
            ->where('tg.dari', 17)
            ->where('tg.ke', 13)
            ->get();

        $x = [];
        $y = [];
        foreach ($data as $k) {

            $jumlah_done = NoseriTGbj::whereHas('detail.header', function ($q) use ($k) {
                $q->where('dari', 17)->where('ke', 13);
                $q->where('tgl_masuk', $k->header->tgl_masuk);
            })
                ->whereHas('detail', function ($qq) use ($k) {
                    $qq->where('gdg_brg_jadi_id', $k->gdg_brg_jadi_id);
                })
                ->where('status_id', 3)
                ->with('detail.header')
                ->get()->count();

            $jumlah = NoseriTGbj::whereHas('detail.header', function ($q) use ($k) {
                $q->where('dari', 17)->where('ke', 13);
                $q->where('tgl_masuk', $k->header->tgl_masuk);
            })
                ->whereHas('detail', function ($qq) use ($k) {
                    $qq->where('gdg_brg_jadi_id', $k->gdg_brg_jadi_id);
                })
                ->with('detail.header')
                ->get()->count();
            if ($jumlah != $jumlah_done) {
                $x[] = $k->header->tgl_masuk;
                $y[] = $k->gdg_brg_jadi_id;
            }
        }

        $datax = TFProduksiDetail::with('header')
            ->leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
            ->select('*', DB::raw('sum(t_gbj_detail.qty) as total'))
            ->groupBy('tg.tgl_masuk')
            ->groupBy('t_gbj_detail.gdg_brg_jadi_id')
            ->where('tg.dari', 17)
            ->where('tg.ke', 13)
            ->whereIn('tg.tgl_masuk', $x)
            ->whereIn('t_gbj_detail.gdg_brg_jadi_id', $y)
            ->get();

        return datatables()->of($datax)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMMM Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->header->tgl_masuk)->where('ke', 13)->where('dari', 17);
                        // $a->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                return $d->total . ' ' . $d->produk->satuan->nama . '<br><span class="badge badge-dark"> Sisa Diterima ' . intval($d->total - $seri_done) . '</span>';
            })
            ->addColumn('action', function ($d) {
                $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->header->tgl_masuk);
                        $a->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                $seri = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->header->tgl_masuk);
                        $a->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->get()->count();
                // return $seri_done;
                if ($seri == $seri_done) {
                    // return 'a';
                    return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->header->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                } else {
                    // return 'b';
                    return  '
                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->header->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-produk="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->header->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-primary btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                            </a>

                           ';
                }
            })
            ->rawColumns(['action', 'jumlah'])
            ->make(true);
    }

    function getRakitNoseri($id, $value)
    {
        // $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->where('status_id', 3)->get();
        $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
            $q->where('gdg_brg_jadi_id', $id);
            $q->whereHas('header', function ($a) use ($value) {
                $a->where('tgl_masuk', $value);
            });
        })->where('status_id', 3)->where('jenis', 'masuk')->get();
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

    function getTerimaRakit($id, $value)
    {
        // $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->where('status_id', null)->get();
        $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
            $q->where('gdg_brg_jadi_id', $id);
            $q->whereHas('header', function ($a) use ($value) {
                $a->where('tgl_masuk', $value)->where('dari', 17)->where('ke', 13);
            });
        })->where('status_id', null)->where('jenis', 'masuk')->get();
        $layout = Layout::where('jenis_id', 1)->get();
        $a = 0;
        return datatables()->of($data)
            ->addColumn('layout', function ($d) use ($layout, $a) {
                $opt = '';

                foreach ($layout as $l) {
                    $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                }
                $a++;
                return '<select name="layout_id[]" id="layout_id" class="form-control layout">
                        ' . $opt . '
                        </select>';
            })
            ->addColumn('noserii', function ($d) {
                return $d->seri->noseri . '<input type="hidden" name="noseri[]" id="noseri[]" value="' . $d->seri->noseri . '">';
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
        // detail
        if ($request->id) {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->where('status_id', 1);
            })->with('header', 'produk', 'noseri')->where('t_gbj_id', $request->id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $d->gdg_brg_jadi_id . '">';
                })
                ->addColumn('jml', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama . '<input type="hidden" name="qty[]" id="qty" value="' . $d->qty . '"><input type="hidden" name="tfid[]" id="tfid" value="' . $d->id . '">';
                })
                ->addColumn('kode_prd', function ($d) {
                    return $d->gdg_brg_jadi_id;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#detail" class="detail" data-attr="" data-var="' . $d->produk->nama . '" data-nama="' . $d->produk->produk->nama . '" data-gbj=' . $d->gdg_brg_jadi_id . ' data-id="' . $d->id . '">
                                <button class="btn btn-info"><i
                                class="far fa-eye"></i> Detail</button>
                            </a>';
                })
                ->addColumn('in', function ($d) {
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM Y');
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
            #header awal
            $data = TFProduksi::with('detail', 'darii')->where(['jenis' => 'masuk', 'status_id' => 1])->get();
            return datatables()->of($data)
                ->addColumn('in', function ($d) {
                    return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM Y');
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

    function getNoseriDraftRakit(Request $request)
    {
        $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $request->t_gbj_detail_id)->get();
        $layout = Layout::where('jenis_id', 1)->get();
        return datatables()->of($data)
            ->addColumn('serii', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function ($d) use ($layout) {
                $opt = '';
                foreach ($layout as $l) {
                    if ($d->layout_id == $l->id) {
                        $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                    } else {
                        $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                    }
                }
                return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                        ' . $opt . '
                        </select>';
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child-rancang" value="' . $d->id . '" data-id="' . $d->noseri_id . '">';
            })
            ->rawColumns(['checkbox', 'posisi'])
            ->make(true);
    }

    function terimaRakit()
    {
        $layout = Layout::where('jenis_id', 1)->get();
        return view('page.gbj.dp', compact('layout'));
    }

    function ceknoseri(Request $request)
    {
        $data = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get();
        $arr_seri = [];

        if (count($data) == 0) {
            return response()->json(['msg' => 'Noseri tersimpan']);
        } else {
            foreach ($data as $d) {
                array_push($arr_seri, $d->noseri);
            }
            return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_seri) . ' sudah terdaftar']);
        }
    }

    function allTp()
    {
        $data1 = TFProduksi::with('pesanan')->where([
            ['jenis', '=', 'keluar'],
            ['status_id', '=', 2],
        ])->whereNotNull('pesanan_id')->get();
        return view('page.gbj.tp.tp', compact('data1'));
    }

    function getSODone()
    {
        $data = TFProduksi::with('pesanan')->where([
            ['jenis', '=', 'keluar'],
            ['status_id', '=', 2],
        ])->whereNotNull('pesanan_id')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noso', function ($d) {
                return $d->pesanan->so;
            })
            ->addColumn('nopo', function ($d) {
                return $d->pesanan->no_po;
            })
            ->addColumn('customer', function ($data) {
                $name = explode('/', $data->pesanan->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->pesanan->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->pesanan->Spb->Customer->nama;
                    }
                }
            })
            ->addColumn('tgl_kontrak', function ($d) {
                if (isset($d->pesanan->Ekatalog->tgl_kontrak)) {

                    if ($d->pesanan->Ekatalog->Provinsi->status == 1) {
                        // return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY');
                        return Carbon::parse($d->pesanan->Ekatalog->tgl_kontrak)->subWeeks(5)->format('d-m-Y');
                    }

                    if ($d->pesanan->Ekatalog->Provinsi->status == 2) {
                        // return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY');
                        return Carbon::parse($d->pesanan->Ekatalog->tgl_kontrak)->subWeeks(4)->format('d-m-Y');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('aksi', function ($d) {
                return 12;
                return '<td><a href="' . url('gbj/export_spb/' . $d->pesanan->id . '') . '">
                        <button class="btn btn-outline-primary"><i class="fas fa-print"></i> Cetak</button>
                        </a></td>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    // Export Excell
    function exportSpb($id)
    {
        $tfbyid = LogSurat::where('pesanan_id', $id)->get();
        if (count($tfbyid) > 0) {
            LogSurat::where('pesanan_id', $id)->update(['transfer_by' => Auth::user()->id]);
        } else {
            LogSurat::create([
                'pesanan_id' => $id,
                'transfer_by' => Auth::user()->id,
            ]);
        }
        $tfby = LogSurat::where('pesanan_id', $id)->get();
        $data = TFProduksiDetail::whereHas('header', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->with('seri.seri', 'produk.produk', 'paket.detailpesanan.penjualanproduk')->groupBy('detail_pesanan_produk_id')->groupBy('gdg_brg_jadi_id')->get();
        $header = TFProduksi::where('pesanan_id', $id)->with('pesanan')->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('page.gbj.reports.spb', ['data' => $data, 'tfby' => $tfby, 'header' => $header])->setPaper('a4', 'portrait');
        return $pdf->stream();
        // return response()->json(['data' => $data]);
        return view('page.gbj.reports.spb', ['data' => $data, 'tfby' => $tfby, 'header' => $header]);
    }

    function download_template_noseri(Request $request)
    {
        // return Excel::download(new ImportNoseri(), 'template_noseri.xls');
        // data
        $no = 1;

        $produk = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get()->sortBy('produk.nama');

        // spreadsheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->createSheet();

        // workshet noseri
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Noseri');
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'Noseri');

        $validation = $spreadsheet->getActiveSheet()->getCell('B2')
            ->getDataValidation();
        $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
        $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please pick a value from the drop-down list.');

        $validation->setFormula1('\'Produk\'!$C$2:$C$288');
        // $validation->setFormula1('"Item A,Item B,Item C"');
        $validation->setSqref('B2:B10000');

        // check duplicate input noseri
        $duplicate = new Conditional();
        $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
        $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
        $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
        $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

        $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->getConditionalStyles();
        $conditionalStyles[] = $duplicate;

        $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->setConditionalStyles($conditionalStyles);


        // workshet master
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('Produk');
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Merk');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Produk');
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        $noo = 2;
        foreach($produk as $p) {
            $spreadsheet->getActiveSheet()->setCellValue('A'. $noo, $p->id);
            $spreadsheet->getActiveSheet()->setCellValue('B'. $noo, $p->produk->merk);
            $spreadsheet->getActiveSheet()->setCellValue('C'. $noo, $p->produk->nama.' '.$p->nama);
            $noo++;
            $no++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Template Noseri.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    function import_noseri(Request $request)
    {
        $file = $request->file('file_csv');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();

        $file->move(public_path('upload/noseri/'), $filename);

        $reader = new ReaderXlsx();
        $spreadsheet = $reader->load(public_path('upload/noseri/'. $filename));
        $spreadsheet->setActiveSheetIndex(0);

        $sheet        = $spreadsheet->getActiveSheet();
        $row_limit    = $sheet->getHighestDataRow();
        $column_limit = $sheet->getHighestDataColumn();
        $row_range    = range( 2, $row_limit );
        $column_range = range( 'C', $column_limit );
        $startcount = 2;
        $data = array();
        foreach ( $row_range as $row ) {
            $data[] = [
                'no' =>$sheet->getCell( 'A' . $row )->getValue(),
                'produk' => $sheet->getCell( 'B' . $row )->getValue(),
                'noseri' => $sheet->getCell( 'C' . $row )->getValue()
            ];
            $startcount++;
        }

        foreach($data as $d) {
            $aa[] = $d['noseri'];
            $bb[] = $d['produk'];
        }

        $check = NoseriBarangJadi::whereIn('noseri', $aa)->get()->pluck('noseri');
        $seri = [];
        $sheet1 = $sheet->toArray(null, true, true, true);
        $numrow = 1;
        // $html = '<form name="formStoreImport" id="formStoreImport" method="post" enctype="multipart/form-data">';
        $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
        $html .= "<table class='table table-bordered table-striped table-hover'>
                <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Noseri</th>
                </tr>";
        foreach($sheet1 as $key => $row) {
            $a = $row['A'];
            $b = $row['B'];
            $c = $row['C'];
            if($numrow > 1) {
                $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                $html .= "<tr>";
                $html .= "<td" . $nis_td . ">" . $a . "</td>";
                $html .= "<td" . $nis_td . ">" . $b . "</td>";
                $html .= "<td" . $nis_td . ">" . $c . "</td>";
                $html .= "</tr>";
            }
            $numrow++;
        }
        $html .= "</table>";

        if(count($check) > 0) {
            foreach ($check as $item) {
                array_push($seri, $item);
            }
            return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri)]);
        } else {
            return response()->json(['msg' => 'Noseri Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
        }
    }

    function import_noseri_to_db(Request $request)
    {
        // $file = $request->file('namafile');
        // $filename = $file->getClientOriginalName();
        // $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        // $tempPath = $file->getRealPath();
        // $fileSize = $file->getSize();

        $reader = new ReaderXlsx();
        $spreadsheet = $reader->load(public_path('upload/noseri/'. $request->namafile));
        $spreadsheet->setActiveSheetIndex(0);

        $sheet        = $spreadsheet->getActiveSheet();
        $row_limit    = $sheet->getHighestDataRow();
        $column_limit = $sheet->getHighestDataColumn();
        $row_range    = range( 2, $row_limit );
        $column_range = range( 'C', $column_limit );
        $startcount = 2;
        $data = array();
        foreach ( $row_range as $row ) {
            $data[] = [
                'no' =>$sheet->getCell( 'A' . $row )->getValue(),
                'produk' => $sheet->getCell( 'B' . $row )->getValue(),
                'noseri' => $sheet->getCell( 'C' . $row )->getValue()
            ];
            $startcount++;
        }

        foreach($data as $d) {
            $aa[] = $d['noseri'];
            $bb[] = $d['produk'];
        }

        return $bb;
    }

    function getListSODone()
        {
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
        foreach ($request->noseri as $key => $value) {
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
        // dd($request->all());
        $h = new TFProduksi();
        $h->tgl_masuk = $request->tgl_masuk;
        $h->dari = $request->dari;
        $h->deskripsi = $request->deskripsi;
        $h->status_id = 1;
        $h->jenis = 'masuk';
        $h->created_at = Carbon::now();
        $h->created_by = $request->userid;
        $h->save();

        foreach ($request->data as $key => $value) {
            $d = new TFProduksiDetail();
            $d->t_gbj_id = $h->id;
            $d->gdg_brg_jadi_id = $key;
            $d->qty = $value['jumlah'];
            $d->status_id = 1;
            $d->jenis = 'masuk';
            $d->created_at = Carbon::now();
            $d->created_by = $request->userid;
            $d->save();

            foreach ($value['noseri'] as $key1 => $value1) {
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
        $h = new TFProduksi();
        $h->tgl_masuk = $request->tgl_masuk;
        $h->dari = $request->dari;
        $h->deskripsi = $request->deskripsi;
        $h->status_id = 2;
        $h->jenis = 'masuk';
        $h->created_at = Carbon::now();
        $h->created_by = $request->userid;
        $h->save();

        foreach ($request->data as $key => $value) {
            $d = new TFProduksiDetail();
            $d->t_gbj_id = $h->id;
            $d->gdg_brg_jadi_id = $key;
            $d->qty = $value['jumlah'];
            $d->status_id = 2;
            $d->jenis = 'masuk';
            $d->created_at = Carbon::now();
            $d->created_by = $request->userid;
            $d->save();

            foreach ($value['noseri'] as $key1 => $value1) {
                $nn = new NoseriBarangJadi();
                $nn->gdg_barang_jadi_id = $key;
                $nn->dari = $request->dari;
                $nn->noseri = strtoupper($value1);
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

    function finalDraftRakit(Request $request)
    {
        // dd($request->all());
        foreach ($request->seri as $k => $v) {
            // echo TFProduksiDetail::where('id', $k)->get();
            // echo NoseriTGbj::where('t_gbj_detail_id', $k)->get();
            foreach ($v['data'] as $kk => $vv) {
                NoseriTGbj::where('id', $vv['noseri'])->update(['layout_id' => $vv['layout'], 'status_id' => 2]);
                $a = NoseriTGbj::where('id', $vv['noseri'])->get();
                foreach ($a as $a) {
                    NoseriBarangJadi::where('id', $a->noseri_id)->update(['is_aktif' => 1, 'layout_id' => $vv['layout']]);
                    $b =  NoseriBarangJadi::where('id', $a->noseri_id)->get();
                    foreach ($b as $b) {
                        $c =  GudangBarangJadi::where('id', $b->gdg_barang_jadi_id)->first();
                        foreach ($c as $c) {
                            $stok = $c->stok +  $v['jumlah'];
                            // echo $stok;
                            GudangBarangJadi::where('id', $c['id'])->update(['stok' => $stok]);
                        }
                    }
                }
            }
        }

        $header = TFProduksi::find($request->id);

        $header->status_id = 2;
        $header->updated_at = Carbon::now();
        $header->save();

        return response()->json(['msg' => 'Data Berhasil Diterima']);
    }

    function storeCekSO(Request $request)
    {
        $h = Pesanan::find($request->pesanan_id);
        $dt = DetailPesanan::where('pesanan_id', $h->id)->get()->pluck('id')->toArray();
        foreach ($request->data as $key => $value) {
            DetailPesananProduk::whereIn('id', $value)->whereIn('gudang_barang_jadi_id', [$key])
                ->update(['status_cek' => 4, 'checked_by' => $request->userid]);
        }

        // $cek = DetailPesananProduk::whereIn('detail_pesanan_id', $dt)->where('status_cek', 4)->get()->count();
        // $cek_prd = DetailPesananProduk::whereIn('detail_pesanan_id', $dt)->get()->count();
        // if ($cek == $cek_prd) {
            $h->status_cek = 4;
            $h->checked_by = $request->userid;
            $h->log_id = 6;
            $h->save();
        // }

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

    function getNoseriTerima(Request $request, $id)
    {
        $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
            $q->where('t_gbj_detail_id', $id);
        })->get();
        return datatables()->of($data)
            ->addColumn('noser', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function ($d) {
                if (isset($d->layout->ruang)) {
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
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                    } else if ($a == 2) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                    } else if ($a >= 3) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
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
        $data = NoseriBarangJadi::select('*', DB::raw('count(layout_id) as jumlah'))
                ->groupBy('gdg_barang_jadi_id')
                ->groupBy('layout_id')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->gudang->produk->nama . ' ' . $d->gudang->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->gudang->satuan->nama;
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
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
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-id="' . $d->id . '">
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
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
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '">
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
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
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
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '">
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
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                    } else if ($a == 2) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                    } else if ($a >= 3) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }
                } else {
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '"  data-id="' . $d->id . '">
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
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function($q) {
            $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(1));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function he2()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function($q) {
            $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(2));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function he3()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(3));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function list_tf1()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function($q) {
            $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(1));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else { }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('tgl_batas', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                    if ($d->Ekatalog->Provinsi->status == 1) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }

                    if ($d->Ekatalog->Provinsi->status == 2) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }

                } else {
                    return '-';
                }
            })
            ->addColumn('status_penjualan', function ($data) {
                if ($data->log_id) {
                    return '<span class="badge badge-light">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    }
                }
            })
            ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
            ->make(true);
    }

    function list_tf2()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function($q) {
            $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(2));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else { }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('tgl_batas', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                    if ($d->Ekatalog->Provinsi->status == 1) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }

                    if ($d->Ekatalog->Provinsi->status == 2) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }

                } else {
                    return '-';
                }
            })
            ->addColumn('status_penjualan', function ($data) {
                if ($data->log_id) {
                    return '<span class="badge badge-light">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    }
                }
            })
            ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
            ->make(true);
    }

    function list_tf3()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(3));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else { }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('tgl_batas', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                    if ($d->Ekatalog->Provinsi->status == 1) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }

                    if ($d->Ekatalog->Provinsi->status == 2) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }

                } else {
                    return '-';
                }
            })
            ->addColumn('status_penjualan', function ($data) {
                if ($data->log_id) {
                    return '<span class="badge badge-light">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                    <button class="btn btn-outline-primary" type="button" >
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    }
                }
            })
            ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
            ->make(true);
    }

    function detailsale($id, $value)
    {
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
                    return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                return $data->detailpesanan->jumlah . ' ' . $data->gudangbarangjadi->satuan->nama;
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
            ->addColumn('produk', function ($d) {
                return $d->prd;
            })
            ->addColumn('permintaan', function ($d) {
                return $d->jumlah;
            })
            ->addColumn('current_stok', function ($d) {
                return $d->stok;
            })
            ->make(true);
    }

    function test(Request $request)
    {
        $jumlah = NoseriTGbj::whereHas('detail.header', function ($q) use ($request) {
            $q->where('dari', 17);
            $q->where('tgl_masuk', $request->tgl);
        })
            ->whereHas('detail', function ($qq) use ($request) {
                $qq->where('gdg_brg_jadi_id', $request->gbj);
            })
            ->where('status_id', 3)
            ->with('detail.header')
            ->get();
        return $jumlah;
    }

    function updateSeriLayout(Request $request)
    {
        $data = NoseriBarangJadi::whereIn('id', $request->cekid)->get();
        foreach ($data as $d) {
            for ($i = 0; $i < count($request->layout); $i++) {
                NoseriBarangJadi::where('id', $request->cekid[$i])->update(['layout_id' => json_decode($request->layout[$i], true)]);
            }
        }
    }

    function addSeri(Request $request)
    {
        $count = count($request->no_seri);
        for ($i = 0; $i < $count; $i++) {
            NoseriBarangJadi::create([
                'noseri' => strtoupper($request->no_seri[$i]),
                'layout_id' => $request->layout[$i],
                'gdg_barang_jadi_id' => $request->id,
                'dari' => $request->dari,
                'created_by' => $request->created_by,
                'jenis' => 'MASUK',
                'is_aktif' => 1
            ]);
        }

        $a = GudangBarangJadi::where('id', $request->id)->first();
        $stok = $a->stok + $count;
        // return $stok;
        GudangBarangJadi::where('id', $request->id)->update(['stok' => $stok]);
        GudangBarangJadiHis::create([
            'gdg_brg_jadi_id' => $request->id,
            'stok' => $count,
            'tgl_masuk' => Carbon::now(),
            'jenis' => 'MASUK',
            'created_by' => $request->created_by,
            'created_at' => Carbon::now(),
            'dari' => $request->dari,
        ]);
        return response()->json(['success' => 'Sukses']);
    }

    function cekReadySeri(Request $request)
    {
        $data = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get();
        // return
        // dd($request->all());
    }

    function dataTesting()
    {
        $data = storage_path('json/testing.json');
        $json = json_decode(file_get_contents($data), true);
        return $json;
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '35';
        } else {
            $days = '28';
        }
        return Carbon::parse($value)->subDays($days);
    }

     function TfbySOFinal(Request $request)
    {
        // dd($request->data);
        $a = TFProduksi::where('pesanan_id', $request->pesanan_id)->first();
        if ($a) {
            foreach ($request->data as $key => $values) {
                $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $values['prd'])->where('detail_pesanan_produk_id', $key)->first();
                if ($c) {
                    // return 'aa';
                    foreach ($values['noseri'] as $k => $v) {
                        NoseriTGbj::create([
                            't_gbj_detail_id' => $c->id,
                            'noseri_id' => $v,
                            'status_id' => 2,
                            'state_id' => 8,
                            'jenis' => 'keluar',
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                    }

                    $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                    $i = 0;
                    foreach ($gdg as $vv) {
                        $vv['stok'] = $vv['stok'] - $values['jumlah'];
                        // print_r($vv['stok']);
                        $i++;
                        GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        GudangBarangJadiHis::create([
                            'gdg_brg_jadi_id' => $vv['id'],
                            'stok' => $values['jumlah'],
                            'tgl_masuk' => Carbon::now(),
                            'jenis' => 'KELUAR',
                            'created_by' => $request->userid,
                            'created_at' => Carbon::now(),
                            'ke' => 23,
                            'tujuan' => $request->deskripsi,
                        ]);
                    }
                } else {
                        $dd = TFProduksiDetail::create([
                            't_gbj_id' => $a->id,
                            'detail_pesanan_produk_id' => $key,
                            'gdg_brg_jadi_id' => $values['prd'],
                            'qty' => $values['jumlah'],
                            'jenis' => 'keluar',
                            'status_id' => 2,
                            'state_id' => 8,
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        $did = $dd->id;
                        $checked = $request->noseri_id;
                        foreach ($values['noseri'] as $k => $v) {
                                NoseriTGbj::create([
                                    't_gbj_detail_id' => $did,
                                    'noseri_id' => $v,
                                    'status_id' => 2,
                                    'state_id' => 8,
                                    'jenis' => 'keluar',
                                    'created_at' => Carbon::now(),
                                    'created_by' => $request->userid
                                ]);

                                NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - $values['jumlah'];
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => $values['jumlah'],
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }
                }
            }
        } else {
            // return 'b';
            $d = TFProduksi::create([
                'pesanan_id' => $request->pesanan_id,
                'tgl_keluar' => Carbon::now(),
                'ke' => 23,
                'jenis' => 'keluar',
                'status_id' => 2,
                'state_id' => 8,
                'created_at' => Carbon::now(),
                'created_by' => $request->userid
            ]);

            $hid = $d->id;
            foreach ($request->data as $key1 => $value1) {
                $dd = TFProduksiDetail::create([
                    't_gbj_id' => $hid,
                    'detail_pesanan_produk_id' => $key1,
                    'gdg_brg_jadi_id' => $value1['prd'],
                    'qty' => $value1['jumlah'],
                    'jenis' => 'keluar',
                    'status_id' => 2,
                    'state_id' => 8,
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                $did = $dd->id;
                $checked = $request->noseri_id;
                foreach ($value1['noseri'] as $k => $v) {
                    NoseriTGbj::create([
                        't_gbj_detail_id' => $did,
                        'noseri_id' => $v,
                        'status_id' => 2,
                        'state_id' => 8,
                        'jenis' => 'keluar',
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                }

                $gdg = GudangBarangJadi::whereIn('id', [$key1])->get()->toArray();
                $i = 0;
                foreach ($gdg as $vv) {
                    $vv['stok'] = $vv['stok'] - $value1['jumlah'];
                    // print_r($vv['stok']);
                    $i++;
                    GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                    GudangBarangJadiHis::create([
                        'gdg_brg_jadi_id' => $vv['id'],
                        'stok' => $value1['jumlah'],
                        'tgl_masuk' => Carbon::now(),
                        'jenis' => 'KELUAR',
                        'created_by' => $request->userid,
                        'created_at' => Carbon::now(),
                        'ke' => 23,
                        'tujuan' => $request->deskripsi,
                    ]);
                }
            }
        }

        $po = Pesanan::find($request->pesanan_id);

        if ($po->getJumlahPesanan() == $po->cekJumlahkirim()) {
            Pesanan::find($request->pesanan_id)->update(['log_id' => 8]);
        } else {
            Pesanan::find($request->pesanan_id)->update(['log_id' => 6]);
        }

        return response()->json(['msg' => 'Data Terkirim ke QC']);
    }
}
