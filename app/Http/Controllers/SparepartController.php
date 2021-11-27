<?php

namespace App\Http\Controllers;

use App\Models\GudangBarangJadi;
use App\Models\GudangKarantina;
use App\Models\GudangKarantinaDetail;
use App\Models\GudangKarantinaNoseri;
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
    // get
    // produk spr
    function get() {
        $spr = SparepartGudang::with('Spare', 'his')->limit(10)->get();
        return datatables()->of($spr)
            ->addColumn('kode', function($d) {
                return $d->spare->kode;
            })
            ->addColumn('produk', function($d) {
                return $d->spare->nama;
            })
            ->addColumn('unit', function($d) {
                return '-';
            })
            ->addColumn('jml', function($d) {
                return $d->stok.' pcs';
            })
            ->addColumn('button', function($d) {
                return '<a class="btn btn-outline-info" href="'.url('gk/gudang/sparepart/'.$d->id.'').'"><i
                class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    // produk unit
    function get_unit() {
        $data = GudangBarangJadi::with('produk', 'satuan')->get();
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
            ->addColumn('kelompok', function ($data) {
                return $data->produk->KelompokProduk->nama;
            })
            ->addColumn('button', function($d) {
                return '<a class="btn btn-outline-info" href="'.url('gk/gudang/unit/'.$d->id.'').'"><i
                class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    // detail
    function detail_spr($id)
    {
        $header = SparepartGudang::with('Spare', 'his')->where('sparepart_id', $id)->get();
        return view('page.gk.gudang.sparepartEdit', compact('header'));
    }

    function detail_unit($id) {
        $header = GudangBarangJadi::with('produk', 'satuan')->where('id', $id)->get();
        return view('page.gk.gudang.unitEdit', compact('header'));
    }

    function history_spr($id) {
        // $data = GudangKarantinaDetail::with('sparepart.Spare', 'header.from', 'header.to', 'noseri')->where('sparepart_id', $id)->get();
        $data = GudangKarantinaNoseri::whereHas('detail', function($q) use ($id) {
            $q->where('sparepart_id', $id);
        })->get();
        return datatables()->of($data)
            ->addColumn('inn', function($d) {
                if (empty($d->detail->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_in));
                }
            })
            ->addColumn('out', function($d) {
                if (empty($d->detail->header->date_out)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_out));
                }
            })
            ->addColumn('from', function($d) {
                if (empty($d->detail->header->dari)) {
                    return '-';
                } else {
                    return '<span class="badge badge-success">'.$d->detail->header->from->nama.'</span>';
                }
            })
            ->addColumn('to', function($d) {
                if (empty($d->detail->header->ke)) {
                    return '-';
                } else {
                    return '<span class="badge badge-info">'.$d->detail->header->to->nama.'</span>';
                }
            })
            ->addColumn('noser', function($d) {
                return $d->noseri;
            })
            ->addColumn('layout', function($d) {
                return '-';
            })
            ->addColumn('remarks', function($d) {
                if (empty($d->remark)) {
                    return '-';
                } else {
                    return $d->remark;
                }
            })
            ->addColumn('tingkat', function($d) {
                return 'Level '. $d->tk_kerusakan;
            })
            ->addColumn('status', function($d) {
                if ('x') {
                    return '<span class="sudah_diterima">Sudah Diperbaiki</span>';
                } else {
                    return '<span class="belum_diterima">Belum Diperbaiki</span>';
                }
            })
            ->addColumn('action', function($d) {
                return '<a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr=""  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info"><i class="far fa-edit"></i></button>
                        </a>';
            })
            ->rawColumns(['status', 'action', 'from', 'to'])
            ->make(true);
    }

    function headerSeri($id) {
        $d = GudangKarantinaNoseri::find($id);
        return response()->json([
            'noser' => $d->noseri,
            'in' => date('d-m-Y', strtotime($d->detail->header->date_in)),
            'out' => date('d-m-Y', strtotime($d->detail->header->date_out))
        ]);
    }

    function history_unit($id) {
        $data = GudangKarantinaNoseri::whereHas('detail', function($q) use ($id) {
            $q->where('gbj_id', $id);
        })->get();
        return datatables()->of($data)
            ->addColumn('inn', function($d) {
                if (empty($d->detail->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_in));
                }
            })
            ->addColumn('out', function($d) {
                if (empty($d->detail->header->date_out)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->detail->header->date_out));
                }
            })
            ->addColumn('from', function($d) {
                if (empty($d->detail->header->dari)) {
                    return '-';
                } else {
                    return '<span class="badge badge-success">'.$d->detail->header->from->nama.'</span>';
                }
            })
            ->addColumn('to', function($d) {
                if (empty($d->detail->header->ke)) {
                    return '-';
                } else {
                    return '<span class="badge badge-info">'.$d->detail->header->to->nama.'</span>';
                }
            })
            ->addColumn('noser', function($d) {
                return $d->noseri;
            })
            ->addColumn('layout', function($d) {
                return '-';
            })
            ->addColumn('remarks', function($d) {
                if (empty($d->remark)) {
                    return '-';
                } else {
                    return $d->remark;
                }
            })
            ->addColumn('tingkat', function($d) {
                return 'Level '. $d->tk_kerusakan;
            })
            ->addColumn('status', function($d) {
                if ('x') {
                    return '<span class="sudah_diterima">Sudah Diperbaiki</span>';
                } else {
                    return '<span class="belum_diterima">Belum Diperbaiki</span>';
                }
            })
            ->addColumn('action', function($d) {
                return '<a data-toggle="modal" data-target="#unitmodal" class="unitmodal" data-attr=""  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info"><i class="far fa-edit"></i></button>
                        </a>';
            })
            ->rawColumns(['status', 'action', 'from', 'to'])
            ->make(true);
    }

    // draft
    function get_draft_tf()
    {
        $data = GudangKarantina::with('from', 'to')->where('is_draft', 0)->get();
        return datatables()->of($data)
            ->addColumn('out', function($d) {
                if (isset($d->date_out)) {
                    return date('d-m-Y', strtotime($d->date_out));
                } else {
                    return '-';
                }
            })
            ->addColumn('in', function($d) {
                if (isset($d->date_in)) {
                    return date('d-m-Y', strtotime($d->date_in));
                } else {
                    return '-';
                }
            })
            ->addColumn('too', function($d) {
                if (isset($d->ke)) {
                    return $d->to->nama;
                } else {
                    return '-';
                }
            })
            ->addColumn('from', function($d) {
                if (isset($d->dari)) {
                    return $d->from->nama;
                } else {
                    return '-';
                }
            })

            ->addColumn('aksi', function($d) {
                return '<a href="'.url('gk/transfer/'.$d->id.'').'" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function edit_tf($id) {
        $data = GudangKarantina::where('id', $id)->get();
        return view('page.gk.transfer.edit', compact('data'));
    }
    // final

    // history_trx
    function historyAll(Request $request)
    {
        $data = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk', 'header.from', 'header.to')->where('is_draft', 0)->get();
        return datatables()->of($data)
            ->addColumn('jenis', function($d) {
                if (empty($d->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('produk', function($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama.' '.$d->units->nama;
                }
            })
            ->addColumn('tanggal', function($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">'.date('d-m-Y', strtotime($d->header->date_out)).'</span>';
                } else {
                    return '<span class="badge badge-success">'.date('d-m-Y', strtotime($d->header->date_in)).'</span>';
                }
            })
            ->addColumn('divisi', function($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">'.$d->header->to->nama.'</span>';
                } else {
                    return '<span class="badge badge-success">'.$d->header->from->nama.'</span>';
                }
            })
            ->addColumn('unitt', function($d) {
                return '-';
            })
            ->addColumn('jumlah', function($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr.' Unit';
                } else {
                    return $d->qty_unit.' '.$d->units->satuan->nama;
                }
            })
            ->addColumn('tujuan', function($d) {
                if (empty($d->header->deskripsi)) {
                    return '-';
                } else {
                    return $d->header->deskripsi;
                }
            })
            ->addColumn('aksi', function($d) {
                return ' <a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr=""  data-id="' . $d->id . '">
                <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                        </a>';
            })
            ->filter(function ($q) use ($request) {
                if ($request->get('jenis') == '0' || $request->get('jenis') == '1' || $request->get('jenis') == '2') {
                    $q->where('jenis', $request->get('jenis'));
                }
            })
            ->rawColumns(['tanggal', 'aksi', 'divisi'])
            ->make(true);
    }

    function get_noseri_history($id) {
        $data = GudangKarantinaNoseri::where('gk_detail_id', $id)->where('is_draft', 0)->get();
        return datatables()->of($data)
            ->addColumn('noser', function($d) {
                return $d->noseri;
            })
            ->addColumn('rusak', function($d) {
                return $d->remark;
            })
            ->addColumn('layout', function($d) {
                return '-';
            })
            ->addColumn('tingkat', function($d) {
                return 'Level '.$d->tk_kerusakan;
            })
            ->make(true);
    }

    function history_by_produk(Request $request)
    {
        $data = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk')->where('is_draft', 0)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function($d) {
                if (empty($d->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('produk', function($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama.' '.$d->units->nama;
                }
            })
            ->addColumn('kode', function($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->spare->kode;
                } else {
                    return $d->units->produk->kode;
                }
            })
            ->addColumn('aksi', function($d) {
                return '<a class="btn btn-info" href="'.url('gk/transaksi/'.$d->id.'').'"><i
                    class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function detail_trx($id) {
        return view('page.gk.transaksi.show');
    }

    // store
    // tf
    function transfer_by_draft(Request $request)
    {
        dd($request->all());
    }

    function transfer_by_final(Request $request)
    {
        dd($request->all());
    }

    // unuse
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
