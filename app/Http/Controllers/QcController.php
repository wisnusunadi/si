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
use App\Models\Pesanan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;


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
            ->addColumn('tgl_uji', function ($data) {
                $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->first();
                if (isset($check)) {
                    return Carbon::createFromFormat('Y-m-d', $check->tgl_uji)->format('d-m-Y');
                } else {
                    return '-';
                }
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
        $data = DetailPesananProduk::with('noseridetailpesanan')->whereIN('detail_pesanan_id', $x)->get();
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
                return $data->detailpesanan->jumlah * $data->detailpesanan->Penjualanproduk->produk->first()->pivot->jumlah;
            })
            ->addColumn('jumlah_ok', function ($data) {
                $c = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $data->id, 'status' => 'ok'])->get()->count();
                return $c;
            })
            ->addColumn('jumlah_nok', function ($data) {
                $c = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $data->id, 'status' => 'nok'])->get()->count();
                return $c;
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
            $data = Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->get();
        } else if ($x == ['ekatalog', 'spa']) {
            $Ekat = collect(Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%ekat%')->get());
            $Spa = collect(Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%spa%')->get());
            $data = $Ekat->merge($Spa);
        } else if ($x == ['ekatalog', 'spb']) {
            $Ekat = collect(Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%ekat%')->get());
            $Spb = collect(Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%spb%')->get());
            $data = $Ekat->merge($Spb);
        } else if ($x == ['spa', 'spb']) {
            $Spa = collect(Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%spa%')->get());
            $Spb = collect(Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%spb%')->get());
            $data = $Spa->merge($Spb);
        } else if ($value == 'ekatalog') {
            $data = Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%ekat%')->get();
        } else if ($value == 'spa') {
            $data = Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%spa%')->get();
        } else if ($value == 'spb') {
            $data = Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->where('so', 'LIKE', '%spb%')->get();
        } else {
            $data = Pesanan::Has('TFProduksi')->whereIN('id', $this->check_input())->get();
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->spb->Customer->nama;
                }
            })
            ->addColumn('batas_uji', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    $z = array();
                    $x = array();

                    $jumlah = 0;
                    foreach ($data->detailpesanan as $d) {
                        $x[] = $d->id;
                        $z[] = $d->jumlah;
                        foreach ($d->penjualanproduk->produk as $l) {
                            $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                        }
                    }
                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();


                    if ($jumlah == $jumlah_seri) {
                        return  '-';
                    } else {

                        $tgl_sekarang = Carbon::now()->format('Y-m-d');
                        $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');

                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);

                            if ($hari > 7) {
                                return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><span class="badge bg-danger">Batas Kontrak Habis</span>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return   '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                $z = array();
                $x = array();

                $jumlah = 0;
                foreach ($data->detailpesanan as $d) {
                    $x[] = $d->id;
                    $z[] = $d->jumlah;
                    foreach ($d->penjualanproduk->produk as $l) {
                        $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                    }
                }
                $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                $y = array();

                foreach ($detail_pesanan_produk as $d) {
                    $y[] = $d->id;
                }

                $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();


                if ($jumlah == $jumlah_seri) {
                    return  '<span class="badge green-text">Selesai</span>';
                } else {
                    if ($jumlah_seri == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    $x =  'ekatalog';
                } elseif ($name[1] == 'SPA') {
                    $x =  'spa';
                } else {
                    $x =  'spb';
                }
                return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('qc.so.detail', [$data->id, $x]) . '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-search"></i>
                        Detail
                    </button>
                </a>
            </div>';
            })
            ->rawColumns(['button', 'status', 'batas_uji'])
            ->make(true);
    }

    public function get_data_riwayat_pengujian()
    {
        $s = DetailPesanan::Has('DetailPesananProduk.NoSeriDetailPesanan')->get();
        $data = array();
        $c = 0;
        foreach ($s as $i) {
            if ($i->getJumlahPesanan() == $i->countNoSeri()) {
                $data[$c]['id'] = $i->id;
                $data[$c]['so'] = $i->Pesanan->so;
                $data[$c]['nama_produk'] = $i->PenjualanProduk->nama;
                $data[$c]['tgl_mulai'] = $i->getTanggalUji()->tgl_mulai;
                $data[$c]['tgl_selesai'] = $i->getTanggalUji()->tgl_selesai;
                $data[$c]['jumlah'] = $i->jumlah;
                $data[$c]['penjualan_produk_id'] = $i->penjualan_produk_id;
                $c++;
            }
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data['so'];
            })
            ->addColumn('nama_produk', function ($data) {
                return $data['nama_produk'];
            })
            ->addColumn('tgl_mulai', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data['tgl_mulai'])->format('d-m-Y');
            })
            ->addColumn('tgl_selesai', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data['tgl_selesai'])->format('d-m-Y');
            })
            ->addColumn('jumlah', function ($data) {
                return $data['jumlah'];
            })
            ->addColumn('button', function ($data) {
                return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data['penjualan_produk_id'] . '" data-id="' . $data['id'] . '" id="detmodal">
                    <div><i class="fas fa-search"></i></div>
                </a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function get_data_detail_riwayat_pengujian($id)
    {
        $s = NoseriDetailPesanan::where('detail_pesanan_produk_id', $id)->get();

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('hasil', function ($data) {
                if ($data->status == "ok") {
                    return '<div><i class="fas fa-check-circle" style="color:green;"></div>';
                } else if ($data->status == "nok") {
                    return '<div><i class="fas fa-times-circle" style="color:red;"></div>';
                };
            })
            ->rawColumns(['hasil'])
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
            $data = Ekatalog::wherehas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $jumlah = 0;
            $z = array();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
                $z[] = $d->jumlah;
                foreach ($d->penjualanproduk->produk as $l) {
                    $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                }
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $detail_id)->get();
            $y = array();
            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }
            $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();

            if ($jumlah == $jumlah_seri) {
                $status =  '<span class="badge green-text">Selesai</span>';
            } else {
                if ($jumlah_seri == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sedang Berlangsung</span>';
                }
            }

            foreach ($data as $d) {
                $tgl_sekarang = Carbon::now()->format('Y-m-d');
                $tgl_parameter = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status)->format('Y-m-d');

                if ($tgl_sekarang < $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);

                    if ($hari > 7) {
                        $param = ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $hari . ' Hari</small>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $param = ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                    } else {
                        $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                    }
                } elseif ($tgl_sekarang == $tgl_parameter) {
                    $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                } else {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);
                    $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                }
            }
            return view('page.qc.so.detail_ekatalog', ['data' => $data, 'detail_id' => $detail_id, 'param' => $param, 'status' => $status]);
        } elseif ($value == 'spa') {
            $data = Spa::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $jumlah = 0;
            $z = array();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
                $z[] = $d->jumlah;
                foreach ($d->penjualanproduk->produk as $l) {
                    $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                }
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $detail_id)->get();
            $y = array();
            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }
            $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();

            if ($jumlah == $jumlah_seri) {
                $status =  '<span class="badge green-text">Selesai</span>';
            } else {
                if ($jumlah_seri == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sedang Berlangsung</span>';
                }
            }
            return view('page.qc.so.detail_spa', ['data' => $data,  'detail_id' => $detail_id, 'status' => $status]);
        } else {
            $data = Spb::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $jumlah = 0;
            $z = array();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
                $z[] = $d->jumlah;
                foreach ($d->penjualanproduk->produk as $l) {
                    $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                }
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $detail_id)->get();
            $y = array();
            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }
            $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();

            if ($jumlah == $jumlah_seri) {
                $status =  '<span class="badge green-text">Selesai</span>';
            } else {
                if ($jumlah_seri == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sedang Berlangsung</span>';
                }
            }
            return view('page.qc.so.detail_spb', ['data' => $data,  'detail_id' => $detail_id, 'status' => $status]);
        }
    }

    public function detail_modal_riwayat_so($id)
    {
        $result = DetailPesanan::find($id);
        return view('page.qc.so.riwayat.detail', ['id' => $id, 'res' => $result]);
    }

    //Tambah
    public function create_data_qc($seri_id, $tfgbj_id, $pesanan_id, $produk_id, Request $request)
    {
        $data = DetailPesananProduk::whereHas('DetailPesanan.Pesanan', function ($q) use ($pesanan_id) {
            $q->where('Pesanan_id', $pesanan_id);
        })->where('gudang_barang_jadi_id', $produk_id)->first();

        $replace_array_seri = strtr($seri_id, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_seri);

        // //  return response()->json(['data' =>  count($array_seri)]);

        $bool = true;
        for ($i = 0; $i < count($array_seri); $i++) {
            $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', '=', $array_seri[$i])->first();
            if ($check == null) {
                $c = NoseriDetailPesanan::create([
                    'detail_pesanan_produk_id' => $data->id,
                    't_tfbj_noseri_id' => $array_seri[$i],
                    'status' => $request->cek,
                    'tgl_uji' => $request->tanggal_uji,
                ]);
                if (!$c) {
                    $bool = false;
                }
            } else {
                $NoseriDetailPesanan = NoseriDetailPesanan::find($check->id);
                $NoseriDetailPesanan->status = $request->cek;
                $NoseriDetailPesanan->tgl_uji = $request->tanggal_uji;
                $u = $NoseriDetailPesanan->save();
                if (!$u) {
                    $bool = false;
                }
            }
        }

        $po = Pesanan::find($pesanan_id);
        if (($po->getJumlahCek() > 0 && $po->getJumlahPesanan >= $po->getJumlahCek()) && $po->getJumlahKirim() == 0) {
            $po->log_id = '8';
            $po->save();
        }

        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }
    //Dashboard
    public function dashboard()
    {
        $a = $this->check_input();

        $terbaru =  Pesanan::WhereHas('TFProduksi', function ($q) {
            $q->where('tgl_keluar', '>=', Carbon::now()->subdays(7));
        })->whereIN('id',  $this->check_input())->get()->count();

        $hasil = TFProduksi::Has('Pesanan')->DoesntHave('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get()->count();
        $lewat_batas_data = TFProduksi::WhereHas('Pesanan.Ekatalog', function ($q) use ($a) {
            $q->whereIN('pesanan.id', $a);
        })->get();

        $tgl_sekarang = Carbon::now()->format('Y-m-d');
        $lewat_batas = 0;
        foreach ($lewat_batas_data as $l) {
            $tgl_parameter = $this->getHariBatasKontrak($l->pesanan->ekatalog->tgl_kontrak, $l->pesanan->ekatalog->provinsi->status)->format('Y-m-d');
            if ($tgl_sekarang > $tgl_parameter) {
                $lewat_batas++;
            }
        }
        return view('page.qc.dashboard', ['terbaru' => $terbaru, 'hasil' => $hasil, 'lewat_batas' => $lewat_batas]);
    }
    public function dashboard_data($value)
    {
        $a = $this->check_input();

        if ($value == 'terbaru') {
            $data = TFProduksi::WhereHas('Pesanan', function ($q) use ($a) {
                $q->whereIN('id', $a);
            })->where('tgl_keluar', '>=', Carbon::now()->subdays(7))->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('batas', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                        $tgl_sekarang = Carbon::now()->format('Y-m-d');
                        $tgl_parameter = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status)->format('Y-m-d');


                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);

                            if ($hari > 7) {
                                return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('status', function ($data) {
                    $z = array();
                    $x = array();

                    $jumlah = 0;
                    foreach ($data->pesanan->detailpesanan as $d) {
                        $x[] = $d->id;
                        $z[] = $d->jumlah;
                        foreach ($d->penjualanproduk->produk as $l) {
                            $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                        }
                    }

                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();
                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();


                    if ($jumlah == $jumlah_seri) {
                        return  '<span class="badge green-text">Selesai</span>';
                    } else {
                        if ($jumlah_seri == 0) {
                            return '<span class="badge red-text">Belum diuji</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x =  'spa';
                    } else {
                        $x =  'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->pesanan->id, $x]) . '"><i class="fas fa-search"></i></a>
                ';
                })
                ->rawColumns(['button', 'batas', 'status'])
                ->make(true);
        } else if ($value == 'belum_uji') {
            $data = TFProduksi::Has('Pesanan')->DoesntHave('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('batas', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                        $tgl_sekarang = Carbon::now()->format('Y-m-d');
                        $tgl_parameter = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status)->format('Y-m-d');


                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);

                            if ($hari > 7) {
                                return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '';
                    }
                })

                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x =  'spa';
                    } else {
                        $x =  'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->pesanan->id, $x]) . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button', 'batas'])
                ->make(true);
        } else if ($value == 'lewat_uji') {

            $lewat_batas_data = TFProduksi::WhereHas('Pesanan.Ekatalog', function ($q) use ($a) {
                $q->whereIN('pesanan.id', $a);
            })->get();

            $tgl_sekarang = Carbon::now()->format('Y-m-d');
            $lewat_batas = 0;
            $id = array();
            foreach ($lewat_batas_data as $l) {
                $tgl_parameter = $this->getHariBatasKontrak($l->pesanan->ekatalog->tgl_kontrak, $l->pesanan->ekatalog->provinsi->status)->format('Y-m-d');
                if ($tgl_sekarang > $tgl_parameter) {
                    $lewat_batas++;
                    $id[] = $l->pesanan->id;
                }
            }
            $data = TFProduksi::whereIN('pesanan_id', $id)->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('batas', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                        $tgl_sekarang = Carbon::now()->format('Y-m-d');
                        $tgl_parameter = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status)->format('Y-m-d');


                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);

                            if ($hari > 7) {
                                return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('status', function ($data) {
                    $z = array();
                    $x = array();

                    $jumlah = 0;
                    foreach ($data->pesanan->detailpesanan as $d) {
                        $x[] = $d->id;
                        $z[] = $d->jumlah;
                        foreach ($d->penjualanproduk->produk as $l) {
                            $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                        }
                    }

                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();
                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();


                    if ($jumlah == $jumlah_seri) {
                        return  '<span class="badge green-text">Selesai</span>';
                    } else {
                        if ($jumlah_seri == 0) {
                            return '<span class="badge red-text">Belum diuji</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x =  'spa';
                    } else {
                        $x =  'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->pesanan->id, $x]) . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button', 'batas', 'status'])
                ->make(true);
        }
    }

    //Laporan
    public function laporan_outgoing(Request $request)
    {
        return Excel::download(new LaporanQcOutgoing($request->produk_id ?? '', $request->no_so ?? '', $request->hasil_uji  ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_qc_outgoing.xlsx');
    }

    public function get_data_laporan_qc($produk, $no_so, $hasil, $tgl_awal, $tgl_akhir)
    {
        $res = "";
        $so = "";
        if ($no_so != "0") {
            $so = str_replace("_", "/", $no_so);
        } else {
            $so = $no_so;
        }

        if ($produk != "0" && $so == '0') {
            if ($hasil != "semua") {
                $res = NoseriDetailPesanan::where('status', $hasil)
                    ->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                    ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            } else {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                    ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            }
        } else if ($produk == "0" && $so != '0') {
            if ($hasil != "semua") {
                $res = NoseriDetailPesanan::where('status', $hasil)
                    ->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                    ->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($so) {
                        $q->where('so', $so);
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            } else {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                    ->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($so) {
                        $q->where('so', $so);
                    })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            }
        } else if ($produk != "0" && $so != '0') {
            if ($hasil != "semua") {
                $res = NoseriDetailPesanan::where('status', $hasil)
                    ->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                    ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })
                    ->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($so) {
                        $q->where('so', $so);
                    })
                    ->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            } else {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                    ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })
                    ->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($so) {
                        $q->where('so', $so);
                    })
                    ->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            }
        } else if ($produk == "0" && $so == '0') {
            if ($hasil != "semua") {
                $res = NoseriDetailPesanan::where('status', $hasil)
                    ->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            } else {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_produk_id', 'ASC')->get();
            }
        }

        return datatables()->of($res)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->DetailPesananProduk->DetailPesanan->Pesanan->so;
            })
            ->addColumn('produk', function ($data) {
                return $data->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama;
            })
            ->addColumn('noseri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('tgl_uji', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
            })
            ->addColumn('status', function ($data) {
                if ($data->status == "ok") {
                    return '<div><i class="fas fa-check-circle" style="color:green;"></i></div>';
                } else if ($data->status == "nok") {
                    return '<div><i class="fas fa-times-circle" style="color:red;"></i></div>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
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

    public function check_input()
    {
        $data = Pesanan::Has('TFProduksi')->get();
        $z = array();
        $x = array();

        $y = array();
        $c = 0;
        $a = array();
        foreach ($data as $d) {
            $jumlah = 0;
            foreach ($d->detailpesanan as $e) {

                $x[$c] = $e->id;
                $z[$c] = $e->jumlah;
                foreach ($e->penjualanproduk->produk as $l) {
                    $jumlah = $jumlah + ($e->jumlah * $l->pivot->jumlah);
                }
                $c++;
            }
            $id = $d->id;
            $jumlah_seri = NoseriDetailPesanan::whereHas('Detailpesananproduk.detailPEsanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->count();
            $y[] = $jumlah;

            if ($jumlah != $jumlah_seri) {
                $a[] = $d->id;
            }
        }
        return $a;
    }

    //Select
    public function getProdukPesananSelect($id)
    {
        $result = DetailPesananProduk::where('detail_pesanan_id', $id)->with('GudangBarangJadi.Produk')->get();
        return $result;
    }
}
