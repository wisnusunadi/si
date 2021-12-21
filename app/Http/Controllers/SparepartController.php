<?php

namespace App\Http\Controllers;

use App\Exports\ProdukGKExport;
use App\Exports\TransaksiGKExport;
use App\Models\GudangBarangJadi;
use App\Models\GudangKarantina;
use App\Models\GudangKarantinaDetail;
use App\Models\GudangKarantinaNoseri;
use App\Models\Layout;
use App\Models\NoseriKeluarGK;
use App\Models\Sparepart;
use App\Models\SparepartGudang;
use App\Models\SparepartHis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class SparepartController extends Controller
{
    // get
    // produk spr
    function get()
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        return datatables()->of($data)
            ->addColumn('kode', function ($d) {
                return $d->sparepart->spare->kode;
            })
            ->addColumn('produk', function ($d) {
                return $d->sparepart->nama;
            })
            ->addColumn('unit', function ($d) {
                return '-';
            })
            ->addColumn('jml', function ($d) {
                return $d->jml . ' pcs';
            })
            ->addColumn('button', function ($d) {
                return '<a class="btn btn-outline-info" href="' . url('gk/gudang/sparepart/' . $d->sparepart_id . '') . '"><i
                class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    // produk unit
    function get_unit()
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->units->produk->nama . ' ' . $data->units->nama;
            })
            ->addColumn('kode_produk', function ($data) {
                return $data->units->produk->product->kode . '' . $data->units->produk->kode;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jml . ' ' . $data->units->satuan->nama;
            })
            ->addColumn('kelompok', function ($data) {
                return $data->units->produk->KelompokProduk->nama;
            })
            ->addColumn('button', function ($d) {
                return '<a class="btn btn-outline-info" href="' . url('gk/gudang/unit/' . $d->gbj_id . '') . '"><i
                class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    // detail
    function detail_spr($id)
    {
        $header = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->where('m_gs.id', $id)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        $layout = Layout::where('jenis_id', 2)->get();
        return view('page.gk.gudang.sparepartEdit', compact('header', 'layout'));
    }

    function detail_unit($id)
    {
        $header1 = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi', 'produk.nama as nama', 'm_satuan.nama as satuan')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->where('gdg_barang_jadi.id', $id)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->join('m_satuan', 'm_satuan.id', 'gdg_barang_jadi.satuan_id')
            ->get();

        $layout = Layout::where('jenis_id', 1)->get();
        $seri = GudangKarantinaNoseri::find($id);
        return view('page.gk.gudang.unitEdit', compact('header1', 'layout', 'seri'));
    }

    function history_spr($id)
    {
        $cek1 = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($id) {
            $q->where('sparepart_id', $id)->where('is_draft', 0);
        })->get();
        $cek = NoseriKeluarGK::whereHas('detail', function ($q) use ($id) {
            $q->where('sparepart_id', $id)->where('is_draft', 0);
        })->get();
        $data = $cek->merge($cek1);
        return datatables()->of($data)
            ->addColumn('inn', function ($d) {
                if (empty($d->detail->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_in));
                }
            })
            ->addColumn('out', function ($d) {
                if (empty($d->detail->header->date_out)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_out));
                }
            })
            ->addColumn('from', function ($d) {
                if (empty($d->detail->header->dari)) {
                    return '-';
                } else {
                    return '<span class="badge badge-success">' . $d->detail->header->from->nama . '</span>';
                }
            })
            ->addColumn('to', function ($d) {
                if (empty($d->detail->header->ke)) {
                    return '-';
                } else {
                    return '<span class="badge badge-info">' . $d->detail->header->to->nama . '</span>';
                }
            })
            ->addColumn('noser', function ($d) {
                if($d->seri) {
                    return $d->seri->noseri;
                } else {
                    return $d->noseri;
                }
            })
            ->addColumn('layout', function ($d) {
                if($d->seri) {
                    return $d->seri->layout->ruang;
                } else {
                    if (empty($d->layout->ruang)) {
                        return '-';
                    } else {
                        return $d->layout->ruang;
                    }
                }

            })
            ->addColumn('remarks', function ($d) {
                if($d->seri) {
                    return $d->seri->remark;
                } else {
                    if (empty($d->remark)) {
                        return '-';
                    } else {
                        return $d->remark;
                    }
                }

            })
            ->addColumn('tingkat', function ($d) {
                if($d->seri) {
                    return 'Level ' . $d->seri->tk_kerusakan;
                } else {
                    return 'Level ' . $d->tk_kerusakan;
                }

            })
            ->addColumn('status', function ($d) {
                if($d->seri) {
                    return '<span class="sudah_ditransfer">Sudah Ditransfer</span>';
                } else {
                    if ($d->status == 0) {
                        return '<span class="belum_diterima">Belum Diperbaiki</span>';
                    } else {
                        return '<span class="sudah_diterima">Sudah Diperbaiki</span>';
                    }
                }

            })
            ->addColumn('action', function ($d) {
                if($d->seri) {
                    return ' ';
                } else {
                    return '<a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr=""  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info"><i class="far fa-edit"></i></button>
                        </a>';
                }

            })
            ->rawColumns(['status', 'action', 'from', 'to'])
            ->make(true);
    }

    function headerSeri($id)
    {
        $d = GudangKarantinaNoseri::find($id);
        return response()->json([
            'noser' => $d->noseri,
            'in' => $d->detail->header->date_in ? date('d-m-Y', strtotime($d->detail->header->date_in)) : '-',
            'out' => $d->detail->header->date_out ? date('d-m-Y', strtotime($d->detail->header->date_out)) : '-'
        ]);
    }

    function history_unit($id)
    {
        $cek1 = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($id) {
            $q->where('gbj_id', $id)->where('is_draft', 0);
        })->get();
        $cek = NoseriKeluarGK::whereHas('detail', function ($q) use ($id) {
            $q->where('gbj_id', $id)->where('is_draft', 0);
        })->get();
        $data = $cek->merge($cek1);
        return datatables()->of($data)
            ->addColumn('inn', function ($d) {
                if (empty($d->detail->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_in));
                }
            })
            ->addColumn('out', function ($d) {
                if (empty($d->detail->header->date_out)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_out));
                }
            })
            ->addColumn('from', function ($d) {
                if (empty($d->detail->header->dari)) {
                    return '-';
                } else {
                    return '<span class="badge badge-success">' . $d->detail->header->from->nama . '</span>';
                }
            })
            ->addColumn('to', function ($d) {
                if (empty($d->detail->header->ke)) {
                    return '-';
                } else {
                    return '<span class="badge badge-info">' . $d->detail->header->to->nama . '</span>';
                }
            })
            ->addColumn('noser', function ($d) {
                if($d->seri) {
                    return $d->seri->noseri;
                } else {
                    return $d->noseri;
                }
            })
            ->addColumn('layout', function ($d) {
                if($d->seri) {
                    return $d->seri->layout->ruang;
                } else {
                    if (empty($d->layout->ruang)) {
                        return '-';
                    } else {
                        return $d->layout->ruang;
                    }
                }

            })
            ->addColumn('remarks', function ($d) {
                if($d->seri) {
                    return $d->seri->remark;
                } else {
                    if (empty($d->remark)) {
                        return '-';
                    } else {
                        return $d->remark;
                    }
                }

            })
            ->addColumn('tingkat', function ($d) {
                if($d->seri) {
                    return 'Level ' . $d->seri->tk_kerusakan;
                } else {
                    return 'Level ' . $d->tk_kerusakan;
                }

            })
            ->addColumn('status', function ($d) {
                if($d->seri) {
                    return '<span class="sudah_ditransfer">Sudah Ditransfer</span>';
                } else {
                    if ($d->status == 0) {
                        return '<span class="belum_diterima">Belum Diperbaiki</span>';
                    } else {
                        return '<span class="sudah_diterima">Sudah Diperbaiki</span>';
                    }
                }

            })
            ->addColumn('action', function ($d) {
                if($d->seri) {
                    return ' ';
                } else {
                    return '<a data-toggle="modal" data-target="#unitmodal" class="unitmodal" data-attr=""  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info"><i class="far fa-edit"></i></button>
                        </a>';
                }

            })
            ->rawColumns(['status', 'action', 'from', 'to'])
            ->make(true);
    }

    // draft
    function get_draft_tf()
    {
        $data = GudangKarantina::with('from', 'to')->where('is_draft', 1)->where('is_keluar', 1)->get();
        return datatables()->of($data)
            ->addColumn('out', function ($d) {
                if (isset($d->date_out)) {
                    return date('d-m-Y', strtotime($d->date_out));
                } else {
                    return '-';
                }
            })
            ->addColumn('in', function ($d) {
                if (isset($d->date_in)) {
                    return date('d-m-Y', strtotime($d->date_in));
                } else {
                    return '-';
                }
            })
            ->addColumn('too', function ($d) {
                if (isset($d->ke)) {
                    return $d->to->nama;
                } else {
                    return '-';
                }
            })
            ->addColumn('from', function ($d) {
                if (isset($d->dari)) {
                    return $d->from->nama;
                } else {
                    return '-';
                }
            })

            ->addColumn('aksi', function ($d) {
                return '<a href="' . url('gk/transfer/' . $d->id . '') . '" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function edit_tf($id)
    {
        $data = GudangKarantina::where('id', $id)->get();
        return view('page.gk.transfer.edit', compact('data'));
    }

    function get_draft_terima()
    {
        $data = GudangKarantina::with('from', 'to')->where('is_draft', 1)->where('is_keluar', 0)->get();
        return datatables()->of($data)
            ->addColumn('out', function ($d) {
                if (isset($d->date_out)) {
                    return date('d-m-Y', strtotime($d->date_out));
                } else {
                    return '-';
                }
            })
            ->addColumn('in', function ($d) {
                if (isset($d->date_in)) {
                    return date('d-m-Y', strtotime($d->date_in));
                } else {
                    return '-';
                }
            })
            ->addColumn('too', function ($d) {
                if (isset($d->ke)) {
                    return $d->to->nama;
                } else {
                    return '-';
                }
            })
            ->addColumn('from', function ($d) {
                if (isset($d->dari)) {
                    return $d->from->nama;
                } else {
                    return '-';
                }
            })

            ->addColumn('aksi', function ($d) {
                return '<a href="' . url('gk/terimaProduk/' . $d->id . '') . '" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function edit_terima($id)
    {
        $did = GudangKarantina::find($id);
        return view('page.gk.terima.edit', compact('did'));
    }

    // history_trx
    function historyAll(Request $request)
    {
        $data = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk', 'header.from', 'header.to')->where('is_draft', 0)->get();
        return datatables()->of($data)
            ->addColumn('jenis', function ($d) {
                if (empty($d->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('tanggal', function ($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">' . date('d-m-Y', strtotime($d->header->date_out)) . '</span>';
                } else {
                    return '<span class="badge badge-success">' . date('d-m-Y', strtotime($d->header->date_in)) . '</span>';
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">' . $d->header->to->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->from->nama . '</span>';
                }
            })
            ->addColumn('unitt', function ($d) {
                return '-';
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->addColumn('tujuan', function ($d) {
                if (empty($d->header->deskripsi)) {
                    return '-';
                } else {
                    return $d->header->deskripsi;
                }
            })
            ->addColumn('aksi', function ($d) {
                if (empty($d->gbj_id)) {
                    return ' <a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr="" data-produk="' . $d->sparepart->nama . '"  data-id="' . $d->id . '">
                                <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                            </a>';
                } else {
                    return ' <a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr="" data-produk="' . $d->units->produk->nama . ' ' . $d->units->nama . '"  data-id="' . $d->id . '">
                                <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                            </a>';
                }
            })
            ->rawColumns(['tanggal', 'aksi', 'divisi'])
            ->make(true);
    }

    function get_noseri_history($id)
    {
        $cek1 = GudangKarantinaNoseri::with('detail')->where('gk_detail_id', $id)->get();
        $cek = NoseriKeluarGK::where('gk_detail_id', $id)->get();
        $data = $cek1->merge($cek);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noser', function ($d) {
                if($d->seri) {
                    return $d->seri->noseri;
                } else {
                    return $d->noseri;
                }

            })
            ->addColumn('rusak', function ($d) {
                if($d->seri) {
                    return $d->seri->remark;
                } else {
                    return $d->remark;
                }
            })
            ->addColumn('layout', function ($d) {
                if($d->seri) {
                    return $d->seri->layout->ruang;
                } else {
                    if (empty($d->layout_id)) {
                        return '-';
                    } else {
                        return $d->layout->ruang;
                    }

                }
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->make(true);
    }

    function exportTransaksi(Request $request) {
        return Excel::download(new TransaksiGKExport(), 'transaksi.xlsx');
    }

    function exportProduk() {
        return Excel::download(new ProdukGKExport(), 'produk.xlsx');
    }

    function history_by_produk(Request $request)
    {
        $data = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk')->where('is_draft', 0)->groupBy('sparepart_id')->groupBy('gbj_id')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($d) {
                if (empty($d->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('kode', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->spare->kode;
                } else {
                    return $d->units->produk->kode;
                }
            })
            ->addColumn('aksi', function ($d) {
                if (empty($d->gbj_id)) {
                    return '<a class="btn btn-info" href="' . url('gk/transaksi/' . $d->sparepart_id . '') . '" data-id="' . $d->sparepart_id . '"><i
                    class="far fa-eye"></i> Detail</a>';
                } else {
                    return '<a class="btn btn-info" href="' . url('gk/transaksi/' . $d->gbj_id . '') . '" data-id="' . $d->gbj_id . '"><i
                    class="far fa-eye"></i> Detail</a>';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function detail_trx($id)
    {
        $d = GudangKarantinaDetail::where('sparepart_id', $id)->orWhere('gbj_id', $id)->where('is_draft', 0)->limit(1)->get();
        $data = GudangKarantina::select(DB::raw("distinct(date_format(date_out, '%Y')) as tahun"))->distinct()->get();
        return view('page.gk.transaksi.show', compact('d', 'data'));
    }

    function get_detail_id($id)
    {
        $d = GudangKarantinaDetail::where('sparepart_id', $id)->orWhere('gbj_id', $id)->where('is_draft', 0)->get();
        foreach ($d as $d) {
            if (empty($d->gbj_id)) {
                $p = SparepartGudang::find($d->sparepart_id);
                $res_p = [
                    'kode' => $p->spare->kode ? $p->spare->kode : '-',
                    'nama' => $p->spare->nama,
                    'desk' => $p->deskripsi,
                    'panjang' => $p->dim_p,
                    'lebar' => $p->dim_l,
                    'tinggi' => $p->dim_t,
                ];
            } else {
                $p = GudangBarangJadi::find($d->gbj_id);
                $res_p = [
                    'kode' => $p->produk->kode ? $p->produk->kode : '-',
                    'nama' => $p->produk->nama . ' ' . $p->nama,
                    'desk' => $p->deskripsi,
                    'panjang' => $p->dim_p,
                    'lebar' => $p->dim_l,
                    'tinggi' => $p->dim_t,
                ];
            }
        }

        return $res_p;
    }

    function get_detail_id1(Request $request)
    {
        $d = GudangKarantinaNoseri::find($request->id);
        return response()->json([
            'id' => $d->id,
            'layout' => $d->layout_id,
            'note' => $d->remark,
            'tingkat' => $d->tk_kerusakan,
        ]);
    }

    function get_trx($id)
    {
        $cek = GudangKarantinaDetail::where('sparepart_id', $id)->where('is_draft', 0)->orWhere('gbj_id', $id)->get();
        return datatables()->of($cek)
            ->addColumn('tanggal', function ($d) {
                if (empty($d->header->date_in)) {
                    return date('d-m-Y', strtotime($d->header->date_out));
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">' . $d->header->to->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->from->nama . '</span>';
                }
            })
            ->addColumn('tujuan', function ($d) {
                if (empty($d->header->deskripsi)) {
                    return '-';
                } else {
                    return $d->header->deskripsi;
                }
            })
            ->addColumn('jml', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->addColumn('aksi', function ($d) {
                return '<button type="button" class="btn btn-outline-info" id="btnDetail"
                            data-id="' . $d->id . '"><i class="far fa-eye"> Detail</i></button>';
            })
            ->rawColumns(['aksi', 'divisi'])
            ->make(true);
    }

    function get_trx_tahun()
    {
        $data = GudangKarantina::select(DB::raw("distinct(date_format(date_out, '%Y')) as tahun"))->distinct()->get();
        return response()->json($data);
    }

    public function grafik_trf(Request $request)
    {
        $data = [];
        $keluar = DB::table('v_gk_keluar')
                ->where([
                    ['tahun', '=', $request->tahun],
                    ['gbj_id', '=', $request->id]
                ])
                ->orWhere([
                    ['tahun', '=', $request->tahun],
                    ['sparepart_id', '=', $request->id]
                ])
               ->get();
        foreach ($keluar as $a) {
            $data[] = [
                'bulan' => $a->bulan,
                'jumlah' => $a->jumlah == null ? 0 : $a->jumlah,
            ];
        }

        $msk = [];
        $masuk = DB::table('v_gk_masuk')
               ->where([
                   ['tahun', '=', $request->tahun],
                   ['gbj_id', '=', $request->id]
               ])
               ->orWhere([
                   ['tahun', '=', $request->tahun],
                   ['sparepart_id', '=', $request->id]
               ])
              ->get();
        foreach ($masuk as $b) {
            $msk[] = [
                'bulan' => $b->bulan,
                'jumlah' => $b->jumlah == null ? 0 : $b->jumlah,
            ];
        }

        return response()->json([
            'data' => $data,
            'masuk' => $msk,
        ]);
    }

    function getSeriDoneSpr(Request $request)
    {
        $data = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($request) {
            $q->where('sparepart_id', $request->sparepart_id);
        })->where('status', 1)->where('is_draft', 0)->where('is_ready', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('kode', function ($d) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('note', function ($d) {
                return Str::limit($d->remark, 30, '...');
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->rawColumns(['kode'])
            ->make(true);
    }

    function getSeriDoneUnit(Request $request)
    {
        $data = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($request) {
            $q->where('gbj_id', $request->gbj_id);
        })->where('status', 1)->where('is_draft', 0)->where('is_ready', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('kode', function ($d) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-unit" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('note', function ($d) {
                return Str::limit($d->remark, 30, '...');
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->rawColumns(['kode'])
            ->make(true);
    }

    function headerNoseriSpr(Request $request)
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) use ($request) {
        })->where('sparepart_id', $request->sparepart_id)->get();

        return $data;
    }

    // cek
    function cekNoseriTerima(Request $request)
    {
        $noseri = GudangKarantinaNoseri::whereIn('noseri', $request->noseri)->get();
        $data = GudangKarantinaNoseri::whereIn('noseri', $request->noseri)->get()->count();
        $dataseri = [];
        if ($data > 0) {
            foreach ($noseri as $item) {
                array_push($dataseri, $item->noseri);
            }
            return response()->json(['error' => 'Nomor seri ' . implode(', ', $dataseri) . ' sudah terdaftar']);
        } else {
            return response()->json(['msg' => 'Nomor seri tersimpan']);
        }
    }

    // store
    // tf
    function transfer_by_draft(Request $request)
    {
        $header = new GudangKarantina();
        $header->date_out = $request->date_out;
        $header->ke = $request->ke;
        $header->deskripsi = $request->deskripsi;
        $header->is_draft = 1;
        $header->is_keluar = 1;
        $header->save();

        $spr = $request->sparepart_id;

        foreach ($spr as $k => $v) {
            $sprr = new GudangKarantinaDetail();
            $sprr->gk_id = $header->id;
            $sprr->sparepart_id = $request->sparepart_id[$k];
            $sprr->qty_spr = $request->qty_spr[$k];
            $sprr->is_draft = 0;
            $sprr->is_keluar = 1;
            $sprr->save();

            $x = $request->noseri;
            $id = $sprr->id;

            for ($i = 0; $i < count($request->noseri[$v]); $i++) {
                $noseri = new NoseriKeluarGK();
                $noseri->gk_detail_id = $id;
                $noseri->noseri_id = json_decode($request->noseri[$v][$i], true);
                $noseri->created_at = Carbon::now();
                $noseri->save();

                GudangKarantinaNoseri::find(json_decode($request->noseri[$v][$i], true))->update(['is_ready' => 1]);
            }
        }

        $unit = $request->gbj_id;
        foreach ($unit as $j => $vv) {
            $unitt = new GudangKarantinaDetail();
            $unitt->gk_id = $header->id;
            $unitt->gbj_id = $request->gbj_id[$j];
            $unitt->qty_unit = $request->qty_unit[$j];
            $unitt->is_draft = 0;
            $unitt->is_keluar = 1;
            $unitt->save();

            $idd = $unitt->id;

            for ($m = 0; $m < count($request->seriunit[$vv]); $m++) {
                $noserii = new NoseriKeluarGK();
                $noserii->gk_detail_id = $id;
                $noserii->noseri_id = json_decode($request->seriunit[$vv][$m], true);
                $noserii->created_at = Carbon::now();
                $noserii->save();

                GudangKarantinaNoseri::find(json_decode($request->seriunit[$vv][$m], true))->update(['is_ready' => 1]);
            }
        }

        return response()->json(['msg' => 'Data Berhasil dirancang']);
    }

    function transfer_by_final(Request $request)
    {
        $header = new GudangKarantina();
        $header->date_out = $request->date_out;
        $header->ke = $request->ke;
        $header->deskripsi = $request->deskripsi;
        $header->is_draft = 0;
        $header->is_keluar = 1;
        $header->save();

        $spr = $request->sparepart_id;

        foreach ($spr as $k => $v) {
            $sprr = new GudangKarantinaDetail();
            $sprr->gk_id = $header->id;
            $sprr->sparepart_id = $request->sparepart_id[$k];
            $sprr->qty_spr = $request->qty_spr[$k];
            $sprr->is_draft = 0;
            $sprr->is_keluar = 1;
            $sprr->save();

            $x = $request->noseri;
            $id = $sprr->id;

            for ($i = 0; $i < count($request->noseri[$v]); $i++) {
                $noseri = new NoseriKeluarGK();
                $noseri->gk_detail_id = $id;
                $noseri->noseri_id = json_decode($request->noseri[$v][$i], true);
                $noseri->created_at = Carbon::now();
                $noseri->save();

                GudangKarantinaNoseri::find(json_decode($request->noseri[$v][$i], true))->update(['is_ready' => 1]);
            }
        }

        $unit = $request->gbj_id;
        foreach ($unit as $j => $vv) {
            $unitt = new GudangKarantinaDetail();
            $unitt->gk_id = $header->id;
            $unitt->gbj_id = $request->gbj_id[$j];
            $unitt->qty_unit = $request->qty_unit[$j];
            $unitt->is_draft = 0;
            $unitt->is_keluar = 1;
            $unitt->save();

            $idd = $unitt->id;

            for ($m = 0; $m < count($request->seriunit[$vv]); $m++) {
                $noserii = new NoseriKeluarGK();
                $noserii->gk_detail_id = $idd;
                $noserii->noseri_id = json_decode($request->seriunit[$vv][$m], true);
                $noserii->created_at = Carbon::now();
                $noserii->save();

                GudangKarantinaNoseri::find(json_decode($request->seriunit[$vv][$m], true))->update(['is_ready' => 1]);
            }
        }

        return response()->json(['msg' => 'Data Berhasil dirancang']);
    }

    function terima_by_draft(Request $request)
    {
        $header = new GudangKarantina();
        $header->date_in = $request->date_in;
        $header->dari = $request->dari;
        $header->is_draft = 1;
        $header->is_keluar = 0;
        $header->save();

        $spr = $request->sparepart_id;

        foreach ($spr as $k => $v) {
            $sprr = new GudangKarantinaDetail();
            $sprr->gk_id = $header->id;
            $sprr->sparepart_id = $request->sparepart_id[$k];
            $sprr->qty_spr = $request->qty_spr[$k];
            $sprr->is_draft = 1;
            $sprr->is_keluar = 0;
            $sprr->save();

            $x = $request->noseri;
            $id = $sprr->id;

            for ($i = 0; $i < count($request->noseri[$v]); $i++) {
                $noseri = new GudangKarantinaNoseri();
                $noseri->gk_detail_id = $id;
                $noseri->noseri = $request->noseri[$v][$i]["noseri"];
                $noseri->remark = $request->noseri[$v][$i]['kerusakan'];
                $noseri->tk_kerusakan = $request->noseri[$v][$i]['tingkat'];
                $noseri->is_draft = 1;
                $noseri->is_keluar = 0;
                $noseri->save();
            }
        }

        $unit = $request->gbj_id;
        foreach ($unit as $j => $vv) {
            $unitt = new GudangKarantinaDetail();
            $unitt->gk_id = $header->id;
            $unitt->gbj_id = $request->gbj_id[$j];
            $unitt->qty_unit = $request->qty_unit[$j];
            $unitt->is_draft = 1;
            $unitt->is_keluar = 0;
            $unitt->save();

            $idd = $unitt->id;

            for ($m = 0; $m < count($request->seriunit[$vv]); $m++) {

                $noserii = new GudangKarantinaNoseri();
                $noserii->gk_detail_id = $idd;
                $noserii->noseri = $request->seriunit[$vv][$m]["noseri"];
                $noserii->remark = $request->seriunit[$vv][$m]['kerusakan'];
                $noserii->tk_kerusakan = $request->seriunit[$vv][$m]['tingkat'];
                $noserii->is_draft = 1;
                $noserii->is_keluar = 0;
                $noserii->save();
            }
        }

        return response()->json(['msg' => 'Data Berhasil dirancang']);
    }

    function terima_by_final(Request $request)
    {
        $header = new GudangKarantina();
        $header->date_in = $request->date_in;
        $header->dari = $request->dari;
        $header->is_draft = 0;
        $header->is_keluar = 0;
        $header->save();

        $spr = $request->sparepart_id;

        foreach ($spr as $k => $v) {
            $sprr = new GudangKarantinaDetail();
            $sprr->gk_id = $header->id;
            $sprr->sparepart_id = $request->sparepart_id[$k];
            $sprr->qty_spr = $request->qty_spr[$k];
            $sprr->is_draft = 0;
            $sprr->is_keluar = 0;
            $sprr->save();

            $x = $request->noseri;
            $id = $sprr->id;

            for ($i = 0; $i < count($request->noseri[$v]); $i++) {
                $noseri = new GudangKarantinaNoseri();
                $noseri->gk_detail_id = $id;
                $noseri->noseri = $request->noseri[$v][$i]["noseri"];
                $noseri->remark = $request->noseri[$v][$i]['kerusakan'];
                $noseri->tk_kerusakan = $request->noseri[$v][$i]['tingkat'];
                $noseri->is_draft = 0;
                $noseri->is_keluar = 0;
                $noseri->save();
            }
        }

        $unit = $request->gbj_id;
        foreach ($unit as $j => $vv) {
            $unitt = new GudangKarantinaDetail();
            $unitt->gk_id = $header->id;
            $unitt->gbj_id = $request->gbj_id[$j];
            $unitt->qty_unit = $request->qty_unit[$j];
            $unitt->is_draft = 0;
            $unitt->is_keluar = 0;
            $unitt->save();

            $idd = $unitt->id;

            for ($m = 0; $m < count($request->seriunit[$vv]); $m++) {

                $noserii = new GudangKarantinaNoseri();
                $noserii->gk_detail_id = $idd;
                $noserii->noseri = $request->seriunit[$vv][$m]["noseri"];
                $noserii->remark = $request->seriunit[$vv][$m]['kerusakan'];
                $noserii->tk_kerusakan = $request->seriunit[$vv][$m]['tingkat'];
                $noserii->is_draft = 0;
                $noserii->is_keluar = 0;
                $noserii->save();
            }
        }

        return response()->json(['msg' => 'Data Berhasil Diterima']);
    }

    function edit_draft_terima(Request $request)
    {
        $cekid = GudangKarantina::find($request->id);

        $sprid = GudangKarantinaDetail::where('gk_id', $cekid->id)->whereNotNull('sparepart_id')->get();
        $arr_spr = [];
        $arr_serispr = [];
        foreach ($sprid as $s) {
            $serisprid = GudangKarantinaNoseri::where('gk_detail_id', $s->id)->get();
            foreach($serisprid as $ss) {
                $arr_serispr[] = [
                    'seri' => $ss->noseri,
                    'remark' => $ss->remark,
                    'tingkat' => $ss->tk_kerusakan,
                    'id' => $ss->id
                ];
            }
            $arr_spr[] = [
                'sparepart_id' => $s->sparepart_id,
                'qty'           => $s->qty_spr,
                'nama'          => $s->sparepart->nama,
                'kode'          => $s->id,
                'seri'          => $serisprid,

            ];
        }

        $unitid = GudangKarantinaDetail::where('gk_id', $cekid->id)->whereNotNull('gbj_id')->get();
        $arr_unit = [];
        $arr_seriunit = [];
        foreach ($unitid as $u) {
            $seriunitid = GudangKarantinaNoseri::where('gk_detail_id', $u->id)->get();
            foreach($seriunitid as $uu) {
                $arr_seriunit[] = [
                    'seri' => $uu->noseri,
                    'remark' => $uu->remark,
                    'tingkat' => $uu->tk_kerusakan,
                    'id' => $uu->id
                ];
            }
            $arr_unit[] = [
                'gbj_id'        => $u->gbj_id,
                'qty'           => $u->qty_unit,
                'nama'          => $u->units->produk->nama . ' ' . $u->units->nama,
                'kode'          => $u->id,
                'seri'          => $arr_seriunit,

            ];
        }

        return response()->json([
            'header' => $cekid,
            'unit' => $arr_unit,
            'spr'   => $arr_spr,
        ]);
    }

    function updateTerima(Request $request)
    {
        $header = GudangKarantina::find($request->id);

        $header->is_draft = 1;
        $header->is_keluar = 0;
        $header->save();

        $cek = GudangKarantinaDetail::where('sparepart_id', $request->sparepart_id)->where('gk_id', $header->id)->get();
        if (count($cek) > 0) {
            foreach($cek as $c => $v) {
                GudangKarantinaNoseri::where('gk_detail_id', $v->id)->delete();
                GudangKarantinaDetail::where('sparepart_id', $request->sparepart_id)->where('gk_id', $header->id)->delete();
            }
            $spr = $request->sparepart_id;
            foreach ($spr as $k => $vv) {
                $sprr = new GudangKarantinaDetail();
                $sprr->gk_id = $header->id;
                $sprr->sparepart_id = $request->sparepart_id[$k];
                $sprr->qty_spr = $request->qty_spr[$k];
                $sprr->is_draft = 1;
                $sprr->is_keluar = 0;
                $sprr->save();

                $x = $request->noseri;
                $id = $sprr->id;

                for ($i = 0; $i < count($request->noseri[$vv]); $i++) {
                    $noseri = new GudangKarantinaNoseri();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri = $request->noseri[$vv][$i]["noseri"];
                    $noseri->remark = $request->noseri[$vv][$i]['kerusakan'];
                    $noseri->tk_kerusakan = $request->noseri[$vv][$i]['tingkat'];
                    $noseri->is_draft = 1;
                    $noseri->is_keluar = 0;
                    $noseri->save();
                }
            }
        }

        // cek unit
        // $unit = $request->gbj_id;
        // $cekunit = GudangKarantinaDetail::where('gbj_id', $unit)->where('gk_id', $header->id)->get();
        // if (count($cekunit) > 0) {
        //     foreach($cekunit as $kk => $vv) {
        //         GudangKarantinaNoseri::where('gk_detail_id', $v->id)->delete();
        //         GudangKarantinaDetail::where('gbj_id', $unit)->where('gk_id', $header->id)->delete();
        //         foreach ($unit as $j => $vv) {
        //             $unitt = new GudangKarantinaDetail();
        //             $unitt->gk_id = $header->id;
        //             $unitt->gbj_id = $request->gbj_id[$j];
        //             $unitt->qty_unit = $request->qty_unit[$j];
        //             $unitt->is_draft = 1;
        //             $unitt->is_keluar = 0;
        //             $unitt->save();

        //             $idd = $unitt->id;

        //             if (isset($request->seriunit)) {
        //                 for ($m = 0; $m < count($request->seriunit[$vv]); $m++) {

        //                     $noserii = new GudangKarantinaNoseri();
        //                     $noserii->gk_detail_id = $idd;
        //                     $noserii->noseri = $request->seriunit[$vv][$m]["noseri"];
        //                     $noserii->remark = $request->seriunit[$vv][$m]['kerusakan'];
        //                     $noserii->tk_kerusakan = $request->seriunit[$vv][$m]['tingkat'];
        //                     $noserii->is_draft = 0;
        //                     $noserii->is_keluar = 0;
        //                     $noserii->save();
        //                 }
        //             }
        //         }
        //     }
        // }

        return response()->json(['msg' => 'Data Rancang berhasil diubah']);
    }

    function deleteDraftTerima(Request $request)
    {
        $noseri_cek = GudangKarantinaNoseri::where('gk_detail_id', $request->id)->delete();
        $cek = GudangKarantinaDetail::find($request->id);
        $cek->delete();

        // $arr = [];
        // foreach($noseri_cek as $n) {
        //     $arr[] = [
        //         'a' => $n
        //     ];
        // }
        // return response()->json([
        //     'a' => $cek,
        //     'b' => $arr
        // ]);
        return response()->json(['msg' => 'Data Berhasil Dihapus']);
    }

    // transaksi noseri
    function updateUnit(Request $request)
    {
        $data = GudangKarantinaNoseri::find($request->id);
        $data->layout_id = $request->layout_id;
        $data->remark = $request->remark;
        $data->tk_kerusakan = $request->tk_kerusakan;
        $data->status = 1;
        $data->updated_at = Carbon::now();
        $data->save();

        return response()->json(['msg' => 'Data Berhasil diubah']);
    }

    // dashboard
    function stok34()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [3, 4])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [3, 4])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function stok510()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [5, 10])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [5, 10])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function stok10plus()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('qty_spr', '>', 10)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('qty_unit', '>', 10)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function h_stok34()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [3, 4])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [3, 4])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return count($data);
    }

    function h_stok510()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [5, 10])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [5, 10])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);

        return count($data);
    }

    function h_stok10plus()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('qty_spr', '>', 10)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('qty_unit', '>', 10)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);

        return count($data);
    }

    // in
    function in36()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function in612()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function in1236()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function in36plus()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->where('date_in', '<=', Carbon::now()->subYears(3));
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function h_in36()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
        })->get();
        return count($data);
    }

    function h_in612()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();
        return count($data);
    }

    function h_in1236()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();
        return count($data);
    }

    function h_in36plus()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->where('date_in', '<=', Carbon::now()->subYears(3));
        })->get();
        return count($data);
    }

    function byLayout()
    {
        $d = GudangKarantinaNoseri::with('detail')->select('*', DB::raw('count(layout_id) as jml'))
            ->where('is_draft', 0)
            ->where('status', 1)
            ->groupBy('layout_id')
            ->groupBy('gk_detail_id')
            ->get();
        return datatables()->of($d)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return $d->detail->sparepart->nama;
                } else {
                    return $d->detail->units->produk->nama . ' ' . $d->detail->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->detail->qty_unit)) {
                    return $d->jml . ' Unit';
                } else {
                    return $d->jml . ' ' . $d->detail->units->satuan->nama;
                }
            })
            ->addColumn('layout', function ($d) {
                if (empty($d->layout_id)) {
                    return $d->Layout->ruang;
                } else {
                    return $d->layout->ruang;
                }
            })
            ->make(true);
    }

    function byTingkat()
    {
        $d = GudangKarantinaNoseri::with('detail')->select('*', DB::raw('count(tk_kerusakan) as jml'))
            ->where('is_draft', 0)
            ->where('status', 1)
            ->groupBy('tk_kerusakan')
            ->groupBy('gk_detail_id')
            ->get();
        return datatables()->of($d)
            ->addIndexColumn()
            ->addColumn('kode', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return $d->detail->sparepart->spare->kode;
                } else {
                    return $d->detail->units->produk->product->kode . '' . $d->detail->units->produk->kode;
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return $d->detail->sparepart->nama;
                } else {
                    return $d->detail->units->produk->nama . ' ' . $d->detail->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->detail->qty_unit)) {
                    return $d->jml . ' Unit';
                } else {
                    return $d->jml . ' ' . $d->detail->units->satuan->nama;
                }
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->addColumn('jenis', function ($d) {
                if (empty($d->detail->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('button', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return '<a href="' . url('gk/gudang/sparepart/' . $d->detail->sparepart_id . '') . '" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i>';
                } else {
                    return '<a href="' . url('gk/gudang/unit/' . $d->detail->gbj_id . '') . '" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i>';
                }
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    function testing()
    {
        $dataspr = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        $dataunit = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get();
        $data = $dataspr->merge($dataunit);
        $arr = [];
        foreach($data as $d) {
            $arr[] = [
                'kode' => $d->kode,
                'nama' => $d->nama,
                'jml' => $d->jml. ' Unit',
                'jenis' => $d->sparepart_id == null ? 'Unit' : 'Sparepart',
            ];
        }
        return response()->json([
            'data' => $arr,
        ]);
    }


}
