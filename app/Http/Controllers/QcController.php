<?php

namespace App\Http\Controllers;

use App\Exports\LaporanQcOutgoing;
use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriTGbj;
use App\Models\OutgoingPesananPart;
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
    public function get_data_select_seri($seri_id, $produk_id, $pesanan_id)
    {
        $x = explode(',', $seri_id);
        $data = "";
        if ($seri_id == '0') {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id) {
                $q->where(['gdg_brg_jadi_id' => $produk_id]);
            })->whereHas('detail.header', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->whereDoesntHave('NoseriDetailPesanan', function ($q) {
                $q->where('status', 'ok');
            })->get();
        } else {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id) {
                $q->where(['gdg_brg_jadi_id' => $produk_id]);
            })->whereIN('noseri_id', $x)->whereHas('detail.header.pesanan', function ($q) use ($pesanan_id) {
                $q->where('id', $pesanan_id);
            })->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('seri', function ($data) {
                return $data->NoseriBarangJadi->noseri;
            })->addColumn('noseri_id', function ($data) {
                return '<input type="text" id="noseri_id" name="noseri_id[]" value="' . $data->id . '">';
            })->addColumn('detail_pesanan_produk_id', function ($data) {
                return '<input type="text" id="detail_pesanan_produk_id" name="detail_pesanan_produk_id[]" value="' . $data->detail->paket->id . '">';
            })
            ->rawColumns(['noseri_id', 'detail_pesanan_produk_id'])
            ->make(true);
    }
    public function get_data_seri_detail_ekatalog($jenis, $produk_id, $pesanan_id)
    {

        // $value2 = array();
        // $x = explode(',', $seri_id);
        // if ($seri_id == '0') {
        //     $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id, $tfgbj_id) {
        //         $q->where(['gdg_brg_jadi_id' => $produk_id, 't_gbj_id' => $tfgbj_id]);
        //     })->get();
        //     foreach ($data as $d) {
        //         $value2[] = $d->id;
        //     }
        //     $id =  json_encode($value2);
        // } else {
        //     $data = NoseriTGbj::whereHas('detail', function ($q) use ($produk_id, $tfgbj_id) {
        //         $q->where(['gdg_brg_jadi_id' => $produk_id, 't_gbj_id' => $tfgbj_id]);
        //     })->whereIN('noseri_id', $x)->get();
        //     foreach ($data as $d) {
        //         $value2[] = $d->id;
        //     }
        //     $id =  json_encode($value2);
        // }
        $data = "";
        if ($jenis == "produk") {
            $data = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->where('gudang_barang_jadi_id', $produk_id)->groupby('gudang_barang_jadi_id')->first();
        } else {
            $data = DetailPesananPart::where([
                ['id', '=', $produk_id],
                ['pesanan_id', '=', $pesanan_id]
            ])->first();
        }
        return view('page.qc.so.edit', ['jenis' => $jenis, 'pesanan_id' => $pesanan_id, 'produk_id' => $produk_id, 'data' => $data]);
    }
    public function get_data_seri_ekatalog($status, $id, $idpesanan)
    {
        if ($status == 'semua') {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
                $q->where(['gdg_brg_jadi_id' => $id]);
            })->whereHas('detail.header', function ($q) use ($idpesanan) {
                $q->where(['pesanan_id' => $idpesanan]);
            });
        } elseif ($status == 'belum') {
            $data = NoseriTGbj::DoesntHave('NoseriDetailPesanan')->whereHas('detail', function ($q) use ($id) {
                $q->where(['gdg_brg_jadi_id' => $id]);
            })->whereHas('detail.header', function ($q) use ($idpesanan) {
                $q->where(['pesanan_id' => $idpesanan]);
            });
        } elseif ($status == 'sudah') {
            $data = NoseriTGbj::Has('NoseriDetailPesanan')->whereHas('detail', function ($q) use ($id) {
                $q->where(['gdg_brg_jadi_id' => $id]);
            })->whereHas('detail.header', function ($q) use ($idpesanan) {
                $q->where(['pesanan_id' => $idpesanan]);
            });
        }

        // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
        //     $q->where(['gdg_brg_jadi_id' => $id]);
        // })->whereHas('detail.header', function ($q) use ($idpesanan) {
        //     $q->where(['pesanan_id' => $idpesanan]);
        // });


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                $get = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->first();
                if (empty($get)) {
                    return '  <div class="form-check">
                    <input class=" form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />

                    </div>';
                } else {
                    //   return $get;
                    if ($get->status == 'nok') {
                        return '<div class="form-check">
                    <input class=" form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />

                    </div>';
                    } else {
                        return '';
                    }
                }
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

    public function get_data_part_cek($value)
    {
        $data = OutgoingPesananPart::where('detail_pesanan_part_id', $value)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tanggal_uji', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tanggal_uji)->format('d-m-Y');
            })
            ->addColumn('jumlah_ok', function ($data) {
                return $data->jumlah_ok;
            })
            ->addColumn('jumlah_nok', function ($data) {
                return $data->jumlah_nok;
            })
            ->make(true);
    }
    public function get_data_detail_so($id)
    {
        // $x = explode(',', $id);
        $dataprd = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->groupby('gudang_barang_jadi_id')->get();
        $datapart = DetailPesananPart::where('pesanan_id', $id)->whereHas('Sparepart', function($q){
            $q->where('kode', 'NOT LIKE', '%JASA%');
        })->get();
        $data = $dataprd->merge($datapart);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                $string = "";
                if (isset($data->gudang_barang_jadi_id)) {
                    if (empty($data->gudangbarangjadi->nama)) {
                        $string .= $data->gudangbarangjadi->produk->nama;
                    } else {
                        $string .= $data->gudangbarangjadi->produk->nama . " - <b>" . $data->gudangbarangjadi->nama . "</b>";
                    }
                } else {
                    $string .= $data->Sparepart->nama;
                }
                return $string;
            })
            ->addColumn('jumlah', function ($data) {
                // $j = DetailPesanan::whereIN('id', $x)->whereHas('DetailPesananProduk', function ($q) use ($id) {
                // })->get();
                // $jumlah_pesanan = 0;
                // $jumlah_pivot = 0;
                // $jumlah = 0;
                // foreach ($j->detailpesanan as $k) {
                //     // if ($data->gdg_barang_jadi_id == $k->DetailPesananProduk->gdg_barang_jadi_id) {
                //     $jumlah_pesanan = $k->jumlah;
                //     foreach ($k->PenjualanProduk as $l) {
                //         if ($l->produk->id == $data->GudangBarangJadi->produk_id) {
                //             $jumlah_pivot = $l->produk->pivot->jumlah;
                //             $jumlah = $jumlah + ($jumlah_pesanan * $jumlah_pivot);
                //         }
                //     }
                //     // }
                // }
                // return $jumlah;

                //V1
                $jumlah = 0;
                if (isset($data->gudang_barang_jadi_id)) {
                    $id = $data->gudang_barang_jadi_id;
                    $pesanan_id = $data->DetailPesanan->pesanan_id;
                    $jumlah = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
                        $q->where('gdg_brg_jadi_id', $id);
                    })->whereHas('detail.header', function ($q) use ($pesanan_id) {
                        $q->where('pesanan_id', $pesanan_id);
                    })->count();
                } else {
                    $jumlah = $data->jumlah;
                }
                return $jumlah;

                //V2
                // $jumlah = $data->getJumlahPesanan();
                // return $jumlah;

                // return $data->detailpesanan->jumlah * $data->detailpesanan->Penjualanproduk->produk->first()->pivot->jumlah;
            })
            ->addColumn('jumlah_ok', function ($data) use ($id) {
                if (isset($data->gudang_barang_jadi_id)) {
                    $ids = $data->gudang_barang_jadi_id;
                    $c = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($ids) {
                        $q->where([
                            ['gudang_barang_jadi_id', '=', $ids],
                            ['status', '=', 'ok']
                        ]);
                    })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->get()->count();
                    return $c;
                } else {
                    if (count($data->OutgoingPesananPart) > 0) {
                        return $data->getJumlahCekPart('ok');
                    } else {
                        return '0';
                    }
                }
            })
            ->addColumn('jumlah_nok', function ($data) use ($id) {
                if (isset($data->gudang_barang_jadi_id)) {
                    $ids = $data->gudang_barang_jadi_id;
                    $c = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($ids) {
                        $q->where([
                            ['gudang_barang_jadi_id', '=', $ids],
                            ['status', '=', 'nok']
                        ]);
                    })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->get()->count();
                    return $c;
                } else {
                    if (count($data->OutgoingPesananPart) > 0) {
                        return $data->getJumlahCekPart('nok');
                    } else {
                        return '0';
                    }
                }
            })
            ->addColumn('button', function ($data) use ($id) {
                if (isset($data->gudang_barang_jadi_id)) {
                    $ids = $data->gudang_barang_jadi_id;
                    $countok = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($ids) {
                        $q->where([
                            ['gudang_barang_jadi_id', '=', $ids],
                            ['status', '=', 'ok']
                        ]);
                    })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->get()->count();

                    $countnok = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($ids) {
                        $q->where([
                            ['gudang_barang_jadi_id', '=', $ids],
                            ['status', '=', 'ok']
                        ]);
                    })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->get()->count();

                    $jumlahditrf = NoseriTGbj::whereHas('detail', function ($q) use ($ids) {
                        $q->where('gdg_brg_jadi_id', $ids);
                    })->whereHas('detail.header', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->count();

                    $bool = "0";
                    if ($jumlahditrf > 0) {
                        if ($jumlahditrf == $countok) {
                            return '<a type="button" class="noserishow" data-count="0" data-id="' . $data->gudang_barang_jadi_id . '" data-jenis="produk"><i class="fas fa-search"></i></a>';
                        } else {
                            return '<a type="button" class="noserishow" data-count="1" data-id="' . $data->gudang_barang_jadi_id . '" data-jenis="produk"><i class="fas fa-search"></i></a>';
                        }
                    }
                } else {
                    if ($data->jumlah == $data->getJumlahCekPart('ok')) {
                        return '<a type="button" class="noserishow" data-count="0" data-id="' . $data->id . '" data-jenis="part"><i class="fas fa-search"></i></a>';
                    } else {
                        return '<a type="button" class="noserishow" data-count="1" data-id="' . $data->id . '" data-jenis="part"><i class="fas fa-search"></i></a>';
                    }
                }

                // $id = $data->gudang_barang_jadi_id;
                // $pesanan_id = $data->DetailPesanan->pesanan_id;

                // $ok = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id, $x) {
                //     $q->where([
                //         ['gudang_barang_jadi_id', '=', $id],
                //         ['status', '=', 'ok']
                //     ])->whereIn('detail_pesanan_id', $x);
                // })->get()->count();
                // $nok = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id, $x) {
                //     $q->where([
                //         ['gudang_barang_jadi_id', '=', $id],
                //         ['status', '=', 'nok']
                //     ])->whereIn('detail_pesanan_id', $x);
                // })->get()->count();

                // $jumlahditrf = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
                //     $q->where('gdg_brg_jadi_id', $id);
                // })->whereHas('detail.header', function ($q) use ($pesanan_id) {
                //     $q->where('pesanan_id', $pesanan_id);
                // })->count();

                // $bool = "0";
                // if ($jumlahditrf > 0) {
                //     if ($jumlahditrf == $ok) {
                //         return '<a type="button" class="noserishow" data-count="0" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
                //     } else {
                //         return '<a type="button" class="noserishow" data-count="1" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
                //     }
                // }
            })
            ->rawColumns(['nama_produk', 'button'])
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
        $data = "";
        $x = explode(',', $value);
        if ($value == 'semua') {
            $data = Pesanan::whereIN('id', $this->check_input())->orderby('id', 'ASC')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get();
        } else if ($x == ['ekatalog', 'spa']) {
            $Ekat = collect(Pesanan::whereIN('id', $this->check_input())->has('Ekatalog')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get());
            $Spa = collect(Pesanan::whereIN('id', $this->check_input())->has('Spa')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get());
            $data = $Ekat->merge($Spa);
        } else if ($x == ['ekatalog', 'spb']) {
            $Ekat = collect(Pesanan::whereIN('id', $this->check_input())->has('Ekatalog')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get());
            $Spb = collect(Pesanan::whereIN('id', $this->check_input())->has('Spb')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get());
            $data = $Ekat->merge($Spb);
        } else if ($x == ['spa', 'spb']) {
            $Spa = collect(Pesanan::whereIN('id', $this->check_input())->has('Spa')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get());
            $Spb = collect(Pesanan::whereIN('id', $this->check_input())->has('Spb')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get());
            $data = $Spa->merge($Spb);
        } else if ($value == 'ekatalog') {
            $data = Pesanan::whereIN('id', $this->check_input())->has('Ekatalog')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get();
        } else if ($value == 'spa') {
            $data = Pesanan::whereIN('id', $this->check_input())->has('Spa')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get();
        } else if ($value == 'spb') {
            $data = Pesanan::whereIN('id', $this->check_input())->has('Spb')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get();
        } else {
            $data = Pesanan::whereIN('id', $this->check_input())->orderby('id', 'ASC')->orHas('DetailPesanan')->orWhereHas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get();
        }


        $arrayid = array();

        foreach ($data as $i) {
            if (count($i->DetailPesanan) > 0 && count($i->DetailPesananPart) <= 0) {
                if ($i->getJumlahSeri() > 0 && $i->getJumlahPesanan() > $i->getJumlahCek()) {
                    $arrayid[] = $i->id;
                }
            } else if (count($i->DetailPesanan) <= 0 && count($i->DetailPesananPart) > 0) {
                if ($i->getJumlahPesananPart() > $i->getJumlahCekPart("ok")) {
                    $arrayid[] = $i->id;
                }
            } else {
                if (($i->getJumlahSeri() > 0 && $i->getJumlahPesanan() > $i->getJumlahCek()) || $i->getJumlahPesananPart() > $i->getJumlahCekPart("ok")) {
                    $arrayid[] = $i->id;
                }
            }
        }

        $s = Pesanan::whereIn('id', $arrayid)->get();

        // echo json_encode($data);
        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('nama_customer', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->satuan_kerja;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } else {
                        return $data->spb->Customer->nama;
                    }
                }
            })
            ->addColumn('batas_uji', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        if ($data->getJumlahPesanan() == $data->getJumlahCek()) {
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
                        return '-';
                    }
                }
            })
            ->addColumn('status', function ($data) {
                if (count($data->DetailPesanan) > 0 && count($data->DetailPesananPart) <= 0) {
                    if ($data->getJumlahCek() == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        if ($data->getJumlahCek() >= $data->getJumlahPesanan()) {
                            return  '<span class="badge green-text">Selesai</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                } else if (count($data->DetailPesanan) <= 0 && count($data->DetailPesananPart) > 0) {
                    if ($data->getJumlahCekPart('ok') == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        if ($data->getJumlahCekPart('ok') >= $data->getJumlahPesananPart()) {
                            return  '<span class="badge green-text">Selesai</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                } else if (count($data->DetailPesanan) > 0 && count($data->DetailPesananPart) > 0) {
                    if ($data->getJumlahCek() == 0 && $data->getJumlahCekPart('ok') == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        if (($data->getJumlahCek() >= $data->getJumlahPesanan()) && ($data->getJumlahCekPart('ok') >= $data->getJumlahPesananPart())) {
                            return  '<span class="badge green-text">Selesai</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                }
            })
            ->addColumn('button', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x =  'spa';
                    } else {
                        $x =  'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '">
                                <i class="fas fa-search"></i>
                        </a>';
                }
            })
            ->rawColumns(['button', 'status', 'batas_uji'])
            ->make(true);
    }

    public function get_data_selesai_so($value)
    {
        $data = "";
        $x = explode(',', $value);
        if ($value == 'semua') {
            $data = Pesanan::orderby('id', 'ASC')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get();
        } else if ($x == ['ekatalog', 'spa']) {
            $Ekat = collect(Pesanan::has('Ekatalog')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get());
            $Spa = collect(Pesanan::has('Spa')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get());
            $data = $Ekat->merge($Spa);
        } else if ($x == ['ekatalog', 'spb']) {
            $Ekat = collect(Pesanan::has('Ekatalog')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get());
            $Spb = collect(Pesanan::has('Spb')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get());
            $data = $Ekat->merge($Spb);
        } else if ($x == ['spa', 'spb']) {
            $Spa = collect(Pesanan::has('Spa')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get());
            $Spb = collect(Pesanan::has('Spb')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get());
            $data = $Spa->merge($Spb);
        } else if ($value == 'ekatalog') {
            $data = Pesanan::has('Ekatalog')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get();
        } else if ($value == 'spa') {
            $data = Pesanan::has('Spa')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get();
        } else if ($value == 'spb') {
            $data = Pesanan::has('Spb')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get();
        } else {
            $data = Pesanan::orderby('id', 'ASC')->orHas('DetailPesanan')->orHas('DetailPesananPart')->get();
        }

        $arrayid = array();

        foreach ($data as $i) {
            if (count($i->DetailPesanan) > 0 && count($i->DetailPesananPart) <= 0) {
                if ($i->getJumlahPesanan() == $i->getJumlahCek()) {
                    $arrayid[] = $i->id;
                }
            } else if (count($i->DetailPesanan) <= 0 && count($i->DetailPesananPart) > 0) {
                if ($i->getJumlahPesananPart() == $i->getJumlahCekPart("ok")) {
                    $arrayid[] = $i->id;
                }
            } else {
                if (($i->getJumlahPesanan() == $i->getJumlahCek()) && ($i->getJumlahPesananPart() == $i->getJumlahCekPart("ok"))) {
                    $arrayid[] = $i->id;
                }
            }
        }

        $s = Pesanan::whereIn('id', $arrayid)->get();

        // echo json_encode($data);
        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('nama_customer', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->satuan_kerja;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } else {
                        return $data->spb->Customer->nama;
                    }
                }
            })
            ->addColumn('batas_uji', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        if ($data->getJumlahPesanan() == $data->getJumlahCek()) {
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
                        return '-';
                    }
                }
            })
            ->addColumn('status', function ($data) {
                if (count($data->DetailPesanan) > 0 && count($data->DetailPesananPart) <= 0) {
                    if ($data->getJumlahCek() == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        if ($data->getJumlahCek() >= $data->getJumlahPesanan()) {
                            return  '<span class="badge green-text">Selesai</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                } else if (count($data->DetailPesanan) <= 0 && count($data->DetailPesananPart) > 0) {
                    if ($data->getJumlahCekPart('ok') == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        if ($data->getJumlahCekPart('ok') >= $data->getJumlahPesananPart()) {
                            return  '<span class="badge green-text">Selesai</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                } else if (count($data->DetailPesanan) > 0 && count($data->DetailPesananPart) > 0) {
                    if ($data->getJumlahCek() == 0 && $data->getJumlahCekPart('ok') == 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        if (($data->getJumlahCek() >= $data->getJumlahPesanan()) && ($data->getJumlahCekPart('ok') >= $data->getJumlahPesananPart())) {
                            return  '<span class="badge green-text">Selesai</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                        }
                    }
                }
            })
            ->addColumn('button', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x =  'spa';
                    } else {
                        $x =  'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '">
                                <i class="fas fa-search"></i>
                        </a>';
                }
            })
            ->rawColumns(['button', 'status', 'batas_uji'])
            ->make(true);
    }

    public function get_data_riwayat_pengujian()
    {
        $prd = DetailPesanan::Has('DetailPesananProduk.NoSeriDetailPesanan')->get();
        $part = DetailPesananPart::Has('OutgoingPesananPart')->get();
        $s = $prd->merge($part);
        $data = array();
        $c = 0;
        // foreach ($s as $i) {
        //     if (count($i->penjualan_produk_id) > 0) {
        //         if ($i->getJumlahPesanan() == $i->countNoSeri()) {
        //             //     $data[$c]['x'] = $i->DetailPesananProduk->GudangBarangkadiProduk->nama;
        //             $data[$c]['id'] = $i->id;
        //             $data[$c]['so'] = $i->Pesanan->so;
        //             $data[$c]['nama_produk'] = $i->PenjualanProduk->nama;
        //             $data[$c]['produk_count'] = $i->PenjualanProduk->Produk->count();
        //             if ($i->PenjualanProduk->Produk->count() <= 1) {
        //                 $data[$c]['produk_id'] = $i->DetailPesananProduk->first()->id;
        //             }
        //             $data[$c]['tgl_mulai'] = $i->getTanggalUji()->tgl_mulai;
        //             $data[$c]['tgl_selesai'] = $i->getTanggalUji()->tgl_selesai;
        //             $data[$c]['jumlah'] = $i->jumlah;
        //             $data[$c]['penjualan_produk_id'] = $i->penjualan_produk_id;
        //             $data[$c]['jenis'] = "produk";
        //             $c++;
        //         }
        //     } else {
        //         if ($i->getJumlahPesananPart() == $i->getJumlahCekPart('ok')) {
        //             $data[$c]['id'] = $i->id;
        //             $data[$c]['so'] = $i->Pesanan->so;
        //             $data[$c]['nama_produk'] = $i->Sparepart->nama;
        //             $data[$c]['produk_count'] = $i->Sparepart->count();
        //             // if ($i->PenjualanProduk->Produk->count() <= 1) {
        //             //     $data[$c]['produk_id'] = $i->DetailPesananProduk->first()->id;
        //             // }
        //             $data[$c]['tgl_mulai'] = $i->getTanggalUji()->tgl_mulai;
        //             $data[$c]['tgl_selesai'] = $i->getTanggalUji()->tgl_selesai;
        //             $data[$c]['jumlah'] = $i->jumlah;
        //             // $data[$c]['penjualan_produk_id'] = $i->penjualan_produk_id;
        //             $data[$c]['jenis'] = "part";
        //             $c++;
        //         }
        //     }
        // }
        // return datatables()->of($data)
        //     ->addIndexColumn()
        //     ->addColumn('so', function ($data) {
        //         return $data['so'];
        //     })
        //     ->addColumn('nama_produk', function ($data) {
        //         return $data['nama_produk'];
        //     })
        //     ->addColumn('tgl_mulai', function ($data) {
        //         return Carbon::createFromFormat('Y-m-d', $data['tgl_mulai'])->format('d-m-Y');
        //     })
        //     ->addColumn('tgl_selesai', function ($data) {
        //         return Carbon::createFromFormat('Y-m-d', $data['tgl_selesai'])->format('d-m-Y');
        //     })
        //     ->addColumn('jumlah', function ($data) {
        //         return $data['jumlah'];
        //     })
        //     ->addColumn('button', function ($data) {
        //         if ($data['jenis'] == "produk") {
        //             $produk_id = "";
        //             if (isset($data['produk_id'])) {
        //                 $produk_id = $data['produk_id'];
        //             }
        //             return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data['penjualan_produk_id'] . '" data-id="' . $data['id'] . '" data-count="' . $data['produk_count'] . '" data-produk="' . $produk_id . '" id="detmodal">
        //             <div><i class="fas fa-search"></i></div>
        //         </a>';
        //         }
        //     })
        //     ->rawColumns(['button', 'nama_produk'])
        //     ->make(true);
        $prdarr = array();
        foreach ($prd as $i) {
            if ($i->getJumlahPesanan() == $i->getJumlahCek()) {
                $prdarr[] = $i->id;
            }
        }

        $partarr = array();
        foreach ($part as $i) {
            if ($i->jumlah == $i->getJumlahCekPart("ok")) {
                $partarr[] = $i->id;
            }
        }

        $prdres = DetailPesanan::whereIn('id', $prdarr)->get();
        $partres = DetailPesananPart::whereIn('id', $partarr)->get();

        $data = $prdres->merge($partres);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->penjualan_produk_id)) {
                    return $data->PenjualanProduk->nama;
                } else {
                    return $data->Sparepart->nama;
                }
            })
            ->addColumn('tgl_mulai', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->getTanggalUji()->tgl_mulai)->format('d-m-Y');
            })
            ->addColumn('tgl_selesai', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->getTanggalUji()->tgl_selesai)->format('d-m-Y');
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                if (isset($data->penjualan_produk_id)) {
                    $produkcount = $data->PenjualanProduk->Produk->count();
                    $produkid = "";
                    if ($produkcount <= 1) {
                        $produkid = $data->DetailPesananProduk->first()->id;
                    }
                    return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data->penjualan_produk_id . '" data-id="' . $data->id . '" data-count="' . $produkcount . '" data-produk="' . $produkid . '" data-jenis="produk" id="detmodal">
                        <div><i class="fas fa-search"></i></div>
                    </a>';
                } else {
                    return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data->part_id . '" data-id="' . $data->id . '" data-count="1" data-produk="0" data-jenis="part" id="detmodal">
                        <div><i class="fas fa-search"></i></div>
                    </a>';
                }
                // if ($data['jenis'] == "produk") {
                //     $produk_id = "";
                //     if (isset($data['produk_id'])) {
                //         $produk_id = $data['produk_id'];
                //     }
                //     return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data['penjualan_produk_id'] . '" data-id="' . $data['id'] . '" data-count="' . $data['produk_count'] . '" data-produk="' . $produk_id . '" id="detmodal">
                //     <div><i class="fas fa-search"></i></div>
                // </a>';
                // }
            })
            ->rawColumns(['button', 'nama_produk'])
            ->make(true);
    }

    public function get_data_detail_riwayat_pengujian($id, $jenis)
    {
        $s = "";
        if ($jenis == "produk") {
            $s = NoseriDetailPesanan::where('detail_pesanan_produk_id', $id)->get();
        } else if ($jenis == "part") {
            $s = OutgoingPesananPart::where('detail_pesanan_part_id', $id)->get();
        }

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    return $data->NoseriTGbj->NoseriBarangJadi->noseri;
                } else {
                    return '-';
                }
            })
            ->addColumn('hasil', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    if ($data->status == "ok") {
                        return '<div><i class="fas fa-check-circle" style="color:green;"></div>';
                    } else if ($data->status == "nok") {
                        return '<div><i class="fas fa-times-circle" style="color:red;"></div>';
                    };
                } else {
                    return '-';
                }
            })
            ->addColumn('tanggal_uji', function ($data) use ($jenis) {
                if ($jenis == "part") {
                    return Carbon::createFromFormat('Y-m-d', $data->tanggal_uji)->format('d-m-Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('jumlah_ok', function ($data) use ($jenis) {
                if ($jenis == "part") {
                    return $data->jumlah_ok;
                } else {
                    return '-';
                }
            })
            ->addColumn('jumlah_nok', function ($data) use ($jenis) {
                if ($jenis == "part") {
                    return $data->jumlah_nok;
                } else {
                    return '-';
                }
            })
            ->rawColumns(['hasil'])
            ->make(true);
    }

    //Detail
    public function update_modal_so()
    {
        return view('page.qc.so.edit', ['']);
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

            $ds = Pesanan::find($id);


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

            if (count($ds->DetailPesanan) > 0 && count($ds->DetailPesananPart) <= 0) {
                if ($ds->getJumlahCek() == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if ($ds->getJumlahCek() >= $ds->getJumlahPesanan()) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            } else if (count($ds->DetailPesanan) <= 0 && count($ds->DetailPesananPart) > 0) {
                if ($ds->getJumlahCekPart('ok') == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPart()) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            } else if (count($ds->DetailPesanan) > 0 && count($ds->DetailPesananPart) > 0) {
                if ($ds->getJumlahCek() == 0 && $ds->getJumlahCekPart('ok') == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if (($ds->getJumlahCek() >= $ds->getJumlahPesanan()) && ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPart())) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
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
            return view('page.qc.so.detail_ekatalog', ['id' => $id, 'data' => $data, 'detail_id' => $detail_id, 'param' => $param, 'status' => $status]);
        } elseif ($value == 'spa') {
            $data = Spa::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $ds = Pesanan::find($id);
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

            if (isset($ds->DetailPesanan) && !isset($ds->DetailPesananPart)) {
                if ($ds->getJumlahCek() == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if ($ds->getJumlahCek() >= $ds->getJumlahPesanan()) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            } else if (!isset($ds->DetailPesanan) && isset($ds->DetailPesananPart)) {
                if ($ds->getJumlahCekPart('ok') == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPart()) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            } else if (isset($ds->DetailPesanan) > 0 && isset($ds->DetailPesananPart) > 0) {
                if ($ds->getJumlahCek() == 0 && $ds->getJumlahCekPart('ok') == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if (($ds->getJumlahCek() >= $ds->getJumlahPesanan()) && ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPart())) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            }

            return view('page.qc.so.detail_spa', ['id' => $id, 'data' => $data,  'detail_id' => $detail_id, 'status' => $status]);
        } else {
            $data = Spb::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $ds = Pesanan::find($id);

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

            if (isset($ds->DetailPesanan) && !isset($ds->DetailPesananPart)) {
                if ($ds->getJumlahCek() == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if ($ds->getJumlahCek() >= $ds->getJumlahPesanan()) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            } else if (!isset($ds->DetailPesanan) && isset($ds->DetailPesananPart)) {
                if ($ds->getJumlahCekPart('ok') == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPart()) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            } else if (isset($ds->DetailPesanan) && isset($ds->DetailPesananPart)) {
                if ($ds->getJumlahCek() == 0 && $ds->getJumlahCekPart('ok') == 0) {
                    $status = '<span class="badge red-text">Belum diuji</span>';
                } else {
                    if (($ds->getJumlahCek() >= $ds->getJumlahPesanan()) && ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPart())) {
                        $status =  '<span class="badge green-text">Selesai</span>';
                    } else {
                        $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                }
            }

            return view('page.qc.so.detail_spb', ['id' => $id, 'data' => $data, 'detail_id' => $detail_id, 'status' => $status]);
        }
    }

    public function detail_modal_riwayat_so($id, $jenis)
    {
        $result = "";
        if ($jenis == "produk") {
            $result = DetailPesanan::find($id);
        } else {
            $result = DetailPesananPart::find($id);
        }
        return view('page.qc.so.riwayat.detail', ['id' => $id, 'res' => $result, 'jenis' => $jenis]);
    }

    //Tambah
    public function create_data_qc(/*$seri_id, $tfgbj_id, */$jenis, $pesanan_id, $produk_id, Request $request)
    {
        // $data = DetailPesananProduk::whereHas('DetailPesanan.Pesanan', function ($q) use ($pesanan_id) {
        //     $q->where('Pesanan_id', $pesanan_id);
        // })->where('gudang_barang_jadi_id', $produk_id)->first();

        // $replace_array_seri = strtr($seri_id, array('[' => '', ']' => ''));
        // $array_seri = explode(',', $replace_array_seri);

        $bool = true;
        $bools = true;
        // for ($i = 0; $i < count($array_seri); $i++) {

        //     $data = NoseriTGbj::find($array_seri[$i]);


        //     $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', '=', $array_seri[$i])->first();
        //     if ($check == null) {
        //         $c = NoseriDetailPesanan::create([
        //             'detail_pesanan_produk_id' => $data->detail->detail_pesanan_produk_id,
        //             't_tfbj_noseri_id' => $array_seri[$i],
        //             'status' => $request->cek,
        //             'tgl_uji' => $request->tanggal_uji,
        //         ]);
        //         if (!$c) {
        //             $bool = false;
        //         }
        //     } else {
        //         $NoseriDetailPesanan = NoseriDetailPesanan::find($check->id);
        //         $NoseriDetailPesanan->status = $request->cek;
        //         $NoseriDetailPesanan->tgl_uji = $request->tanggal_uji;
        //         $u = $NoseriDetailPesanan->save();
        //         if (!$u) {
        //             $bool = false;
        //         }
        //     }
        // }
        if ($jenis == "produk") {
            for ($i = 0; $i < count($request->noseri_id); $i++) {
                $data = NoseriTGbj::find($request->noseri_id[$i]);
                $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', '=', $request->noseri_id[$i])->first();
                if ($check == null) {
                    $c = NoseriDetailPesanan::create([
                        'detail_pesanan_produk_id' => $data->detail->detail_pesanan_produk_id,
                        't_tfbj_noseri_id' => $request->noseri_id[$i],
                        'status' => $request->cek,
                        'tgl_uji' => $request->tanggal_uji,
                    ]);
                    if (!$c) {
                        $bool = false;
                        $bools = false;
                    }
                } else {
                    $NoseriDetailPesanan = NoseriDetailPesanan::find($check->id);
                    $NoseriDetailPesanan->status = $request->cek;
                    $NoseriDetailPesanan->tgl_uji = $request->tanggal_uji;
                    $u = $NoseriDetailPesanan->save();
                    if (!$u) {
                        $bool = false;
                        $bools = false;
                    }
                }
            }
        } else if ($jenis == "part") {
            $data = OutgoingPesananPart::create([
                'detail_pesanan_part_id' => $produk_id,
                'tanggal_uji' => $request->tanggal_uji,
                'jumlah_ok' => $request->jumlah_ok,
                'jumlah_nok' => $request->jumlah_nok
            ]);

            if (!$data) {
                $bool = false;
                $bools = false;
            }
        }

        if ($bool == true) {
            // $uk = "";
            $po = Pesanan::find($pesanan_id);

            // $uk = count($po->DetailPesanan)." ".count($po->DetailPesananPart);
            if (count($po->DetailPesanan) > 0 && count($po->DetailPesananPart) <= 0) {
                if ($po->log_id == "8") {
                    // $uk = "Jumlah Pesan Produk ".$po->getJumlahPesanan()." Jumlah Cek Produk ".$po->getJumlahCek();
                    if ($po->getJumlahPesanan() == $po->getJumlahCek()) {
                        if ($po->getJumlahKirim() == 0) {
                            $pou = Pesanan::find($pesanan_id);
                            $pou->log_id = '11';
                            $u = $pou->save();
                            if (!$u) {
                                $bools = false;
                            }
                        } else {
                            if ($po->getJumlahKirim() >= $po->getJumlahPesanan()) {
                                $pou = Pesanan::find($pesanan_id);
                                $pou->log_id = '10';
                                $u = $pou->save();
                                if (!$u) {
                                    $bools = false;
                                }
                            } else {
                                $pou = Pesanan::find($pesanan_id);
                                $pou->log_id = '13';
                                $u = $pou->save();
                                if (!$u) {
                                    $bools = false;
                                }
                            }
                        }
                    }
                }
            } else if (count($po->DetailPesanan) <= 0 && count($po->DetailPesananPart) > 0) {
                // $uk = "Jumlah Pesan Part ".$po->getJumlahPesananPart()." Jumlah Cek Part ".$po->getJumlahCekPart("ok");
                if ($po->getJumlahPesananPart() <= $po->getJumlahCekPart("ok")) {
                    if ($po->getJumlahKirimPart() == 0) {
                        $pou = Pesanan::find($pesanan_id);
                        $pou->log_id = '11';
                        $u = $pou->save();
                        if (!$u) {
                            $bools = false;
                        }
                    } else {
                        if ($po->getJumlahKirimPart() >= $po->getJumlahPesananPart()) {
                            $pou = Pesanan::find($pesanan_id);
                            $pou->log_id = '10';
                            $u = $pou->save();
                            if (!$u) {
                                $bools = false;
                            }
                        } else {
                            $pou = Pesanan::find($pesanan_id);
                            $pou->log_id = '13';
                            $u = $pou->save();
                            if (!$u) {
                                $bools = false;
                            }
                        }
                    }
                } else if ($po->getJumlahPesananPart() > $po->getJumlahCekPart("ok")) {
                    $pou = Pesanan::find($pesanan_id);
                    $pou->log_id = '8';
                    $u = $pou->save();
                    if (!$u) {
                        $bools = false;
                    }
                }
            } else if (count($po->DetailPesanan) > 0 && count($po->DetailPesananPart) > 0) {
                // $uk = "Jumlah Pesan Produk ".$po->getJumlahPesanan()." Jumlah Cek Produk ".$po->getJumlahCek()." Jumlah Pesan Part ".$po->getJumlahPesananPart()." Jumlah Cek Part ".$po->getJumlahCekPart("ok");
                if ($po->log_id == "8") {
                    if (($po->getJumlahPesanan() == $po->getJumlahCek()) && ($po->getJumlahPesananPart() == $po->getJumlahCekPart("ok"))) {
                        if ($po->getJumlahKirim() == 0 && $po->getJumlahKirimPart() == 0) {
                            $pou = Pesanan::find($pesanan_id);
                            $pou->log_id = '11';
                            $u = $pou->save();
                            if (!$u) {
                                $bools = false;
                            }
                        } else if ($po->getJumlahKirim() > 0 || $po->getJumlahKirimPart() > 0) {
                            if ($po->getJumlahKirim() >= $po->getJumlahPesanan() && $po->getJumlahKirimPart() >= $po->getJumlahPesananPart()) {
                                $pou = Pesanan::find($pesanan_id);
                                $pou->log_id = '10';
                                $u = $pou->save();
                                if (!$u) {
                                    $bools = false;
                                }
                            } else {
                                $pou = Pesanan::find($pesanan_id);
                                $pou->log_id = '13';
                                $u = $pou->save();
                                if (!$u) {
                                    $bools = false;
                                }
                            }
                        }
                    }
                }
            }

            if ($bools == true) {
                return response()->json(['data' => 'success']);
            }
        } else if ($bool == false) {
            return response()->json(['data' =>  'error']);
        }
    }
    //Dashboard
    public function dashboard()
    {
        $a = $this->check_input();
        $terbaru = 0;
        $hasil = 0;
        $lewatbatas = "";

        $data = Pesanan::whereNotIn('log_id', ['7', '10'])->orderby('id', "DESC")->get();

        $terbaruprd = Pesanan::whereHas('TFProduksi', function ($q) {
            $q->where('tgl_keluar', '>=', Carbon::now()->subdays(7));
        })->whereIN('id',  $this->check_input())->get();
        $terbaruprt = Pesanan::wherehas('DetailPesananPart.Sparepart', function ($q) {
            $q->where('nama', 'not like', '%JASA%');
        })->where('tgl_po', '>=', Carbon::now()->subdays(7))->get();

        $cekterbaru = $terbaruprd->merge($terbaruprt);
        foreach ($cekterbaru as $j) {
            if ($j->getJumlahCek() == 0 && $j->getJumlahCekPart("ok") == 0) {
                $terbaru++;
            }
        }
        $cekhasil = Pesanan::whereIN('id', $this->check_input())->orderby('id', 'ASC')->orHas('DetailPesanan')->orWherehas('DetailPesananPart.Sparepart', function ($q) {
            $q->where('nama', 'not like', '%JASA%');
        })->get();

        $arrayid = array();
        foreach ($cekhasil as $h) {
            if (count($h->DetailPesanan) > 0 && count($h->DetailPesananPart) <= 0) {
                if ($h->getJumlahSeri() > 0 && $h->getJumlahPesanan() > $h->getJumlahCek()) {
                    $arrayid[] = $h->id;
                }
            } else if (count($h->DetailPesanan) <= 0 && count($h->DetailPesananPart) > 0) {
                if ($h->getJumlahPesananPart() > $h->getJumlahCekPart("ok")) {
                    $arrayid[] = $h->id;
                }
            } else {
                if (($h->getJumlahSeri() > 0 && $h->getJumlahPesanan() > $h->getJumlahCek()) || $h->getJumlahPesananPart() > $h->getJumlahCekPart("ok")) {
                    $arrayid[] = $h->id;
                }
            }
        }
        $hasil = Pesanan::whereIn('id', $arrayid)->get()->count();


        $lewat_batas_data = Pesanan::has('Ekatalog')->whereIN('id',  $this->check_input())->get();
        $tgl_sekarang = Carbon::now()->format('Y-m-d');
        $lewat_batas = 0;
        foreach ($lewat_batas_data as $l) {
            $tgl_parameter = $this->getHariBatasKontrak($l->ekatalog->tgl_kontrak, $l->ekatalog->provinsi->status)->format('Y-m-d');
            if ($tgl_sekarang > $tgl_parameter) {
                $lewat_batas++;
            }
        }

        $cpo = Pesanan::where('log_id', ['9'])->count();
        $cgudang = Pesanan::where('log_id', ['6'])->count();
        $clogistik = Pesanan::where('log_id', ['11', '13'])->count();
        return view('page.qc.dashboard', ['terbaru' => $terbaru, 'hasil' => $hasil, 'lewat_batas' => $lewat_batas, 'po' => $cpo, 'gudang' => $cgudang, 'logistik' => $clogistik]);
    }
    public function dashboard_data($value)
    {
        $a = $this->check_input();

        if ($value == 'terbaru') {
            $terbaruprd = Pesanan::whereHas('TFProduksi', function ($q) {
                $q->where('tgl_keluar', '>=', Carbon::now()->subdays(7));
            })->whereIN('id',  $this->check_input())->get();
            $terbaruprt = Pesanan::wherehas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->where('tgl_po', '>=', Carbon::now()->subdays(7))->get();
            $terbaru_data = $terbaruprd->merge($terbaruprt);
            $terbaru_id = [];
            foreach ($terbaru_data as $j) {
                if ($j->getJumlahCek() == 0 && $j->getJumlahCekPart("ok") == 0) {
                    $terbaru_id[] = $j->id;
                }
            }

            $prd = Pesanan::has('DetailPesanan')->whereIN('id', $terbaru_id)->get();
            $part = Pesanan::wherehas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->whereIN('id', $terbaru_id)->get();

            $data = $prd->merge($part);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
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
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    if (isset($data->DetailPesanan) && !isset($data->DetailPesananPart)) {
                        if ($data->getJumlahPesanan() <= $data->getJumlahCek()) {
                            return '<span class="badge green-text">Selesai</span>';
                        } else {
                            if ($data->getJumlahCek() == 0) {
                                return '<span class="badge red-text">Belum diuji</span>';
                            } else {
                                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
                            }
                        }
                    } else if (!isset($data->DetailPesanan) && isset($data->DetailPesananPart)) {
                        if ($data->getJumlahPesananPart() <= $data->getJumlahCekPart("ok")) {
                            return '<span class="badge green-text">Selesai</span>';
                        } else {
                            if ($data->getJumlahCekPart("ok") == 0) {
                                return '<span class="badge red-text">Belum diuji</span>';
                            } else {
                                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
                            }
                        }
                    } else if (isset($data->DetailPesanan) && isset($data->DetailPesananPart)) {
                        if ($data->getJumlahPesanan() <= $data->getJumlahCek() && $data->getJumlahPesananPart() <= $data->getJumlahCekPart("ok")) {
                            return '<span class="badge green-text">Selesai</span>';
                        } else {
                            if ($data->getJumlahCek() == 0 && $data->getJumlahCekPart("ok") == 0) {
                                return '<span class="badge red-text">Belum diuji</span>';
                            } else {
                                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
                            }
                        }
                    }
                    // $z = array();
                    // $x = array();

                    // $jumlah = 0;
                    // foreach ($data->detailpesanan as $d) {
                    //     $x[] = $d->id;
                    //     $z[] = $d->jumlah;
                    //     foreach ($d->penjualanproduk->produk as $l) {
                    //         $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                    //     }
                    // }

                    // $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();
                    // $y = array();

                    // foreach ($detail_pesanan_produk as $d) {
                    //     $y[] = $d->id;
                    // }

                    // $jumlah_seri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get()->count();


                    // if ($jumlah == $jumlah_seri) {
                    //     return  '<span class="badge green-text">Selesai</span>';
                    // } else {
                    //     if ($jumlah_seri == 0) {
                    //         return '<span class="badge red-text">Belum diuji</span>';
                    //     } else {
                    //         return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    //     }
                    // }
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
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '"><i class="fas fa-search"></i></a>
                ';
                })
                ->rawColumns(['button', 'batas', 'status'])
                ->make(true);
        } else if ($value == 'belum_uji') {


            $cekhasil = Pesanan::whereIN('id', $this->check_input())->orderby('id', 'ASC')->orHas('DetailPesanan')->orwherehas('DetailPesananPart.Sparepart', function ($q) {
                $q->where('nama', 'not like', '%JASA%');
            })->get();

            $arrayid = array();
            foreach ($cekhasil as $h) {
                if (count($h->DetailPesanan) > 0 && count($h->DetailPesananPart) <= 0) {
                    if ($h->getJumlahSeri() > 0 && $h->getJumlahPesanan() > $h->getJumlahCek()) {
                        $arrayid[] = $h->id;
                    }
                } else if (count($h->DetailPesanan) <= 0 && count($h->DetailPesananPart) > 0) {
                    if ($h->getJumlahPesananPart() > $h->getJumlahCekPart("ok")) {
                        $arrayid[] = $h->id;
                    }
                } else {
                    if (($h->getJumlahSeri() > 0 && $h->getJumlahPesanan() > $h->getJumlahCek()) || $h->getJumlahPesananPart() > $h->getJumlahCekPart("ok")) {
                        $arrayid[] = $h->id;
                    }
                }
            }
            $data = Pesanan::whereIn('id', $arrayid)->get();

            // $hasilprd = Pesanan::doesntHave('DetailPesanan.DetailPesananProduk.Noseridetailpesanan')->whereNotIn('log_id', ['7', '10'])->whereIN('id',  $this->check_input())->get();
            // $hasilprt = Pesanan::doesntHave('DetailPesananPart.OutgoingPesananPart')->whereNotIn('log_id', ['7', '10'])->get();
            // $hasildata = $hasilprd->merge($hasilprt);


            // $terbaru_id = [];
            // foreach ($hasildata as $j) {
            //     if ($j->getJumlahCek() == 0 && $j->getJumlahCekPart("ok") == 0) {
            //         $terbaru_id[] = $j->id;
            //     }
            // }

            // $prd = Pesanan::has('DetailPesanan')->whereIN('id', $terbaru_id)->get();
            // $part = Pesanan::has('DetailPesananPart')->whereIN('id', $terbaru_id)->get();
            // $data = $prd->merge($part);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
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
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '-';
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
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button', 'batas'])
                ->make(true);
        } else if ($value == 'lewat_uji') {

            $lewat_batas_data = Pesanan::has('Ekatalog')->whereIN('id',  $this->check_input())->get();
            $tgl_sekarang = Carbon::now()->format('Y-m-d');
            $lewat_batas = 0;
            $id = array();
            foreach ($lewat_batas_data as $l) {
                $tgl_parameter = $this->getHariBatasKontrak($l->ekatalog->tgl_kontrak, $l->ekatalog->provinsi->status)->format('Y-m-d');
                if ($tgl_sekarang > $tgl_parameter) {
                    $id[] = $l->id;
                }
            }
            $data = Pesanan::whereIN('id', $id)->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x =  'ekatalog';
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
                                return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
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
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button', 'batas', 'status'])
                ->make(true);
        }
    }

    public function dashboard_so()
    {
        $data = Pesanan::whereIn('log_id', ['9', '6', '11', '13'])->orderBy('id', 'desc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return '<div>' . $data->Ekatalog->Customer->nama . '</div><small>' . $data->Ekatalog->instansi . '</small>';
                } else if ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else if ($name[1] == 'SPB') {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if ($data->log_id == "9") {
                    $datas .= '<span class="badge purple-text">';
                } else if ($data->log_id == "6") {
                    $datas .= '<span class="badge orange-text">';
                } else if ($data->log_id == "11") {
                    $datas .= '<span class="badge red-text">';
                } else if ($data->log_id == "13") {
                    $datas .= '<span class="badge blue-text">';
                }
                $datas .= $data->State->nama . '</span>';
                return $datas;
            })
            ->rawColumns(['customer', 'status'])
            ->make(true);
    }
    //Laporan
    public function laporan_outgoing(Request $request)
    {
        return Excel::download(new LaporanQcOutgoing($request->produk_id ?? '', $request->no_so ?? '', $request->hasil_uji  ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_qc_outgoing.xlsx');
    }
    public function get_data_laporan_qc($jenis, $produk, $no_so, $hasil, $tgl_awal, $tgl_akhir)
    {
        $res = "";
        $so = "";
        if ($no_so != "0") {
            $so = str_replace("_", "/", $no_so);
        } else {
            $so = $no_so;
        }

        if ($jenis == "produk") {
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
        } else if ($jenis == "part") {
            if ($produk != "0" && $so == '0') {
                $res = OutgoingPesananPart::whereHas('DetailPesananPart', function ($q) use ($produk) {
                    $q->where('m_sparepart_id', $produk);
                })->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            } else if ($produk == "0" && $so != '0') {
                $res = OutgoingPesananPart::whereHas('DetailPesananPart.Pesanan', function ($q) use ($so) {
                    $q->where('so', $so);
                })->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            } else if ($produk != "0" && $so != '0') {
                $res = OutgoingPesananPart::whereHas('DetailPesananPart', function ($q) use ($produk) {
                    $q->where('m_sparepart_id', $produk);
                })->whereHas('DetailPesananPart.Pesanan', function ($q) use ($so) {
                    $q->where('so', $so);
                })->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            } else if ($produk == "0" && $so == '0') {
                $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            }
        }
        return datatables()->of($res)
            ->addIndexColumn()
            ->addColumn('so', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->so;
                } else {
                    return $data->DetailPesananPart->Pesanan->so;
                }
            })
            ->addColumn('produk', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    if (count($data->DetailPesananProduk->DetailPesanan->PenjualanProduk->Produk) > 1) {
                        if ($data->DetailPesananProduk->GudangBarangJadi->nama != '') {
                            $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' - <b>' . $data->DetailPesananProduk->GudangBarangJadi->nama . '</b> ';
                            $datas .= "<div><small>(" . $data->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama . ")</small></div>";
                            return $datas;
                        } else {
                            $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . " ";
                            $datas .= "<div><small>(" . $data->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama . ")</small></div>";
                            return $datas;
                        }
                    } else {
                        if ($data->DetailPesananProduk->GudangBarangJadi->nama != '') {
                            $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' - <b>' . $data->DetailPesananProduk->GudangBarangJadi->nama . '</b> ';
                            return $datas;
                        } else {
                            $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . " ";
                            return $datas;
                        }
                    }
                } else {
                    return $data->DetailPesananPart->Sparepart->nama;
                }
            })
            ->addColumn('noseri', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    return $data->NoseriTGbj->NoseriBarangJadi->noseri;
                }
            })
            ->addColumn('tgl_uji', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
                } else {
                    return Carbon::createFromFormat('Y-m-d', $data->tanggal_uji)->format('d-m-Y');
                }
            })
            ->addColumn('status', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    if ($data->status == "ok") {
                        return 'OK';
                    } else if ($data->status == "nok") {
                        return 'Tidak OK';
                    }
                }
            })
            ->addColumn('jumlah_ok', function ($data) use ($jenis) {
                if ($jenis == "part") {
                    return $data->jumlah_ok;
                }
            })
            ->addColumn('jumlah_nok', function ($data) use ($jenis) {
                if ($jenis == "part") {
                    return $data->jumlah_nok;
                }
            })
            ->rawColumns(['status', 'produk'])
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
        $id =  array();
        $data = Pesanan::WhereHas('TFProduksi.detail.seri', function ($q) {
            $q->where('status_id', 2);
        })->get();
        foreach ($data as $d) {
            if ($d->getJumlahPesanan() > $d->getJumlahCekSeri()) {
                $id[] = $d->id;
            }
        }
        return $id;
    }

    public function get_jumlah_cek_part($id)
    {
        $d = DetailPesananPart::find($id);
        $s = OutgoingPesananPart::where('detail_pesanan_part_id', $id)->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah = $jumlah + $i->jumlah_ok + $i->jumlah_nok;
        }
        $sisa = $d->jumlah - $jumlah;
        return $sisa;
    }

    //Select
    public function getProdukPesananSelect($id)
    {
        $result = DetailPesananProduk::where('detail_pesanan_id', $id)->with('GudangBarangJadi.Produk')->get();
        return $result;
    }
}
