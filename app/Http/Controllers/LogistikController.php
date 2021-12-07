<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\Ekspedisi;
use App\Models\Logistik;
use App\Models\NoseriDetailLogistik;
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
    public function pdf_surat_jalan($id)
    {
        $data = Logistik::where('id', $id)->get();
        $data_produk = DetailLogistik::where('logistik_id', $id)->get();
        $customPaper = array(0, 0, 684.8094, 792.9372);
        $pdf = PDF::loadView('page.logistik.pengiriman.print_sj', ['data' => $data, 'data_produk' => $data_produk])->setPaper($customPaper);
        return $pdf->stream('');
    }
    public function get_data_select_produk($detail_produk, $pesanan_id)
    {
        $x = explode(',', $detail_produk);
        if ($detail_produk == '0') {
            // $data = DetailPesananProduk::whereHas('DetailPEsanan', function ($q) use ($pesanan_id) {
            //     $q->where('pesanan_id', $pesanan_id);
            // })->get();
            $datas = DetailPesananProduk::WhereHas('NoSeriDetailPesanan', function ($q) {
                $q->whereIN('status', ['ok']);
            })->whereHas('DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->get();

            $array_id = array();
            foreach ($datas as $i) {
                $id = $i->id;
                $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                    $q->where('detail_pesanan_produk_id', $id);
                })->count();
                $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                $detail_pesanan = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->get();
                $jumlahpesanan = 0;
                foreach ($detail_pesanan as $j) {
                    foreach ($j->PenjualanProduk->Produk as $k) {
                        // echo $k->id . " dengan " . $i->GudangBarangJadi->produk_id . ". ";
                        if ($k->id == $i->GudangBarangJadi->produk_id) {
                            $jumlahpesanan = $jumlahpesanan + ($j->jumlah * $k->pivot->jumlah);
                        }
                    }
                }

                $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
                if ($jumlahsekarang > 0) {
                    $array_id[] = $i->id;
                }
            }
            $data = DetailPesananProduk::whereIN('id', $array_id)->get();
        } else {
            $data = DetailPesananProduk::whereIN('id', $x)->get();
        }


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if ($data->GudangBarangJadi->nama == '') {
                    return $data->GudangBarangJadi->produk->nama;
                } else {
                    return $data->GudangBarangJadi->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                $c = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $data->id, 'status' => 'ok'])->get()->count();
                return $c;
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

    public function get_noseri_so($id)
    {
        $data = NoseriDetailPesanan::whereHas('DetailPesananProduk')->where('detail_pesanan_produk_id', $id)->get();

        // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $idtrf) {
        //     $q->where(['gdg_brg_jadi_id' => $id, 't_gbj_id' => $idtrf]);
        // });

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }

    public function get_data_detail_belum_kirim_so($id)
    {

        // $x = explode(',', $id);
        // $data = DetailPesananProduk::WhereHas('noseridetailpesanan', function ($q) {
        //     $q->where('status', 'ok');
        // })->whereIN('detail_pesanan_id', $x)->get();

        $datas = DetailPesananProduk::WhereHas('NoSeriDetailPesanan', function ($q) {
            $q->whereIN('status', ['ok']);
        })->whereHas('DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();

        $array_id = array();
        foreach ($datas as $i) {
            $id = $i->id;
            $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                $q->where('detail_pesanan_produk_id', $id);
            })->count();
            $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
            $detail_pesanan = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();
            $jumlahpesanan = 0;

            foreach ($detail_pesanan as $j) {
                foreach ($j->PenjualanProduk->Produk as $k) {
                    // echo $k->id . " dengan " . $i->GudangBarangJadi->produk_id . ". ";
                    if ($k->id == $i->GudangBarangJadi->produk_id) {
                        $jumlahpesanan = $jumlahpesanan + ($j->jumlah * $k->pivot->jumlah);
                    }
                }
            }

            $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
            if ($jumlahsekarang > 0) {
                $array_id[] = $i->id;
            }
        }

        $data = DetailPesananProduk::whereIN('id', $array_id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '  <div class="form-check">
                        <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
                        </div>';
            })
            ->addColumn('nama_produk', function ($data) {
                if ($data->gudangbarangjadi->nama == '') {
                    return $data->gudangbarangjadi->produk->nama;
                } else {
                    return $data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                $id = $data->id;
                $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                    $q->where('detail_pesanan_produk_id', $id);
                })->count();
                $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                $s = $jumlahsudahuji - $jumlahterkirim;
                return $s;
            })
            ->addColumn('button', function ($data) {
                return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_noseri_so_belum_kirim($id)
    {
        $data = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $id, 'status' => 'ok'])->doesntHave('NoseriDetailLogistik')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }

    public function get_data_detail_selesai_kirim_so($id)
    {
        $data = DetailLogistik::whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();
        // $data = DetailPesanan::where('pesanan_id', $id)->Has('DetailLogistik')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no', function ($data) {
                if (isset($data->Logistik)) {
                    return $data->Logistik->nosurat;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                if (isset($data->Logistik)) {
                    return $data->Logistik->tgl_kirim;
                } else {
                    return '';
                }
            })
            ->addColumn('pengirim', function ($data) {
                if (isset($data->Logistik)) {
                    if ($data->Logistik->nama_pengirim == "") {
                        return $data->Logistik->ekspedisi['nama'];
                    } else {
                        return $data->Logistik->nama_pengirim;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                return $c;
            })
            ->addColumn('button', function ($data) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                <div><i class="fas fa-search"></i></div>
            </a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_noseri_so_selesai_kirim($id)
    {
        $data = DetailLogistik::find($id);
        return view('page.logistik.so.noseri', ['id' => $id, 'res' => $data]);
    }


    public function get_noseri_so_selesai_kirim_data($id)
    {
        $data = NoseriDetailPesanan::whereHas('NoseriDetailLogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }



    public function get_data_no_seri($id)
    {
        // $data = NoseriDetailPesanan::where('detail_pesanan_produk', $id)->doesntHave('DetailLogistik')->get();
        // return datatables()->of($data)
        //     ->addIndexColumn()
        //     ->addColumn('checkbox', function ($data) {
        //         return '  <div class="form-check">
        //             <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
        //             </div>';
        //     })
        //     ->addColumn('nama_produk', function ($data) {
        //         return $data->penjualanproduk->nama;
        //     })
        //     ->addColumn('jumlah', function ($data) {
        //         return $data->jumlah;
        //     })
        //     ->addColumn('button', function ($data) {
        //         return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-search"></i></a>';
        //     })
        //     ->rawColumns(['checkbox', 'button', 'status'])
        //     ->make(true);
    }
    //Get Data 
    public function get_data_so()
    {
        $data = Pesanan::Has('DetailPesanan.DetailPesananProduk.Noseridetailpesanan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->Customer->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->alamat;
                } else {
                    return $data->Spb->Customer->alamat;
                }
            })
            ->addColumn('telp', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->telp;
                } else {
                    return $data->Spb->Customer->telp;
                }
            })
            ->addColumn('ket', function ($data) {
                return $data->ket;
            })
            ->addColumn('status', function ($data) {
                return '';
            })
            ->addColumn('batas', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    $x =  'ekatalog';
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');
                    $param = "";

                    if ($tgl_sekarang < $tgl_parameter) {
                        $to = Carbon::now();
                        $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
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
                        $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                        $hari = $to->diffInDays($from);
                        $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                    }
                    return $param;
                } else {
                    return '';
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
                <a href="' . route('logistik.so.detail', [$data->id, $x]) . '">
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

    public function get_data_pengiriman()
    {
        $data = Logistik::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
            })
            ->addColumn('sj', function ($data) {
                return $data->nosurat;
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    $data->ekspedisi->nama;
                } else {
                    $data->nama_pengirim;
                }
            })
            ->addColumn('no_resi', function ($data) {
                return "-";
            })
            ->addColumn('tgl_kirim', function ($data) {
                return $data->tgl_kirim;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('provinsi', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                } else {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('status', function ($data) {
                return '';
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>
                    <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="' . route('logistik.pengiriman.edit', [$data->id, 'dalam_pengiriman']) . '" data-id="">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </button>
                    </a>
                    <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Laporan PDF
                        </button>
                    </a>
                </div>';
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }

    public function get_pengiriman_detail_data($id, $jenis)
    {
        $l = Logistik::find($id);
        return view('page.logistik.pengiriman.detail', ['id' => $id, 'l' => $l, 'jenis' => $jenis]);
    }

    public function get_produk_detail_pengiriman($id)
    {
        $l = DetailLogistik::where('logistik_id', $id)->get();
        return datatables()->of($l)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                // $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                return $data->NoseriDetailLogistik->count();
            })
            ->addColumn('no_seri', function ($data) {
                $array = array();
                foreach ($data->NoseriDetailLogistik as $i) {
                    $array[] = $i->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
                }
                return implode(", ", $array);
            })
            ->addColumn('keterangan', function ($data) {
                return "-";
            })
            ->addColumn('aksi', function ($data) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                <div><i class="fas fa-eye"></i></div>
            </a>';
            })
            ->rawColumns(['aksi'])
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

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }
            // $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

            // if ($count == $detail_logistik) {
            //     $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            // } else {
            //     if ($detail_logistik == 0) {
            //         $status =  ' <span class="badge red-text">Belum Dikirim</span>';
            //     } else {
            //         $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
            //     }
            // }




            // foreach ($data as $d) {
            //     $tgl_sekarang = Carbon::now()->format('Y-m-d');
            //     $tgl_parameter = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status)->format('Y-m-d');

            //     if ($tgl_sekarang < $tgl_parameter) {
            //         $to = Carbon::now();
            //         $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
            //         $hari = $to->diffInDays($from);

            //         if ($hari > 7) {
            //             $param = ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $hari . ' Hari</small>';
            //         } else if ($hari > 0 && $hari <= 7) {
            //             $param = ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
            //         } else {
            //             $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
            //         }
            //     } elseif ($tgl_sekarang == $tgl_parameter) {
            //         $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
            //     } else {
            //         $to = Carbon::now();
            //         $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
            //         $hari = $to->diffInDays($from);
            //         $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
            //     }
            // }
            return view('page.logistik.so.detail_ekatalog', ['data' => $data, 'detail_id' => $detail_id]);
        } elseif ($value == 'SPA') {
            return view('page.logistik.so.detail_spa');
        } else {
            return view('page.logistik.so.detail_spb');
        }
    }
    public function create_logistik_view($detail_pesanan_id, $pesanan_id)
    {
        $value = array();
        $value2 = [];
        $id = [];
        $a = 0;
        $x = explode(',', $detail_pesanan_id);

        if ($detail_pesanan_id == '0') {
            $datas = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->get();
            $array_id = array();
            foreach ($datas as $i) {
                $ids = $i->id;
                $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($ids) {
                    $q->where('detail_pesanan_produk_id', $ids);
                })->count();
                $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $ids])->count();

                $detail_pesanan = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($ids) {
                    $q->where('id', $ids);
                })->get();
                $jumlahpesanan = 0;

                $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
                if ($jumlahsekarang > 0) {
                    $array_id[] = $i->id;
                }
            }

            foreach ($array_id as $d) {
                $value[$a]['id'] = $d;
                $count = 0;

                $e = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $d])->doesntHave('NoseriDetailLogistik')->get();
                foreach ($e as $f) {
                    $value[$a]['noseri'][$count] = $f->id;
                    $count++;
                }
                $a++;
            }

            $id =  json_encode($value);
            $id_produk =  json_encode($value2);
        } else {
            foreach ($x as $d) {
                $value[$a]['id'] = $d;
                $count = 0;
                $e = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $d])->doesntHave('NoseriDetailLogistik')->get();
                foreach ($e as $f) {
                    $value[$a]['noseri'][$count] = $f->id;
                    $count++;
                }
                $a++;
            }
            $id =  json_encode($value);
            $id_produk =  json_encode($value2);
        }

        return view('page.logistik.so.create', ['id' => $id, 'id_produk' => $id_produk]);
    }
    public function create_logistik(Request $request, $detail_pesanan_id, $id_produk)
    {
        $array = array_values(json_decode($detail_pesanan_id, true));
        $bool = true;
        $Logistik = "";
        if ($request->pengiriman == 'ekspedisi') {
            $Logistik = Logistik::create([
                'ekspedisi_id' => $request->ekspedisi_id,
                'nosurat' => 'SPA-' . $request->no_invoice,
                'tgl_kirim' => $request->tgl_kirim,
            ]);
        } else {
            $Logistik = Logistik::create([
                'nosurat' => 'SPA-' . $request->no_invoice,
                'tgl_kirim' => $request->tgl_kirim,
                'nama_pengirim' => $request->nama_pengirim,
            ]);
        }


        if ($Logistik) {
            for ($i = 0; $i < count($array); $i++) {
                $c = DetailLogistik::create([
                    'logistik_id' => $Logistik->id,
                    'detail_pesanan_produk_id' => $array[$i]['id'],
                ]);
                if ($c) {
                    for ($y = 0; $y < count($array[$i]['noseri']); $y++) {
                        $b = NoseriDetailLogistik::create([
                            'detail_logistik_id' => $c->id,
                            'noseri_detail_pesanan_id' => $array[$i]['noseri'][$y],
                        ]);
                    }
                    if (!$b) {
                        $bool = false;
                    }
                } else {
                    $bool = false;
                }
            }
        } else {
            return response()->json(['data' =>  $Logistik]);
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
        //     $terbaru = Pesanan::HAs('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
        //         $q->where('tgl_uji', '>=', Carbon::now()->subdays(7));
        //     })->get()->count();
        //     $belum_dikirim = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->DoesntHave('Pesanan.DetailPesanan.DetailLogistik')->get()->count();
        //     $lewat_batas_data = Ekatalog::Has('Pesanan.DEtailPesanan.detailpesananproduk.detaillogistik')->get();


        //     $tgl_sekarang = Carbon::now()->format('Y-m-d');
        //     $lewat_batas = 0;
        //     foreach ($lewat_batas_data as $l) {
        //         $tgl_parameter = $this->getHariBatasKontrak($l->tgl_kontrak, $l->provinsi->status)->format('Y-m-d');
        //         if ($tgl_sekarang > $tgl_parameter) {
        //             $lewat_batas++;
        //         }
        //     }

        return view('page.logistik.dashboard');
        // }
    }
    public function dashboard_data($value)
    {
        // {
        //     if ($value == 'terbaru') {
        //         $data = Pesanan::Has('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
        //             $q->where('tgl_uji', '>=', Carbon::now()->subdays(7));
        //         })->get();

        //         return datatables()->of($data)
        //             ->addIndexColumn()
        //             ->addColumn('so', function ($data) {
        //                 return $data->so;
        //             })
        //             ->addColumn('batas', function ($data) {
        //                 $name = explode('/', $data->so);
        //                 if ($name[1] == 'EKAT') {
        //                     $x =  'ekatalog';
        //                     $tgl_sekarang = Carbon::now()->format('Y-m-d');
        //                     $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');


        //                     if ($tgl_sekarang < $tgl_parameter) {
        //                         $to = Carbon::now();
        //                         $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
        //                         $hari = $to->diffInDays($from);

        //                         if ($hari > 7) {
        //                             return ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
        //                         } else if ($hari > 0 && $hari <= 7) {
        //                             return ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
        //                         } else {
        //                             return '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
        //                         }
        //                     } elseif ($tgl_sekarang == $tgl_parameter) {
        //                         return  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
        //                     } else {
        //                         $to = Carbon::now();
        //                         $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
        //                         $hari = $to->diffInDays($from);
        //                         return '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
        //                     }
        //                 } else {
        //                     return '';
        //                 }
        //             })
        //             ->addColumn('status', function ($data) {
        //                 $y = array();
        //                 $count = 0;
        //                 foreach ($data->detailpesanan as $d) {
        //                     $y[] = $d->id;
        //                     $count++;
        //                 }
        //                 $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

        //                 if ($count == $detail_logistik) {
        //                     return  '<span class="badge green-text">Sudah Dikirim</span>';
        //                 } else {
        //                     if ($detail_logistik == 0) {
        //                         return ' <span class="badge red-text">Belum Dikirim</span>';
        //                     } else {
        //                         return  '<span class="badge yellow-text">Sebagian Dikirim</span>';
        //                     }
        //                 }
        //             })
        //             ->addColumn('button', function ($data) {
        //                 $name = explode('/', $data->so);
        //                 $x = $name[1];
        //                 if ($x == 'EKAT') {
        //                     $y = $data->ekatalog->id;
        //                 } elseif ($x == 'SPA') {
        //                     $y = $data->spa->id;
        //                 } else {
        //                     $y = $data->spb->id;
        //                 }
        //                 return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        //             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //                 <a href="' . route('logistik.so.detail', [$y, $x]) . '">
        //                     <button class="dropdown-item" type="button">
        //                         <i class="fas fa-search"></i>
        //                         Detail
        //                     </button>
        //                 </a>
        //             </div>';
        //             })
        //             ->rawColumns(['batas', 'status', 'button'])
        //             ->make(true);
        //     } else if ($value == 'belum_dikirim') {
        //         $data = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->DoesntHave('Pesanan.DetailPesanan.DetailLogistik')->get();
        //         return datatables()->of($data)
        //             ->addIndexColumn()
        //             ->addColumn('so', function ($data) {
        //                 return $data->pesanan->so;
        //             })
        //             ->addColumn('batas', function ($data) {
        //                 $name = explode('/', $data->pesanan->so);
        //                 if ($name[1] == 'EKAT') {
        //                     $x =  'ekatalog';
        //                     $tgl_sekarang = Carbon::now()->format('Y-m-d');
        //                     $tgl_parameter = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status)->format('Y-m-d');


        //                     if ($tgl_sekarang < $tgl_parameter) {
        //                         $to = Carbon::now();
        //                         $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
        //                         $hari = $to->diffInDays($from);

        //                         if ($hari > 7) {
        //                             return ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
        //                         } else if ($hari > 0 && $hari <= 7) {
        //                             return ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
        //                         } else {
        //                             return '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
        //                         }
        //                     } elseif ($tgl_sekarang == $tgl_parameter) {
        //                         return  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
        //                     } else {
        //                         $to = Carbon::now();
        //                         $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
        //                         $hari = $to->diffInDays($from);
        //                         return '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
        //                     }
        //                 } else {
        //                     return '';
        //                 }
        //             })
        //             ->addColumn('button', function ($data) {
        //                 $name = explode('/', $data->pesanan->so);
        //                 $x = $name[1];
        //                 if ($x == 'EKAT') {
        //                     $y = $data->pesanan->ekatalog->id;
        //                 } elseif ($x == 'SPA') {
        //                     $y = $data->pesanan->spa->id;
        //                 } else {
        //                     $y = $data->pesanan->spb->id;
        //                 }
        //                 return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        //             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //                 <a href="' . route('logistik.so.detail', [$y, $x]) . '">
        //                     <button class="dropdown-item" type="button">
        //                         <i class="fas fa-search"></i>
        //                         Detail
        //                     </button>
        //                 </a>
        //             </div>';
        //             })
        //             ->rawColumns(['batas', 'button'])
        //             ->make(true);
        //     } else {

        //     $lewat_batas_data = Ekatalog::Has('Pesanan.DEtailPesanan.detaillogistik')->get();

        //     $tgl_sekarang = Carbon::now()->format('Y-m-d');
        //     $id = array();
        //     foreach ($lewat_batas_data as $l) {
        //         $tgl_parameter = $this->getHariBatasKontrak($l->tgl_kontrak, $l->provinsi->status)->format('Y-m-d');
        //         if ($tgl_sekarang > $tgl_parameter) {
        //             $id[] = $l->pesanan->id;
        //         }
        //     }
        //     $data = Pesanan::whereIN('id', $id)->get();
        //     return datatables()->of($data)
        //         ->addIndexColumn()
        //         ->addColumn('so', function ($data) {
        //             return $data->so;
        //         })
        //         ->addColumn('batas', function ($data) {
        //             $name = explode('/', $data->so);
        //             if ($name[1] == 'EKAT') {
        //                 $x =  'ekatalog';
        //                 $tgl_sekarang = Carbon::now()->format('Y-m-d');
        //                 $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');


        //                 if ($tgl_sekarang < $tgl_parameter) {
        //                     $to = Carbon::now();
        //                     $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
        //                     $hari = $to->diffInDays($from);

        //                     if ($hari > 7) {
        //                         return ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
        //                     } else if ($hari > 0 && $hari <= 7) {
        //                         return ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
        //                     } else {
        //                         return '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
        //                     }
        //                 } elseif ($tgl_sekarang == $tgl_parameter) {
        //                     return  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
        //                 } else {
        //                     $to = Carbon::now();
        //                     $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
        //                     $hari = $to->diffInDays($from);
        //                     return '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
        //                 }
        //             } else {
        //                 return '';
        //             }
        //         })
        //         ->addColumn('status', function ($data) {
        //             $y = array();
        //             $count = 0;
        //             foreach ($data->detailpesanan as $d) {
        //                 $y[] = $d->id;
        //                 $count++;
        //             }
        //             $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

        //             if ($count == $detail_logistik) {
        //                 return  '<span class="badge green-text">Sudah Dikirim</span>';
        //             } else {
        //                 if ($detail_logistik == 0) {
        //                     return ' <span class="badge red-text">Belum Dikirim</span>';
        //                 } else {
        //                     return  '<span class="badge yellow-text">Sebagian Dikirim</span>';
        //                 }
        //             }
        //         })
        //         ->addColumn('button', function ($data) {
        //             $name = explode('/', $data->so);
        //             $x = $name[1];
        //             if ($x == 'EKAT') {
        //                 $y = $data->ekatalog->id;
        //             } elseif ($x == 'SPA') {
        //                 $y = $data->spa->id;
        //             } else {
        //                 $y = $data->spb->id;
        //             }
        //             return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        //         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //             <a href="' . route('logistik.so.detail', [$y, $x]) . '">
        //                 <button class="dropdown-item" type="button">
        //                     <i class="fas fa-search"></i>
        //                     Detail
        //                 </button>
        //             </a>
        //         </div>';
        //         })
        //         ->rawColumns(['batas', 'status', 'button'])
        //         ->make(true);
        // }
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
            $s = DetailLogistik::whereHas('Logistik', function ($q) use ($ekspedisi, $tgl_awal, $tgl_akhir) {
                $q->where('ekspedisi_id', $ekspedisi)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
        } else if ($pengiriman == "nonekspedisi") {
            $s = DetailLogistik::whereHas('Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
        } else {
            $s = DetailLogistik::whereHas('Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
        }

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->DetailPesananProduk->DetailPesanan->Pesanan->so;
            })
            ->addColumn('sj', function ($data) {
                return $data->Logistik->nosurat;
            })
            ->addColumn('invoice', function ($data) {
                return '-';
            })
            ->addColumn('no_resi', function ($data) {
                // if ($data->DetailLogistik->Logistik->no_resi == "") {
                //     return '-';
                // } else {
                //     return $data->DetailLogistik->Logistik->no_resi;
                // }
            })
            ->addColumn('customer', function ($data) {
                $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                } else {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('telp', function ($data) {
                $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->Logistik->ekspedisi_id)) {
                    return $data->Logistik->Ekspedisi->nama;
                } else {
                    return $data->Logistik->nama_pengirim;
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('tgl_selesai', function ($data) {
                return '-';
            })
            ->addColumn('produk', function ($data) {
                return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->NoseriDetailLogistik->count();
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

    public function tgl_footer($value)
    {
        $footer = Carbon::createFromFormat('Y-m-d', $value)->isoFormat('D MMMM Y');
        return $footer;
    }
}
