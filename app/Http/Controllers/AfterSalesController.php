<?php

namespace App\Http\Controllers;

use App\Models\Logistik;
use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;

use App\Models\DetailLogistikPart;
use App\Models\DetailPesananPart;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use App\Models\Spb;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AfterSalesController extends Controller
{
    public function get_data_so()
    {
        // $data = Logistik::all();

        $datas = DetailPesanan::whereHas('DetailPesananProduk.DetailLogistik.Logistik', function ($q) {
            $q->whereIn('status_id', ['10']);
        })->orderBy('id', 'desc')->get();

        $datas1 = DetailPesananPart::whereHas('DetailLogistikPart.Logistik', function ($q) {
            $q->whereIn('status_id', ['10']);
        })->orderBy('id', 'desc')->get();

        $data = $datas->merge($datas1);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $id = array();
                    $detail_produk = DetailPesananProduk::where('detail_pesanan_id', $data->id)->get();
                    foreach ($detail_produk as $d) {
                        if ($d->gudangbarangjadi->nama == '') {
                            $id[] = $d->gudangbarangjadi->produk->nama;
                        } else {
                            $id[]  = $d->gudangbarangjadi->nama;
                        }
                    }
                    return implode(',', $id);
                } else {
                    return $data->Sparepart->nama;
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                $id = $data->id;
                if (isset($data->DetailPesananProduk)) {
                    $l = Logistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
                        $q->where('detail_pesanan_id', $id);
                    })->selectRaw("min(tgl_kirim) as tgl_kirim")->first();
                    return Carbon::createFromFormat('Y-m-d', $l->tgl_kirim)->format('d-m-Y');
                } else {
                    $l = Logistik::whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                        $q->where('id', $id);
                    })->selectRaw("min(tgl_kirim) as tgl_kirim")->first();
                    return Carbon::createFromFormat('Y-m-d', $l->tgl_kirim)->format('d-m-Y');
                }
                // $arr = array();
                // foreach ($l as $k) {
                //     $arr[] = Carbon::createFromFormat('Y-m-d', $k->tgl_kirim)->format('d-m-Y');
                // };

                // return implode(", ", $arr);
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Provinsi->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->Provinsi->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('telepon', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('status', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $id = $data->id;
                    $jumlahkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                        $q->where('id', $id);
                    })->count();
                    $jumlahseri = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                        $q->where('detail_pesanan_id', $id);
                    })->count();

                    if ($jumlahkirim == $jumlahseri) {
                        return '<span class="badge green-text">Selesai</span>';
                    } else if ($jumlahkirim < $jumlahseri) {
                        return '<span class="badge yellow-text">Terkirim Sebagian</span>';
                    }
                } else {
                    $jumlahkirim = DetailLogistikPart::where('detail_pesanan_part_id', $data->id)->first();
                    if ($jumlahkirim->DetailPesananPart->jumlah >= $data->jumlah) {
                        return '<span class="badge green-text">Selesai</span>';
                    } else {
                        return '<span class="badge yellow-text">Terkirim Sebagian</span>';
                    }
                }
            })
            ->addColumn('keterangan', function ($data) {
                return "-";
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                $jenis = "";
                if (isset($data->DetailPesananProduk)) {
                    $jenis = "produk";
                } else {
                    $jenis = "part";
                }
                return '<a href="' . route('as.so.detail', ['id' => $data->id, 'jenis' => $jenis]) . '"><i class="fas fa-search"></i></a>';
                // $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                // return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                // <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                //     <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
                //         <button class="dropdown-item" type="button">
                //             <i class="fas fa-search"></i>
                //             Detail
                //         </button>
                //     </a>
                //     <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                //         <button class="dropdown-item" type="button">
                //             <i class="fas fa-file"></i>
                //             Laporan PDF
                //         </button>
                //     </a>
                // </div>';
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
        // return datatables()->of($data)
        //     ->addIndexColumn()
        //     ->addColumn('so', function ($data) {
        //         return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
        //     })
        //     ->addColumn('tgl_kirim', function ($data) {
        //         return  Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
        //     })
        //     ->addColumn('nama_customer', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
        //         }
        //     })
        //     ->addColumn('alamat', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
        //         }
        //     })
        //     ->addColumn('provinsi', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
        //         }
        //     })
        //     ->addColumn('telepon', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp;
        //         }
        //     })
        //     ->addColumn('status', function ($data) {
        //         if ($data->status_id  == "10") {
        //             return '<span class="badge blue-text">' . $data->State->nama . '</span>';
        //         } else if ($data->status_id  == "11") {
        //             return '<span class="badge red-text">' . $data->State->nama . '</span>';
        //         }
        //     })
        //     ->addColumn('keterangan', function ($data) {
        //         return "-";
        //     })
        //     ->addColumn('button', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        //         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //             <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
        //                 <button class="dropdown-item" type="button">
        //                     <i class="fas fa-search"></i>
        //                     Detail
        //                 </button>
        //             </a>
        //             <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
        //                 <button class="dropdown-item" type="button">
        //                     <i class="fas fa-file"></i>
        //                     Laporan PDF
        //                 </button>
        //             </a>
        //         </div>';
        //     })
        //     ->rawColumns(['status', 'button'])
        //     ->make(true);
    }

    public function get_detail_so($id, $jenis)
    {
        $d = "";
        $status = "";
        if ($jenis == "produk") {
            $d = DetailPesanan::find($id);

            $jumlahkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->count();

            $jumlahseri = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('detail_pesanan_id', $id);
            })->count();

            if ($jumlahkirim == $jumlahseri) {
                $status = '<span class="badge green-text">Selesai</span>';
            } else if ($jumlahkirim < $jumlahseri) {
                $status = '<span class="badge yellow-text">Terkirim Sebagian</span>';
            }
        } else {
            $d = DetailPesananPart::find($id);
            $jumlahkirim = 0;
            if (isset($d->DetailLogistikPart)) {
                $jumlahkirim = $d->jumlah;
            } else {
                $jumlahkirim = 0;
            }

            if ($jumlahkirim == $d->jumlah) {
                $status = '<span class="badge green-text">Selesai</span>';
            } else if ($jumlahkirim < $d->jumlah) {
                $status = '<span class="badge yellow-text">Terkirim Sebagian</span>';
            }
        }

        return view('page.as.so.detail', ['id' => $id, 'jenis' => $jenis, 'd' => $d, 'status' => $status]);
    }

    public function get_detail_pengiriman($id, $jenis)
    {
        $data = "";
        if ($jenis == "produk") {
            $data = DetailLogistik::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('detail_pesanan_id', $id);
            })->whereHas('Logistik', function ($q) {
                $q->whereIn('status_id', ['10']);
            })->orderBy('id', 'desc')->get();
        } else {
            $data = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
                $q->where('id', $id);
            })->whereHas('Logistik', function ($q) {
                $q->whereIn('status_id', ['10']);
            })->orderBy('id', 'desc')->get();
        }

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
                if (isset($data->DetailPesananProduk)) {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                } else {
                    return $data->DetailPesananPart->Sparepart->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                } else {
                    $c = $data->DetailPesananPart->jumlah;
                }
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
}
