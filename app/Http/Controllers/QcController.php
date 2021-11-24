<?php

namespace App\Http\Controllers;

use App\Exports\LaporanQcOutgoing;
use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriTGbj;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Pesanan;

class QcController extends Controller
{
    //Get Data
    public function get_data_select_seri($seri_id, $produk_id, $tfgbj_id)
    {
        $x = explode(',', $seri_id);
        if ($seri_id == '0') {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id, $tfgbj_id) {
                $q->where(['gdg_brg_jadi_id' => $produk_id, 't_gbj_id' => $tfgbj_id]);
            });
        } else {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id, $tfgbj_id) {
                $q->where(['gdg_brg_jadi_id' => $produk_id, 't_gbj_id' => $tfgbj_id]);
            })->whereIN('noseri_id', $x);
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('seri', function ($data) {
                return $data->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }
    public function get_data_seri_detail_ekatalog($seri_id, $produk_id, $tfgbj_id, $pesanan_id)
    {
        $value2 = array();
        $x = explode(',', $seri_id);

        if ($seri_id == '0') {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id, $tfgbj_id) {
                $q->where(['gdg_brg_jadi_id' => $produk_id, 't_gbj_id' => $tfgbj_id]);
            })->get();
            foreach ($data as $d) {
                $value2[] = $d->id;
            }
            $id =  json_encode($value2);
        } else {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id, $tfgbj_id) {
                $q->where(['gdg_brg_jadi_id' => $produk_id, 't_gbj_id' => $tfgbj_id]);
            })->whereIN('noseri_id', $x)->get();
            foreach ($data as $d) {
                $value2[] = $d->id;
            }
            $id =  json_encode($value2);
        }
        return view('page.qc.so.edit', ['id' => $id, 'tfgbj_id' => $tfgbj_id, 'pesanan_id' => $pesanan_id, 'produk_id' => $produk_id]);
    }
    public function get_data_seri_ekatalog($id, $idtrf)
    {
        $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $idtrf) {
            $q->where(['gdg_brg_jadi_id' => $id, 't_gbj_id' => $idtrf]);
        });

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '  <div class="form-check">
                <input class=" form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />
            </div>';
            })
            ->addColumn('seri', function ($data) {
                return $data->NoseriBarangJadi->noseri;
            })
            ->addColumn('status', function ($data) {
                $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->get();
                if (count($check) > 0) {
                    foreach ($check as $c) {
                        if ($c->status == 'ok') {
                            return '<i class="fas fa-check-circle ok"></i>';
                        } else {
                            return '<i class="fas fa-times-circle nok"></i>';
                        }
                    }
                } else {
                    return '<i class="fas fa-question-circle warning"></i>';
                }
            })
            ->addColumn('button', function () {
                return '';
            })
            ->rawColumns(['checkbox', 'status', 'button'])
            ->make(true);
    }
    public function get_data_detail_so($id)
    {
        $x = explode(',', $id);
        //$data = DetailEkatalog::where('ekatalog_id', $id)->with('GudangBarangJadi', 'GudangBarangJadi.Produk')->get();
        $data = DetailPesananProduk::with('noseridetailpesanan')->whereIN('detail_pesanan_id', $x)->get();
        // echo json_encode($data);
        // $l = [];
        // $v = 0;
        // foreach ($data as $s) {
        //     foreach ($s->GudangBarangJadi as $k) {
        //         $l[$v]['id'] = $k->pivot->gudang_barang_jadi_id;
        //         $l[$v]['nama_produk'] = $k->produk->nama;
        //         $l[$v]['jumlah'] = $k->pivot->jumlah;
        //         $v++;
        //     }
        // }
        //echo json_encode($data);
        // $l = [];
        // $v = 0;
        // foreach ($data as $s) {
        //     foreach ($s->GudangBarangJadi as $k) {
        //         $l[$v]['id'] = $k->pivot->gudang_barang_jadi_id;
        //         $l[$v]['nama_produk'] = $k->produk->nama;
        //         $l[$v]['jumlah'] = $k->pivot->jumlah;
        //         $v++;
        //     }
        // }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama;
                } else {
                    return $data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                return $data->detailpesanan->jumlah;
            })
            ->addColumn('jumlah_ok', function ($data) {
                if ($data->noseridetailpesanan == '') {
                    return '1';
                } else {
                    return '0';
                }
            })
            ->addColumn('jumlah_nok', function ($data) {
                if ($data->noseridetailpesanan == '') {
                    return '1';
                } else {
                    return '0';
                }
            })
            ->addColumn('button', function ($data) {
                return '<a type="button" class="noserishow" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['button'])
            ->make(true);
        //echo json_encode($data);
    }
    public function get_data_so_qc()
    {
        $data = Ekatalog::whereHas('Pesanan.TFProduksi', function ($q) {
            $q->whereNotNull('no_po');
        })->get();
        return datatables()->of($data)
            ->make(true);
    }
    public function get_data_so($value)
    {
        $x = explode(',', $value);
        if ($value == 'semua') {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else if ($x == ['ekatalog', 'spa']) {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Ekatalog->merge($Spa);
        } else if ($x == ['ekatalog', 'spb']) {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Ekatalog->merge($Spb);
        } else if ($x == ['spa', 'spb']) {
            $Spa = collect(Spa::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Spa->merge($Spb);
        } else if ($value == 'ekatalog') {
            $data = Ekatalog::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        } else if ($value == 'spa') {
            $data = Spa::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        } else if ($value == 'spb') {
            $data = Spb::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        } else {
            $data = Spa::whereHas('Pesanan.TFProduksi', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        }

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
            ->addColumn('status', function () {
                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
            })
            ->addColumn('button', function ($data) {
                $name =  $data->getTable();
                return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a href="' . route('qc.so.detail', [$data->id, $name]) . '">
                <button class="dropdown-item" type="button">
                    <i class="fas fa-search"></i>
                    Detail
                </button>
            </a>
        </div>';
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }

    //Detail
    public function update_modal_so()
    {
        return view('page.qc.so.edit');
    }

    public function detail_so($id, $value)
    {
        if ($value == 'ekatalog') {
            $data = Ekatalog::where('id', $id)->get();
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('ekatalog.id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            foreach ($data as $d) {
                $tgl_sekarang = Carbon::now()->format('Y-m-d');
                $tgl_parameter = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status)->format('Y-m-d');

                if ($tgl_sekarang < $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);

                    if ($hari > 7) {
                        $param = ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $param = ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
                    } else {
                        $param = '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                    }
                } elseif ($tgl_sekarang == $tgl_parameter) {
                    $param =  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                } else {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);
                    $param =  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                }
            }
            return view('page.qc.so.detail_ekatalog', ['data' => $data, 'detail_id' => $detail_id, 'param' => $param]);
        } elseif ($value == 'spa') {
            $data = Spa::where('id', $id)->get();
            return view('page.qc.so.detail_spa', ['data' => $data]);
        } else {
            $data = Spb::where('id', $id)->get();
            return view('page.qc.so.detail_spb', ['data' => $data]);
        }
    }

    public function detail_modal_riwayat_so()
    {
        return view('page.qc.so.riwayat.detail');
    }

    //Tambah
    public function create_data_qc($seri_id, $tfgbj_id, $pesanan_id, $produk_id, Request $request)
    {
        $data = DetailPesananProduk::whereHas('DetailPesanan.Pesanan', function ($q) use ($seri_id, $tfgbj_id, $pesanan_id) {
            $q->where('Pesanan_id', $pesanan_id);
        })->where('gudang_barang_jadi_id', $produk_id)->first();

        $replace_array_seri = strtr($seri_id, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_seri);

        //  return response()->json(['data' =>  count($array_seri)]);

        for ($i = 0; $i < count($array_seri); $i++) {
            NoseriDetailPesanan::create([
                'detail_pesanan_produk_id' => $data->id,
                't_tfbj_noseri_id' => $array_seri[$i],
                'status' => $request->cek,
                'tgl_uji' => $request->tanggal_uji,
            ]);
        }
    }
    //Laporan
    public function laporan_outgoing(Request $request)
    {
        return Excel::download(new LaporanQcOutgoing($request->produk_id ?? '', $request->no_so ?? '', $request->hasil_uji  ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_qc_outgoing.xlsx');
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '21';
        } else {
            $days = '28';
        }
        return Carbon::parse($value)->subDays($days);
    }
}
