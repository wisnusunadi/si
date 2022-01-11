<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;

use App\Models\DetailLogistikPart;
use App\Models\DetailPesananPart;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
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
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\returnSelf;

class LogistikController extends Controller
{
    public function pdf_surat_jalan($id)
    {
        $data = Logistik::find($id);
        $data_produk = "";
        if (isset($data->DetailLogistik)) {
            $data_produk = DetailLogistik::where('logistik_id', $id)->get();
        } else {
            $data_produk = DetailLogistikPart::where('logistik_id', $id)->get();
        }
        $customPaper = array(0, 0, 684.8094, 792.9372);
        $pdf = PDF::loadView('page.logistik.pengiriman.print_sj', ['data' => $data, 'data_produk' => $data_produk])->setPaper($customPaper);
        return $pdf->stream('');
    }
    public function get_data_select_produk($produk_id, $part_id, $pesanan_id, $jenis)
    {
        $x = explode(',', $produk_id);
        if ($jenis == 'EKAT') {
            if ($produk_id == 0) {
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
        } else {
            $array_prd = explode(',', $produk_id);
            $array_part = explode(',', $part_id);
            if ($produk_id != 0 && $part_id == 0) {
                $data = DetailPesananProduk::whereIN('id', $array_prd)->get();
            } else if ($produk_id == 0 && $part_id != 0) {
                $data = DetailPesananPart::whereIN('id', $array_part)->get();
            } else if ($produk_id != 0 && $part_id != 0) {
                $Part = collect(DetailPesananProduk::whereIN('id', $array_prd)->get());
                $Produk = collect(DetailPesananPart::whereIN('id', $array_part)->get());
                $data = $Produk->merge($Part);
            } else {

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

                $produk = collect(DetailPesananProduk::whereIN('id', $array_id)->get());
                $part = collect(DetailPesananPart::DoesntHave('DetailLogistikPart')->where('pesanan_id', $pesanan_id)->get());
                $data = $produk->merge($part);
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    if (isset($data->GudangBarangJadi)) {
                        if ($data->GudangBarangJadi->nama == '') {
                            return $data->GudangBarangJadi->produk->nama;
                        } else {
                            return $data->GudangBarangJadi->nama;
                        }
                    } else {
                        return $data->Sparepart->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    if (isset($data->GudangBarangJadi)) {
                        $c = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $data->id, 'status' => 'ok'])->get()->count();
                        return $c;
                    } else {
                        return $data->jumlah;
                    }
                })
                ->make(true);
        }
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
                    return  Carbon::createFromFormat('Y-m-d', $data->DetailLogistik->Logistik->tgl_kirim)->format('d-m-Y');
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

    public function get_data_detail_belum_kirim_so($id, $jenis)
    {

        // $x = explode(',', $id);
        // $data = DetailPesananProduk::WhereHas('noseridetailpesanan', function ($q) {
        //     $q->where('status', 'ok');
        // })->whereIN('detail_pesanan_id', $x)->get();
        if ($jenis == "EKAT") {
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
                        <input class=" form-check-input yet detail_produk_id check_detail"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
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
        } else {
            $pesanan_id = $id;
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

            $c_prd = DetailPesanan::where('pesanan_id', $pesanan_id)->get()->count();
            $c_prt = DetailPesananPart::where('pesanan_id', $pesanan_id)->get()->count();

            if ($c_prd <= 0 && $c_prt > 0) {
                $data = DetailPesananPart::DoesntHave('DetailLogistikPart')->where('pesanan_id', $pesanan_id)->get();
            } else if ($c_prt <= 0 && $c_prd > 0) {
                $data = DetailPesananProduk::whereIN('id', $array_id)->get();
            } else if ($c_prd > 0 && $c_prd > 0) {
                $Produk = collect(DetailPesananProduk::whereIN('id', $array_id)->get());
                $Part = collect(DetailPesananPart::DoesntHave('DetailLogistikPart')->where('pesanan_id', $pesanan_id)->get());
                $data = $Produk->merge($Part);
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        return '  <div class="form-check">
                        <input class=" form-check-input yet detail_produk_id check_detail"  data-id="' . $data->id . '" type="checkbox"  data-value="' . $data->id . '" />
                        </div>';
                    } else {
                        return '  <div class="form-check">
                        <input class=" form-check-input yet detail_part_id check_detail"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
                        </div>';
                    }
                })
                ->addColumn('nama_produk', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        if ($data->gudangbarangjadi->nama == '') {
                            return $data->gudangbarangjadi->produk->nama;
                        } else {
                            return $data->gudangbarangjadi->nama;
                        }
                    } else {
                        return $data->Sparepart->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        $id = $data->id;
                        $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                            $q->where('detail_pesanan_produk_id', $id);
                        })->count();
                        $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                        $s = $jumlahsudahuji - $jumlahterkirim;
                        return $s;
                    } else {
                        return $data->jumlah;
                    }
                })
                ->addColumn('button', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-search"></i></a>';
                    } else {
                        return '';
                    }
                })
                ->rawColumns(['checkbox', 'button', 'status'])
                ->make(true);
            // $datas = DetailPesananPart::where('pesanan_id', $id)->get();
            // $array_id = array();
            // foreach ($datas as $i) {
            //     if (!isset($i->DetailLogistikPart)) {
            //         $array_id[] = $i->id;
            //     }
            // }
            // $data = DetailPesananPart::whereIN('id', $array_id)->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('checkbox', function ($data) {
            //         return '  <div class="form-check">
            //             <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
            //             </div>';
            //     })
            //     ->addColumn('nama_produk', function ($data) {
            //         return $data->Sparepart->nama;
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

    public function get_data_detail_selesai_kirim_so($id, $jenis)
    {
        if ($jenis != "SPB") {
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
                        return  Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
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
        } else {
            $data = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

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
                        return  Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
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
                    return $data->DetailPesananPart->Sparepart->nama;
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->DetailPesananPart->jumlah;
                })
                ->addColumn('button', function ($data) {
                    return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                <div><i class="fas fa-search"></i></div>
            </a>';
                })
                ->rawColumns(['checkbox', 'button', 'status'])
                ->make(true);
        }
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
    public function get_data_so($value)
    {
        $x = explode(',', $value);
        $datas = Pesanan::Has('DetailPesanan.DetailPesananProduk.Noseridetailpesanan')->get();
        $array_id = array();
        foreach ($datas as $d) {
            if ($value == 'semua') {
                if ($d->getJumlahPesanan() > $d->getJumlahKirim() || $d->getJumlahKirim() == "0") {
                    $array_id[] = $d->id;
                }
            } else if ($x == ['sebagian_kirim', 'sudah_kirim']) {
                if ($d->getJumlahCek() != $d->getJumlahKirim() ||  $d->getJumlahCek() == $d->getJumlahKirim()) {
                    $array_id[] = $d->id;
                }
            } else if ($x == ['belum_kirim', 'sebagian_kirim']) {
                if ($d->getJumlahCek() != $d->getJumlahKirim() || $d->getJumlahKirim() == "0") {
                    $array_id[] = $d->id;
                }
            } else if ($x == ['belum_kirim', 'sudah_kirim']) {
                if ($d->getJumlahKirim() == "0" || $d->getJumlahCek() == $d->getJumlahKirim()) {
                    $array_id[] = $d->id;
                }
            } else if ($value == 'sebagian_kirim') {
                if ($d->getJumlahCek() != $d->getJumlahKirim()) {
                    $array_id[] = $d->id;
                }
            } else if ($value == 'sudah_kirim') {
                if ($d->getJumlahCek() == $d->getJumlahKirim()) {
                    $array_id[] = $d->id;
                }
            } else if ($value == 'belum_kirim') {
                if ($d->getJumlahKirim() == 0) {
                    $array_id[] = $d->id;
                }
            } else {
                if ($d->getJumlahPesanan() > $d->getJumlahKirim() || $d->getJumlahKirim() == "0") {
                    $array_id[] = $d->id;
                }
            }
        }
        $array_ids = array();
        $dataspb = Pesanan::has('DetailPesananPart')->get();
        foreach ($dataspb as $d) {
            if ($value == 'semua') {
                if ($d->getJumlahPesananPart() > $d->getJumlahKirimPart() || $d->getJumlahKirimPart() == "0") {
                    $array_ids[] = $d->id;
                }
            } else if ($x == ['sebagian_kirim', 'sudah_kirim']) {
                if ($d->getJumlahPesananPart() != $d->getJumlahKirimPart() ||  $d->getJumlahPesananPart() == $d->getJumlahKirimPart()) {
                    $array_ids[] = $d->id;
                }
            } else if ($x == ['belum_kirim', 'sebagian_kirim']) {
                if ($d->getJumlahPesananPart() != $d->getJumlahKirimPart() || $d->getJumlahKirimPart() == "0") {
                    $array_ids[] = $d->id;
                }
            } else if ($x == ['belum_kirim', 'sudah_kirim']) {
                if ($d->getJumlahKirimPart() == "0" || $d->getJumlahPesananPart() == $d->getJumlahKirimPart()) {
                    $array_ids[] = $d->id;
                }
            } else if ($value == 'sebagian_kirim') {
                if ($d->getJumlahPesananPart() != $d->getJumlahKirimPart()) {
                    $array_ids[] = $d->id;
                }
            } else if ($value == 'sudah_kirim') {
                if ($d->getJumlahPesananPart() == $d->getJumlahKirimPart()) {
                    $array_ids[] = $d->id;
                }
            } else if ($value == 'belum_kirim') {
                if ($d->getJumlahKirimPart() == 0) {
                    $array_ids[] = $d->id;
                }
            } else {
                if ($d->getJumlahPesananPart() > $d->getJumlahKirimPart() || $d->getJumlahKirimPart() == "0") {
                    $array_ids[] = $d->id;
                }
            }
        }
        // $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($array_id) {
        //     $q->where('pesanan_id', $array_id);
        // })->count();

        // $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($array_id) {
        //     $q->where('pesanan_id', $array_id);
        // })->count();
        $datas = Pesanan::whereIn('id', $array_id)->get();
        $datas2 = Pesanan::whereIn('id', $array_ids)->get();
        $data = $datas->merge($datas2);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->satuan;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->alamat;
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
                $status = "";
                $pesanan_id = $data->id;
                $name = explode('/', $data->so);
                if ($name[1] != 'SPB') {
                    $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                        $q->where('pesanan_id', $pesanan_id);
                    })->count();

                    $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                        $q->where('pesanan_id', $pesanan_id);
                    })->count();

                    if ($jumlahsudahuji == $jumlahterkirim) {
                        $status =   '<span class="badge green-text">Sudah Dikirim</span>';
                    } else {
                        if ($jumlahterkirim == 0) {
                            $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                        } else {
                            $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                        }
                    }
                } else {
                    if ($data->getJumlahKirimPart() == $data->getJumlahPesananPart()) {
                        $status = '<span class="badge green-text">Sudah Dikirim</span>';
                    } else {
                        if ($data->getJumlahKirimPart() == 0) {
                            $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                        } else {
                            $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                        }
                    }
                }
                return $status;
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
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                $x = $name[1];
                $y = "";
                if ($x == 'EKAT') {
                    $y = $data->ekatalog->id;
                } elseif ($x == 'SPA') {
                    $y = $data->spa->id;
                } else {
                    $y = $data->spb->id;
                }
                $z = 'proses';
                return '<a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
                        <i class="fas fa-search"></i>
                    </a>';
            })
            ->rawColumns(['status', 'button', 'batas'])
            ->make(true);
    }

    public function get_data_selesai_so()
    {
        $datas = Pesanan::has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->get();
        $arr = [];
        foreach ($datas as $i) {
            if ($i->getJumlahPesanan() == $i->getJumlahKirim()) {
                $arr[] = $i->id;
            }
        }
        $dataspb = Pesanan::has('DetailPesananPart')->get();
        foreach ($dataspb as $i) {
            if ($i->getJumlahPesananPart() == $i->getJumlahKirimPart()) {
                $arr[] = $i->id;
            }
        }

        $data = Pesanan::whereIn('id', $arr)->orderBy('id', 'desc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->satuan;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->alamat;
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
                $name = explode('/', $data->so);
                $status = "";
                $pesanan_id = $data->id;
                if ($name[1] != 'SPB') {
                    $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                        $q->where('pesanan_id', $pesanan_id);
                    })->count();

                    $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                        $q->where('pesanan_id', $pesanan_id);
                    })->count();

                    if ($jumlahsudahuji == $jumlahterkirim) {
                        $status =   '<span class="badge green-text">Sudah Dikirim</span>';
                    } else {
                        if ($jumlahterkirim == 0) {
                            $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                        } else {
                            $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                        }
                    }
                } else {
                    if ($data->getJumlahKirimPart() == $data->getJumlahPesananPart()) {
                        $status =   '<span class="badge green-text">Sudah Dikirim</span>';
                    } else {
                        if ($data->getJumlahKirimPart() == 0) {
                            $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                        } else {
                            $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                        }
                    }
                }
                return $status;
            })
            ->addColumn('batas', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    $x =  'ekatalog';
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');
                    $param = "";
                    return Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_awal', function ($data) {
                $id = $data->id;
                $name = explode('/', $data->so);
                if ($name[1] != 'SPB') {
                    $k = Logistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->selectRaw('MIN(tgl_kirim) as tgl_awal')->first();
                    return Carbon::createFromFormat('Y-m-d', $k->tgl_awal)->format('d-m-Y');
                } else {
                    $k = Logistik::whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->selectRaw('MIN(tgl_kirim) as tgl_awal')->first();
                    return Carbon::createFromFormat('Y-m-d', $k->tgl_awal)->format('d-m-Y');
                }
            })
            ->addColumn('tgl_akhir', function ($data) {
                $id = $data->id;
                $name = explode('/', $data->so);
                if ($name[1] != 'SPB') {
                    $k = Logistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->selectRaw('MAX(tgl_kirim) as tgl_akhir')->first();
                    return Carbon::createFromFormat('Y-m-d', $k->tgl_akhir)->format('d-m-Y');
                } else {
                    $k = Logistik::whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                        $q->where('pesanan_id', $id);
                    })->selectRaw('MAX(tgl_kirim) as tgl_akhir')->first();
                    return Carbon::createFromFormat('Y-m-d', $k->tgl_akhir)->format('d-m-Y');
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                $x = $name[1];
                $y = "";
                if ($x == 'EKAT') {
                    $y = $data->ekatalog->id;
                } elseif ($x == 'SPA') {
                    $y = $data->spa->id;
                } else {
                    $y = $data->spb->id;
                }
                $z = 'selesai';
                return '<a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
                            <i class="fas fa-search"></i>
                    </a>';
            })
            ->rawColumns(['status', 'button', 'batas'])
            ->make(true);
    }

    public function get_data_pesanan_sj($id)
    {
        $data = Logistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('noresi', function ($data) {
                if (!empty($data->noresi)) {
                    return $data->noresi;
                } else {
                    return '-';
                }
            })->editColumn('tgl_kirim', function ($data) {
                if (!empty($data->tgl_kirim)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
                } else {
                    return '-';
                }
            })->editColumn('status_id', function ($data) {
                if ($data->status_id == "10") {
                    return '<span class="badge green-text">Selesai</span>';
                } else if ($data->status_id == "11") {
                    return '<span class="badge red-text">Belum Kirim</span>';
                }
            })->editColumn('ekspedisi_id', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->Ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })->addColumn('aksi', function ($data) {
                return '<a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                    <i class="fas fa-file"></i>
                </a>';
            })
            ->rawColumns(['status_id', 'aksi'])
            ->make(true);;
    }
    public function get_data_pengiriman($pengiriman, $provinsi, $jenis_penjualan)
    {
        $x = explode(',', $pengiriman);
        $y = explode(',', $provinsi);
        $z = explode(',', $jenis_penjualan);
        $data = "";
        if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
            $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
            $data = $dataeks->merge($datanoneks);
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = "";
            $datanoneks = "";

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
            }

            if ($dataeks != "" && $datanoneks != "") {
                $data = $dataeks->merge($datanoneks);
            } else if ($dataeks != "" && $datanoneks == "") {
                $data = $dataeks;
            } else if ($dataeks == "" && $datanoneks != "") {
                $data = $datanoneks;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $ekatalogeks = "";
            $spaeks = "";
            $spbeks = "";

            $ekatalognoneks = "";
            $spanoneks = "";
            $spbnoneks = "";

            $ekatalogeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spaeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spbeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $ekatalognoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spbnoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
            }

            $data = $ekatalogeks->merge($ekatalognoneks)->merge($spaeks)->merge($spanoneks)->merge($spbeks)->merge($spbnoneks);
        } else if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $ekatalogeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                $ekatalognoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                $Ekatalog = $ekatalogeks->merge($ekatalognoneks);
            }

            if (in_array('spa', $z)) {
                $spaeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                $spanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                $Spa = $spaeks->merge($spanoneks);
            }

            if (in_array('spb', $z)) {
                $spbeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                $spbnoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                $Spb = $spbeks->merge($spbnoneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $eks = "";
            $noneks = "";
            if (in_array('ekspedisi', $x)) {
                $ekatalogeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spaeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spbeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $eks = $ekatalogeks->merge($spaeks)->merge($spbeks);
            }
            if (in_array('nonekspedisi', $x)) {
                $ekatalognoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spbnoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = $ekatalognoneks->merge($spanoneks)->merge($spbnoneks);
            }

            if ($eks != "" && $noneks != "") {
                $data = $eks->merge($noneks);
            } else if ($eks != "" && $noneks == "") {
                $data = $eks;
            } else if ($eks == "" && $noneks != "") {
                $data = $noneks;
            }
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }
            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }
            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Ekatalog = $eks->merge($noneks);
            }

            if (in_array('spa', $z)) {
                $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Spa = $eks->merge($noneks);
            }

            if (in_array('spb', $z)) {
                $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Spb = $eks->merge($noneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }

            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }

            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        }

        // $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
        // $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
        // $data = $dataeks->merge($datanoneks);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->DetailLogistik)) {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
                } else if (isset($data->DetailLogistikPart)) {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so;
                }
            })
            ->addColumn('sj', function ($data) {
                return $data->nosurat;
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })
            ->addColumn('no_resi', function ($data) {
                return "-";
            })
            ->addColumn('tgl_kirim', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->DetailLogistik)) {
                    $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    }
                } else if (isset($data->DetailLogistikPart)) {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('provinsi', function ($data) {
                if (isset($data->DetailLogistik)) {
                    $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                    }
                } else if (isset($data->DetailLogistikPart)) {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->status_id  == "10") {
                    return '<span class="badge blue-text">' . $data->State->nama . '</span>';
                } else if ($data->status_id  == "11") {
                    if (auth()->user()->divisi_id == "15") {
                        return '<a id="ubahstatus" data-id="' . $data->id . '" data-status="10"><button class="btn btn-outline-info btn-sm btn-circle"><i class="fas fa-paper-plane"></i></button><div><small>Kirim</small></div></a>';
                    } else if (auth()->user()->divisi_id == "2") {
                        return '<span class="badge red-text">' . $data->State->nama . '</span>';
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $string = "";
                $name = "";

                $provinsi = "";
                if (isset($data->DetailLogistik)) {
                    $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $provinsi =  $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->id;
                    } elseif ($name[1] == 'SPA') {
                        $provinsi =  $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->id;
                    }
                } else if (isset($data->DetailLogistikPart)) {
                    $provinsi =  $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->id;
                }


                if (isset($data->DetailLogistik)) {
                    $names = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    $name = $names[1];
                } else {
                    $name = "SPB";
                }
                $string .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>';
                if (auth()->user()->divisi_id == "15") {
                    $string .= '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-href="' . route('logistik.pengiriman.edit', [$data->id, $name]) . '" data-id="' . $data->id . '" data-attr="' . $name . '" data-provinsi="' . $provinsi . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </button>
                    </a>';
                }
                $string .= '<a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Surat Jalan
                        </button>
                    </a>
                </div>';
                return $string;
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }

    public function get_data_riwayat_pengiriman($pengiriman, $provinsi, $jenis_penjualan)
    {
        $x = explode(',', $pengiriman);
        $y = explode(',', $provinsi);
        $z = explode(',', $jenis_penjualan);
        $data = "";
        if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->get();
            $datanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->get();
            $data = $dataeks->merge($datanoneks);
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = "";
            $datanoneks = "";

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->get();
            }

            if ($dataeks != "" && $datanoneks != "") {
                $data = $dataeks->merge($datanoneks);
            } else if ($dataeks != "" && $datanoneks == "") {
                $data = $dataeks;
            } else if ($dataeks == "" && $datanoneks != "") {
                $data = $datanoneks;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $ekatalogeks = "";
            $spaeks = "";
            $spbeks = "";

            $ekatalognoneks = "";
            $spanoneks = "";
            $spbnoneks = "";

            $ekatalogeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spaeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spbeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $ekatalognoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spbnoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->get();
            }

            $data = $ekatalogeks->merge($ekatalognoneks)->merge($spaeks)->merge($spanoneks)->merge($spbeks)->merge($spbnoneks);
        } else if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $ekatalogeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                $ekatalognoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                $Ekatalog = $ekatalogeks->merge($ekatalognoneks);
            }

            if (in_array('spa', $z)) {
                $spaeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                $spanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                $Spa = $spaeks->merge($spanoneks);
            }

            if (in_array('spb', $z)) {
                $spbeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                $spbnoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                $Spb = $spbeks->merge($spbnoneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $eks = "";
            $noneks = "";
            if (in_array('ekspedisi', $x)) {
                $ekatalogeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spaeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spbeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $eks = $ekatalogeks->merge($spaeks)->merge($spbeks);
            }
            if (in_array('nonekspedisi', $x)) {
                $ekatalognoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spbnoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = $ekatalognoneks->merge($spanoneks)->merge($spbnoneks);
            }

            if ($eks != "" && $noneks != "") {
                $data = $eks->merge($noneks);
            } else if ($eks != "" && $noneks == "") {
                $data = $eks;
            } else if ($eks == "" && $noneks != "") {
                $data = $noneks;
            }
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }
            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }
            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Ekatalog = $eks->merge($noneks);
            }

            if (in_array('spa', $z)) {
                $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Spa = $eks->merge($noneks);
            }

            if (in_array('spb', $z)) {
                $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Spb = $eks->merge($noneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }

            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }

            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        }


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->DetailLogistik)) {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
                } else {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so;
                }
            })
            ->addColumn('sj', function ($data) {
                return $data->nosurat;
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })
            ->addColumn('no_resi', function ($data) {
                if ($data->noresi == "") {
                    return '-';
                } else {
                    return $data->noresi;
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                return  Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->DetailLogistik)) {
                    $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    }
                } else {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('provinsi', function ($data) {
                if (isset($data->DetailLogistik)) {
                    $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                    }
                } else {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge green-text">Selesai</span>';
            })
            ->addColumn('button', function ($data) {
                $name = "";
                if (isset($data->DetailLogistik)) {
                    $names = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    $name = $names[1];
                } else {
                    $name = "SPB";
                }
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>
                    <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Surat Jalan
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

    public function get_produk_detail_pengiriman($id, $jenis)
    {
        if ($jenis != "SPB") {
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
        } else {
            $l = DetailLogistikPart::where('logistik_id', $id)->get();
            return datatables()->of($l)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    return $data->DetailPesananPart->Sparepart->nama;
                })
                ->addColumn('jumlah', function ($data) {
                    // $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                    return $data->DetailPesananPart->jumlah;
                })
                ->addColumn('no_seri', function ($data) {
                    return '-';
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
    }

    //Edit
    public function update_modal_surat_jalan($id, $jenis)
    {
        $data = Logistik::find($id);
        return view('page.logistik.pengiriman.edit', ['id' => $id, 'data' => $data, 'jenis' => $jenis]);
    }

    public function update_pengiriman(Request $request, $id)
    {
        $bool = true;
        $datass = "";
        $l = Logistik::find($id);
        if ($l->status_id == "11") {
            if ($request->nama_pengirim == NULL) {
                $l->ekspedisi_id = $request->ekspedisi_id;
                $l->nama_pengirim = NULL;
                $r = $l->save();
                if (!$r) {
                    $bool = false;
                }
            } else {
                $l->ekspedisi_id = NULL;
                $l->nama_pengirim = $request->nama_pengirim;
                $r = $l->save();
                if (!$r) {
                    $bool = false;
                }
            }
        } else if ($l->status_id == "10") {
            $l->noresi = $request->no_resi;
            $r = $l->save();
            if (!$r) {
                $bool = false;
            }
        }
        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function update_status_pengiriman($id, $status)
    {
        $l = Logistik::find($id);
        $l->status_id = $status;
        $l->save();
        if ($l) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function update_so($proses, $id, $value)
    {
        if ($value == 'EKAT') {
            $data = Ekatalog::find($id);
            $status = "";

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('ekatalog.id', $id);
            })->get();

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $pesanan_id = $data->pesanan_id;

            $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->count();

            $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->count();

            if ($jumlahsudahuji == $jumlahterkirim) {
                $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            } else {
                if ($jumlahterkirim == 0) {
                    $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                }
            }


            $tgl_sekarang = Carbon::now()->format('Y-m-d');
            $tgl_parameter = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status)->format('Y-m-d');
            $param = "";
            if ($proses == "proses") {
                if ($tgl_sekarang < $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
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
                    $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                    $hari = $to->diffInDays($from);
                    $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                }
            } else {
                $param = Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y');
            }




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
            return view('page.logistik.so.detail_ekatalog', ['proses' => $proses, 'data' => $data, 'detail_id' => $detail_id, 'value' => $value, 'status' => $status, 'tgl_pengiriman' => $param]);
        } elseif ($value == 'SPA') {
            $data = Spa::find($id);

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $pesanan_id = $data->pesanan_id;

            $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->count();

            $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
                $q->where('pesanan_id', $pesanan_id);
            })->count();

            if ($jumlahsudahuji == $jumlahterkirim) {
                $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            } else {
                if ($jumlahterkirim == 0) {
                    $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                }
            }

            return view('page.logistik.so.detail_ekatalog', ['proses' => $proses, 'status' => $status, 'data' => $data, 'detail_id' => $detail_id, 'value' => $value, 'status' => $status]);
        } else {
            $data = Spb::find($id);

            $detail_pesanan  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $pesanan_id = $data->pesanan_id;
            $po = Pesanan::find($pesanan_id);

            // $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
            //     $q->where('pesanan_id', $pesanan_id);
            // })->count();

            // $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
            //     $q->where('pesanan_id', $pesanan_id);
            // })->count();

            // if ($jumlahsudahuji == $jumlahterkirim) {
            //     $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            // } else {
            //     if ($jumlahterkirim == 0) {
            //         $status =  ' <span class="badge red-text">Belum Dikirim</span>';
            //     } else {
            //         $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
            //     }
            // }

            if ($po->getJumlahKirimPart() == $po->getJumlahPesananPart()) {
                $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            } else {
                if ($po->getJumlahKirimPart() == 0) {
                    $status =  ' <span class="badge red-text">Belum Dikirim</span>';
                } else {
                    $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
                }
            }
            return view('page.logistik.so.detail_ekatalog', ['proses' => $proses, 'status' => $status, 'data' => $data, 'detail_id' => $detail_id, 'value' => $value, 'status' => $status]);
        }
    }
    public function create_logistik_view($produk_id, $part_id, $pesanan_id, $jenis)
    {
        $value = array();
        $value2 = [];
        $id = [];
        $id_produk = [];
        $a = 0;
        $x = explode(',', $produk_id);
        $y = explode(',', $part_id);

        if ($jenis == "EKAT") {
            if ($produk_id == '0') {
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

            return view('page.logistik.so.create', ['id' => $id, 'id_produk' => $id_produk, 'jenis' => $jenis]);
        } else {

            if ($produk_id != 0 && $part_id == 0) {
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
            } else if ($produk_id == 0 && $part_id != 0) {
                foreach ($y as $d) {
                    $value[$a]['id'] = $d;
                    $a++;
                }
                $id =  json_encode($value);
                $id_produk =  json_encode($value2);
            } else if ($produk_id != 0 && $part_id != 0) {
            } else {
            }
            // if ($detail_pesanan_id == "0") {
            //     $array_id = array();
            //     $datas = DetailPesananPart::where('pesanan_id', $pesanan_id)->get();
            //     foreach ($datas as $i) {
            //         if (isset($i->DetailLogistikPart)) {
            //             echo $i->id;
            //             $value[$a]['id'] = $i->id;
            //             $a++;
            //         }
            //     }

            //     $id =  json_encode($value);
            //     $id_produk =  json_encode($value2);
            // } else {
            //     foreach ($x as $d) {
            //         $value[$a]['id'] = $d;
            //         $a++;
            //     }
            //     $id =  json_encode($value);
            //     $id_produk =  json_encode($value2);
            // }
            return view('page.logistik.so.create', ['id' => $id, 'id_produk' => $id_produk, 'jenis' => $jenis]);
        }
    }
    public function create_logistik(Request $request, $detail_pesanan_id, $id_produk, $jenis)
    {
        $array = array_values(json_decode($detail_pesanan_id, true));
        $ids = "";
        $kodesj = "";
        if ($jenis != "SPB") {
            $kodesj = "SPA-";
        } else {
            $kodesj = "B.";
        }
        // return response()->json(['data' =>  $poid]);

        $bool = true;
        $Logistik = "";
        if ($request->pengiriman == 'ekspedisi') {
            $Logistik = Logistik::create([
                'ekspedisi_id' => $request->ekspedisi_id,
                'nosurat' => $kodesj . $request->no_invoice,
                'tgl_kirim' => $request->tgl_kirim,
                'status_id' => '11'
            ]);
        } else {
            $Logistik = Logistik::create([
                'nosurat' => $kodesj . $request->no_invoice,
                'tgl_kirim' => $request->tgl_kirim,
                'nama_pengirim' => $request->nama_pengirim,
                'status_id' => '11'
            ]);
        }


        if ($jenis != "SPB") {
            if ($Logistik) {
                for ($i = 0; $i < count($array); $i++) {
                    $c = DetailLogistik::create([
                        'logistik_id' => $Logistik->id,
                        'detail_pesanan_produk_id' => $array[$i]['id'],
                    ]);
                    $ids =  $array[$i]['id'];
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
            // echo $ids;
            // $poid = "";
            if ($ids) {
                $iddpp = DetailPesananProduk::find($ids);
                $poid = strval($iddpp->DetailPesanan->Pesanan->id);
                $po = Pesanan::find($poid);
                if ($po) {
                    if ($po->getJumlahKirim() > 0) {
                        if ($po->getJumlahPesanan() > $po->getJumlahKirim()) {
                            $po->log_id = '13';
                            $po->save();
                        } else if ($po->getJumlahPesanan() == $po->getJumlahKirim()) {
                            $po->log_id = '10';
                            $po->save();
                        }
                    }
                }
            }
        } else {
            if ($Logistik) {
                for ($i = 0; $i < count($array); $i++) {
                    $c = DetailLogistikPart::create([
                        'logistik_id' => $Logistik->id,
                        'detail_pesanan_part_id' => $array[$i]['id']
                    ]);
                    $ids =  $array[$i]['id'];
                    if (!$c) {
                        $bool = false;
                    }
                }
            }

            if ($bool == true) {
                $iddpp = DetailPesananPart::find($ids);
                $poid = $iddpp->pesanan_id;
                $po = Pesanan::find($poid);
                if ($po) {
                    if ($po->getJumlahKirimPart() > 0) {
                        if ($po->getJumlahPesananPart() > $po->getJumlahKirimPart()) {
                            $po->log_id = '13';
                            $po->save();
                        } else if ($po->getJumlahPesananPart() == $po->getJumlahKirimPart()) {
                            $po->log_id = '10';
                            $po->save();
                        }
                    }
                }
            }
        }

        if ($bool == true) {
            return response()->json(['data' => "success"]);
        } else if ($bool == false) {
            return response()->json(['data' => 'error']);
        }
    }

    //Dashboard
    public function dashboard()
    {
        $terbaru = Pesanan::Has('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
            $q->where('tgl_uji', '>=', Carbon::now()->subdays(7));
        })->get()->count();
        $belum_dikirim = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->DoesntHave('Pesanan.DetailPesanan.DetailPesananProduk.DetailLogistik')->get()->count();
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
                // $datas = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($pesanan_id) {
                //     $q->where('pesanan_id', $pesanan_id);
                // })->get();
                // $array_id = array();
                // foreach ($datas as $i) {
                //     $ids = $i->id;
                //     $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($ids) {
                //         $q->where('detail_pesanan_produk_id', $ids);
                //     })->count();
                //     $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $ids])->count();

                //     $detail_pesanan = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($ids) {
                //         $q->where('id', $ids);
                //     })->get();
                //     $jumlahpesanan = 0;

                //     $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
                //     if ($jumlahsekarang > 0) {
                //         $array_id[] = $i->id;
                //     }
                // }

                // foreach ($array_id as $d) {
                //     $value[$a]['id'] = $d;
                //     $count = 0;

                //     $e = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $d])->doesntHave('NoseriDetailLogistik')->get();
                //     foreach ($e as $f) {
                //         $value[$a]['noseri'][$count] = $f->id;
                //         $count++;
                //     }
                //     $a++;
                // }
            }
        }

        return view('page.logistik.dashboard', ['terbaru' => $terbaru, 'belum_dikirim' => $belum_dikirim, 'lewat_batas' => $lewat_batas]);
        // }
    }
    public function dashboard_data($value)
    {
        if ($value == 'terbaru') {
            $data = Pesanan::Has('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
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
                                return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
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

                    $z = "";
                    if ($data->getJumlahCek() == $data->getJumlahPesanan()) {
                        $z = "selesai";
                    } else {
                        $z = "proses";
                    }
                    return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
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
            $data = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->DoesntHave('Pesanan.DetailPesanan.DetailPesananProduk.DetailLogistik')->get();
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
                                return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                            } else {
                                return '' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                            }
                        } elseif ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        }
                    } else {
                        return '-';
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
                    $z = "proses";
                    return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
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
                    $z = "proses";
                    return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
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
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($ekspedisi, $tgl_awal, $tgl_akhir) {
                $q->where('ekspedisi_id', $ekspedisi)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($ekspedisi, $tgl_awal, $tgl_akhir) {
                $q->where('ekspedisi_id', $ekspedisi)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $s = $prd->merge($prt);
        } else if ($pengiriman == "nonekspedisi") {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $s = $prd->merge($prt);
        } else {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $s = $prd->merge($prt);
        }

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return $data->DetailPesananProduk->DetailPesanan->Pesanan->so;
                } else {
                    return $data->DetailPesananPart->Pesanan->so;
                }
            })
            ->addColumn('sj', function ($data) {
                return $data->Logistik->nosurat;
            })
            ->addColumn('invoice', function ($data) {
                return '-';
            })
            ->addColumn('no_resi', function ($data) {
                if ($data->Logistik->noresi == "") {
                    return '-';
                } else {
                    return $data->Logistik->noresi;
                }
            })
            ->addColumn('customer', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                    } else if ($name[1] == 'SPA') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    }
                } else {
                    return $data->DetailPesananPart->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                    }
                } else {
                    return $data->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                    }
                } else {
                    return $data->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('telp', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
                    }
                } else {
                    return $data->DetailPesananPart->Pesanan->Spb->Customer->telp;
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
                if (isset($data->DetailPesananProduk)) {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                } else {
                    return $data->DetailPesananPart->Sparepart->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                if (isset($data->NoseriDetailLogistik)) {
                    return $data->NoseriDetailLogistik->count();
                } else {
                    return $data->DetailPesananPart->jumlah;
                }
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

    public function check_no_sj($val)
    {
        $e = Logistik::where('nosurat', 'SPA-' . $val)->count();
        return $e;
    }

    public function check_no_resi($val)
    {
        $e = Logistik::where('noresi', $val)->count();
        return $e;
    }
}
