<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

// model
use App\Models\JadwalPerakitan;
use App\Models\JadwalPerakitanRencana;
use App\Models\JadwalPerakitanLog;
use App\Models\GudangBarangJadi;
use App\Models\GudangKarantinaDetail;
use App\Models\KomentarJadwalPerakitan;
use App\Models\DetailPesanan;
use App\Models\NoseriDetailLogistik;
use App\Models\Pesanan;
use App\Models\Produk;


class PpicController extends Controller
{
    // Properties
    public function change_status($status)
    {
        if ($status == 'penyusunan') return 6;
        else if ($status == 'pelaksanaan') return 7;
        else if ($status == 'selesai') return 8;
        return $status;
    }

    public function change_state($state)
    {
        if ($state == 'perencanaan') return 17;
        else if ($state == 'persetujuan') return 18;
        else if ($state == 'perubahan') return 19;
        return $state;
    }

    // API
    public function get_data_perakitan($status = "all")
    {
        $this->update_perakitan_status();
        $status = $this->change_status($status);
        if ($status == $this->change_status('penyusunan')) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        } else if ($status == $this->change_status("pelaksanaan")) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        } else {
            $data = JadwalPerakitan::with('Produk.produk')->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        }

        return $data;
    }

    public function get_data_perakitan_rencana()
    {
        $data = JadwalPerakitanRencana::with('JadwalPerakitan.Produk.produk')->orderBy('tanggal_mulai', 'asc')->get();
        return $data;
    }

    public function get_data_barang_jadi(Request $request)
    {
        $data = GudangBarangJadi::with('produk.KelompokProduk', 'produk.product', 'satuan');
        if (isset($request->id)) {
            $data->where('id', $request->id);
        }
        $data = $data->get();
        return $data;
    }

    public function get_data_so()
    {
        $data = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if ($data->nama) {
                    return $data->Produk->nama . " - <b>" . $data->nama . "</b>";
                } else {
                    return $data->Produk->nama;
                }
            })
            ->addColumn('gbj', function ($data) {
                return $data->stok;
            })
            ->addColumn('total', function ($data) {
                $jumlah_stok_permintaan = $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat') + $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi') + $this->get_count_spa_spb_po($data->id, $data->produk->id);
                return $jumlah_stok_permintaan;
            })
            ->addColumn('penjualan', function ($data) {
                $jumlah_gbj = $data->stok;
                $jumlah_stok_permintaan = $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat') + $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi') + $this->get_count_spa_spb_po($data->id, $data->produk->id);
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                if ($jumlah >= 0) {
                    return "<div>" . $jumlah . "</div>";
                } else {
                    return '<div style="color:red;">' . $jumlah . '</div>';
                }
            })
            ->addColumn('aksi', function ($data) {
                return '
                    <button @click="alert(`test`)">test</button>
                ';
            })
            ->rawColumns(['gbj', 'aksi', 'penjualan', 'nama_produk'])
            ->make(true);
    }

    public function get_data_sparepart_gk()
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        return $data;
    }

    public function get_data_unit_gk(Request $request)
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id');

        if (isset($request->id)) {
            $data->where('gbj_id', $request->id);
        }

        $data = $data->get();
        return $data;
    }

    public function get_komentar_jadwal_perakitan(Request $request)
    {
        $data = KomentarJadwalPerakitan::where('status', $this->change_status($request->status))->orderBy('tanggal_permintaan', 'desc')->get();
        return $data;
    }

    public function count_proses_jadwal()
    {
        $data = KomentarJadwalPerakitan::all();
        $permintaan = 0;
        $proses = 0;

        foreach ($data as $item) {
            if (!$item->tanggal_hasil) {
                $permintaan += 1;
            } else {
                if ((time() - (60 * 60 * 24)) < strtotime($item->tanggal_hasil)) {
                    $proses += 1;
                }
            }
        }

        return [$permintaan, $proses];
    }

    public function create_data_perakitan(Request $request)
    {
        $status = $this->change_status($request->status);
        $state = $this->change_state($request->state);

        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $status,
            'state' => $state,
            'konfirmasi' => $request->konfirmasi,
            'warna' => $request->warna,
            'status_tf' => 11,
        ];
        JadwalPerakitan::create($data);

        return $this->get_data_perakitan($status);
    }

    public function create_komentar_jadwal_perakitan(Request $request)
    {
        $state = $this->change_state($request->state);
        $status = $this->change_status($request->status);
        KomentarJadwalPerakitan::create([
            'tanggal_permintaan' => $request->tanggal_permintaan,
            'tanggal_hasil' => $request->tanggal_hasil,
            'state' => $state,
            'status' => $status,
            'hasil' => $request->hasil,
            'komentar' => $request->komentar,
        ]);
    }

    public function update_komentar_jadwal_perakitan(Request $request)
    {
        $data = KomentarJadwalPerakitan::orderBy('tanggal_permintaan', 'desc')->where("status", $this->change_status($request->status))->first();
        $data->tanggal_hasil = $request->tanggal_hasil;
        $data->hasil = $request->hasil;
        $data->komentar = $request->komentar;
        $data->save();
    }

    public function update_data_perakitan(Request $request, $id)
    {
        $data = JadwalPerakitan::find($id);

        $object = new JadwalPerakitanLog();
        $object->jadwal_perakitan_id = $data->id;
        $object->tanggal_mulai = $data->tanggal_mulai;
        $object->tanggal_selesai = $data->tanggal_selesai;

        if (isset($request->tanggal_mulai)) {
            $data->tanggal_mulai = $request->tanggal_mulai;
            $object->tanggal_mulai_baru = $request->tanggal_mulai;
        } else {
            $object->tanggal_mulai_baru = $data->tanggal_mulai;
        }
        if (isset($request->tanggal_selesai)) {
            $data->tanggal_selesai = $request->tanggal_selesai;
            $object->tanggal_selesai_baru = $request->tanggal_selesai;
        } else {
            $object->tanggal_selesai_baru = $data->tanggal_selesai;
        }

        if (isset($request->state)) {
            $state = $this->change_state($request->state);
            $data->state = $state;
        }
        if (isset($request->konfirmasi)) {
            $data->konfirmasi = $request->konfirmasi;
        }
        $object->save();
        $data->save();

        return $this->get_data_perakitan($request->status);
    }

    public function update_many_data_perakitan(Request $request, $status)
    {
        if (isset($request->data)) {
            foreach ($request->data as $data) {
                $this->update_data_perakitan($request, $data['id']);
            }
        } else {
            $event = JadwalPerakitan::where('status', $this->change_status($status))->get();
            foreach ($event as $data) {
                $object = new JadwalPerakitanLog();
                $object->jadwal_perakitan_id = $data->id;
                $object->tanggal_mulai = $data->tanggal_mulai;
                $object->tanggal_selesai = $data->tanggal_selesai;

                if (isset($request->tanggal_mulai)) {
                    $data->tanggal_mulai = $request->tanggal_mulai;
                    $object->tanggal_mulai_baru = $request->tanggal_mulai;
                } else {
                    $object->tanggal_mulai_baru = $data->tanggal_mulai;
                }

                if (isset($request->tanggal_selesai)) {
                    $data->tanggal_selesai = $request->tanggal_selesai;
                    $object->tanggal_selesai_baru = $request->tanggal_selesai;
                } else {
                    $object->tanggal_selesai_baru = $data->tanggal_selesai;
                }

                if (isset($request->state)) {
                    $state = $this->change_state($request->state);
                    $data->state = $state;
                }
                if (isset($request->konfirmasi)) {
                    $data->konfirmasi = $request->konfirmasi;
                }
                $object->save();
                $data->save();
            }
        }

        return $this->get_data_perakitan($status);
    }

    public function delete_data_perakitan(Request $request, $id)
    {
        $data = JadwalPerakitan::find($id);
        $data->delete();
    }

    public function counting_status_data_perakitan()
    {
        $penyusunan = count(JadwalPerakitan::where('status', $this->change_status('penyusunan'))->get());
        $pelaksanaan = count(JadwalPerakitan::where('status', $this->change_status('pelaksanaan'))->get());
        $selesai = count(JadwalPerakitan::where('status', $this->change_status('selesai'))->get());

        return [$penyusunan, $pelaksanaan, $selesai];
    }


    // helper function
    public function update_perakitan_status()
    {
        // update jadwal_perakitan
        $month = date('m');
        $year = date('Y');

        if ($month != 12) {
            $new_month = $month + 1;
            $new_year = $year;
        } else {
            $new_month = 1;
            $new_year = $year + 1;
        }
        $penyusunan = JadwalPerakitan::where('tanggal_mulai', '>=', "$new_year-$new_month-01")->get();
        foreach ($penyusunan as $data) {
            $data->status = $this->change_status('penyusunan');
            $data->save();
        }

        $update_rencana_jadwal = false;
        if (
            count(JadwalPerakitanRencana::all()) == 0 ||
            $month != date('m', strtotime(JadwalPerakitanRencana::first()->tanggal_mulai))
        ) {
            // empty jadwal_perakitan_rencana table
            JadwalPerakitanRencana::truncate();
            $update_rencana_jadwal = true;
        }

        $pelaksanaan = JadwalPerakitan::whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
        foreach ($pelaksanaan as $data) {
            $data->status = $this->change_status('pelaksanaan');
            $data->save();

            if ($update_rencana_jadwal) {
                // insert data to jadwal_perakitan_rencana                
                JadwalPerakitanRencana::create([
                    'jadwal_perakitan_id' => $data->id,
                    'tanggal_mulai' => $data->tanggal_mulai,
                    'tanggal_selesai' => $data->tanggal_selesai,
                ]);
            }
        }

        $selesai = JadwalPerakitan::where('tanggal_mulai', '<', "$year-$month-01")->get();
        foreach ($selesai as $data) {
            $data->status = $this->change_status('selesai');
            $data->save();
        }
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

        $data = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->get();
        // $Nonekatalog = GudangBarangJadi::doesntHave('DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
        //     $q->whereIn('log_id', ['7', '9']);
        // })->get();

        // $data = $Ekatalog;

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (!empty($data->nama)) {
                    return $data->Produk->nama . " - <b>" . $data->nama . "</b>";
                } else {
                    return $data->Produk->nama;
                }
            })
            ->addColumn('gbj', function ($data) {
                return $data->stok;
            })
            ->addColumn('penjualan', function ($data) {
                $jumlah_gbj = $data->stok;
                $jumlah_stok_permintaan = $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat') + $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi') + $this->get_count_spa_spb_po($data->id, $data->produk->id);
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                if ($jumlah >= 0) {
                    return "<div>" . $jumlah . "</div>";
                } else {
                    return '<div style="color:red;">' . $jumlah . '</div>';
                }
            })
            ->addColumn('total', function ($data) {
                $jumlah_stok_permintaan = $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat') + $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi') + $this->get_count_spa_spb_po($data->id, $data->produk->id);
                return $jumlah_stok_permintaan;
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
                return $this->get_count_spa_spb_po($data->id, $data->produk->id);
            })
            ->addColumn('aksi', function ($data) {
                return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '" id="detmodal">
                <div><i class="fas fa-search"></i></div>
            </a>';
            })
            ->rawColumns(['gbj', 'aksi', 'penjualan', 'nama_produk'])
            ->make(true);
    }

    public function master_stok_detail_show($id)
    {
        $data = GudangBarangJadi::find($id);
        $jumlah = $this->get_count_ekatalog($data->id, $data->produk->id, 'sepakat') + $this->get_count_ekatalog($data->id, $data->produk->id, 'negosiasi') + $this->get_count_spa_spb_po($data->id, $data->produk->id);
        return view('spa.ppic.master_stok.detail', ['id' => $id, 'data' => $data, 'jumlah' => $jumlah]);
    }

    public function get_detail_master_stok($id)
    {
        $data = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereIn('log_id', ['7', '9'])->get();

        $prd = Produk::whereHas('GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->first();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('tgl_order', function ($data) {
                if (isset($data->Ekatalog)) {
                    return Carbon::createFromFormat('Y-m-d', $data->Ekatalog->tgl_buat)->format('d-m-Y');
                } else {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
                }
            })
            ->addColumn('tgl_delivery', function ($data) {
                if (isset($data->Ekatalog)) {
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
            ->addColumn('jumlah', function ($data) use ($prd) {
                $id = $data->id;
                $res = DetailPesanan::where('pesanan_id', $id)->get();
                $jumlah = 0;
                foreach ($res as $a) {
                    foreach ($a->PenjualanProduk->Produk as $b) {
                        if ($b->id == $prd->id) {
                            $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                        }
                    }
                }
                return $jumlah;
            })
            ->rawColumns(['tgl_delivery'])
            ->make(true);
    }

    public function get_master_pengiriman_data()
    {
        $data = GudangBarangJadi::has('DetailPesananProduk.NoseriDetailPesanan')->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '9', '10']);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (!empty($data->nama)) {
                    return $data->Produk->nama . " - <b>" . $data->nama . "</b>";
                } else {
                    return $data->Produk->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                $jumlah = $this->get_count_pesanan_produk($data->id, $data->produk->id);
                return $jumlah;
            })
            ->addColumn('jumlah_pengiriman', function ($data) {
                // $jumlah = 0;
                // foreach ($data->DetailPesananProduk as $o) {
                //     $jumlah = $jumlah + $o->DetailPesanan->Pesanan->getJumlahCek();
                // }
                // return $jumlah;
                return $this->get_count_selesai_pengiriman_produk($data->id);
            })

            ->addColumn('belum_pengiriman', function ($data) {
                $jumlahpesanan = $this->get_count_pesanan_produk($data->id, $data->produk_id) - $this->get_count_selesai_pengiriman_produk($data->id);
                return $jumlahpesanan;
            })
            ->addColumn('aksi', function ($data) {
                return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '" id="detmodal">
                <div><i class="fas fa-search"></i></div>
            </a>';
            })
            ->rawColumns(['nama_produk', 'aksi'])
            ->make(true);
    }

    public function master_pengiriman_detail_show($id)
    {
        $data = GudangBarangJadi::find($id);
        $jumlah = $this->get_count_pesanan_produk($data->id, $data->produk->id);
        $jumlahselesai = $this->get_count_selesai_pengiriman_produk($data->id);
        $jumlahproses = $this->get_count_pesanan_produk($data->id, $data->produk->id) - $this->get_count_selesai_pengiriman_produk($data->id);
        return view('spa.ppic.master_pengiriman.detail', ['id' => $id, 'data' => $data, 'jumlah' => $jumlah, 'jumlahselesai' => $jumlahselesai, 'jumlahproses' => $jumlahproses]);
    }

    public function get_detail_master_pengiriman($id)
    {
        $data = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotIn('log_id', ['7', '9', '10'])->get();

        $prd = Produk::whereHas('GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->first();


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('jumlah_pesanan', function ($data) use ($prd) {
                $ids = $data->id;
                $res = DetailPesanan::where('pesanan_id', $ids)->get();
                $jumlah = 0;
                foreach ($res as $a) {
                    foreach ($a->PenjualanProduk->Produk as $b) {
                        if ($b->id == $prd->id) {
                            $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                        }
                    }
                }
                return $jumlah;
            })
            ->addColumn('jumlah_selesai_kirim', function ($data) use ($id) {
                $ids = $data->id;
                $c = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
                    $q->where('gudang_barang_jadi_id', $id);
                })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($ids) {
                    $q->where('pesanan_id', $ids);
                })->count();
                return $c;
            })
            ->addColumn('jumlah_belum_kirim', function ($data) use ($prd, $id) {
                $ids = $data->id;
                $res = DetailPesanan::where('pesanan_id', $ids)->get();
                $jumlahpesanan = 0;
                foreach ($res as $a) {
                    foreach ($a->PenjualanProduk->Produk as $b) {
                        if ($b->id == $prd->id) {
                            $jumlahpesanan = $jumlahpesanan + ($a->jumlah * $b->pivot->jumlah);
                        }
                    }
                }

                $c = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
                    $q->where('gudang_barang_jadi_id', $id);
                })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($ids) {
                    $q->where('pesanan_id', $ids);
                })->count();

                return $jumlahpesanan - $c;
            })
            ->addColumn('tgl_delivery', function ($data) {
                if (isset($data->Ekatalog)) {
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
            ->rawColumns(['tgl_delivery'])
            ->make(true);
    }

    public function get_count_ekatalog($id, $produk_id, $status)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan.Ekatalog', function ($q) use ($status) {
            $q->where('status', '=', $status);
        })->whereHas('Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
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

    public function get_count_spa_spb_po($id, $produk_id)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->doesntHave('Pesanan.Ekatalog')->get();
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

    public function get_count_pesanan_produk($id, $produk_id)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '9', '10']);
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

    public function test_query()
    {
        $data = JadwalPerakitan::all();
        foreach ($data as $d) {
            if ($d->status == $this->change_status("penyusunan")) {
                $d->state = $this->change_state("perencanaan");
                $d->save();
            } else {
                $d->state = $this->change_state("persetujuan");
                $d->konfirmasi = 1;
                $d->save();
            }
        }
    }

    public function get_count_selesai_pengiriman_produk($id)
    {
        $res = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '9', '10']);
        })->count();
        // $jumlah = 0;
        // foreach ($res as $a) {
        //     $a->jumlah;
        //     foreach ($a->PenjualanProduk->Produk as $b) {
        //         if ($b->id == $produk_id) {
        //             $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
        //         }
        //     }
        // }
        return $res;
    }

    // public function get_count_spa_spb_po($id, $produk_id)
    // {
    //     $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
    //         $q->where('gudang_barang_jadi_id', $id);
    //     })->whereHas('Pesanan', function ($q) {
    //         $q->whereIn('log_id', ['7', '9']);
    //     })->doesntHave('Pesanan.Ekatalog')->get();
    //     $jumlah = 0;
    //     foreach ($res as $a) {
    //         $a->jumlah;
    //         foreach ($a->PenjualanProduk->Produk as $b) {
    //             if ($b->id == $produk_id) {
    //                 $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
    //             }
    //         }
    //     }
    //     return $jumlah;
    // }

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
