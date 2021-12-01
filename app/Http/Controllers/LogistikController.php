<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\Ekatalog;
use App\Models\Ekspedisi;
use App\Models\Logistik;
use App\Models\NoseriDetailPesanan;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\NoseriTGbj;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\returnSelf;

class LogistikController extends Controller
{
    public function pdf_surat_jalan()
    {
        $customPaper = array(0, 0, 684.8094, 792.9372);
        $pdf = PDF::loadView('page.logistik.pengiriman.print_sj')->setPaper($customPaper);
        return $pdf->stream('');
    }
    public function get_data_select_produk($detail_produk, $pesanan_id)
    {
        $x = explode(',', $detail_produk);
        if ($detail_produk == '0') {
            $data = DetailPesanan::DoesntHave('detaillogistik')->where('pesanan_id', $pesanan_id)->get();
        } else {
            $data = DetailPesanan::whereIN('id', $x)->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->make(true);
    }
    public function get_data_detail_so($id)
    {
        $data = DetailPesanan::where('pesanan_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return '';
                } else {
                    return '  <div class="form-check">
                    <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
                    </div>';
                }
            })

            ->addColumn('no', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return $data->DetailLogistik->Logistik->nosurat;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return $data->DetailLogistik->Logistik->tgl_kirim;
                } else {
                    return '';
                }
            })
            ->addColumn('pengirim', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    if ($data->DetailLogistik->Logistik->nama_pengirim == "") {
                        return $data->DetailLogistik->Logistik->ekspedisi->nama;
                    } else {
                        return $data->DetailLogistik->Logistik->nama_pengirim;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return '<span class="badge green-text">Sudah Dikirim</span>';
                } else {
                    return '<span class="badge red-text">Belum Dikirim</span>';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function () {
                return '<a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_data_detail_belum_kirim_so($id)
    {
        $data = DetailPesanan::where('pesanan_id', $id)->doesntHave('DetailLogistik')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '  <div class="form-check">
                    <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
                    </div>';
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_noseri_so($id)
    {
        $s = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('detail_pesanan_id', $id);
        })->get();

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }

    public function get_data_detail_selesai_kirim_so($id)
    {
        $data = DetailPesanan::where('pesanan_id', $id)->Has('DetailLogistik')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return $data->DetailLogistik->Logistik->nosurat;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return $data->DetailLogistik->Logistik->tgl_kirim;
                } else {
                    return '';
                }
            })
            ->addColumn('pengirim', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    if ($data->DetailLogistik->Logistik->nama_pengirim == "") {
                        return $data->DetailLogistik->Logistik->ekspedisi->nama;
                    } else {
                        return $data->DetailLogistik->Logistik->nama_pengirim;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                <div><i class="fas fa-search"></i></div>
            </a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_data_no_seri($id)
    {
        $data = NoseriDetailPesanan::where('detail_pesanan_produk', $id)->doesntHave('DetailLogistik')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '  <div class="form-check">
                    <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
                    </div>';
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }
    //Get Data 
    public function get_data_so()
    {
        $data = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get();
        // $data = Ekatalog::Has('Pesanan.TFProduksi.detail.seri.NoseriDetailPesanan');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('telp', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('ket', function ($data) {
                return $data->pesanan->ket;
            })
            ->addColumn('status', function ($data) {
                $y = array();
                $count = 0;
                foreach ($data->pesanan->detailpesanan as $d) {
                    $y[] = $d->id;
                    $count++;
                }
                $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

                if ($count == $detail_logistik) {
                    return  '<span class="badge green-text">Sudah Dikirim</span>';
                } else {
                    if ($detail_logistik == 0) {
                        return ' <span class="badge red-text">Belum Dikirim</span>';
                    } else {
                        return  '<span class="badge yellow-text">Sebagian Dikirim</span>';
                    }
                }
            })
            ->addColumn('batas', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x =  'ekatalog';
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status)->format('Y-m-d');
                    $param = "";

                    if ($tgl_sekarang < $tgl_parameter) {
                        $to = Carbon::now();
                        $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
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
                        $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                        $hari = $to->diffInDays($from);
                        $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                    }
                    return $param;
                } else {
                    return '';
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->pesanan->so);
                $x = $name[1];
                if ($x == 'EKAT') {
                    $y = $data->Pesanan->ekatalog->id;
                } elseif ($x == 'SPA') {
                    $y = $data->Pesanan->spa->id;
                } else {
                    $y = $data->Pesanan->spb->id;
                }
                return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('logistik.so.detail', [$y, $x]) . '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-search"></i>
                        Detail
                    </button>
                </a>
            </div>';
            })
            ->rawColumns(['status', 'button', 'batas'])
            ->make(true);
    }
    //Edit 
    public function update_modal_surat_jalan($id, $status)
    {
        return view('page.logistik.pengiriman.edit', ['id' => $id, 'status' => $status]);
    }
    public function update_so($id, $value)
    {
        if ($value == 'EKAT') {
            $data = Ekatalog::where('id', $id)->get();

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('ekatalog.id', $id);
            })->get();

            $y = array();
            $count = 0;
            foreach ($detail_pesanan as $d) {
                $y[] = $d->id;
                $count++;
            }
            $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

            if ($count == $detail_logistik) {
                $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            } else {
                if ($detail_logistik == 0) {
                    $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
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
            return view('page.logistik.so.detail_ekatalog', ['data' => $data, 'param' => $param, 'status' => $status]);
        } elseif ($value == 'SPA') {
            return view('page.logistik.so.detail_spa');
        } else {
            return view('page.logistik.so.detail_spb');
        }
    }
    public function create_logistik_view($detail_pesanan_id, $pesanan_id)
    {
        $value = [];
        $x = explode(',', $detail_pesanan_id);
        if ($detail_pesanan_id == '0') {
            $data = DetailPesanan::DoesntHave('detaillogistik')->where('pesanan_id', $pesanan_id)->get();
            foreach ($data as $d) {
                $value[] = $d->id;
            }
            $id =  json_encode($value);
        } else {
            $data = DetailPesanan::whereIN('id', $x)->get();
            foreach ($data as $d) {
                $value[] = $d->id;
            }
            $id =  json_encode($value);
        }
        return view('page.logistik.so.create', ['id' => $id]);
    }
    public function create_logistik(Request $request, $detail_pesanan_id)
    {
        $replace_array_detail = strtr($detail_pesanan_id, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_detail);
        $bool = true;
        $Logistik = 0;
        if ($request->pengiriman == 'ekspedisi') {
            $Logistik = Logistik::create([
                'ekspedisi_id' => $request->ekspedisi_id,
                'nosurat' => $request->no_invoice,
                'tgl_kirim' => $request->tgl_kirim,
            ]);
        } else {
            $Logistik = Logistik::create([
                'nosurat' => $request->no_invoice,
                'tgl_kirim' => $request->tgl_kirim,
                'nama_pengirim' => $request->nama_pengirim,
            ]);
        }
        for ($i = 0; $i < count($array_seri); $i++) {
            $c = DetailLogistik::create([
                'logistik_id' => $Logistik->id,
                'detail_pesanan_id' => $array_seri[$i],
            ]);
            if (!$c) {
                $bool = false;
            }
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
        $terbaru = Pesanan::HAs('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
            $q->where('tgl_uji', '>=', Carbon::now()->subdays(7));
        })->get()->count();
        $belum_dikirim = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->DoesntHave('Pesanan.DetailPesanan.DetailLogistik')->get()->count();
        $lewat_batas_data = Ekatalog::Has('Pesanan.DEtailPesanan.detaillogistik')->get();


        $tgl_sekarang = Carbon::now()->format('Y-m-d');
        $lewat_batas = 0;
        foreach ($lewat_batas_data as $l) {
            $tgl_parameter = $this->getHariBatasKontrak($l->tgl_kontrak, $l->provinsi->status)->format('Y-m-d');
            if ($tgl_sekarang > $tgl_parameter) {
                $lewat_batas++;
            }
        }

        return view('page.logistik.dashboard', ['terbaru' => $terbaru, 'belum_dikirim' => $belum_dikirim, 'lewat_batas' => $lewat_batas]);
    }

    public function dashboard_data($value)
    {
        if ($value == 'terbaru') {
            $data = Pesanan::HAs('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
                $q->where('tgl_uji', '>=', Carbon::now()->subdays(7));
            })->get();

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
                                return ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('status', function ($data) {
                    $y = array();
                    $count = 0;
                    foreach ($data->detailpesanan as $d) {
                        $y[] = $d->id;
                        $count++;
                    }
                    $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

                    if ($count == $detail_logistik) {
                        return  '<span class="badge green-text">Sudah Dikirim</span>';
                    } else {
                        if ($detail_logistik == 0) {
                            return ' <span class="badge red-text">Belum Dikirim</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sebagian Dikirim</span>';
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->spa->id;
                    } else {
                        $y = $data->spb->id;
                    }
                    return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.so.detail', [$y, $x]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>
                </div>';
                })
                ->rawColumns(['batas', 'status', 'button'])
                ->make(true);
        } else if ($value == 'belum_dikirim') {
            $data = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->DoesntHave('Pesanan.DetailPesanan.DetailLogistik')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->pesanan->so;
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
                                return ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->pesanan->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->pesanan->spa->id;
                    } else {
                        $y = $data->pesanan->spb->id;
                    }
                    return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.so.detail', [$y, $x]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>
                </div>';
                })
                ->rawColumns(['batas', 'button'])
                ->make(true);
        } else {

            $lewat_batas_data = Ekatalog::Has('Pesanan.DEtailPesanan.detaillogistik')->get();

            $tgl_sekarang = Carbon::now()->format('Y-m-d');
            $id = array();
            foreach ($lewat_batas_data as $l) {
                $tgl_parameter = $this->getHariBatasKontrak($l->tgl_kontrak, $l->provinsi->status)->format('Y-m-d');
                if ($tgl_sekarang > $tgl_parameter) {
                    $id[] = $l->pesanan->id;
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
                                return ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('status', function ($data) {
                    $y = array();
                    $count = 0;
                    foreach ($data->detailpesanan as $d) {
                        $y[] = $d->id;
                        $count++;
                    }
                    $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

                    if ($count == $detail_logistik) {
                        return  '<span class="badge green-text">Sudah Dikirim</span>';
                    } else {
                        if ($detail_logistik == 0) {
                            return ' <span class="badge red-text">Belum Dikirim</span>';
                        } else {
                            return  '<span class="badge yellow-text">Sebagian Dikirim</span>';
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->spa->id;
                    } else {
                        $y = $data->spb->id;
                    }
                    return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.so.detail', [$y, $x]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>
                </div>';
                })
                ->rawColumns(['batas', 'status', 'button'])
                ->make(true);
        }
    }

    //Other
    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '28';
        } else {
            $days = '35';
        }
        return Carbon::parse($value)->subDays($days);
    }

    //Laporan
    public function get_data_laporan_logistik($pengiriman, $ekspedisi, $tgl_awal, $tgl_akhir)
    {
        $s = "";
        if ($pengiriman == "ekspedisi") {
            $s = DetailPesanan::whereHas('DetailLogistik.Logistik', function ($q) use ($ekspedisi, $tgl_awal, $tgl_akhir) {
                $q->where('ekspedisi_id', $ekspedisi)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
        } else if ($pengiriman == "nonekspedisi") {
            $s = DetailPesanan::whereHas('DetailLogistik.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
        } else {
            $s = DetailPesanan::whereHas('DetailLogistik.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
        }

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('sj', function ($data) {
                return $data->DetailLogistik->Logistik->nosurat;
            })
            ->addColumn('invoice', function ($data) {
                return '-';
            })
            ->addColumn('no_resi', function ($data) {
                if ($data->DetailLogistik->Logistik->no_resi == "") {
                    return '-';
                } else {
                    return $data->DetailLogistik->Logistik->no_resi;
                }
            })
            ->addColumn('customer', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->instansi;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Provinsi->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->Provinsi->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('telp', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->DetailLogistik->Logistik->ekspedisi_id)) {
                    return $data->DetailLogistik->Logistik->Ekspedisi->nama;
                } else {
                    return $data->DetailLogistik->Logistik->nama_pengirim;
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->DetailLogistik->Logistik->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('tgl_selesai', function ($data) {
                return '-';
            })
            ->addColumn('produk', function ($data) {
                return $data->PenjualanProduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('ongkir', function ($data) {
                return '0';
            })
            ->addColumn('status', function ($data) {
                return '-';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
