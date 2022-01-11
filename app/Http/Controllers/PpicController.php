<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
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
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\Produk;

//temp
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;




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

        foreach ($data as $item) {
            $noseri_count = count($item->noseri);
            $item->noseri_count = $noseri_count;
        }

        return $data;
    }

    public function get_datatables_data_perakitan()
    {
        $data = JadwalPerakitan::where('status', '!=', $this->change_status('penyusunan'))->orderBy('tanggal_mulai', 'desc')->get();
        return datatables($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($data) {
                if ($data->Produk->nama) {
                    return $data->Produk->produk->nama . " - <b>" . $data->Produk->nama . "</b>";
                } else {
                    return $data->Produk->produk->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('tanggal_mulai', function ($data) {
                return $data->tanggal_mulai;
            })
            ->addColumn('tanggal_selesai', function ($data) {
                return $data->tanggal_selesai;
            })
            ->addColumn('progres', function ($data) {
                $max_value = $data->jumlah;
                $progres = count($data->noseri);
                $percentage = $progres * 100 / $max_value;
                $color = $data->status == $this->change_status('pelaksanaan') ? 'is-warning' : 'is-success';
                return
                    "<progress class='progress " . $color . "' " .
                    "style='margin-bottom: 0;'" .
                    "value='" . $progres . "' " .
                    "max='" . $max_value . "' >" .
                    $percentage . "%" .
                    "</progress>" .
                    "<small>" .
                    $progres . " dari " . $max_value .
                    "</small>";
            })
            ->addColumn('status', function ($data) {
                return $data->status;
            })
            ->rawColumns(['nama', 'progres'])
            ->make(true);
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
        $getid = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '10']);
        })->get();
        $arrayid = array();

        foreach ($getid as $i) {
            $jumlahpesan = $i->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $i->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $i->getJumlahPermintaanPesanan("spa", "");
            $jumlahtf = $i->getJumlahTransferPesanan("ekatalog", "sepakat") + $i->getJumlahTransferPesanan("ekatalog", "negosiasi") + $i->getJumlahTransferPesanan("spa", "");
            if ($jumlahtf < $jumlahpesan) {
                $arrayid[] = $i->id;
            }
        }

        $data = GudangBarangJadi::whereIn('id', $arrayid)->get();

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
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
                $jumlah = $jumlahdiminta - $jumlahtf;
                return $jumlah;
            })
            ->addColumn('penjualan', function ($data) {
                $jumlah_gbj = $data->stok;
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
                $jumlah_stok_permintaan = $jumlahdiminta - $jumlahtf;
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                return $jumlah;
            })
            ->addColumn('sepakat', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") - $data->getJumlahTransferPesanan("ekatalog", "sepakat");
            })
            ->addColumn('nego', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") - $data->getJumlahTransferPesanan("ekatalog", "negosiasi");
            })
            ->addColumn('batal', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "batal");
            })
            ->addColumn('po', function ($data) {
                return $data->getJumlahPermintaanPesanan("spa", "") - $data->getJumlahTransferPesanan("spa", "");
            })
            ->rawColumns(['gbj', 'aksi', 'penjualan', 'nama_produk'])
            ->make(true);
    }

    public function get_data_so_detail($id)
    {
        $datas = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotIn('log_id', ['7', '10'])->get();

        $prd = Produk::whereHas('GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->first();

        $arrayid = array();
        foreach ($datas as $i) {
            if ($this->getJumlahPermintaanPesanan($prd->id, $id, $i->id) > $this->getJumlahTransferPesanan($id, $i->id)) {
                $arrayid[] = $i->id;
            }
        }

        $data = Pesanan::whereIn('id', $arrayid)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so ? $data->so : "-";
            })
            ->addColumn('po', function ($data) {
                return $data->no_po ? $data->no_po : "-";
            })
            ->addColumn('akn', function ($data) {
                if (isset($data->Ekatalog)) {
                    return $data->Ekatalog->no_paket;
                } else {
                    return "-";
                }
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
                    $tanggal_sekarang = Carbon::now()->format('Y-m-d');
                    $tanggal_sekarang = Carbon::parse($tanggal_sekarang);
                    $tanggal_pengiriman = Carbon::parse($data->ekatalog->tgl_kontrak);
                    $days = $tanggal_sekarang->diffInDays($tanggal_pengiriman);

                    $param = "";
                    if ($tanggal_sekarang <= $tanggal_pengiriman) {
                        if ($days > 7) {
                            $param = ' <div>' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $days . ' Hari</small>';
                        } else if ($days > 0 && $days <= 7) {
                            $param = ' <div class="has-text-warning">' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $days . ' Hari</small>';
                        } else {
                            $param = '<div class="has-text-danger">' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                        }
                    } else {
                        $param =  '<div class="has-text-danger">' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $days . ' Hari</small>';
                    }

                    return $param;
                } else {
                    return '-';
                }
            })
            ->addColumn('customer', function ($data) {
                if (isset($data->Ekatalog)) {
                    return $data->ekatalog->instansi;
                } else if (isset($data->spa)) {
                    return $data->spa->customer->nama;
                } else if (isset($data->spb)) {
                    return $data->spb->customer->nama;
                }
            })
            ->addColumn('jenis', function ($data) {
                if (isset($data->Ekatalog)) {
                    return "Ekatalog";
                } else if (isset($data->spa)) {
                    return "SPA";
                } else if (isset($data->spb)) {
                    return "SPB";
                }
            })
            ->addColumn('status', function ($data) {
                return $data->log->nama;
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
                if ((time() - (60 * 60 * 12)) < strtotime($item->tanggal_hasil)) {
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

        $color = ["#007bff", "#6c757d", "#28a745", "#dc3545", "#ffc107", "#17a2b8"];
        $selected_color = $color[array_rand($color)];

        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $status,
            'state' => $state,
            'konfirmasi' => $request->konfirmasi,
            'warna' => $selected_color,
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

        if (isset($request->jumlah)) {
            $noseri_count = count($data->noseri);
            if ($noseri_count > $request->jumlah) $data->jumlah = $noseri_count;
            else $data->jumlah = $request->jumlah;
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
        $getid = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '10']);
        })->get();
        $arrayid = array();

        foreach ($getid as $i) {
            $jumlahpesan = $i->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $i->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $i->getJumlahPermintaanPesanan("spa", "");
            $jumlahtf = $i->getJumlahTransferPesanan("ekatalog", "sepakat") + $i->getJumlahTransferPesanan("ekatalog", "negosiasi") + $i->getJumlahTransferPesanan("spa", "");
            if ($jumlahtf < $jumlahpesan) {
                $arrayid[] = $i->id;
            }
        }

        $data = GudangBarangJadi::whereIn('id', $arrayid)->get();

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
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
                $jumlah_stok_permintaan = $jumlahdiminta - $jumlahtf;
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                if ($jumlah >= 0) {
                    return "<div>" . $jumlah . "</div>";
                } else {
                    return '<div style="color:red;">' . $jumlah . '</div>';
                }
            })
            ->addColumn('total', function ($data) {
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
                $jumlah = $jumlahdiminta - $jumlahtf;
                return $jumlah;
            })
            ->addColumn('sepakat', function ($data) {
                $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") - $data->getJumlahTransferPesanan("ekatalog", "sepakat");
                return $jumlah;
            })
            ->addColumn('nego', function ($data) {
                $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") - $data->getJumlahTransferPesanan("ekatalog", "negosiasi");
                return $jumlah;
            })
            ->addColumn('batal', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "batal");
            })
            ->addColumn('po', function ($data) {
                $jumlah = $data->getJumlahPermintaanPesanan("spa", "") - $data->getJumlahTransferPesanan("spa", "");
                return $jumlah;
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
        $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "");
        $jumlahtf = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
        $jumlah = $jumlahdiminta - $jumlahtf;
        return view('spa.ppic.master_stok.detail', ['id' => $id, 'data' => $data, 'jumlah' => $jumlah]);
    }

    public function get_detail_master_stok($id)
    {
        $datas = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotIn('log_id', ['7', '10'])->get();

        $prd = Produk::whereHas('GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->first();

        $arrayid = array();
        foreach ($datas as $i) {
            if ($this->getJumlahPermintaanPesanan($prd->id, $id, $i->id) > $this->getJumlahTransferPesanan($id, $i->id)) {
                $arrayid[] = $i->id;
            }
        }

        $data = Pesanan::whereIn('id', $arrayid)->get();

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
                    $tgl_parameter = $data->ekatalog->tgl_kontrak;
                    $param = "";

                    if ($tgl_sekarang < $tgl_parameter) {
                        $to = Carbon::now();
                        $from = $data->ekatalog->tgl_kontrak;
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
            ->addColumn('jumlah', function ($data) use ($prd, $id) {
                $jumlah = $this->getJumlahPermintaanPesanan($prd->id, $id, $data->id) - $this->getJumlahTransferPesanan($id, $data->id);
                // $id = $data->id;
                // $res = DetailPesanan::where('pesanan_id', $id)->get();
                // $jumlah = 0;
                // foreach ($res as $a) {
                //     foreach ($a->PenjualanProduk->Produk as $b) {
                //         if ($b->id == $prd->id) {
                //             $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                //         }
                //     }
                // }
                return $jumlah;
            })
            ->rawColumns(['tgl_delivery'])
            ->make(true);
    }

    public function get_master_pengiriman_data()
    {
        $datass = GudangBarangJadi::has('DetailPesananProduk.NoseriDetailPesanan')->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '10']);
        })->get();

        $arrayid = array();

        foreach ($datass as $i) {
            $jumlah = $i->getJumlahTransferPesanan("ekatalog", "sepakat") + $i->getJumlahTransferPesanan("ekatalog", "negosiasi") + $i->getJumlahTransferPesanan("spa", "");
            if ($jumlah > $i->getJumlahKirimPesanan()) {
                $arrayid[] = $i->id;
            }
        }

        $data = GudangBarangJadi::whereIn('id', $arrayid)->get();
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
                $jumlah = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
                return $jumlah;
            })
            ->addColumn('jumlah_pengiriman', function ($data) {
                // $jumlah = 0;
                // foreach ($data->DetailPesananProduk as $o) {
                //     $jumlah = $jumlah + $o->DetailPesanan->Pesanan->getJumlahCek();
                // }
                // return $jumlah;
                return $data->getJumlahKirimPesanan();
            })

            ->addColumn('belum_pengiriman', function ($data) {
                $jumlah = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
                $jumlahselesai = $data->getJumlahKirimPesanan();
                $jumlahproses = $jumlah - $jumlahselesai;
                return $jumlahproses;
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
        $jumlah = $data->getJumlahTransferPesanan("ekatalog", "sepakat") + $data->getJumlahTransferPesanan("ekatalog", "negosiasi") + $data->getJumlahTransferPesanan("spa", "");
        $jumlahselesai = $data->getJumlahKirimPesanan();
        $jumlahproses = $jumlah - $jumlahselesai;
        return view('spa.ppic.master_pengiriman.detail', ['id' => $id, 'data' => $data, 'jumlah' => $jumlah, 'jumlahselesai' => $jumlahselesai, 'jumlahproses' => $jumlahproses]);
    }

    public function get_detail_master_pengiriman($id)
    {
        $datas = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotIn('log_id', ['7', '9', '10'])->get();
        $arrayid = array();
        foreach ($datas as $i) {
            if ($this->getJumlahKirimPesanan($id, $i->id) < $this->getJumlahTransferPesanan($id, $i->id)) {
                $arrayid[] = $i->id;
            }
        }

        $prd = Produk::whereHas('GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->first();

        $data = Pesanan::whereIn('id', $arrayid)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('jumlah_pesanan', function ($data) use ($id) {
                $jumlah = $this->getJumlahTransferPesanan($id, $data->id);
                // $res = DetailPesanan::where('pesanan_id', $ids)->get();
                // $jumlah = 0;
                // foreach ($res as $a) {
                //     foreach ($a->PenjualanProduk->Produk as $b) {
                //         if ($b->id == $prd->id) {
                //             $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                //         }
                //     }
                // }
                return $jumlah;
            })
            ->addColumn('jumlah_selesai_kirim', function ($data) use ($id) {
                // $ids = $data->id;
                // $c = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
                //     $q->where('gudang_barang_jadi_id', $id);
                // })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($ids) {
                //     $q->where('pesanan_id', $ids);
                // })->count();
                $jumlah = $this->getJumlahKirimPesanan($id, $data->id);
                return $jumlah;
            })
            ->addColumn('jumlah_belum_kirim', function ($data) use ($id) {
                // $ids = $data->id;
                // $res = DetailPesanan::where('pesanan_id', $ids)->get();
                // $jumlahpesanan = 0;
                // foreach ($res as $a) {
                //     foreach ($a->PenjualanProduk->Produk as $b) {
                //         if ($b->id == $prd->id) {
                //             $jumlahpesanan = $jumlahpesanan + ($a->jumlah * $b->pivot->jumlah);
                //         }
                //     }
                // }

                // $c = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
                //     $q->where('gudang_barang_jadi_id', $id);
                // })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($ids) {
                //     $q->where('pesanan_id', $ids);
                // })->count();

                $jumlahpesan = $this->getJumlahTransferPesanan($id, $data->id);
                $jumlahselesai = $this->getJumlahKirimPesanan($id, $data->id);
                $jumlah = $jumlahpesan - $jumlahselesai;

                return $jumlah;
            })
            ->addColumn('tgl_delivery', function ($data) {
                if (isset($data->Ekatalog)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $data->ekatalog->tgl_kontrak;
                    $param = "";

                    if ($tgl_sekarang < $tgl_parameter) {
                        $to = Carbon::now();
                        $from = $data->ekatalog->tgl_kontrak;
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
                        $from = $data->ekatalog->tgl_kontrak;
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

    public function get_detail_pengiriman_for_ppic($id)
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
                    $tanggal_sekarang = Carbon::now()->format('Y-m-d');
                    $tanggal_sekarang = Carbon::parse($tanggal_sekarang);
                    $tanggal_pengiriman = Carbon::parse($data->ekatalog->tgl_kontrak);
                    $days = $tanggal_sekarang->diffInDays($tanggal_pengiriman);

                    $param = "";
                    if ($tanggal_sekarang <= $tanggal_pengiriman) {
                        if ($days > 7) {
                            $param = ' <div>' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $days . ' Hari</small>';
                        } else if ($days > 0 && $days <= 7) {
                            $param = ' <div class="has-text-warning">' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $days . ' Hari</small>';
                        } else {
                            $param = '<div class="has-text-danger">' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                        }
                    } else {
                        $param =  '<div class="has-text-danger">' . Carbon::parse($tanggal_pengiriman)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $days . ' Hari</small>';
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
        // $data = TFProduksi::
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


    public function getJumlahPermintaanPesanan($produk_id, $gdg_id, $po_id)
    {
        $jumlah = 0;
        // $s = DetailPesananProduk::where('gudang_barang_jadi_id', $gdg_id)->whereHas('DetailPesanan.Pesanan', function ($q) use ($po_id) {
        //     $q->where('id', $po_id);
        // })->get();
        $s = Pesanan::whereHas('DetailPesanan.DetailPesananProduk', function ($q) use ($gdg_id) {
            $q->where('gudang_barang_jadi_id', $gdg_id);
        })->where('id', $po_id)->get();

        foreach ($s as $z) {
            foreach ($z->DetailPesanan as $i) {
                foreach ($i->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
                    }
                }
            }
        }
        return $jumlah;
    }

    public function getJumlahTransferPesanan($produk_id, $po_id)
    {
        $jumlah = 0;
        $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($produk_id) {
            $q->where('gdg_brg_jadi_id', $produk_id);
        })->whereHas('detail.header.pesanan', function ($q) use ($po_id) {
            $q->where('id', $po_id);
        })->count();
        return $jumlah;
    }

    public function getJumlahKirimPesanan($produk_id, $po_id)
    {
        $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($produk_id) {
            $q->where('gudang_barang_jadi_id', $produk_id);
        })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($po_id) {
            $q->where('id', $po_id)->whereNotIn('log_id', ['10']);
        })->count();
        return $jumlah;
    }
}
