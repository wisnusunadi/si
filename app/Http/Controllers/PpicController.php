<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

// model
use App\Models\Produk;
use App\Models\JadwalPerakitan;
use App\Models\GudangBarangJadi;

// event
use App\Events\TestEvent;
use App\Models\DetailPesananProduk;
use App\Models\DetailPesanan;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;

class PpicController extends Controller
{
    // API
    public function getEvent($status, Request $request)
    {
        $month = date('m');
        $year = date('Y');
        $event = JadwalPerakitan::with('Produk')->orderBy('tanggal_mulai', 'asc');

        if (isset($request->proses_konfirmasi)) $event->where('proses_konfirmasi', $request->proses_konfirmasi);

        if ($status == "pelaksanaan") {
            $event = $event->whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
            $this->updateStatus($event, 'pelaksanaan');
        } else if ($status == "penyusunan") {
            $month += 1;
            $event = $event->where('tanggal_mulai', '>=', "$year-$month-01")->get();
            $this->updateStatus($event, 'penyusunan');
        } else {
            $event = $event->where('tanggal_mulai', '<', "$year-$month-01")->get();
            $this->updateStatus($event, 'selesai');
        }

        return $event;
    }

    public function getProduk()
    {
        $model = Produk::all();

        return $model;
    }

    public function addEvent(Request $request)
    {
        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'warna' => $request->warna,
            'konfirmasi_rencana' => 0,
            'konfirmasi_perubahan' => 0,
            'proses_konfirmasi' => 0
        ];
        JadwalPerakitan::create($data);

        return $this->getEvent($request->status, $request);
    }

    public function deleteEvent(Request $request)
    {
        JadwalPerakitan::destroy($request->id);
        return JadwalPerakitan::with("Produk")->get();
    }

    public function updateStatus($event, $status)
    {
        foreach ($event as $data) {
            $data->status = $status;
            $data->save();
        }
    }

    public function updateConfirmation(Request $request)
    {
        $event = JadwalPerakitan::where('status', $request->status)->get();
        foreach ($event as $data) {
            if (isset($request->proses_konfirmasi)) $data->proses_konfirmasi = $request->proses_konfirmasi;
            if (isset($request->konfirmasi_rencana)) $data->konfirmasi_rencana = $request->konfirmasi_rencana;
            if (isset($request->konfirmasi_perubahan)) $data->konfirmasi_perubahan = $request->konfirmasi_perubahan;
            $data->save();
        }

        return $this->getEvent($request->status, $request);
    }

    public function resetConfirmation()
    {
        $event = JadwalPerakitan::all();
        foreach ($event as $data) {
            if ($data->status == "penyusunan") {
                $data->konfirmasi_rencana = 0;
                $data->konfirmasi_perubahan = 0;
            } else if ($data->status == "pelaksanaan") {
                $data->konfirmasi_rencana = 1;
                $data->konfirmasi_perubahan = 0;
            }
            $data->proses_konfirmasi = 0;
            $data->save();
        }

        return "success";
    }

    public function getGbjQuery()
    {
        $query = GudangBarangJadi::with('produk', 'noseri')->get();

        return $query;
    }

    public function getGbjDatatable()
    {
        $query = $this->getGbjQuery();

        return DataTables::of($query)->addIndexColumn()->make(true);
    }

    public function testBroadcast(Request $request)
    {
        broadcast(new TestEvent($request->message))->toOthers();
        return "success";
    }

    public function get_master_stok_data()
    {
        // $data = GudangBarangJadi::has('DetailPesananProduk')->orWhereHas('DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) {
        //     $q->whereIn('status', ['sepakat', 'nego'])->whereIn('log', ['penjualan', 'po']);
        // })->orWhereHas('DetailPesananProduk.DetailPesanan.Pesanan.Spa', function ($q) {
        //     $q->whereIn('log', ['penjualan', 'po']);
        // })->orWhereHas('DetailPesananProduk.DetailPesanan.Pesanan.Spb', function ($q) {
        //     $q->whereIn('log', ['penjualan', 'po']);
        // })->count();

        $Ekatalog = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) {
            $q->whereIn('status', ['sepakat', 'nego', 'batal'])->whereIn('log', ['penjualan', 'po']);
        })->get();
        $Spa = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan.Spa', function ($q) {
            $q->whereIn('log', ['penjualan', 'po']);
        })->get();
        $Spb = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan.Spb', function ($q) {
            $q->whereIn('log', ['penjualan', 'po']);
        })->get();

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->Produk->nama . " " . $data->nama;
            })
            ->addColumn('gbj', function ($data) {
                $jumlah_gbj = $data->stok;
                $jumlah_stok_permintaan = $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat') + $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi') + $this->get_count_spa_spb_po($data->id);
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                if ($jumlah >= 0) {
                    return "<div>" . $jumlah . "</div>";
                } else {
                    return '<div style="color:red;">' . $jumlah . '</div>';
                }
            })
            ->addColumn('gk', function ($data) {
                return "-";
            })
            ->addColumn('sepakat', function ($data) {
                // $id = $data->id;
                // $result = Ekatalog::whereHas('Pesanan.DetailPesanan.DetailPesananProduk', function ($q) use ($id) {
                //     $q->where('gudang_barang_jadi_id', $id);
                // })->where('status', '=', 'sepakat')->whereIn('log', ['penjualan', 'po'])->get();

                // $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                //     $q->where('gudang_barang_jadi_id', $id);
                // })->whereHas('Pesanan.Ekatalog', function ($q) {
                //     $q->where('status', '=', 'sepakat')->whereIn('log', ['penjualan', 'po']);
                // })->get();
                // $jumlah = 0;
                // foreach ($res as $a) {
                //     $a->jumlah;
                //     foreach ($a->PenjualanProduk->Produk as $b) {
                //         if ($b->id == $data->produk->id) {
                //             $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                //         }
                //     }
                // }
                // return $result;
                // $gs = array();
                // // $hs = array();
                // $jumlah = 0;
                // foreach ($result as $f) {
                //     foreach ($f->Pesanan->DetailPesanan as $g) {
                //         $gs[] = $g;
                //         $jumlahpivot = 0;
                //         $jumlahbarang = 0;
                // $hs[] = $g->PenjualanProduk->Produk->pivot->id;

                // foreach ($g->PenjualanProduk->Produk as $h) {
                //     if ($h->id == $data->produk->id) {
                //         $jumlahpivot = $h->pivot->jumlah;
                //         $jumlahbarang = $g->jumlah;

                //         $jumlah = $jumlah + ($jumlahpivot * $jumlahbarang);
                // $hs[] = " jumlah pivot " . $h->pivot->jumlah . ' jumlah barang ' . $g->jumlah;
                // }

                // foreach ($h as $i) {
                // }
                // if ($h->produk_id == $data->produk->id) {
                //     $hs[] = $h->produk_id;
                // }
                // $hs[]['id'] = $h->produk_id;
                // $hs[]['jumlah'] = $h->pivot->jumlah;
                // $hs[]['id_produk'] = $data->produk_id;
                // return $h->pivot->jumlah . " produk id mtom " . $h->id . " produk data " . $data->produk->id;
                // }

                // foreach ($g->DetailPesananProduk as $h) {
                //     if ($h->gudang_barang_jadi_id == $id) {
                //         $hs[] = 
                //     }
                // }
                // }
                // }
                // return $hs;
                // return $jumlah;
                return $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat');
                // foreach ($result as $f) {
                //     foreach ($f->Pesanan->DetailPesanan as $g) {
                //         return $g->id;
                //         foreach ($g->PenjualanProduk->Produk as $h) {
                //             return $h->pivot->jumlah . " produk id mtom " . $h->id . " produk data " . $data->produk->id;
                //         }
                //         // return $g->PenjualanProduk->Produk->first()->pivot->jumlah . " produk id mtom " . $g->PenjualanProduk->Produk->first()->id . " produk data " . $data->produk->id;
                //         // if ($data->produk->id) {
                //         //     $jumlah = $g->PenjualanProduk->Produk->first()->pivot->jumlah;
                //         //     return $jumlah;
                //         // }
                //     }
                // }
            })
            ->addColumn('nego', function ($data) {
                // $id = $data->id;
                // $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                //     $q->where('gudang_barang_jadi_id', $id);
                // })->whereHas('Pesanan.Ekatalog', function ($q) {
                //     $q->where('status', '=', 'negosiasi')->whereIn('log', ['penjualan', 'po']);
                // })->get();
                // $jumlah = 0;
                // foreach ($res as $a) {
                //     $a->jumlah;
                //     foreach ($a->PenjualanProduk->Produk as $b) {
                //         if ($b->id == $data->produk->id) {
                //             $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                //         }
                //     }
                // }
                // // return $hs;
                return $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi');
            })
            ->addColumn('batal', function ($data) {
                // $id = $data->id;
                // $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                //     $q->where('gudang_barang_jadi_id', $id);
                // })->whereHas('Pesanan.Ekatalog', function ($q) {
                //     $q->where('status', '=', 'batal')->whereIn('log', ['penjualan', 'po']);
                // })->get();
                // $jumlah = 0;
                // foreach ($res as $a) {
                //     $a->jumlah;
                //     foreach ($a->PenjualanProduk->Produk as $b) {
                //         if ($b->id == $data->produk->id) {
                //             $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                //         }
                //     }
                // }
                // return $hs;

                // return $jumlah;
                return $this->get_count_ekatalog($data->id, $data->produk->id, 'batal');
            })
            ->addColumn('po', function ($data) {
                return $this->get_count_spa_spb_po($data->id);
            })
            ->addColumn('aksi', function ($data) {
                return '<i class="fas fa-search"></i>';
            })
            ->rawColumns(['gbj', 'aksi'])
            ->make(true);
    }

    public function get_count_ekatalog($id, $produk_id, $status)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan.Ekatalog', function ($q) use ($status) {
            $q->where('status', '=', $status)->whereIn('log', ['penjualan', 'po']);
        })->get();
        $jumlah = 0;
        foreach ($res as $a) {
            $a->jumlah;
            foreach ($a->PenjualanProduk->Produk as $b) {
                if ($b->id == $produk_id) {
                    $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                }
            }
        }
        return $jumlah;
    }

    public function get_count_spa_spb_po($id)
    {
        $res1 = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan.Spa', function ($q) {
            $q->whereIn('log', ['penjualan', 'po']);
        })->get();

        $res2 = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan.Spb', function ($q) {
            $q->whereIn('log', ['penjualan', 'po']);
        })->get();

        $jumlah = $res1->merge($res2)->count();
        return $jumlah;
    }
}
