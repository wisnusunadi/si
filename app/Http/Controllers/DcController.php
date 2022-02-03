<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\Logistik;
use App\Models\NoseriCoo;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use App\Models\Produk;

use Illuminate\Support\Carbon;

class DcController extends Controller
{
    public function pdf_coo($id)
    {
        $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->get();
        $pdf = PDF::loadView('page.dc.coo.pdf_semua', ['data' => $data])->setPaper('A4');
        return $pdf->stream('');
    }
    public function pdf_semua_coo($id, $value, $jenis)
    {

        $data = NoseriCoo::whereHas('NoseriDetailLogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })->get();
        $count = $data->count();

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        }
        return $pdf->stream('');
    }
    public function pdf_semua_so_coo($id, $value, $jenis)
    {

        $data = NoseriCoo::whereHas('NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
            $q->where('pesanan.id', $id);
        })->get();
        $count = $data->count();


        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat_so', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa_so', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        }
        return $pdf->stream('');
    }
    public function pdf_seri_coo($id, $value, $jenis)
    {
        $data = NoseriCoo::where('noseri_logistik_id', $id)->first();
        $tgl_sj = $data->NoseriDetailLogistik->DetailLogistik->logistik->tgl_kirim;
        $bulan =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('m');
        $tahun =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('Y');
        $romawi = $this->toRomawi($bulan);
        $footer = Carbon::createFromFormat('Y-m-d', $tgl_sj)->isoFormat('D MMMM Y');

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_ekat', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer, 'jenis' => $jenis])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_spa', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer, 'jenis' => $jenis])->setPaper('A4');
        }

        return $pdf->stream('');
    }
    public function get_data_coo()
    {
        $data = NoseriCoo::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kosong', function () {
                return '-';
            })
            ->addColumn('seri', function ($data) {
                return $data->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('so', function ($data) {
                return $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
            })
            ->addColumn('no_paket', function ($data) {

                if (isset($data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket)) {
                    return $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                if ($data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo != '') {
                    return $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo;
                } else {
                    return '';
                }
            })
            ->addColumn('noakd', function ($data) {
                if ($data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd != '') {
                    return $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd;
                } else {
                    return '';
                }
            })
            ->addColumn('bulan', function ($data) {
                $bulan =  Carbon::createFromFormat('Y-m-d', $data->NoseriDetailLogistik->DetailLogistik->logistik->tgl_kirim)->format('m');
                $romawi = $this->toRomawi($bulan);
                return $romawi;
            })
            ->addColumn('tgl_sj', function ($data) {
                return  $data->NoseriDetailLogistik->DetailLogistik->logistik->tgl_kirim;
            })
            ->addColumn('laporan', function ($data) {

                $name = explode('/', $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }
                return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a href="' . route('dc.seri.coo.pdf', [$data->NoseriDetailLogistik->id, $x, "kosong"]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo
                      </button>
                  </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->NoseriDetailLogistik->id, $x, "back"]) . '" target="_blank">
                          <button class="dropdown-item" type="button">
                              <i class="fas fa-file"></i>
                              Coo + Background
                          </button>
                      </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->NoseriDetailLogistik->id, $x, "ttd"]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo + Background + Ttd
                      </button>
                  </a>
                  </div>';
            })
            ->rawColumns(['laporan'])
            ->make(true);
    }
    public function get_data_so($value)
    {
        $array_id = array();
        $x = explode(',', $value);
        $datas = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->get();

        foreach ($datas as $d) {
            if ($value == 'semua') {
                $array_id[] = $d->id;
            } else if ($value == 'belum_diproses') {
                if ($d->getJumlahCoo() == 0) {
                    $array_id[] = $d->id;
                }
            } else {
                if ($d->getJumlahCoo() < $d->getJumlahPaketPesanan() && $d->getJumlahCoo() != 0) {
                    $array_id[] = $d->id;
                }
            }
        }

        $data = Pesanan::DoesntHave('Spb')->whereIn('id', $array_id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('batas_paket', function ($data) {

                if (isset($data->tgl_kontrak)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status)->format('Y-m-d');

                    if (isset($data->Pesanan->so)) {
                        if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
                            return $tgl_parameter;
                        } else {
                            if ($tgl_sekarang < $tgl_parameter) {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                      <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else if ($tgl_sekarang == $tgl_parameter) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            } else {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                            }
                        }
                    } else {
                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else if ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                        }
                    }
                } else {
                    return '';
                }
                // $name = explode('/', $data->so);
                // if ($name[1] == 'EKAT') {

                //     $tgl_sekarang = Carbon::now()->format('Y-m-d');
                //     $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');


                //     if ($tgl_sekarang < $tgl_parameter) {
                //         $to = Carbon::now();
                //         $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                //         $hari = $to->diffInDays($from);

                //         if ($hari > 7) {
                //             return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                //         } else if ($hari > 0 && $hari <= 7) {
                //             return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                //         } else {
                //             return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                //         }
                //     } elseif ($tgl_sekarang == $tgl_parameter) {
                //         return  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                //     } else {
                //         $to = Carbon::now();
                //         $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                //         $hari = $to->diffInDays($from);
                //         return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                //     }
                // } else {
                //     return '';
                // }
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->customer->nama;
                } else {
                    return $data->spa->customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->instansi;
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {

                if ($data->getJumlahPaketPesanan() == $data->getJumlahCoo()) {
                    return ' <span class="badge green-text">Sudah Diproses</span>';
                } else {
                    if ($data->getJumlahCoo() == 0) {
                        return  '<span class="badge red-text">Belum Diproses</span>';
                    } else {
                        return '<span class="badge yellow-text">Sebagian Diproses</span>';
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                $x = array();

                $jumlah = 0;
                foreach ($data->detailpesanan as $d) {
                    $x[] = $d->id;
                    $jumlah += $d->jumlah;
                }

                $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                $y = array();

                foreach ($detail_pesanan_produk as $d) {
                    $y[] = $d->id;
                }

                $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


                $r = array();
                foreach ($noseri as $j) {

                    $r[] = $j->id;
                }
                $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

                $d = array();

                foreach ($logistik as $l) {
                    $d[] =  $l->id;
                }

                $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();


                if ($jumlah == $coo) {
                    $class = '';
                } else {
                    if ($coo == 0) {

                        $class = 'd-none';
                    } else {
                        $class = '';
                    }
                }

                if ($name[1] == 'EKAT') {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-search"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                            </a>
                    </div>';
                } else {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-search"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd
                                </button>
                            </a>

                    </div>';
                }
            })
            ->rawColumns(['button', 'status', 'batas_paket'])
            ->make(true);
    }
    public function get_data_detail_so($id)
    {
        //pesanan_id
        // $data = Logistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
        //     $q->where('Pesanan.id', $id);
        // })->get();
        $data = DetailLogistik::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
            $q->where('Pesanan.id', $id);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_surat', function ($data) {
                return $data->Logistik->tgl_kirim;
            })
            ->addColumn('nama_paket', function ($data) {
                if ($data->DetailPesananProduk->GudangBarangJadi->nama == '') {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                } else {
                    return $data->DetailPesananProduk->GudangBarangJadi->nama;
                }
            })
            ->addColumn('no_akd', function ($data) {
                if ($data->DetailPesananProduk->GudangBarangJadi->Produk->no_akd == '') {
                    return '';
                } else {
                    return  $data->DetailPesananProduk->GudangBarangJadi->Produk->no_akd;
                }
            })
            ->addColumn('bulan', function ($data) {
                $bulan =  Carbon::createFromFormat('Y-m-d', $data->logistik->tgl_kirim)->format('m');
                $romawi = $this->toRomawi($bulan);
                return $romawi;
            })
            ->addColumn('status', function ($data) {
                $value = array();
                $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                foreach ($get as $d) {
                    $value[] = $d->id;
                }
                $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();

                if ($data->DetailPesananProduk->GudangBarangJadi->Produk->coo == '0') {
                    return '<span class="badge red-text">Bukan Produk Utama</span>';
                } else {
                    if ($coo == 0) {
                        return '<span class="badge red-text">Belum Tersedia</span>';
                    } else {
                        return ' <span class="badge green-text">Tersedia</span>';
                    }
                }
            })
            ->addColumn('button', function ($data) {

                $name = explode('/', $data->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }

                $value = array();
                $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                foreach ($get as $d) {
                    $value[] = $d->id;
                }
                $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();
                $count_trf = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();

                if ($count_trf == $coo) {
                    $c = 0;
                } else {
                    $c = 1;
                }

                if ($coo == 0) {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    </div>';
                } else {

                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "kosong"]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                    </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "back"]) . '" target="_blank">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background
                            </button>
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "ttd"]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                    </a>
                    </div>';
                }
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }
    public function get_data_detail_seri_so($id)
    {
        $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                if (isset($data->NoseriCoo)) {
                    return '';
                } else {
                    return '  <div class="form-check">
                    <input class=" form-check-input  nosericheck" type="checkbox" data-value="' . $data->detail_logistik_id . '" data-id="' . $data->id . '" />
                    </div>';
                }
            })
            ->addColumn('noseri', function ($data) {
                return  $data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('tgl', function ($data) {
                if (isset($data->NoseriCoo->tgl_kirim)) {
                    return $data->NoseriCoo->tgl_kirim;
                } else {
                    return '';
                }
            })
            ->addColumn('ket', function ($data) {
                if (isset($data->NoseriCoo->catatan)) {
                    return $data->NoseriCoo->catatan;
                } else {
                    return '';
                }
            })
            ->addColumn('laporan', function ($data) {
                $get = NoseriCoo::where('noseri_logistik_id', $data->id)->get()->count();
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }

                if ($get != 0) {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-target="#tglkirim_modal" class="tglkirim_modal"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                    <i class="fas fa-pencil-alt"></i>
                        Edit
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "kosong"]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "back"]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                    </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "ttd"]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                </a>
                </div>';
                }
            })
            ->rawColumns(['checkbox', 'laporan'])
            ->make(true);
    }
    public function get_data_detail_select_seri_so($id, $value)
    {
        $array_seri = explode(',', $id);
        if ($id == 0) {
            $data =  NoseriDetailLogistik::DoesntHave('NoseriCoo')->where('detail_logistik_id', $value)->get();
        } else {
            $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noseri', function ($data) {
                return  $data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }
    //Show
    public function detail_coo($id, $value)
    {
        if ($value == 'ekatalog') {
            $data = Pesanan::find($id);



            $x = array();

            $jumlah = 0;
            foreach ($data->detailpesanan as $d) {
                $x[] = $d->id;
                $jumlah += $d->jumlah;
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

            $y = array();

            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }

            $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


            $r = array();
            foreach ($noseri as $j) {

                $r[] = $j->id;
            }
            $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

            $d = array();

            foreach ($logistik as $l) {
                $d[] =  $l->id;
            }

            $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();

            if ($jumlah == $coo) {
                $status = ' <span class="badge green-text">Sudah Diproses</span>';
            } else {
                if ($coo == 0) {
                    $status =  '<span class="badge red-text">Belum Diproses</span>';
                } else {
                    $status = '<span class="badge yellow-text">Sebagian Diproses</span>';
                }
            }

            return view('page.dc.so.detail_ekatalog', ['data' => $data, 'status' => $status]);
        } else {
            $data = Pesanan::find($id);


            $x = array();

            $jumlah = 0;
            foreach ($data->detailpesanan as $d) {
                $x[] = $d->id;
                $jumlah += $d->jumlah;
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

            $y = array();

            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }

            $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


            $r = array();
            foreach ($noseri as $j) {

                $r[] = $j->id;
            }
            $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

            $d = array();

            foreach ($logistik as $l) {
                $d[] =  $l->id;
            }

            $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();

            if ($jumlah == $coo) {
                $status = ' <span class="badge green-text">Sudah Diproses</span>';
            } else {
                if ($coo == 0) {
                    $status =  '<span class="badge red-text">Belum Diproses</span>';
                } else {
                    $status = '<span class="badge yellow-text">Sebagian Diproses</span>';
                }
            }
            return view('page.dc.so.detail_spa', ['data' => $data, 'status' => $status]);
        }
    }
    public function edit_coo($id, $value)
    {
        $value2 = array();
        $array_seri = explode(',', $id);
        if ($id == 0) {
            $data =  NoseriDetailLogistik::where('detail_logistik_id', $value)->first();
            $jumlah = count($array_seri);

            $seri_data = NoseriDetailLogistik::where('detail_logistik_id', $value)->get();
            foreach ($seri_data as $d) {
                $value2[] = $d->id;
            }
            $noseri_id =  json_encode($value2);
        } else {
            $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->first();
            $jumlah = count($array_seri);

            $seri_data = NoseriDetailLogistik::whereIN('id', $array_seri)->get();
            foreach ($seri_data as $d) {
                $value2[] = $d->id;
            }
            $noseri_id =  json_encode($value2);
        }

        return view('page.dc.coo.edit', ['data' => $data, 'id' => $id, 'jumlah' => $jumlah, 'noseri_id' => $noseri_id]);
    }
    public function edit_tglkirim_coo($id)
    {
        $data =  NoseriDetailLogistik::find($id);
        return view('page.dc.coo.tglkirim_edit', ['data' => $data]);
    }
    public function create_coo(Request $request, $value)
    {
        if ($request->diketahui == 'spa') {
            $nama = NULL;
            $jabatan = NULL;
            $ket = 'spa';
        } elseif ($request->diketahui == 'emiindo') {
            $nama = NULL;
            $jabatan = NULL;
            $ket = 'emiindo';
        } else {
            $nama = $request->nama;
            $jabatan = $request->jabatan;
            $ket = NULL;
        }
        $replace_array_seri = strtr($value, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_seri);
        $bool = true;
        for ($i = 0; $i < count($array_seri); $i++) {
            $c = NoseriCoo::create([
                'nama' => $nama,
                'jabatan' => $jabatan,
                'ket' => $ket,
                'noseri_logistik_id' => $array_seri[$i],
                'tgl_kirim' => $request->tgl_kirim,
                'catatan' => $request->keterangan,
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
    public function update_coo(Request $request, $id)
    {
        //  return response($id);
        $l = NoseriCoo::find($id);
        $l->tgl_kirim = $request->tgl_kirim;
        $l->catatan = $request->keterangan;
        $l->save();
        if ($l) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }
    public function dashboard()
    {
        $daftar_so = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->DoesntHave('Spb')->get()->count();
        $belum_coo = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->DoesntHave('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik.NoseriCoo')->DoesntHave('Spb')->get()->count();
        $lewat_batas_data = Ekatalog::Has('Pesanan.DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->get();

        $tgl_sekarang = Carbon::now()->format('Y-m-d');
        $lewat_batas = 0;
        foreach ($lewat_batas_data as $l) {
            $tgl_parameter = $this->getHariBatasKontrak($l->tgl_kontrak, $l->provinsi->status)->format('Y-m-d');
            if ($tgl_sekarang > $tgl_parameter) {
                $p = Pesanan::where('id', $l->pesanan_id)->first();
                if ($p->getJumlahCek() > $p->getJumlahKirim()) {
                    $lewat_batas++;
                }
            }
        }

        $penjualan = Pesanan::where('log_id', ['9'])->count();
        $gudang = Pesanan::where('log_id', '6')->count();
        $qc = Pesanan::where('log_id', '8')->count();
        $logistik = Pesanan::whereIn('log_id', ['11', '13'])->count();
        return view('page.dc.dashboard', ['daftar_so' => $daftar_so, 'belum_coo' => $belum_coo, 'lewat_batas' => $lewat_batas, 'penjualan' => $penjualan, 'gudang' => $gudang, 'qc' => $qc, 'logistik' => $logistik]);
    }
    public function dashboard_data($value)
    {
        if ($value == 'pengirimansotable') {
            $data = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->DoesntHave('Spb')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('status', function ($data) {

                    $x = array();

                    $jumlah = 0;
                    foreach ($data->detailpesanan as $d) {
                        $x[] = $d->id;
                        $jumlah += $d->jumlah;
                    }

                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


                    $r = array();
                    foreach ($noseri as $j) {

                        $r[] = $j->id;
                    }
                    $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

                    $d = array();

                    foreach ($logistik as $l) {
                        $d[] =  $l->id;
                    }

                    $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();

                    if ($jumlah == $coo) {
                        return ' <span class="badge green-text">Sudah Diproses</span>';
                    } else {
                        if ($coo == 0) {
                            return  '<span class="badge red-text">Belum Diproses</span>';
                        } else {
                            return '<span class="badge yellow-text">Sebagian Diproses</span>';
                        }
                    }
                })
                ->addColumn('batas_kontrak', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {

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
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = array();

                    $jumlah = 0;
                    foreach ($data->detailpesanan as $d) {
                        $x[] = $d->id;
                        $jumlah += $d->jumlah;
                    }

                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


                    $r = array();
                    foreach ($noseri as $j) {

                        $r[] = $j->id;
                    }
                    $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

                    $d = array();

                    foreach ($logistik as $l) {
                        $d[] =  $l->id;
                    }

                    $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();


                    if ($jumlah == $coo) {
                        $class = '';
                    } else {
                        if ($coo == 0) {

                            $class = 'd-none';
                        } else {
                            $class = '';
                        }
                    }

                    if ($name[1] == 'EKAT') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                            <i class="fas fa-search"></i>
                                Detail
                            </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo
                            </button>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background
                            </button>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background + Ttd
                            </button>
                                    </a>

                        </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                            <i class="fas fa-search"></i>
                                Detail
                            </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>
                        </div>';
                    }
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        } else if ($value == 'sotanpacootable') {
            $data = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->DoesntHave('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik.NoseriCoo')->DoesntHave('Spb')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return  $data->so;
                })
                ->addColumn('status', function ($data) {

                    $x = array();

                    $jumlah = 0;
                    foreach ($data->detailpesanan as $d) {
                        $x[] = $d->id;
                        $jumlah += $d->jumlah;
                    }

                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


                    $r = array();
                    foreach ($noseri as $j) {

                        $r[] = $j->id;
                    }
                    $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

                    $d = array();

                    foreach ($logistik as $l) {
                        $d[] =  $l->id;
                    }

                    $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();

                    if ($jumlah == $coo) {
                        return ' <span class="badge green-text">Sudah Diproses</span>';
                    } else {
                        if ($coo == 0) {
                            return  '<span class="badge red-text">Belum Diproses</span>';
                        } else {
                            return '<span class="badge yellow-text">Sebagian Diproses</span>';
                        }
                    }
                })
                ->addColumn('batas_kontrak', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {

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
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = array();

                    $jumlah = 0;
                    foreach ($data->detailpesanan as $d) {
                        $x[] = $d->id;
                        $jumlah += $d->jumlah;
                    }

                    $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                    $y = array();

                    foreach ($detail_pesanan_produk as $d) {
                        $y[] = $d->id;
                    }

                    $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


                    $r = array();
                    foreach ($noseri as $j) {

                        $r[] = $j->id;
                    }
                    $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

                    $d = array();

                    foreach ($logistik as $l) {
                        $d[] =  $l->id;
                    }

                    $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();


                    if ($jumlah == $coo) {
                        $class = '';
                    } else {
                        if ($coo == 0) {

                            $class = 'd-none';
                        } else {
                            $class = '';
                        }
                    }

                    if ($name[1] == 'EKAT') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                            <i class="fas fa-search"></i>
                                Detail
                            </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>

                        </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                            <i class="fas fa-search"></i>
                                Detail
                            </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>

                        </div>';
                    }
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        } else {
            $lewat_batas_data = Ekatalog::Has('Pesanan.DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->get();
            $tgl_sekarang = Carbon::now()->format('Y-m-d');
            $id = array();
            foreach ($lewat_batas_data as $l) {
                $tgl_parameter = $this->getHariBatasKontrak($l->tgl_kontrak, $l->provinsi->status)->format('Y-m-d');
                if ($tgl_sekarang > $tgl_parameter) {
                    $p = Pesanan::where('id', $l->pesanan_id)->first();
                    if ($p->getJumlahCek() > $p->getJumlahKirim()) {
                        $id[] = $l->pesanan->id;
                    }
                }
            }
            $data = Pesanan::whereIN('id', $id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas_kontrak', function ($data) {
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
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $y = array();
                    $count = 0;
                    foreach ($data->detailpesanan as $d) {
                        foreach ($d->detailpesananproduk as $e) {
                            $y[] = $e->id;
                            $count++;
                        }
                    }
                    $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_produk_id', $y)->get()->Count();

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
                    return '<a href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                    <i class="fas fa-search"></i>
                    </a>';
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        }
    }

    public function dashboard_so(){
        $data = Pesanan::whereIn('log_id', ['6', '8', '9', '11', '13'])->get();
        return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
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
                    } else if ($data->log_id == "8") {
                        $datas .= '<span class="badge yellow-text">';
                    } else if ($data->log_id == "7") {
                        $datas .= '<span class="badge red-text">';
                    } else if ($data->log_id == "11") {
                        $datas .= '<span class="badge red-text">';
                    } else if ($data->log_id == "13") {
                        $datas .= '<span class="badge red-text">';
                    }
                    $datas .= $data->State->nama . '</span>';
                    return $datas;
                })
                ->rawColumns(['customer', 'status'])
                ->make(true);
    }
    //Another
    public function bulan_romawi($value)
    {
        $bulan =  Carbon::createFromFormat('Y-m-d', $value)->format('m');
        $to = new DcController();
        $x = $to->toRomawi($bulan);
        return $x;
    }
    public function tahun($value)
    {
        $tahun =  Carbon::createFromFormat('Y-m-d', $value)->format('Y');
        return $tahun;
    }
    public function tgl_footer($value)
    {
        $footer = Carbon::createFromFormat('Y-m-d', $value)->isoFormat('D MMMM Y');
        return $footer;
    }
    public function toRomawi($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '28';
        } else {
            $days = '35';
        }
        return Carbon::parse($value)->subDays($days);
    }
}
