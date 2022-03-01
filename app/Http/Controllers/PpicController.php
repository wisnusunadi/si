<?php

namespace App\Http\Controllers;

// library
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Produk;
use App\Models\Pesanan;

// model
use App\Models\Ekatalog;
use App\Events\PpicNotif;
use App\Models\NoseriTGbj;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use Illuminate\Support\Carbon;
use App\Models\JadwalPerakitan;
use App\Models\GudangBarangJadi;
use App\Models\JadwalPerakitanLog;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPesananProduk;
use App\Models\GudangKarantinaDetail;
use App\Models\KomentarJadwalPerakitan;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriDetailLogistik;

// event
use App\Models\JadwalPerakitanRencana;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DetailLogistikPart;
use App\Models\DetailPesananPart;

class PpicController extends Controller
{
    // Properties
    /**
     * Change status from string to number
     *
     * This function used as converter status, so status can be uploaded
     * to database
     *
     * @param string $status status string
     *
     * @return int status number
     */
    public function change_status($status)
    {
        if ($status == 'penyusunan') return 6;
        else if ($status == 'pelaksanaan') return 7;
        else if ($status == 'selesai') return 8;
        return $status;
    }

    /**
     * Change state from string to number
     *
     * This function used as converter state, so state can be uploaded
     * to database
     *
     * @param string $state status string
     *
     * @return int state number
     */
    public function change_state($state)
    {
        if ($state == 'perencanaan') return 17;
        else if ($state == 'persetujuan') return 18;
        else if ($state == 'perubahan') return 19;
        return $state;
    }

    /**
     * Get data perakitan from database
     *
     * @param string $status status string
     * @return array collection of data
     */
    public function get_data_perakitan($status = "all")
    {
        $this->update_perakitan_status();
        $status = $this->change_status($status);
        if ($status == $this->change_status('penyusunan')) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        } else if ($status == $this->change_status("pelaksanaan")) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orwhereNotIn('status', [6])->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        } else {
            $data = JadwalPerakitan::with('Produk.produk')->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        }

        foreach ($data as $item) {
            $noseri_count = count($item->noseri);
            $item->noseri_count = $noseri_count;
        }

        return $data;
    }

    /**
     * Change data get from get_data_perakitan function to datatables format
     *
     * @return array datatables formatted data
     */
    public function get_datatables_data_perakitan()
    {
        $data = JadwalPerakitan::where('status', '!=', $this->change_status('penyusunan'))->orderBy('tanggal_mulai', 'desc')->get();
        return datatables($data)
            ->addIndexColumn()
            ->addColumn('periode', function ($d)
            {
                return $d->tanggal_mulai == true ? Carbon::parse($d->tanggal_mulai)->isoFormat('MMMM') : '-';
            })
            ->addColumn('no_bppb', function ($d)
            {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
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
                return $data->tanggal_mulai == true ? Carbon::parse($data->tanggal_mulai)->isoFormat('D MMM YYYY') : '-';
            })
            ->addColumn('tanggal_selesai', function ($d) {
                $m = strtotime($d->tanggal_selesai);
                $a = strtotime(Carbon::now());
                $s = $a - $m;
                $x = floor($s / (60 * 60 * 24));

                if (isset($d->tanggal_selesai)) {
                    if ($x >= -10 && $x < -5) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="tag is-warning">Kurang ' . abs($x) . ' Hari</span>';
                    } elseif ($x >= -5 && $x <= -2) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="tag is-warning">Kurang ' . abs($x) . ' Hari</span>';
                    } elseif ($x > -2 && $x <= 0) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="tag is-danger">Kurang ' . $x . ' Hari</span>';
                    } elseif ($x > 0) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="tag is-danger">Lebih ' . $x . ' Hari</span>';
                    } elseif ($x < -10) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="tag is-warning">Kurang ' . abs($x) . ' Hari</span>';
                    } else {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span> ' . $x;
                    }
                    // return date('d-m-Y', strtotime($d->tanggal_selesai)).' '.$x;
                } else {
                    return '-';
                }
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
                    "</small><br>";
            })
            ->addColumn('status', function ($data) {
                return $data->status;
            })
            ->addColumn('aksi', function ($data)
            {
                return $data->id;
            })
            ->rawColumns(['nama', 'progres', 'tanggal_selesai', 'aksi'])
            ->make(true);
    }

    /**
     * Get data perakitan from previous planning from database
     *
     * @return array collections of data
     */
    public function get_data_perakitan_rencana()
    {
        $data = JadwalPerakitanRencana::with('JadwalPerakitan.Produk.produk')->orderBy('tanggal_mulai', 'asc')->get();
        return $data;
    }

    /**
     * Get data from GBJ
     *
     * @return array collections of data
     */
    public function get_data_barang_jadi(Request $request)
    {
        $data = GudangBarangJadi::with('produk.KelompokProduk', 'produk.product', 'satuan');
        if (isset($request->id)) {
            $data->where('id', $request->id);
        }
        $data = $data->get();
        return $data;
    }

    function get_data_pesanan_produk($id, $value)
    {
        // $data = Pesanan::orHas('DetailPesanan')->orHas('DetailPesananPart')->where('id', $id)->get();
        // return $data;
        if ($value == "ekatalog") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $detail_pesanan_part  = DetailPesananPart::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $data = $detail_pesanan->merge($detail_pesanan_part);
            $detail_id = array();
            $did = [];
            foreach ($data as $d) {
                $detail_id[] = $d->id;
                $did[] = $d->pesanan_id;
            }

            $g = collect(DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get());
            $g_part = collect(DetailPesananPart::whereIn('pesanan_id', $did)->get());
            $g_data = $g->merge($g_part);
        } else if ($value == "spa") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $detail_pesanan_part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $data = $detail_pesanan->merge($detail_pesanan_part);

            $detail_id = array();
            $did = [];
            foreach ($data as $d) {
                $detail_id[] = $d->id;
                $did[] = $d->pesanan_id;

            }


            $g = collect(DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get());
            $g_part = collect(DetailPesananPart::whereIn('pesanan_id', $did)->get());
            $g_data = $g->merge($g_part);
        } else if ($value == "spb") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $detail_pesanan_part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $data = $detail_pesanan->merge($detail_pesanan_part);

            $detail_id = array();
            $did = [];
            foreach ($data as $d) {
                $detail_id[] = $d->id;
                $did[] = $d->pesanan_id;

            }

            $g = collect(DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get());
            $g_part = collect(DetailPesananPart::whereIn('pesanan_id', $did)->get());
            $g_data = $g->merge($g_part);
        }

        return datatables()->of($g_data)
            ->addIndexColumn()
            ->addColumn('paket', function($d) {
                if(empty($d->GudangBarangJadi)) {
                   return '-';
                } else {
                    return $d->detailpesanan->penjualanproduk->nama;
                }
            })
            ->addColumn('produk', function ($data) {
                if(empty($data->GudangBarangJadi)) {
                    return $data->Sparepart->nama;
                } else {
                    if (empty($data->gudangbarangjadi->nama)) {
                        return $data->gudangbarangjadi->produk->nama;
                    } else {
                        return $data->gudangbarangjadi->produk->nama . '-' . $data->gudangbarangjadi->nama;
                    }
                }

            })
            ->addColumn('jumlah', function ($data) {
                if(empty($data->GudangBarangJadi)) {
                    return $data->jumlah;
                } else {
                    $s = DetailPesanan::whereHas('DetailPesananProduk', function($q) use($data) {
                        $q->where('id', $data->id);
                    })->get();
                    $x = 0;
                    foreach ($s as $i) {
                        foreach ($i->PenjualanProduk->Produk as $j) {
                            if ($j->id == $data->gudangbarangjadi->produk_id) {
                                $x = $i->jumlah * $j->pivot->jumlah;
                            }
                        }
                    }
                    return $x . ' ' .   $data->gudangbarangjadi->satuan->nama;
                }

            })
            ->addColumn('jumlah_kirim', function($d) {

                if(empty($d->GudangBarangJadi)) {
                    // return 'a';
                    $s = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($d) {
                        $q->where('pesanan_id', $d->pesanan->id);
                    })->get();
                    $jumlah = 0;
                    foreach ($s as $i) {
                        // return $i->DetailPesananPart->jumlah;
                        $jumlah = $jumlah + $i->DetailPesananPart->jumlah;
                    }
                    return $jumlah;
                } else {
                    $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($d) {
                        $q->where('pesanan_id', $d->detailpesanan->pesanan->id);
                    })->count();
                    return $jumlah;
                }

            })
            ->make(true);
    }

    /**
     * Get data product from sales order
     *
     * @return array datatables formatted data
     */
    public function get_data_so()
    {
        $getid = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['10']);
        })->get();
        $arrayid = array();

        foreach ($getid as $i) {
            $jumlahpesan = $i->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $i->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $i->getJumlahPermintaanPesanan("ekatalog", "batal") + $i->getJumlahPermintaanPesanan("ekatalog_po", "") + $i->getJumlahPermintaanPesanan("spa", "") + $i->getJumlahPermintaanPesanan("spb", "");
            $jumlahtf = $i->getJumlahTransferPesanan("ekatalog") + $i->getJumlahTransferPesanan("ekatalog", "negosiasi") + $i->getJumlahTransferPesanan("spa") + $i->getJumlahTransferPesanan("spb");
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
            $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("ekatalog_po", "") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
            $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa") + $data->getJumlahTransferPesanan("spb");
            $jumlah_stok_permintaan = $jumlahdiminta - $jumlahtf;
            $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
            if ($jumlah >= 0) {
                return "<div>" . $jumlah . "</div>";
            } else {
                return '<div class="has-text-danger">' . $jumlah . "</div>";
            }
        })
        ->addColumn('total', function ($data) {
            $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("ekatalog_po", "") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
            $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa") + $data->getJumlahTransferPesanan("spb");
            $jumlah = $jumlahdiminta - $jumlahtf;
            return $jumlah;
        })
        ->addColumn('sepakat', function ($data) {
            // $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") - $data->getJumlahTransferPesanan("ekatalog");
            $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat");
            return $jumlah;
        })
        ->addColumn('nego', function ($data) {
            // $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") - $data->getJumlahTransferPesanan("ekatalog", "negosiasi");
            $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi");
            return $jumlah;
        })
        ->addColumn('batal', function ($data) {
            return $data->getJumlahPermintaanPesanan("ekatalog", "batal");
        })
        ->addColumn('po', function ($data) {
            $jumlah = ($data->getJumlahPermintaanPesanan("ekatalog_po", "") - $data->getJumlahTransferPesanan("ekatalog")) + ($data->getJumlahPermintaanPesanan("spa", "") - $data->getJumlahTransferPesanan("spa")) + ($data->getJumlahPermintaanPesanan("spb", "") - $data->getJumlahTransferPesanan("spb"));
            return $jumlah;
        })
        ->rawColumns(['gbj', 'aksi', 'penjualan', 'nama_produk'])
        ->make(true);
    }

    public function get_data_so2()
    {
        $getid = GudangBarangJadi::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '10']);
        })->get();
        $arrayid = array();

        // foreach ($getid as $i) {
        //     $jumlahpesan = $i->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $i->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $i->getJumlahPermintaanPesanan("spa", "");
        //     $jumlahtf = $i->getJumlahTransferPesanan("ekatalog") + $i->getJumlahTransferPesanan("ekatalog", "negosiasi") + $i->getJumlahTransferPesanan("spa");
        //     if ($jumlahtf < $jumlahpesan) {
        //         $arrayid[] = $i->id;
        //     }
        // }

        $data = GudangBarangJadi::whereIn('id', $arrayid)->get();

        return DataTables::of($getid)
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
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa") + $data->getJumlahTransferPesanan("spb");
                $jumlah = $jumlahdiminta - $jumlahtf;
                return $jumlah;
            })
            ->addColumn('penjualan', function ($data) {
                $jumlah_gbj = $data->stok;
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa") + $data->getJumlahTransferPesanan("spb");
                $jumlah_stok_permintaan = $jumlahdiminta - $jumlahtf;
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                return $jumlah;
            })
            ->addColumn('sepakat', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") - $data->getJumlahTransferPesanan("ekatalog");
            })
            ->addColumn('nego', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") - $data->getJumlahTransferPesanan("ekatalog", "negosiasi");
            })
            ->addColumn('batal', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "batal");
            })
            ->addColumn('po', function ($data) {
                return $data->getJumlahPermintaanPesanan("spa", "") - $data->getJumlahTransferPesanan("spa");
            })
            ->addColumn('jumlah_kirim', function($data) {
                return $data->getJumlahKirimPesanan();
            })
            ->rawColumns(['gbj', 'aksi', 'penjualan', 'nama_produk'])
            ->make(true);
    }

    /**
     * Get detail sales order from spesific product
     *
     * @param int $id product id from gdg_barang_jadi table
     * @return array collections of data
     */
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
                return $data->so;
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
                return $jumlah;
            })
            ->rawColumns(['tgl_delivery'])
            ->make(true);
    }

    /**
     * Get data sparepart from GK
     *
     * @return array collections of data
     */
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

    /**
     * Get data unit from GK
     *
     * @return array collections of data
     */
    public function get_data_unit_gk(Request $request)
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
        ->whereNotNull('t_gk_detail.gbj_id')
        ->where('is_draft', 0)
        ->where('is_keluar', 0)
        ->groupBy('t_gk_detail.gbj_id')
        ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
        ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
        ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->units->produk->nama . ' ' . $data->units->nama;
            })
            ->addColumn('kode_produk', function ($data) {
                return $data->units->produk->product->kode . '' . $data->units->produk->kode;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jml . ' ' . $data->units->satuan->nama;
            })
            ->make(true);
    }

    /**
     * Get komentar jadwal perakitan based on status request and current date
     *
     * @return array collections of data
     */
    public function get_komentar_jadwal_perakitan(Request $request)
    {
        $data = KomentarJadwalPerakitan::where('status', $this->change_status($request->status))
            ->where('tanggal_hasil', '>=', date('Y-m-d'))
            ->orderBy('tanggal_permintaan', 'desc')
            ->get();
        return $data;
    }

    /**
     * Function to count number of request and process of schedule change
     * from PPIC
     *
     * @return array array data consist of 2 member which is number of request
     * and number of precess
     */
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

    /**
     * add data peratkitan to database
     *
     * @return array collections of data perakitan after new data added
     */
    public function create_data_perakitan(Request $request)
    {
        $status = $this->change_status($request->status);
        $state = $this->change_state($request->state);

        $color = ["#007bff", "#6c757d", "#28a745", "#dc3545", "#ffc107", "#17a2b8"];
        $selected_color = $color[array_rand($color)];

        $data = [
            'no_bppb' => $request->no_bppb,
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

    /**
     * add data to komentar_jadwal_perakitan table
     *
     * @return void
     */
    public function create_komentar_jadwal_perakitan(Request $request)
    {
        $state = $this->change_state($request->state);
        $status = $this->change_status($request->status);
        if (!isset($request->tanggal_permintaan)) {
            $data = KomentarJadwalPerakitan::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->first();
            $data->delete();
            return;
        }
        KomentarJadwalPerakitan::create([
            'tanggal_permintaan' => $request->tanggal_permintaan,
            'tanggal_hasil' => $request->tanggal_hasil,
            'state' => $state,
            'status' => $status,
            'hasil' => $request->hasil,
            'komentar' => $request->komentar,
        ]);
    }

    /**
     * update data from komentar_jadwal_perakitan table
     *
     * @return void
     */
    public function update_komentar_jadwal_perakitan(Request $request)
    {
        $data = KomentarJadwalPerakitan::orderBy('tanggal_permintaan', 'desc')->where("status", $this->change_status($request->status))->first();
        $data->tanggal_hasil = $request->tanggal_hasil;
        $data->hasil = $request->hasil;
        $data->komentar = $request->komentar;
        $data->save();
    }

    /**
     * update data from jadwal_perakitan table
     *
     * @param int $id id data jadwal_perakitan
     * @return array collections of data jadwal_perakitan after update
     */
    public function update_data_perakitan(Request $request, $id)
    {
        $data = JadwalPerakitan::find($id);

        $object = new JadwalPerakitanLog();
        $object->jadwal_perakitan_id = $data->id;
        $object->no_bppb = $data->no_bppb;
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

        if (isset($request->no_bppb)) {
            $data->no_bppb = $request->no_bppb;
            $object->no_bppb = $request->no_bppb;
        } else {
            $object->no_bppb = $data->no_bppb;
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

    /**
     * update many datas from jadwal_perakitan table based on status
     *
     * @param string $status status string
     * @return array collections of data jadwal_perakitan after update
     */
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

    /**
     * delete data from jadwal_perakitan table
     *
     * @param int $id id data of jadwal_perakitan
     * @return void
     */
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

    /**
     * trigger event for notification
     *
     * @return void
     */
    public function send_notification(Request $request)
    {
        event(new PpicNotif($request->user, $request->status, $request->state));
    }

    // helper function
    /**
     * update status of data from jadwal_perkaitan based on current month,
     * this function called inside get_data_perakitan()
     *
     * @return void
     */
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
            $q->whereNotIn('log_id', ['10']);
        })->get();
        $arrayid = array();

        foreach ($getid as $i) {
            $jumlahpesan = $i->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $i->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $i->getJumlahPermintaanPesanan("ekatalog", "batal") + $i->getJumlahPermintaanPesanan("ekatalog_po", "") + $i->getJumlahPermintaanPesanan("spa", "") + $i->getJumlahPermintaanPesanan("spb", "");
            $jumlahtf = $i->getJumlahTransferPesanan("ekatalog") + $i->getJumlahTransferPesanan("ekatalog", "negosiasi") + $i->getJumlahTransferPesanan("spa") + $i->getJumlahTransferPesanan("spb");
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
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("ekatalog_po", "") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa") + $data->getJumlahTransferPesanan("spb");
                $jumlah_stok_permintaan = $jumlahdiminta - $jumlahtf;
                $jumlah = $jumlah_gbj - $jumlah_stok_permintaan;
                if ($jumlah >= 0) {
                    return "<div>" . $jumlah . "</div>";
                } else {
                    return '<div style="color:red;">' . $jumlah . '</div>';
                }
            })
            ->addColumn('total', function ($data) {
                $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("ekatalog_po", "") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa") + $data->getJumlahTransferPesanan("spb");
                $jumlah = $jumlahdiminta - $jumlahtf;
                return $jumlah;
            })
            ->addColumn('sepakat', function ($data) {
                // $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") - $data->getJumlahTransferPesanan("ekatalog");
                $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat");
                return $jumlah;
            })
            ->addColumn('nego', function ($data) {
                // $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") - $data->getJumlahTransferPesanan("ekatalog", "negosiasi");
                $jumlah = $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi");
                return $jumlah;
            })
            ->addColumn('batal', function ($data) {
                return $data->getJumlahPermintaanPesanan("ekatalog", "batal");
            })
            ->addColumn('po', function ($data) {
                $jumlah = ($data->getJumlahPermintaanPesanan("ekatalog_po", "") - $data->getJumlahTransferPesanan("ekatalog")) + ($data->getJumlahPermintaanPesanan("spa", "") - $data->getJumlahTransferPesanan("spa")) + ($data->getJumlahPermintaanPesanan("spb", "") - $data->getJumlahTransferPesanan("spb"));
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
        $jumlahdiminta = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi")  + $data->getJumlahPermintaanPesanan("ekatalog", "batal") + $data->getJumlahPermintaanPesanan("ekatalog_po", "") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
        $jumlahtf = $data->getJumlahTransferPesanan("ekatalog") + $data->getJumlahTransferPesanan("spa")  + $data->getJumlahTransferPesanan("spb");
        $jumlah = $jumlahdiminta - $jumlahtf;
        return view('spa.ppic.master_stok.detail', ['id' => $id, 'data' => $data, 'jumlah' => $jumlah]);
    }

    public function get_detail_master_stok($id)
    {
        $datas = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotIn('log_id', ['10'])->get();

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
                if (!empty($data->so)) {
                    return $data->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_order', function ($data) {
                if (isset($data->Ekatalog)) {
                    if (!empty($data->Ekatalog->tgl_buat)) {
                        return Carbon::createFromFormat('Y-m-d', $data->Ekatalog->tgl_buat)->format('d-m-Y');
                    } else {
                        return '-';
                    }
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
                        if (isset($data->ekatalog->provinsi)) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                        } else {
                            $param = '-';
                        }
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
            $jumlah = $i->getJumlahCekPesanan();
            // echo $i->Produk->nama . '-' . $i->nama . ' : ' . $jumlah . ' - ' . $i->getJumlahKirimPesanan() . '<br>';
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
                $jumlah = $data->getJumlahCekPesanan();
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
                $jumlah = $data->getJumlahCekPesanan();
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
        $jumlah = $data->getJumlahCekPesanan();
        $jumlahselesai = $data->getJumlahKirimPesanan();
        $jumlahproses = $jumlah - $jumlahselesai;
        return view('spa.ppic.master_pengiriman.detail', ['id' => $id, 'data' => $data, 'jumlah' => $jumlah, 'jumlahselesai' => $jumlahselesai, 'jumlahproses' => $jumlahproses]);
    }

    public function get_detail_master_pengiriman($id)
    {
        $datas = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->whereNotIn('log_id', ['7', '10'])->get();
        $arrayid = array();
        foreach ($datas as $i) {
            if ($this->getJumlahCekPesanan($id, $i->id) > $this->getJumlahKirimPesanan($id, $i->id)) {
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
                $jumlah = $this->getJumlahCekPesanan($id, $data->id);
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

                $jumlahpesan = $this->getJumlahCekPesanan($id, $data->id);
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
        event(new PpicNotif('test user', 'test status', 'test state'));
        return "success";
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
        $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($gdg_id) {
            $q->where('gudang_barang_jadi_id', $gdg_id);
        })->where('pesanan_id', $po_id)->get();

        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                if ($j->id == $produk_id) {
                    $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
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
            $q->where('id', $po_id)->whereNotIn('log_id', ['10']);
        })->count();
        return $jumlah;
    }

    public function getJumlahCekPesanan($produk_id, $po_id)
    {
        $jumlah = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($produk_id) {
            $q->where('gudang_barang_jadi_id', $produk_id);
        })->doesntHave('NoseriDetailLogistik')->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($po_id) {
            $q->where('id', $po_id)->whereNotIn('log_id', ['10']);
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
    public function get_datatables_data_perakitan_detail($id)
    {
        $data = JadwalPerakitan::where('id', $id)->pluck('keterangan','keterangan_transfer');
        return $data;
    }
}
