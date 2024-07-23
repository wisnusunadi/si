<?php

namespace App\Http\Controllers;

use App\Exports\LaporanQcOutgoing;
use App\Exports\LaporanQc;
use App\Exports\NoseriQC;
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
use App\Models\PenjualanProduk;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatBatalPo;
use App\Models\RiwayatBatalPoPrd;
use App\Models\RiwayatTf;
use App\Models\SeriGanti;
use App\Models\Spa;
use App\Models\Sparepart;
use App\Models\Spb;
use App\Models\SystemLog;
use App\Models\TFProduksi;
use App\Models\UjiLab;
use App\Models\UjiLabDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Arabic;

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

    public function get_data_seri_detail_ekatalog_kalibrasi($jenis, $produk_id, $pesanan_id)
    {
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
        return view('page.qc.so.kalibrasi', ['jenis' => $jenis, 'pesanan_id' => $pesanan_id, 'produk_id' => $produk_id, 'data' => $data]);
    }

    public function get_data_seri_ekatalog($status = 'kosong', $id, $idpesanan)
    {
        if ($status == 'semua') {
            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 't_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->get();
        } elseif ($status == 'belum') {

            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->whereNull('noseri_detail_pesanan.id')
                ->get();
        } elseif ($status == 'sudah') {
            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->whereNotNull('noseri_detail_pesanan.id')
                ->get();
        } elseif ($status == 'semuaKalibrasi') {
            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->whereNotNull('uji_lab_detail.id')
                ->get();
        } elseif ($status == 'prosesKalibrasi') {
            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->where('uji_lab_detail.status', 'belum')
                ->whereNotNull('uji_lab_detail.id')
                ->get();
        } elseif ($status == 'tidakLolosKalibrasi') {
            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->where('uji_lab_detail.status', 'nok')
                ->whereNotNull('uji_lab_detail.id')
                ->get();
        } elseif ($status == 'lolosKalibrasi') {
            $data = NoseriBarangJadi::select('t_gbj_noseri.id', 'uji_lab_detail.status as status_lab', 'noseri_detail_pesanan.is_lab', 't_gbj_detail.detail_pesanan_produk_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri as seri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_detail_pesanan.is_ready')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'uji' => function ($q) use ($idpesanan) {
                        $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->where('t_gbj.pesanan_id', $idpesanan)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                            ->from('uji_lab_detail')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_kalibrasi' => function ($q) {
                        $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
                            ->from('detail_pesanan')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                            ->where('detail_pesanan.kalibrasi', 1)
                            ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id');
                    }
                ])
                ->havingRaw('is_batal = 0')
                ->where('t_gbj.pesanan_id', $idpesanan)
                ->where('noseri_barang_jadi.gdg_barang_jadi_id', $id)
                ->where('uji_lab_detail.status', 'ok')
                ->whereNotNull('uji_lab_detail.id')
                ->get();
        } else if ($status == 'kosong') {
            $data = array();
        }



        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                if ($data->uji > 0) {
                    if (!$data->kalibrasi && $data->is_ready == 1) {
                        if ($data->is_kalibrasi) {
                            if ($data->status == 'ok') {
                                return '<div class="form-check">
                                <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->gdg_barang_jadi_id . '" data-id="' . $data->noseri_id . '" data-kalibrasi="' . $data->noseri_id . '"/>
                                </div>';
                            } else {
                                return '';
                            }
                        }
                        return '<div class="form-check">
                        <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->gdg_barang_jadi_id . '" data-id="' . $data->noseri_id . '" data-kalibrasi="' . $data->noseri_id . '"/>
                        </div>';
                    } else {
                        return '';
                    }
                } else {
                    return '<div class="form-check">
                <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->gdg_barang_jadi_id . '" data-id="' . $data->noseri_id . '" />
                </div>';
                }
                return '-';
            })
            ->addColumn('seri', function ($data) {
                // return $data->is_kalibrasi;
                return $data->seri;
            })
            ->addColumn('tgl_uji', function ($data) {
                if ($data->tgl_uji != null) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('item', function ($d) {
                if ($d->isi == null) {
                    return  array();
                } else {
                    return json_decode($d->isi);
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->uji > 0) {
                    if ($data->kalibrasi > 0) {
                        if ($data->status_lab == 'ok') {
                            return '<i class="fas fa-check-circle ok text-success"></i>';
                        } else if ($data->status_lab == 'nok') {
                            return '<i class="fas fa-ban nok has-text-danger"></i>';
                        } else {
                            return '<i class="fas fa-wrench text-warning"></i>';
                        }
                        return '<i class="fas fa-wrench text-warning"></i>';
                    } else {
                        if ($data->status == 'ok') {
                            return '<i class="fas fa-check-circle ok text-success"></i>';
                        } else {
                            return '<i class="fas fa-times-circle nok has-text-danger"></i>';
                        }
                    }
                } else {
                    return '<i class="fas fa-question-circle warning has-text-warning"></i>';
                }
                //LAB
                // $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->get();
                // if (count($check) > 0) {
                //     foreach ($check as $c) {
                //         if ($c->status == 'ok') {
                //             if ($data->NoseriDetailPesanan->is_lab == 1) {
                //                 if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'ok') {
                //                     return '<i class="far fa-check-circle ok text-success"></i>';
                //                 } else if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'nok') {
                //                     return '<i class="far fa-times-circle nok has-text-danger"></i>';
                //                 } else {
                //                     return '<i class="fas fa-wrench text-warning"></i>';
                //                 }
                //             } else {
                //                 if ($c->status == 'ok') {
                //                     return '<i class="fas fa-check-circle ok text-success"></i>';
                //                 } else {
                //                     return '<i class="fas fa-times-circle nok has-text-danger"></i>';
                //                 }
                //             }
                //         } else {
                //             return '<i class="fas fa-times-circle nok has-text-danger"></i>';
                //         }
                //     }
                // } else {
                //     return '<i class="fas fa-question-circle warning has-text-warning"></i>';
                // }
                //END LAB





                // kalibrasi icon tidak lolos
                // <i class="fas fa-exclamation-triangle text-warning"></i>
                // kalibrasi icon lolos
                // <i class="fas fa-check-circle ok text-success"></i>

            })
            ->addColumn('status_seri', function ($data) {
                if ($data->uji > 0) {
                    if ($data->is_lab == 1) {
                        if ($data->status_lab == 'ok') {
                            return 'Lolos Kalibrasi';
                        } else if ($data->status_lab == 'nok') {
                            return 'Tidak Lolos Kalibrasi';
                        } else {
                            return 'Proses Kalibrasi';
                        }
                    } else {
                        if ($data->is_ready == 0) {
                            return 'Sudah Di Transfer';
                        } elseif ($data->is_ready == 1) {
                            return 'Belum Di Transfer';
                        }
                    }
                } else {
                    return '-';
                }
                // LAB
                // if ($data->NoseriDetailPesanan) {
                //     if ($data->NoseriDetailPesanan->is_lab == 1) {
                //         if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'ok') {
                //             return 'Lolos Kalibrasi';
                //         } else if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'nok') {
                //             return 'Tidak Lolos Kalibrasi';
                //         } else {
                //             return 'Proses Kalibrasi';
                //         }
                //     } else {
                //         if ($data->NoseriDetailPesanan->is_ready == 0) {
                //             return 'Sudah Di Transfer';
                //         } elseif ($data->NoseriDetailPesanan->is_ready == 1) {
                //             return 'Belum Di Transfer';
                //         }
                //     }
                // } else {
                //     return '-';
                // }
            })
            ->addColumn('detail_pesanan_produk_id', function ($data) {
                return $data->detail_pesanan_produk_id;
            })
            ->addColumn('button', function () {
                return '';
            })
            ->addColumn('is_kalibrasi', function ($data) {
                return $data->is_kalibrasi ? 1 : 0;
            })
            ->rawColumns(['checkbox', 'status', 'button'])
            ->make(true);
    }

    // public function get_data_seri_ekatalog($status = 'kosong', $id, $idpesanan)
    // {
    //     if ($status == 'semua') {
    //         $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])->orderBy('id');
    //     } elseif ($status == 'belum') {
    //         $data = NoseriTGbj::DoesntHave('NoseriDetailPesanan')->whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])->orderBy('id');
    //     } elseif ($status == 'sudah') {
    //         $data = NoseriTGbj::Has('NoseriDetailPesanan')->whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])->orderBy('id');
    //     } elseif ($status == 'semuaKalibrasi') {
    //         $data = NoseriTGbj::Has('NoseriDetailPesanan.UjilabDetail')->whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])->orderBy('id');
    //     } elseif ($status == 'prosesKalibrasi') {
    //         $data = NoseriTGbj::Has('NoseriDetailPesanan.UjilabDetail')->whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })
    //             ->whereHas('NoseriDetailPesanan.UjilabDetail', function ($q) use ($idpesanan) {
    //                 $q->where(['status' => 'belum']);
    //             })
    //             ->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])
    //             ->orderBy('id');
    //     } elseif ($status == 'tidakLolosKalibrasi') {
    //         $data = NoseriTGbj::Has('NoseriDetailPesanan.UjilabDetail')->whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })
    //             ->whereHas('NoseriDetailPesanan.UjilabDetail', function ($q) use ($idpesanan) {
    //                 $q->where(['status' => 'nok']);
    //             })
    //             ->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])
    //             ->orderBy('id');
    //     } elseif ($status == 'lolosKalibrasi') {
    //         $data = NoseriTGbj::Has('NoseriDetailPesanan.UjilabDetail')->whereHas('detail', function ($q) use ($id) {
    //             $q->where(['gdg_brg_jadi_id' => $id]);
    //         })->whereHas('detail.header', function ($q) use ($idpesanan) {
    //             $q->where(['pesanan_id' => $idpesanan]);
    //         })
    //             ->whereHas('NoseriDetailPesanan.UjilabDetail', function ($q) use ($idpesanan) {
    //                 $q->where(['status' => 'ok']);
    //             })
    //             ->with(['NoseriBarangJadi.Gudang.Produk', 'NoseriDetailPesanan'])
    //             ->orderBy('id');
    //     } else if ($status == 'kosong') {
    //         $data = array();
    //         dd($data);
    //     }



    //     return datatables()->of($data)
    //         ->addIndexColumn()
    //         ->addColumn('checkbox', function ($data) {
    //             if ($data->NoseriDetailPesanan) {
    //                 if ($data->NoseriDetailPesanan->is_ready == 1 && $data->NoseriBarangJadi->Gudang->Produk->kode_lab_id != NULL) {
    //                     if ($data->NoseriDetailPesanan->is_lab == 1) {
    //                         return '';
    //                     } else {
    //                         if ($data->NoseriDetailPesanan->status == 'ok') {
    //                             return '<div class="form-check">
    //                                 <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '"  data-kalibrasi="' . $data->noseri_id . '"/>
    //                             </div>';
    //                         } else {
    //                             return '';
    //                         }
    //                     }
    //                 }
    //                 return '';
    //                 // else{
    //                 // return '<div class="form-check">
    //                 // <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />
    //                 // </div>';
    //                 // }
    //             } else {
    //                 return '<div class="form-check">
    //             <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />
    //             </div>';
    //             }



    //             // $proses_qc = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->first();
    //             // $proses_lab = UjiLabDetail::where('noseri_id',$data->id)->first();
    //             // if (empty($proses_qc) && empty($proses_lab) ) {
    //             //     return '  <div class="form-check">
    //             //     <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />
    //             //     </div>';
    //             // } else if(!empty($proses_qc) &&  empty($proses_lab)) {
    //             //     if ($proses_qc->status == 'nok') {
    //             //         return '<div class="form-check">
    //             //         <input class="form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail->gdg_brg_jadi_id . '" data-id="' . $data->noseri_id . '" />
    //             //         </div>';
    //             //     } else {
    //             //         return '';
    //             //     }
    //             // }
    //             //  else {

    //             //         return '';

    //             // }
    //         })
    //         ->addColumn('seri', function ($data) {
    //             return $data->NoseriBarangJadi->noseri;
    //         })
    //         ->addColumn('tgl_uji', function ($data) {
    //             $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->first();
    //             if (isset($check)) {
    //                 return Carbon::createFromFormat('Y-m-d', $check->tgl_uji)->format('d-m-Y');
    //             } else {
    //                 return '-';
    //             }
    //         })
    //         ->addColumn('status', function ($data) {
    //             $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', $data->id)->get();
    //             if (count($check) > 0) {
    //                 foreach ($check as $c) {
    //                     if ($c->status == 'ok') {
    //                         if ($data->NoseriDetailPesanan->is_lab == 1) {
    //                             if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'ok') {
    //                                 return '<i class="far fa-check-circle ok text-success"></i>';
    //                             } else if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'nok') {
    //                                 return '<i class="far fa-times-circle nok has-text-danger"></i>';
    //                             } else {
    //                                 return '<i class="fas fa-wrench text-warning"></i>';
    //                             }
    //                         } else {
    //                             if ($c->status == 'ok') {
    //                                 return '<i class="fas fa-check-circle ok text-success"></i>';
    //                             } else {
    //                                 return '<i class="fas fa-times-circle nok has-text-danger"></i>';
    //                             }
    //                         }
    //                     } else {
    //                         return '<i class="fas fa-times-circle nok has-text-danger"></i>';
    //                     }
    //                 }
    //             } else {
    //                 return '<i class="fas fa-question-circle warning has-text-warning"></i>';
    //             }

    //             // kalibrasi icon tidak lolos
    //             // <i class="fas fa-exclamation-triangle text-warning"></i>
    //             // kalibrasi icon lolos
    //             // <i class="fas fa-check-circle ok text-success"></i>

    //         })
    //         ->addColumn('status_seri', function ($data) {
    //             if ($data->NoseriDetailPesanan) {
    //                 if ($data->NoseriDetailPesanan->is_lab == 1) {
    //                     if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'ok') {
    //                         return 'Lolos Kalibrasi';
    //                     } else if ($data->NoseriDetailPesanan->UjiLabDetail->status == 'nok') {
    //                         return 'Tidak Lolos Kalibrasi';
    //                     } else {
    //                         return 'Proses Kalibrasi';
    //                     }
    //                 } else {
    //                     if ($data->NoseriDetailPesanan->is_ready == 0) {
    //                         return 'Sudah Di Transfer';
    //                     } elseif ($data->NoseriDetailPesanan->is_ready == 1) {
    //                         return 'Belum Di Transfer';
    //                     }
    //                 }
    //             } else {
    //                 return '-';
    //             }
    //         })
    //         ->addColumn('detail_pesanan_produk_id', function ($data) {
    //             return $data->detail->detail_pesanan_produk_id;
    //         })
    //         ->addColumn('button', function () {
    //             return '';
    //         })
    //         ->rawColumns(['checkbox', 'status', 'button'])
    //         ->make(true);
    // }
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
    // public function get_data_detail_so($id)
    // {
    //     $dataprd = DetailPesananProduk::with(['GudangBarangJadi.Produk'])->whereHas('DetailPesanan', function ($q) use ($id) {
    //         $q->where('pesanan_id', $id);
    //     })->addSelect([
    //         'jumlah' => function ($q) {
    //             $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
    //                 ->from('t_gbj_noseri')
    //                 ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
    //                 ->whereColumn('t_gbj_detail.gdg_brg_jadi_id', 'detail_pesanan_produk.gudang_barang_jadi_id')
    //                 ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
    //         },
    //         'jumlah_ok' => function ($q) {
    //             $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
    //                 ->from('noseri_detail_pesanan')
    //                 ->leftJoin('t_gbj_noseri', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
    //                 ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
    //                 ->where('noseri_detail_pesanan.status', 'ok')
    //                 ->whereColumn('t_gbj_detail.gdg_brg_jadi_id', 'detail_pesanan_produk.gudang_barang_jadi_id')
    //                 ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
    //         },
    //         'jumlah_nok' => function ($q) {
    //             $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
    //                 ->from('noseri_detail_pesanan')
    //                 ->leftJoin('t_gbj_noseri', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
    //                 ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
    //                 ->where('noseri_detail_pesanan.status', 'nok')
    //                 ->whereColumn('t_gbj_detail.gdg_brg_jadi_id', 'detail_pesanan_produk.gudang_barang_jadi_id')
    //                 ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
    //         },
    //         'jumlah_lab' => function ($q) {
    //             $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
    //                 ->from('noseri_detail_pesanan')
    //                 ->leftJoin('t_gbj_noseri', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
    //                 ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
    //                 ->where('noseri_detail_pesanan.is_lab', 1)
    //                 ->whereColumn('t_gbj_detail.gdg_brg_jadi_id', 'detail_pesanan_produk.gudang_barang_jadi_id')
    //                 ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
    //         },
    //         'is_kalibrasi' => function ($q) {
    //             $q->selectRaw('coalesce(count(detail_pesanan.id), 0)')
    //                 ->from('detail_pesanan')
    //                 ->where('detail_pesanan.kalibrasi', 1)
    //                 ->whereColumn('detail_pesanan.id', 'detail_pesanan_produk.detail_pesanan_id');
    //         }
    //     ])
    //         ->get();
    //     $datapart = DetailPesananPart::where('pesanan_id', $id)->whereHas('Sparepart', function ($q) {
    //         $q->where('kode', 'NOT LIKE', '%JASA%');
    //     })
    //         ->addSelect([
    //             'is_kalibrasi' => function ($q) {
    //                 $q->selectRaw('"0" AS is_kalibrasi');
    //             },
    //             'nama' => function ($q) {
    //                 $q->selectRaw('m_sparepart.nama')
    //                     ->from('detail_pesanan_part as dpp')
    //                     ->leftJoin('m_sparepart', 'dpp.m_sparepart_id', '=', 'm_sparepart.id')
    //                     ->whereColumn('dpp.id', 'detail_pesanan_part.id')
    //                     ->limit(1);
    //             },
    //             'jumlah_ok' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
    //                     ->from('outgoing_pesanan_part')
    //                     ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
    //             },
    //             'jumlah_nok' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
    //                     ->from('outgoing_pesanan_part')
    //                     ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
    //             },
    //             'jenis' => function ($q) {
    //                 $q->selectRaw("'sparepart' AS sparepart");
    //             },
    //         ])
    //         ->get();
    //     $produks = [];
    //     foreach ($dataprd as $item) {
    //         $gudang_barang_jadi_id = $item['gudang_barang_jadi_id'];

    //         if (!isset($produks[$gudang_barang_jadi_id])) {
    //             $produks[$gudang_barang_jadi_id] = [
    //                 "nama" => $item->GudangBarangJadi->Produk->nama,
    //                 "jenis" => 'produk',
    //                 "gudang_barang_jadi_id" => $gudang_barang_jadi_id,
    //                 "jumlah_ok" => 0,
    //                 "jumlah_nok" => 0,
    //                 "jumlah" => 0,
    //                 "jumlah_lab" => 0,
    //             ];
    //         }
    //         $produks[$gudang_barang_jadi_id]["jumlah_ok"] += $item['jumlah_ok'];
    //         $produks[$gudang_barang_jadi_id]["jumlah_nok"] += $item['jumlah_nok'];
    //         $produks[$gudang_barang_jadi_id]["jumlah_lab"] += $item['jumlah_lab'];
    //         $produks[$gudang_barang_jadi_id]["jumlah"] += $item['jumlah'];
    //         $produks[$gudang_barang_jadi_id]["is_kalibrasi"] = $item['is_kalibrasi'];
    //     }
    //     $produks = array_values($produks);
    //     $data = array_merge($produks, $datapart->toArray());

    //     return datatables()->of($data)
    //         ->addIndexColumn()
    //         ->addColumn('nama_produk', function ($data) {
    //             return $data['nama'];
    //         })
    //         ->addColumn('jumlah', function ($data) {
    //             return $data['jumlah'];
    //         })
    //         ->addColumn('jumlah_ok', function ($data) use ($id) {
    //             return $data['jumlah_ok'];
    //         })
    //         ->addColumn('jumlah_nok', function ($data) use ($id) {
    //             return $data['jumlah_nok'];
    //         })
    //         ->addColumn('button', function ($data) use ($id) {
    //             $kalibrasi = $data['is_kalibrasi'] == 1 ? "true" : "false";
    //             if ($data['jenis'] == 'produk') {
    //                 return '<a class="noserishow" data-count="1"  data-kalibrasi="' . $kalibrasi . '" data-id="' . $data['gudang_barang_jadi_id'] . '" data-jenis="produk"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
    //             } else {
    //                 if ($data['jumlah'] == $data['jumlah_ok']) {
    //                     return '<a class="noserishow" data-count="0" data-kalibrasi="false" data-id="' . $data['id'] . '" data-jenis="part"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
    //                 } else {
    //                     return '<a class="noserishow" data-count="1"  data-kalibrasi="false" data-id="' . $data['id'] . '" data-jenis="part"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
    //                 }
    //             }
    //         })
    //         ->rawColumns(['nama_produk', 'button', 'aksi'])
    //         ->make(true);

    //     //echo json_encode($data);
    // }
    public function get_data_detail_so($id)
    {
        // $x = explode(',', $id);
        $dataprd = DetailPesananProduk::addSelect([
            'jumlah_siap' => function ($q) {
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('detail_pesanan_produk as dp', 'dp.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
                    ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                    ->whereNull('riwayat_batal_po_seri.id')
                    ->whereColumn('dp.id', 'detail_pesanan_produk.id');
            },
        ])->whereHas('DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->havingRaw('jumlah_siap > 0')->get();

        // $dataprd = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($id) {
        //     $q->where('pesanan_id', $id);
        // })->groupby('gudang_barang_jadi_id')->get();
        $datapart = DetailPesananPart::where('pesanan_id', $id)->whereHas('Sparepart', function ($q) {
            $q->where('kode', 'NOT LIKE', '%JASA%');
        })->whereDoesntHave('RiwayatBatalPoPart')->get();
        $data = $dataprd->merge($datapart);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                $string = "";
                if (isset($data->gudang_barang_jadi_id)) {
                    if (empty($data->Gudangbarangjadi->nama)) {
                        $string .= $data->Gudangbarangjadi->Produk->nama;
                    } else {
                        $string .= $data->Gudangbarangjadi->Produk->nama . " - <b>" . $data->Gudangbarangjadi->nama . "</b>";
                    }
                } else {
                    $string .= $data->Sparepart->nama;
                }
                return $string;
            })
            ->addColumn('jumlah', function ($data) {
                //V1
                $jumlah = 0;
                if (isset($data->gudang_barang_jadi_id)) {
                    $id = $data->gudang_barang_jadi_id;
                    $pesanan_id = $data->DetailPesanan->pesanan_id;

                    $jumlah = NoseriTGbj::leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->whereHas('detail', function ($q) use ($id) {
                            $q->where('gdg_brg_jadi_id', $id);
                        })->whereHas('detail.header', function ($q) use ($pesanan_id) {
                            $q->where('pesanan_id', $pesanan_id);
                        })
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->count();
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
                    $kalibrasi = $data->DetailPesanan->kalibrasi == 1 && $data->GudangBarangJadi->Produk->kode_lab_id != null ? "true" : "false";
                } else {
                    $kalibrasi = "false";
                }

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
                            return '<a class="noserishow" data-kalibrasi="' . $kalibrasi . '"data-count="0" data-id="' . $data->gudang_barang_jadi_id . '" data-jenis="produk"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                        } else {
                            return '<a class="noserishow" data-kalibrasi="' . $kalibrasi . '" data-count="1" data-id="' . $data->gudang_barang_jadi_id . '" data-jenis="produk"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                        }
                    }
                } else {
                    if ($data->jumlah == $data->getJumlahCekPart('ok')) {
                        return '<a class="noserishow" data-kalibrasi="' . $kalibrasi . '"  data-count="0" data-id="' . $data->id . '" data-jenis="part"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                    } else {
                        return '<a class="noserishow" data-kalibrasi="' . $kalibrasi . '" data-count="1" data-id="' . $data->id . '" data-jenis="part"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                    }
                }
            })
            ->addColumn('aksi', function ($data) use ($id) {
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
                            return '<a data-toggle="modal" data-target="#noserimodal" class="noseri" data-count="0" data-id="' . $data->gudang_barang_jadi_id . '" data-pesan="' . $id . '" data-jenis="produk"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                        } else {
                            return '<a data-toggle="modal" data-target="#noserimodal" class="noseri" data-count="1" data-id="' . $data->gudang_barang_jadi_id . '" data-pesan="' . $id . '" data-jenis="produk"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                        }
                    }
                }
            })
            ->rawColumns(['nama_produk', 'button', 'aksi'])
            ->make(true);
        //echo json_encode($data);
    }
    public function get_data_riwayat_seri_ganti($gbj, $pesanan_id)
    {
        $dpp = DetailPesananProduk::leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->where('gudang_barang_jadi_id', $gbj)
            ->where('detail_pesanan.pesanan_id', $pesanan_id)
            ->pluck('detail_pesanan_produk.id')
            ->toArray();

        $data =   SeriGanti::whereIN('detail_pesanan_produk_id', $dpp)->get();

        if ($data->isEmpty() || count($data) == 0) {
            $x = array();
        } else {
            foreach ($data as $d) {
                $o = json_decode($d->isi);
                $seri[] = array(
                    'state' => $d->state,
                    'item' => $o
                );
            }
            foreach ($seri as $a) {
                foreach ($a['item'] as $b) {
                    $x[] = array(
                        'noseri' => $b->noseri,
                        'tgl_kirim' => $b->tgl_kirim,
                        'state' => $b->state
                    );
                }
            }
        }

        return response()->json($x);
    }
    // public function get_data_riwayat_seri_ganti($id){
    // $data =   SeriGanti::where('detail_pesanan_produk_id',$id)->get();
    // foreach($data as $d)
    // {
    //     $o = json_decode($d->isi);
    //     $seri[] = $o;
    // }
    // foreach($seri as $a){
    //     foreach($a as $b){
    //         $x[] = $b;
    //     }
    // }
    // return response()->json($x);
    // }

    public function get_data_so_qc()
    {
        $data = Ekatalog::whereHas('Pesanan.TFProduksi', function ($q) {
            $q->whereNotNull('no_po');
        })->get();
        return datatables()->of($data)
            ->make(true);
    }
    public function tf_so($value)
    {
        if ($value == 'ok') {
            $data = Pesanan::whereNotIn('log_id', ['7', '10', '20'])->addSelect([
                'jumlah_prd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'jumlah_part' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->whereNull('riwayat_batal_po_part.id');
                },
                'cqcuji' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_kalibrasi', 0)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('uji_lab_detail.id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                // 'cqcuji' => function ($q) {
                //     $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                //         ->from('noseri_detail_pesanan')
                //         ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //         ->where('noseri_detail_pesanan.status', 'ok')
                //         ->where('noseri_detail_pesanan.is_ready', 1)
                //         ->where('noseri_detail_pesanan.is_lab', 0)
                //         ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                // },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        // ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_kalibrasi', 0)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                // 'belum_tf' => function ($q) {
                //     $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                //         ->from('noseri_detail_pesanan')
                //         ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //         ->where('noseri_detail_pesanan.is_ready', 1)
                //         ->where('noseri_detail_pesanan.is_lab', 0)
                //         ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                // },
                'sudah_tf' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 1)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->whereNull('riwayat_batal_po_part.id');
                },
                'clabprd' => function ($q) {
                    $q->selectRaw('coalesce(count(uji_lab_detail.id), 0)')
                        ->from('uji_lab_detail')
                        ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('uji_lab_detail.is_ready', 0)
                        ->where('uji_lab_detail.status', 'ok')
                        ->whereColumn('uji_lab.pesanan_id', 'pesanan.id');
                }
            ])
                ->with(['Ekatalog.Customer.provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                // ->havingRaw('(cqcprd > 0) OR (cqcpart > 0)')
                ->havingRaw('((clabprd > 0 OR cqcuji > 0) AND cqcprd > 0) OR (cqcpart > 0) ')
                // ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($value == 'nok') {
            $data = Pesanan::whereNotIn('log_id', ['7', '10', '20'])->addSelect([
                // 'tgl_kontrak' => function ($q) {
                //     $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                //         ->from('ekatalog')
                //         ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                //         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                //         ->limit(1);
                // },
                // 'ctfprd' => function ($q) {
                //     $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                //         ->from('t_gbj_noseri')
                //         ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                //         ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                //         ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                // },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        // ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcuji' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('uji_lab_detail.id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                // 'cqcuji' => function ($q) {
                //     $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                //         ->from('noseri_detail_pesanan')
                //         ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //         ->where('noseri_detail_pesanan.status', 'nok')
                //         ->where('noseri_detail_pesanan.is_ready', 1)
                //         ->where('noseri_detail_pesanan.is_lab', 0)
                //         ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                // },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_nok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 1)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->whereNull('riwayat_batal_po_part.id');
                },
                'clabprd' => function ($q) {
                    $q->selectRaw('coalesce(count(uji_lab_detail.id), 0)')
                        ->from('uji_lab_detail')
                        ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('uji_lab_detail.is_ready', 0)
                        ->where('uji_lab_detail.status', 'nok')
                        ->whereColumn('uji_lab.pesanan_id', 'pesanan.id');
                },
                'jumlah_prd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'jumlah_part' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->whereNull('riwayat_batal_po_part.id');
                },
                'sudah_tf' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                // 'ctfpart' => function ($q) {
                //     $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                //         ->from('detail_pesanan_part')
                //         ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                //         ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                //         ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                // },
                //  'clogprd' => function ($q) {
                //     $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                //         ->from('noseri_logistik')
                //         ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //         ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //         ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                //         ->limit(1);
                // },
                // 'clogpart' => function ($q) {
                //     $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                //         ->from('detail_logistik_part')
                //         ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                //         ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                //         ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                //         ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                //         ->limit(1);
                // }
            ])
                ->with(['Ekatalog.Customer.provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('((clabprd > 0 OR cqcuji > 0) AND cqcprd > 0)')
                //Skip Part
                //->havingRaw('((clabprd > 0 OR cqcuji > 0) AND cqcprd > 0) OR (cqcpart > 0) ')
                //    ->havingRaw('(cqcprd > 0 AND clabprd > 0) OR (cqcpart > 0)')
                // ->orderBy('tgl_kontrak', 'asc')
                ->get();
        }
        if ($data->isEmpty()) {
            $setData = array();
        } else {
            foreach ($data as $d) {
                $setData[] = array(
                    'id' => $d->id,
                    'so' => $d->so,
                    'no_po' => $d->no_po,
                    'pengujian' => $d->cqcuji + $d->cqcpart,
                    'kalibrasi' => $d->clabprd,
                    // 'status' => $d->jumlah_part,
                    'status' => intval($d->sudah_tf / ($d->jumlah_prd + $d->jumlah_part) * 100),
                    'customer' => ($d->Ekatalog) ? $d->Ekatalog->satuan : (($d->Spa) ? $d->Spa->Customer->nama : $d->Spb->Customer->nama)
                );
            }
        }
        return response()->json($setData);
    }
    public function tf_store(Request $request, $status)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        //dd($obj);
        DB::beginTransaction();
        try {
            if ($status == 'ok') {
                foreach ($obj->produk as $r) {
                    if ($r->jenis == 'produk') {
                        for ($j = 0; $j < count($r->noseri); $j++) {
                            NoseriDetailPesanan::where('id', $r->noseri[$j]->id)
                                ->update([
                                    'is_ready' => 0,
                                ]);
                        }
                        DB::commit();
                    } else if ($r->jenis == 'sparepart') {
                        $id_part = OutgoingPesananPart::where('detail_pesanan_part_id', $r->id)
                            ->where('is_ready', 1)
                            ->whereraw('jumlah_ok != 0')
                            ->pluck('id')
                            ->toArray();
                        OutgoingPesananPart::whereIN('id',  $id_part)
                            ->update([
                                'is_ready' => 0,
                            ]);
                        DB::commit();
                    }
                }
                RiwayatTf::create([
                    'dari' => 23,
                    'ke' => 15,
                    'jenis' => 'transfer',
                    'isi' => json_encode($request->all())
                ]);
            } else if ($status == 'nok') {
                foreach ($obj->produk as $r) {
                    if ($r->jenis == 'produk') {
                        for ($j = 0; $j < count($r->noseri); $j++) {
                            $id = $r->noseri[$j]->id;
                            NoseriDetailPesanan::where('id', $r->noseri[$j]->id)
                                ->update([
                                    'is_ready' => 0,
                                ]);
                            NoseriTGbj::where('id', $r->noseri[$j]->tfbj_id)->update([
                                'status_id' => 10,
                            ]);
                        }
                        DB::commit();
                    } else if ($r->jenis == 'sparepart') {
                        $id_part = OutgoingPesananPart::where('detail_pesanan_part_id', $r->id)
                            ->where('is_ready', 1)
                            ->whereraw('jumlah_nok != 0')
                            ->pluck('id')
                            ->toArray();

                        OutgoingPesananPart::whereIN('id', $id_part)
                            ->update([
                                'is_ready' => 0,
                            ]);
                        DB::commit();
                    }
                }
                RiwayatTf::create([
                    'dari' => 23,
                    'ke' => 13,
                    'jenis' => 'transfer',
                    'isi' => json_encode($request->all())
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Transaksi Update Gagal',
            ], 500);
        }
    }

    public function tf_so_detail($status, $id)
    {
        if ($status == 'nok') {
            $datapart = DetailPesananPart::where('pesanan_id', $id)->whereHas('Sparepart', function ($q) {
                $q->where('kode', 'NOT LIKE', '%JASA%');
            })
                ->addSelect([
                    'nama' => function ($q) {
                        $q->selectRaw('m_sparepart.nama')
                            ->from('detail_pesanan_part as dpp')
                            ->leftJoin('m_sparepart', 'dpp.m_sparepart_id', '=', 'm_sparepart.id')
                            ->whereColumn('dpp.id', 'detail_pesanan_part.id')

                            ->limit(1);
                    },
                    'jumlah_nok' => function ($q) {
                        $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_nok), 0)')
                            ->from('outgoing_pesanan_part')
                            ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                            ->where('is_ready', 1)
                            ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
                            ->whereNull('riwayat_batal_po_part.id');
                    },
                    'jenis' => function ($q) {
                        $q->selectRaw("'sparepart' AS sparepart");
                    },
                ])
                ->havingRaw('jumlah_nok > 0')
                ->get();

            $dataprd = DetailPesananProduk::with(['GudangBarangJadi.Produk'])->whereHas('DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->addSelect([
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
                'clabprd' => function ($q) {
                    $q->selectRaw('coalesce(count(uji_lab_detail.id), 0)')
                        ->from('uji_lab_detail')
                        ->where('uji_lab_detail.is_ready', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->where('uji_lab_detail.is_ready', 0)
                        ->where('uji_lab_detail.status', 'nok')
                        ->whereColumn('uji_lab_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
                'clabprds' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->Join('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->where('uji_lab_detail.status', 'nok')
                        ->where('uji_lab_detail.is_ready', 0)
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
            ])
                ->havingRaw('clabprds > 0 OR cqcprd > 0')
                ->get();
        } else if ($status == 'ok') {
            $datapart = DetailPesananPart::where('pesanan_id', $id)->whereHas('Sparepart', function ($q) {
                $q->where('kode', 'NOT LIKE', '%JASA%');
            })
                ->addSelect([
                    'nama' => function ($q) {
                        $q->selectRaw('m_sparepart.nama')
                            ->from('detail_pesanan_part as dpp')
                            ->leftJoin('m_sparepart', 'dpp.m_sparepart_id', '=', 'm_sparepart.id')
                            ->whereColumn('dpp.id', 'detail_pesanan_part.id')
                            ->limit(1);
                    },
                    'jumlah_nok' => function ($q) {
                        $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                            ->from('outgoing_pesanan_part')
                            ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                            ->where('is_ready', 1)
                            ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
                            ->whereNull('riwayat_batal_po_part.id');
                    },
                    'jenis' => function ($q) {
                        $q->selectRaw("'sparepart' AS sparepart");
                    },
                ])
                ->havingRaw('jumlah_nok > 0')
                ->get();

            $dataprd = DetailPesananProduk::with(['GudangBarangJadi.Produk'])->whereHas('DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->addSelect([
                'clabprds' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->Join('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->where('uji_lab_detail.status', 'ok')
                        ->where('uji_lab_detail.is_ready', 0)
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
                // 'cqcprd' => function ($q) {
                //     $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                //         ->from('noseri_detail_pesanan')
                //         ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                //         ->where('noseri_detail_pesanan.status', 'ok')
                //         ->where('noseri_detail_pesanan.is_ready', 1)
                //         ->where('noseri_detail_pesanan.is_kalibrasi', 0)
                //         ->where('noseri_detail_pesanan.is_lab', 0)
                //         ->whereNull('uji_lab_detail.id')
                //         ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                // },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 1)
                        ->where('noseri_detail_pesanan.is_kalibrasi', 0)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereNull('uji_lab_detail.id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
            ])
                ->havingRaw('clabprds > 0 OR cqcprd > 0 ')
                ->get();
        } else {
            return response()->json('Data Kosong');
        }
        $data = array();
        if ($dataprd->isnotEmpty()) {
            foreach ($dataprd as $d) {
                $gudang_barang_jadi_id = $d->gudang_barang_jadi_id;
                if (!isset($data[$gudang_barang_jadi_id])) {
                    $data[$gudang_barang_jadi_id] = [
                        'id' => $d->id,
                        "nama" => $d->GudangBarangJadi->Produk->nama,
                        "jenis" => 'produk',
                        "jumlah" => 0,
                    ];
                }
                $data[$gudang_barang_jadi_id]["jumlah"] +=  $d->clabprds + $d->cqcprd;
            }

            $data = array_values($data);
        }
        if ($datapart->isnotEmpty()) {
            foreach ($datapart as $d) {
                $data[] = array(
                    'id' => $d->id,
                    'nama' => $d->nama,
                    'jumlah' => $d->jumlah_nok,
                    'jenis' => 'sparepart'
                );
            }
        }

        return response()->json($data);


        // if ($dataprd->isEmpty()) {
        //     $setPrd = array();
        // } else {
        //     $produks = [];
        //     foreach ($dataprd as $d) {
        //         $gudang_barang_jadi_id = $d->gudang_barang_jadi_id;
        //         if (!isset($produks[$gudang_barang_jadi_id])) {
        //             $produks[$gudang_barang_jadi_id] = [
        //                 'id' => $d->id,
        //                 "nama" => $d->GudangBarangJadi->Produk->nama,
        //                 "jenis" => 'produk',
        //                 "jumlah" => 0,
        //             ];
        //         }
        //         $produks[$gudang_barang_jadi_id]["jumlah"] +=  $d->clabprds + $d->cqcprd;
        //         // $setPrd[] = array(
        //         //     'id' => $d->id,
        //         //     'nama' => $d->GudangBarangJadi->Produk->nama . $d->GudangBarangJadi->nama,
        //         //      'jumlah' => $d->clabprds + $d->cqcprd,
        //         //     'jenis' => 'produk'
        //         // );


        //     }
        //     $setPrd = array_values($produks);
        // }
        // if ($datapart->isEmpty()) {
        //     $setPart = array();
        // } else {
        //     foreach ($datapart as $d) {
        //         $setPart[] = array(
        //             'id' => $d->id,
        //             'nama' => $d->nama,
        //             'jumlah' => $d->jumlah_nok,
        //             'jenis' => 'sparepart'
        //         );
        //     }
        // }
        // if ($status == 'ok') {
        //     $data = array_merge($setPrd, $setPart);
        // }
        // $data = $setPrd;
        // return response()->json($data);
    }

    public function tf_so_detail_seri($status, $id)
    {
        if ($status == 'ok') {
            $dpp = DetailPesananProduk::find($id);

            $get_dpp = DetailPesananProduk::whereHas("DetailPesanan", function ($q) use ($dpp) {
                $q->where('pesanan_id', $dpp->DetailPesanan->pesanan_id);
            })->where('detail_pesanan_produk.gudang_barang_jadi_id', $dpp->gudang_barang_jadi_id)->pluck('detail_pesanan_produk.id')->toArray();

            $dataprd = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $get_dpp)
                ->where('noseri_detail_pesanan.status', 'ok')
                ->where('noseri_detail_pesanan.is_lab', 0)
                ->where('noseri_detail_pesanan.is_ready', 1)
                ->addSelect([
                    'clabprds' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id), 0)')
                            ->from('uji_lab_detail')
                            ->where('uji_lab_detail.status', 'ok')
                            ->where('uji_lab_detail.is_ready', 0)
                            ->whereColumn('uji_lab_detail.noseri_id', 'noseri_detail_pesanan.id');
                    },
                    'status' => function ($q) {
                        $q->selectRaw('
                    CASE
                        WHEN id IS NOT NULL THEN COUNT(uji_lab_detail.id)
                    ELSE
                    0
                    END
                    ')
                            ->from('uji_lab_detail')
                            ->whereColumn('uji_lab_detail.noseri_id', 'noseri_detail_pesanan.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 'noseri_detail_pesanan.t_tfbj_noseri_id');
                    }
                ])
                ->with('NoseriTGbj.NoseriBarangJadi')
                ->havingRaw('(status = 0 OR (status > 0 AND clabprds > 0) )AND is_batal = 0 ')
                ->get();
            $stat = 'lolos';
        } elseif ($status == 'nok') {

            $dpp = DetailPesananProduk::find($id);

            $get_dpp = DetailPesananProduk::whereHas("DetailPesanan", function ($q) use ($dpp) {
                $q->where('pesanan_id', $dpp->DetailPesanan->pesanan_id);
            })->where('detail_pesanan_produk.gudang_barang_jadi_id', $dpp->gudang_barang_jadi_id)->pluck('detail_pesanan_produk.id')->toArray();


            $dataprd = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $get_dpp)
                ->where('noseri_detail_pesanan.is_lab', 0)
                ->where('noseri_detail_pesanan.is_ready', 1)
                ->addSelect([
                    'cnok' => function ($q) {
                        $q->selectRaw('coalesce(count(ndp.id), 0)')
                            ->from('noseri_detail_pesanan as ndp')
                            ->where('ndp.status', 'nok')
                            ->whereColumn('ndp.id', 'noseri_detail_pesanan.id');
                    },
                    'clabprds' => function ($q) {
                        $q->selectRaw('coalesce(count(uji_lab_detail.id), 0)')
                            ->from('uji_lab_detail')
                            ->where('uji_lab_detail.status', 'nok')
                            ->where('uji_lab_detail.is_ready', 0)
                            ->whereColumn('uji_lab_detail.noseri_id', 'noseri_detail_pesanan.id');
                    },
                    'status' => function ($q) {
                        $q->selectRaw('
                    CASE
                        WHEN id IS NOT NULL THEN COUNT(uji_lab_detail.id)
                    ELSE
                    0
                    END
                    ')
                            ->from('uji_lab_detail')
                            ->whereColumn('uji_lab_detail.noseri_id', 'noseri_detail_pesanan.id');
                    },
                    'is_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id), 0)')
                            ->from('riwayat_batal_po_seri')
                            ->where('riwayat_batal_po_seri.posisi', 'qc')
                            ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 'noseri_detail_pesanan.t_tfbj_noseri_id');
                    }
                ])
                ->with('NoseriTGbj.NoseriBarangJadi')
                ->havingRaw('((status = 0 AND cnok = 1) OR (status > 0 AND clabprds > 0)) AND is_batal = 0 ')
                ->get();
            $stat = 'tidak_lolos';
        } else {
            return response()->json('Data Kosong');
        }


        if ($dataprd->isEmpty()) {
            $setPrd = array();
        } else {
            foreach ($dataprd as $d) {
                $setPrd[] = array(
                    'id' => $d->id,
                    'tfbj_id' => $d->t_tfbj_noseri_id,
                    'seri' => $d->NoseriTGbj->NoseriBarangJadi->noseri,
                    'jenis' => $d->status != 0 ? $stat . '_kalibrasi' : $stat . '_pengujian',
                );
            }
        }

        return response()->json($setPrd);
    }

    public function get_data_so($value)
    {

        function get_jenis($so)
        {
            $jenis = explode('/', $so);
            // return small text
            return strtolower($jenis[1]) == 'ekat' ? 'ekatalog' : strtolower($jenis[1]);
        }

        $data = "";
        $arrayid = array();
        $x = explode(',', $value);
        if ($value == 'semua') {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereNull('riwayat_batal_po_part.id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereNull('riwayat_batal_po_part.id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
            ])
                ->with(['Ekatalog.Customer.provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($x == ['ekatalog', 'spa']) {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi'])
                ->doesntHave('Spb')
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($x == ['ekatalog', 'spb']) {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->doesntHave('Spa')
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($x == ['spa', 'spb']) {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->doesntHave('ekatalog')
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($value == 'ekatalog') {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi'])
                ->doesntHave('Spa')
                ->doesntHave('Spb')
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($value == 'spa') {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Spa.Customer.Provinsi'])
                ->doesntHave('Ekatalog')
                ->doesntHave('Spb')
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($value == 'spb') {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Spb.Customer.Provinsi'])
                ->doesntHave('Ekatalog')
                ->doesntHave('Spa')
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else {
            $data = Pesanan::whereNotIn('log_id', ['10', '20'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqnok' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpartok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpartnok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        }

        function get_keterangan($data)
        {
            if (!empty($data->so)) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->ket;
                } else if ($name[1] == 'SPA') {
                    return $data->Spa->ket;
                } else if ($name[1] == 'SPB') {
                    return $data->Spb->ket;
                }
            }
        }

        function get_status($data)
        {
            $datas = "";
            $res = $data->ctfprd + $data->ctfpart;
            if ($res > 0) {
                $hitung = floor(((($data->cqcprd + $data->cqcpart) / ($data->ctfprd + $data->ctfpart)) * 100));
                if ($hitung > 0) {
                    $datas = $hitung;
                } else {
                    $datas = $hitung;
                }
            } else {
                $datas = $res;
            }
            return $datas;
        }

        function getCustomer($data)
        {
            if (!empty($data->so)) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->satuan;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            }
        }

        $data = $data->map(function ($item) {
            $item->jenis = get_jenis($item->so);
            $item->ket = get_keterangan($item);
            $item->jumlah_ok = $item->cqcok + $item->cqcpartok;
            $item->jumlah_nok = $item->cqnok + $item->cqcpartnok;
            $item->persentase = get_status($item);
            $item->customer = getCustomer($item);
            return $item;
        });

        return response()->json($data);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jumlah_ok', function ($data) {
                return $data->cqcok + $data->cqcpartok;
            })
            ->addColumn('jumlah_nok', function ($data) {
                return $data->cqnok + $data->cqcpartnok;
            })
            ->addColumn('nama_customer', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->satuan;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } else {
                        return $data->Spb->Customer->nama;
                    }
                }
            })
            ->addColumn('batas_uji', function ($data) {
                if ($data->tgl_kontrak != "") {
                    if ($data->log_id != "10") {
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                            if ($hari > 7) {
                                return '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return '<div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else {
                            return '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                        }
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('keterangan', function ($data) {
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->ket;
                    } else if ($name[1] == 'SPA') {
                        return $data->Spa->ket;
                    } else if ($name[1] == 'SPB') {
                        return $data->Spb->ket;
                    }
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $res = $data->ctfprd + $data->ctfpart;
                if ($res > 0) {
                    $hitung = floor(((($data->cqcprd + $data->cqcpart) / ($data->ctfprd + $data->ctfpart)) * 100));
                    if ($hitung > 0) {
                        $datas = '<div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                    } else {
                        $datas = '<div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                    }
                } else {
                    $datas = '<div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
                if ($data->Ekatalog) {
                    if ($data->Ekatalog->status == "batal") {
                        return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="EKAT" data-provinsi="">
                            <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                        </a>';
                    } else {
                        return $datas;
                    }
                } else if ($data->Spa) {
                    if ($data->Spa->log == "batal") {
                        return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPA" data-provinsi="">
                            <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                        </a>';
                    } else {
                        return $datas;
                    }
                } else if ($data->Spb) {
                    if ($data->Spb->log == "batal") {
                        return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPB" data-provinsi="">
                            <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                        </a>';
                    } else {
                        return $datas;
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $return = '';
                if (!empty($data->so)) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x = 'ekatalog';
                    } else if ($name[1] == 'SPA') {
                        $x = 'spa';
                    } else {
                        $x = 'spb';
                    }
                    $return .= '<a class="btn btn-outline-primary btn-sm" href="' . route('qc.so.detail', [$data->id, $x]) . '">
                                <i class="fas fa-eye"></i> Detail
                        </a>';
                    if ($data->no_po != NULL && $data->tgl_po != NULL) {
                        $return .= '    <a target="_blank" class="btn btn-outline-primary btn-sm" class href="' . route('penjualan.penjualan.cetak_surat_perintah', [$data->id]) . '">
                            <i class="fas fa-print"></i>
                            SPPB
                        </a>
                        ';
                    }
                    return $return;
                }
            })
            ->rawColumns(['button', 'status', 'batas_uji'])
            ->setRowClass(function ($data) {
                if ($data->Ekatalog) {
                    if ($data->Ekatalog->status == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                } else if ($data->Spa) {
                    if ($data->Spa->log == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                } else {
                    if ($data->Spb->log == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                }
            })
            ->make(true);
    }
    public function get_data_selesai_so($value)
    {
        $data = "";
        $x = explode(',', $value);
        if ($value == 'semua') {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereNull('riwayat_batal_po_part.id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->leftJoin('riwayat_batal_po_part', 'riwayat_batal_po_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereNull('riwayat_batal_po_part.id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        } else if ($x == ['ekatalog', 'spa']) {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->doesntHave('Spb')
                ->get();
        } else if ($x == ['ekatalog', 'spb']) {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->doesntHave('Spa')
                ->get();
        } else if ($x == ['spa', 'spb']) {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->doesntHave('Ekatalog')
                ->get();
        } else if ($value == 'ekatalog') {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->has('Ekatalog')
                ->get();
        } else if ($value == 'spa') {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->has('Spa')
                ->get();
        } else if ($value == 'spb') {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->has('Spb')
                ->get();
        } else {
            $data = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->havingRaw('(cqcprd >= ctfprd AND ctfprd > 0)  OR (cqcpart >= ctfpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->get();
        }

        function get_keterangan($data)
        {
            if ($data->Ekatalog) {
                return $data->Ekatalog->ket;
            } else if ($data->Spa) {
                return $data->Spa->ket;
            } else if ($data->Spb) {
                return $data->Spb->ket;
            }
        }

        function getJenisPesanan($data)
        {
            if ($data->Ekatalog) {
                return 'ekatalog';
            } elseif ($data->Spa) {
                return 'spa';
            } else {
                return 'spb';
            }
        }

        function getCustomer($data)
        {
            if ($data->Ekatalog) {
                return $data->Ekatalog->satuan;
            } elseif ($data->Spa) {
                return $data->Spa->Customer->nama;
            } else {
                return $data->Spb->Customer->nama;
            }
        }

        $data = $data->map(function ($item) {
            $item->customer = getCustomer($item);
            $item->keterangan = get_keterangan($item);
            $item->jenis = getJenisPesanan($item);
            return $item;
        });

        return response()->json($data);

        // $arrayid = array();
        // foreach ($data as $i) {
        //     if (count($i->DetailPesanan) > 0 && count($i->DetailPesananPart) <= 0) {
        //         if ($i->getJumlahSeri() <= $i->getJumlahCek()) {
        //             $arrayid[] = $i->id;
        //         }
        //     } else if (count($i->DetailPesanan) <= 0 && count($i->DetailPesananPart) > 0) {
        //         if ($i->getJumlahPesananPartNonJasa() <= $i->getJumlahCekPart("ok")) {
        //             $arrayid[] = $i->id;
        //         }
        //     } else {
        //         if (($i->getJumlahSeri() <= $i->getJumlahCek()) || $i->getJumlahPesananPartNonJasa() <= $i->getJumlahCekPart("ok")) {
        //             $arrayid[] = $i->id;
        //         }
        //     }
        // }
        // $s = Pesanan::whereIn('id', $arrayid)->get();
        // echo json_encode($data);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_customer', function ($data) {


                if ($data->Ekatalog) {
                    return $data->Ekatalog->satuan;
                } elseif ($data->Spa) {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('batas_uji', function ($data) {
                // if (!empty($data->so)) {
                //     $name = explode('/', $data->so);
                //     if ($name[1] == 'EKAT') {
                // $tgl_sekarang = Carbon::now()->format('Y-m-d');
                // $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');
                // return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>';

                // if ($tgl_sekarang < $tgl_parameter) {
                //     $to = Carbon::now();
                //     $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                //     $hari = $to->diffInDays($from);

                //     if ($hari > 7) {
                //         return ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas sisa ' . $hari . ' Hari</small>';
                //     } else if ($hari > 0 && $hari <= 7) {
                //         return ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                //     } else {
                //         return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><span class="badge bg-danger">Batas Kontrak Habis</span>';
                //     }
                // } else {
                //     $to = Carbon::now();
                //     $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                //     $hari = $to->diffInDays($from);
                //     return '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                // }
                //     } else {
                //         return '-';
                //     }
                // }
            })
            ->addColumn('keterangan', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->ket;
                } else if ($data->Spa) {
                    return $data->Spa->ket;
                } else if ($data->Spb) {
                    return $data->Spb->ket;
                }
            })
            ->addColumn('status', function ($data) {
                // if($data->log_id != 20){
                // if (count($data->DetailPesanan) > 0 && count($data->DetailPesananPart) <= 0) {
                //     if ($data->getJumlahCek() == 0) {
                //         return '<span class="badge red-text">Belum diuji</span>';
                //     } else {
                //         if ($data->getJumlahCek() >= $data->getJumlahPesanan()) {
                //             return  '<span class="badge green-text">Selesai</span>';
                //         } else {
                //             return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                //         }
                //     }
                // } else if (count($data->DetailPesanan) <= 0 && count($data->DetailPesananPart) > 0) {
                //     if ($data->getJumlahCekPart('ok') == 0) {
                //         return '<span class="badge red-text">Belum diuji</span>';
                //     } else {
                //         if ($data->getJumlahCekPart('ok') >= $data->getJumlahPesananPartNonJasa()) {
                //             return  '<span class="badge green-text">Selesai</span>';
                //         } else {
                //             return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                //         }
                //     }
                // } else if (count($data->DetailPesanan) > 0 && count($data->DetailPesananPart) > 0) {
                //     if ($data->getJumlahCek() == 0 && $data->getJumlahCekPart('ok') == 0) {
                //         return '<span class="badge red-text">Belum diuji</span>';
                //     } else {
                //         if (($data->getJumlahCek() >= $data->getJumlahPesanan()) && ($data->getJumlahCekPart('ok') >= $data->getJumlahPesananPartNonJasa())) {
                //             return  '<span class="badge green-text">Selesai</span>';
                //         } else {
                //             return  '<span class="badge yellow-text">Sedang Berlangsung</span>';
                //         }
                //     }
                // }
                // } else {
                //     $name = explode('/', $data->so);
                //         return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="'.$name[1].'" data-provinsi="">
                //                 <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                //                     <i class="fas fa-times"></i>
                //                     Batal
                //                 </button>
                //             </a>';
                // }
            })
            ->addColumn('button', function ($data) {
                //
                $return = '';
                if ($data->Ekatalog) {
                    $x =  'ekatalog';
                } else if ($data->Spa) {
                    $x =  'spa';
                } else {
                    $x =  'spb';
                }
                $return .= '<a class="btn btn-outline-primary btn-sm" href="' . route('qc.so.detail', [$data->id, $x]) . '">
                                <i class="fas fa-eye"></i> Detail
                        </a>';

                if ($data->no_po != NULL && $data->tgl_po != NULL) {
                    $return .= '    <a target="_blank" class="btn btn-outline-primary btn-sm" class href="' . route('penjualan.penjualan.cetak_surat_perintah', [$data->id]) . '">
                            <i class="fas fa-print"></i>
                            SPPB
                        </a>
                        ';
                }

                return $return;
            })
            ->rawColumns(['button', 'status', 'batas_uji'])
            ->make(true);
    }
    public function get_data_riwayat_pengujian(Request $request)
    {
        $prd = collect(DetailPesanan::addSelect([
            'tgl_mulai' => function ($q) {
                $q->selectRaw('MIN(noseri_detail_pesanan.tgl_uji)')
                    ->from('noseri_detail_pesanan')
                    ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                    ->limit(1);
            },
            'tgl_selesai' => function ($q) {
                $q->selectRaw('MAX(noseri_detail_pesanan.tgl_uji)')
                    ->from('noseri_detail_pesanan')
                    ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                    ->limit(1);
            },
            'jumlah_pengujian' => function ($q) {
                $q->selectRaw('count(noseri_detail_pesanan.id)')
                    ->from('noseri_detail_pesanan')
                    ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id');
            },
            'nama' => function ($q) {
                $q->selectRaw('penjualan_produk.nama')
                    ->from('penjualan_produk')
                    ->whereColumn('penjualan_produk.id', 'detail_pesanan.penjualan_produk_id')
                    ->limit(1);
            }
        ])->havingRaw('jumlah_pengujian > 0')->with(['PenjualanProduk.Produk', 'DetailPesananProduk', 'Pesanan.Ekatalog', 'Pesanan.Spa', 'Pesanan.Spb', 'DetailPesananProduk.GudangBarangJadi'])->whereYear('created_at', $request->years)->get());

        $part = collect(DetailPesananPart::addSelect([
            'tgl_mulai' => function ($q) {
                $q->selectRaw('MIN(outgoing_pesanan_part.tanggal_uji)')
                    ->from('outgoing_pesanan_part')
                    ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
                    ->limit(1);
            },
            'tgl_selesai' => function ($q) {
                $q->selectRaw('MAX(outgoing_pesanan_part.tanggal_uji)')
                    ->from('outgoing_pesanan_part')
                    ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
                    ->limit(1);
            },
            'jumlah_pengujian' => function ($q) {
                $q->selectRaw('SUM(outgoing_pesanan_part.jumlah_ok)')
                    ->from('outgoing_pesanan_part')
                    ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
            },
            'nama' => function ($q) {
                $q->selectRaw('m_sparepart.nama')
                    ->from('m_sparepart')
                    ->whereColumn('m_sparepart.id', 'detail_pesanan_part.m_sparepart_id')
                    ->limit(1);
            }
        ])->havingRaw('jumlah_pengujian > 0')->with(['Pesanan.Ekatalog', 'Pesanan.Spa', 'Pesanan.Spb'])->whereYear('created_at', $request->years)->get());

        function getCustomer($data)
        {
            if (isset($data->Pesanan->Ekatalog)) {
                return [
                    'jenis' => 'ekatalog',
                    'nama' => $data->Pesanan->Ekatalog->Customer->nama,
                    'satuan' => $data->Pesanan->Ekatalog->satuan,
                    'alamat' => $data->Pesanan->Ekatalog->alamat,
                    'provinsi' => $data->Pesanan->Ekatalog->Customer->Provinsi->nama,
                ];
            } elseif (isset($data->Pesanan->Spa)) {
                return [
                    'jenis' => 'spa',
                    'nama' => $data->Pesanan->Spa->Customer->nama,
                    'alamat' => $data->Pesanan->Spa->Customer->alamat,
                    'provinsi' => $data->Pesanan->Spa->Customer->Provinsi->nama,
                ];
            } else {
                return [
                    'jenis' => 'spb',
                    'nama' => $data->Pesanan->Spb->Customer->nama,
                    'alamat' => $data->Pesanan->Spb->Customer->alamat,
                    'provinsi' => $data->Pesanan->Spb->Customer->Provinsi->nama,
                ];
            }
        }

        $data =  $prd->merge($part);
        $data = $data->map(function ($item) {
            $item->customer = getCustomer($item);
            return $item;
        });
        return $data;

        // return datatables()->of($s)
        //     ->addIndexColumn()
        //     ->addColumn('so', function ($data) {
        //         return $data->Pesanan->so;
        //     })
        //     ->addColumn('nama_produk', function ($data) {
        //         return $data->nama;
        //     })
        //     ->addColumn('tgl_mulai', function ($data) {
        //         return Carbon::createFromFormat('Y-m-d', $data->tgl_mulai)->format('d-m-Y');
        //     })
        //     ->addColumn('tgl_selesai', function ($data) {
        //         return Carbon::createFromFormat('Y-m-d', $data->tgl_selesai)->format('d-m-Y');
        //     })
        //     ->addColumn('jumlah', function ($data) {
        //         return $data->jumlah_pengujian;
        //     })
        //     ->addColumn('button', function ($data) {
        //         if (isset($data->penjualan_produk_id)) {
        //             $produkcount = $data->PenjualanProduk->Produk->count();
        //             $produkid = "";
        //             if ($produkcount <= 1) {
        //                 $produkid = $data->DetailPesananProduk->first()->id;
        //             }
        //             return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data->penjualan_produk_id . '" data-id="' . $data->id . '" data-count="' . $produkcount . '" data-produk="' . $produkid . '" data-jenis="produk" id="detmodal">
        //                 <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
        //             </a>';
        //         } else {
        //             return '<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" data-attr="' . $data->m_sparepart_id . '" data-id="' . $data->id . '" data-count="1" data-produk="0" data-jenis="part" id="detmodal">
        //                 <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
        //             </a>';
        //         }
        //     })
        //     ->rawColumns(['button', 'nama_produk'])
        //     ->setRowClass(function ($data) {
        //         if ($data->Pesanan->Ekatalog) {
        //             if ($data->Pesanan->Ekatalog->status == 'batal') {
        //                 return 'text-danger font-weight-bold';
        //             }
        //         } else if ($data->Pesanan->Spa) {
        //             if ($data->Pesanan->Spa->log == 'batal') {
        //                 return 'text-danger font-weight-bold';
        //             }
        //         } else {
        //             if ($data->Pesanan->Spb->log == 'batal') {
        //                 return 'text-danger font-weight-bold';
        //             }
        //         }
        //     })
        //     ->make(true);
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

            $detail_pesanan = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $ds = Pesanan::where('id', $id)->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->first();


            $jumlah = 0;
            $z = array();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
                $z[] = $d->jumlah;
                // foreach ($d->penjualanproduk->produk as $l) {
                //     $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                // }
            }

            // if (count($ds->DetailPesanan) > 0 && count($ds->DetailPesananPart) <= 0) {
            //     if ($ds->getJumlahCek() == 0) {
            //         $status = '<span class="badge red-text">Belum diuji</span>';
            //     } else {
            //         if ($ds->getJumlahCek() >= $ds->getJumlahPesanan()) {
            //             $status =  '<span class="badge green-text">Selesai</span>';
            //         } else {
            //             $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
            //         }
            //     }
            // } else if (count($ds->DetailPesanan) <= 0 && count($ds->DetailPesananPart) > 0) {
            //     if ($ds->getJumlahCekPart('ok') == 0) {
            //         $status = '<span class="badge red-text">Belum diuji</span>';
            //     } else {
            //         if ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPartNonJasa()) {
            //             $status =  '<span class="badge green-text">Selesai</span>';
            //         } else {
            //             $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
            //         }
            //     }
            // } else if (count($ds->DetailPesanan) > 0 && count($ds->DetailPesananPart) > 0) {
            //     if ($ds->getJumlahCek() == 0 && $ds->getJumlahCekPart('ok') == 0) {
            //         $status = '<span class="badge red-text">Belum diuji</span>';
            //     } else {
            //         if (($ds->getJumlahCek() >= $ds->getJumlahPesanan()) && ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPartNonJasa())) {
            //             $status =  '<span class="badge green-text">Selesai</span>';
            //         } else {
            //             $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
            //         }
            //     }
            // }
            $status = "";
            $res = $ds->ctfprd + $ds->ctfpart;
            if ($res > 0) {
                $hitung = floor(((($ds->cqcprd + $ds->cqcpart) / ($ds->ctfprd + $ds->ctfpart)) * 100));
                if ($hitung > 0) {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                } else {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
            } else {
                $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
            }

            $param = "";
            if ($ds->tgl_kontrak != "") {
                $tgl_sekarang = Carbon::now()->format('Y-m-d');
                $tgl_parameter = $ds->tgl_kontrak;

                if ($tgl_sekarang <= $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $tgl_parameter;
                    $hari = $to->diffInDays($from);

                    if ($hari > 7) {
                        $param = ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $hari . ' Hari</small>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $param = ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                    } else {
                        $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                    }
                } else {
                    $to = Carbon::now();
                    $from = $tgl_parameter;
                    $hari = $to->diffInDays($from);
                    $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                }
            }
            return view('page.qc.so.detail_ekatalog', ['id' => $id, 'data' => $data, 'detail_id' => $detail_id, 'param' => $param, 'status' => $status]);
        } elseif ($value == 'spa') {
            $data = Spa::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $ds = Pesanan::where('id', $id)->addSelect([
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->first();
            $detail_pesanan = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $jumlah = 0;
            $z = array();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
                $z[] = $d->jumlah;
                // foreach ($d->penjualanproduk->produk as $l) {
                //     $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                // }
            }

            // if (isset($ds->DetailPesanan) && !isset($ds->DetailPesananPart)) {
            //     if ($ds->getJumlahCek() == 0) {
            //         $status = '<span class="badge red-text">Belum diuji</span>';
            //     } else {
            //         if ($ds->getJumlahCek() >= $ds->getJumlahPesanan()) {
            //             $status =  '<span class="badge green-text">Selesai</span>';
            //         } else {
            //             $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
            //         }
            //     }
            // } else if (!isset($ds->DetailPesanan) && isset($ds->DetailPesananPart)) {
            //     if ($ds->getJumlahCekPart('ok') == 0) {
            //         $status = '<span class="badge red-text">Belum diuji</span>';
            //     } else {
            //         if ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPartNonJasa()) {
            //             $status =  '<span class="badge green-text">Selesai</span>';
            //         } else {
            //             $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
            //         }
            //     }
            // } else if (isset($ds->DetailPesanan) > 0 && isset($ds->DetailPesananPart) > 0) {
            //     if ($ds->getJumlahCek() == 0 && $ds->getJumlahCekPart('ok') == 0) {
            //         $status = '<span class="badge red-text">Belum diuji</span>';
            //     } else {
            //         if (($ds->getJumlahCek() >= $ds->getJumlahPesanan()) && ($ds->getJumlahCekPart('ok') >= $ds->getJumlahPesananPartNonJasa())) {
            //             $status =  '<span class="badge green-text">Selesai</span>';
            //         } else {
            //             $status =  '<span class="badge yellow-text">Sedang Berlangsung</span>';
            //         }
            //     }
            // }
            $status = "";
            $res = $ds->ctfprd + $ds->ctfpart;
            if ($res > 0) {
                $hitung = floor(((($ds->cqcprd + $ds->cqcpart) / ($ds->ctfprd + $ds->ctfpart)) * 100));
                if ($hitung > 0) {

                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                } else {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
            } else {
                $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
            }
            //  $x=  $ds->cqcprd.'-'.$ds->cqcpart.'-'.$ds->ctfprd.'-'.$ds->ctfpart;

            return view('page.qc.so.detail_spa', ['id' => $id, 'data' => $data, 'detail_id' => $detail_id, 'status' => $status]);
        } else {
            $data = Spb::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $ds = Pesanan::where('id', $id)->addSelect([
                'ctfprd' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                        ->from('t_gbj_noseri')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->first();
            $detail_pesanan = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_pesanan = DetailPesanan::whereHas('Pesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $jumlah = 0;
            $z = array();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
                $z[] = $d->jumlah;
                // foreach ($d->penjualanproduk->produk as $l) {
                //     $jumlah = $jumlah + ($d->jumlah * $l->pivot->jumlah);
                // }
            }

            $status = "";
            $res = $ds->ctfprd + $ds->ctfpart;
            if ($res > 0) {
                $hitung = floor(((($ds->cqcprd + $ds->cqcpart) / ($ds->ctfprd + $ds->ctfpart)) * 100));
                if ($hitung > 0) {
                    $status = '<div class="progress progresscust">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                } else {
                    $status = '<div class="progress progresscust">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                }
            } else {
                $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
            }

            return view('page.qc.so.detail_spb', ['id' => $id, 'data' => $data, 'detail_id' => $detail_id, 'status' => $status]);
        }
    }

    public function cancel_so()
    {
        // $p = Pesanan::where('id', $id)->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->first();

        return view('page.qc.so.cancel');
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
    public function create_data_qc( /*$seri_id, $tfgbj_id, */$jenis, $pesanan_id, $produk_id, Request $request)
    {
        // dd($request->all());
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
                $data = NoseriTGbj::find($request->noseri_id[$i]['id']);
                $check = NoseriDetailPesanan::where('t_tfbj_noseri_id', '=', $request->noseri_id[$i]['id'])->first();
                if ($check == null) {
                    $c = NoseriDetailPesanan::create([
                        'detail_pesanan_produk_id' => $data->detail->detail_pesanan_produk_id,
                        't_tfbj_noseri_id' => $request->noseri_id[$i]['id'],
                        'status' => $request->cek,
                        'tgl_uji' => $request->tanggal_uji,
                        'is_ready' =>  1,
                        'is_kalibrasi' =>   $request->noseri_id[$i]['is_kalibrasi'] == 1 ? 1 : 0,
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
                $noseri[] =  $request->noseri_id[$i]['id'];
            }

            $datas = NoseriTGbj::find($request->noseri_id[0]['id']);
            $obj = [
                'pesanan_so' => Pesanan::find(DetailPesanan::find(DetailPesananProduk::find($datas->detail->detail_pesanan_produk_id)->detail_pesanan_id)->pesanan_id)->so,
                'pesanan_so' => Pesanan::find(DetailPesanan::find(DetailPesananProduk::find($datas->detail->detail_pesanan_produk_id)->detail_pesanan_id)->pesanan_id)->no_po,
                'paket' => PenjualanProduk::find(DetailPesanan::find(DetailPesananProduk::find($datas->detail->detail_pesanan_produk_id)->detail_pesanan_id)->penjualan_produk_id)->nama,
                'produk' => Produk::find(GudangBarangJadi::find(DetailPesananProduk::find($datas->detail->detail_pesanan_produk_id)->gudang_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(DetailPesananProduk::find($data->detail->detail_pesanan_produk_id)->gudang_barang_jadi_id)->nama,
                'jumlah' => count($request->noseri_id),
                'noseri' => NoseriBarangJadi::whereIN('id', NoseriTGbj::whereIN('id',   $noseri)->get()->pluck('noseri_id'))->get()->pluck('noseri'),
                'status' => $request->cek,
                'tgl_uji' => $request->tanggal_uji,
            ];
            SystemLog::create([
                'tipe' => 'QC',
                'subjek' => 'Pengujian Produk',
                'response' => json_encode($obj),
                'user_id' => $request->user_idd,
            ]);
        } else if ($jenis == "part") {
            $data = OutgoingPesananPart::create([
                'detail_pesanan_part_id' => $produk_id,
                'tanggal_uji' => $request->tanggal_uji,
                'jumlah_ok' => $request->jumlah_ok,
                'jumlah_nok' => $request->jumlah_nok,
                'is_ready' => 1,
            ]);

            if (!$data) {
                $bool = false;
                $bools = false;
            }
            $obj = [
                'dpp_id' => $produk_id,
                'part' => Sparepart::find(DetailPesananPart::find($produk_id)->m_sparepart_id)->nama,
                'kode_part' => Sparepart::find(DetailPesananPart::find($produk_id)->m_sparepart_id)->kode,
                'tanggal_uji' => $request->tanggal_uji,
                'jumlah_ok' => $request->jumlah_ok,
                'jumlah_nok' => $request->jumlah_nok
            ];

            SystemLog::create([
                'tipe' => 'QC',
                'subjek' => 'Pengujian Sparepart',
                'response' => json_encode($obj),
                'user_id' => $request->user_id,
            ]);
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
                // $uk = "Jumlah Pesan Part ".$po->getJumlahPesananPartNonJasa()." Jumlah Cek Part ".$po->getJumlahCekPart("ok");
                if ($po->getJumlahPesananPartNonJasa() <= $po->getJumlahCekPart("ok")) {
                    if ($po->getJumlahKirimPart() == 0) {
                        $pou = Pesanan::find($pesanan_id);
                        $pou->log_id = '11';
                        $u = $pou->save();
                        if (!$u) {
                            $bools = false;
                        }
                    } else {
                        if ($po->getJumlahKirimPart() >= $po->getJumlahPesananPartNonJasa()) {
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
                } else if ($po->getJumlahPesananPartNonJasa() > $po->getJumlahCekPart("ok")) {
                    $pou = Pesanan::find($pesanan_id);
                    $pou->log_id = '8';
                    $u = $pou->save();
                    if (!$u) {
                        $bools = false;
                    }
                }
            } else if (count($po->DetailPesanan) > 0 && count($po->DetailPesananPart) > 0) {
                // $uk = "Jumlah Pesan Produk ".$po->getJumlahPesanan()." Jumlah Cek Produk ".$po->getJumlahCek()." Jumlah Pesan Part ".$po->getJumlahPesananPartNonJasa()." Jumlah Cek Part ".$po->getJumlahCekPart("ok");
                if ($po->log_id == "8") {
                    if (($po->getJumlahPesanan() == $po->getJumlahCek()) && ($po->getJumlahPesananPartNonJasa() == $po->getJumlahCekPart("ok"))) {
                        if ($po->getJumlahKirim() == 0 && $po->getJumlahKirimPart() == 0) {
                            $pou = Pesanan::find($pesanan_id);
                            $pou->log_id = '11';
                            $u = $pou->save();
                            if (!$u) {
                                $bools = false;
                            }
                        } else if ($po->getJumlahKirim() > 0 || $po->getJumlahKirimPart() > 0) {
                            if ($po->getJumlahKirim() >= $po->getJumlahPesanan() && $po->getJumlahKirimPart() >= $po->getJumlahPesananPartNonJasa()) {
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
            return response()->json(['data' => 'error']);
        }
    }
    //Dashboardpublic
    function dashboard()
    {
        $terbaru = 0;
        $hasil = 0;
        $lewatbatas = "";

        $terbaru_prd = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                ->where('t_gbj.tgl_keluar', '>=', Carbon::now()->subdays(7))
                ->groupBy('pesanan.id')
                ->havingRaw('count(t_gbj_noseri.id) > (select count(noseri_detail_pesanan.id)
                    from noseri_detail_pesanan
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id)');
        })->whereNotIn('log_id', ['7', '9', '10', '20']);

        $terbaru_part = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw("m_sparepart.kode NOT LIKE '%JASA%'")
                ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                        select sum(outgoing_pesanan_part.jumlah_ok)
                        from outgoing_pesanan_part
                        left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS (select * from outgoing_pesanan_part
                        left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->whereNotIn('log_id', ['7', '10', '20'])
            ->where('tgl_po', '>=', Carbon::now()->subdays(7))
            ->union($terbaru_prd)
            ->orderBy('tgl_po', 'desc')
            ->count();

        $terbaru = $terbaru_part;

        // $cekhasil = Pesanan::whereIN('id', $this->check_input())->orderby('id', 'ASC')->orHas('DetailPesanan')->orWherehas('DetailPesananPart.Sparepart', function ($q) {
        //     $q->where('nama', 'not like', '%JASA%');
        // })->get();

        // $arrayid = array();
        // foreach ($cekhasil as $h) {
        //     if (count($h->DetailPesanan) > 0 && count($h->DetailPesananPart) <= 0) {
        //         if ($h->getJumlahSeri() > $h->getJumlahCek()) {
        //             $arrayid[] = $h->id;
        //         }
        //     } else if (count($h->DetailPesanan) <= 0 && count($h->DetailPesananPart) > 0) {
        //         if ($h->getJumlahPesananPartNonJasa() > $h->getJumlahCekPart("ok")) {
        //             $arrayid[] = $h->id;
        //         }
        //     } else {
        //         if (($h->getJumlahSeri() > $h->getJumlahCek()) || $h->getJumlahPesananPartNonJasa() > $h->getJumlahCekPart("ok")) {
        //             $arrayid[] = $h->id;
        //         }
        //     }
        // }
        // $hasil = Pesanan::whereIn('id', $arrayid)->get()->count();

        $blm_uji_prd = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(t_gbj_noseri.id) > 0 AND NOT EXISTS (select *
                        from noseri_detail_pesanan
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id)');
        })->whereNotIn('log_id', ['7', '9', '10', '20']);

        $blm_uji_part = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw("m_sparepart.kode NOT LIKE '%JASA%'")
                ->havingRaw("sum(detail_pesanan_part.jumlah) > 0 AND NOT EXISTS (select * from outgoing_pesanan_part
                left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->whereNotIn('log_id', ['7', '10', '20'])
            ->union($blm_uji_prd)
            ->count();

        $hasil = $blm_uji_part;

        // $lewat_batas_data = Pesanan::has('Ekatalog')->whereIN('id',  $this->check_input())->get();
        // $tgl_sekarang = Carbon::now()->format('Y-m-d');
        // $lewat_batas = 0;
        // foreach ($lewat_batas_data as $l) {
        //     $tgl_parameter = $this->getHariBatasKontrak($l->ekatalog->tgl_kontrak, $l->ekatalog->provinsi->status)->format('Y-m-d');
        //     if ($tgl_sekarang > $tgl_parameter) {
        //         $lewat_batas++;
        //     }
        // }

        $lewat_batas = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(t_gbj_noseri.id) > (select count(noseri_detail_pesanan.id)
            from noseri_detail_pesanan
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id)');
        })->whereNotIn('log_id', ['7', '9', '10', '20'])->whereHas('Ekatalog.Provinsi', function ($q) {
            $q->whereRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY) < CURDATE(), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY) < CURDATE())');
        })->count();

        $cpo = Pesanan::addSelect([
            'cjumlahprd' => function ($q) {
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function ($q) {
                $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'clogpart' => function ($q) {
                $q->selectRaw('sum(detail_logistik_part.jumlah)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            }
        ])
            ->whereIn('log_id', ['9'])
            ->havingRaw('clogprd < cjumlahprd OR clogpart < cjumlahpart')
            ->count();

        $cgudang = Pesanan::addSelect([
            'jumlah_produk' => function ($q) {
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'jumlah_gudang' => function ($q) {
                $q->selectRaw('count(t_gbj_noseri.id)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            }
        ])->whereNotIn('log_id', ['7', '20'])->havingRaw('jumlah_produk > jumlah_gudang')->count();

        $clogistik = Pesanan::addSelect([
            'cqcprd' => function ($q) {
                $q->selectRaw('count(noseri_detail_pesanan.id)')
                    ->from('noseri_detail_pesanan')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'cqcpart' => function ($q) {
                $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                    ->from('outgoing_pesanan_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function ($q) {
                $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'clogpart' => function ($q) {
                $q->selectRaw('sum(detail_logistik_part.jumlah)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            }
        ])
            ->havingRaw('cqcprd > clogprd OR clogprd > clogpart')
            ->count();
        return view('page.qc.dashboard', ['terbaru' => $terbaru, 'hasil' => $hasil, 'lewat_batas' => $lewat_batas, 'po' => $cpo, 'gudang' => $cgudang, 'logistik' => $clogistik]);
    }

    public function tf_riwayat(Request $request)
    {
        $data = RiwayatTf::where('dari', 23)->whereYear('created_at', $request->years)->get();
        $setData = array();
        foreach ($data as $d) {
            $e = json_decode($d->isi);
            $setData[] = array(
                'id' => $d->id,
                'so' => $e->header->so,
                'no_po' => $e->header->no_po,
                'customer' => $e->header->customer,
                'tgl_transfer' => $d->created_at->format('Y-m-d'),
                'detail' => $e->produk
            );
        }
        return response()->json($setData);
    }
    public function dashboard_data($value)
    {
        if ($value == 'terbaru') {
            $terbaru_prd = Pesanan::addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('sum(outgoing_pesanan_part.jumlah_ok)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                    ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                    ->where('t_gbj.tgl_keluar', '>=', Carbon::now()->subdays(7))
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(t_gbj_noseri.id) > (select count(noseri_detail_pesanan.id)
                                    from noseri_detail_pesanan
                                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                                    where detail_pesanan.pesanan_id = pesanan.id)');
            })->whereNotIn('log_id', ['7', '9', '10', '20'])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi']);

            $terbaru_part = Pesanan::addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('sum(outgoing_pesanan_part.jumlah_ok)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw("m_sparepart.kode NOT LIKE '%JASA%'")
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                                    select sum(outgoing_pesanan_part.jumlah_ok)
                                    from outgoing_pesanan_part
                                    left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                                    where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS (select * from outgoing_pesanan_part
                                    left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                                    where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
            })->whereNotIn('log_id', ['7', '10', '20'])
                ->where('tgl_po', '>=', Carbon::now()->subdays(7))
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->union($terbaru_prd)
                ->orderBy('tgl_kontrak', 'asc')
                ->get();

            $data = $terbaru_part;

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                                if ($hari > 7) {
                                    return '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return '<div id="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('status', function ($data) {

                    $cdata = $data->cqcprd + $data->cqcpart;
                    if ($cdata <= 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        return '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x = 'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x = 'spa';
                    } else {
                        $x = 'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</a>
                ';
                })
                ->rawColumns(['button', 'batas', 'status'])
                ->make(true);
        } else if ($value == 'belum_uji') {


            // $cekhasil = Pesanan::whereIN('id', $this->check_input())->orderby('id', 'ASC')->orHas('DetailPesanan')->orwherehas('DetailPesananPart.Sparepart', function ($q) {
            //     $q->where('nama', 'not like', '%JASA%');
            // })->get();

            // $arrayid = array();
            // foreach ($cekhasil as $h) {
            //     if (count($h->DetailPesanan) > 0 && count($h->DetailPesananPart) <= 0) {
            //         if ($h->getJumlahSeri() > 0 && $h->getJumlahPesanan() > $h->getJumlahCek()) {
            //             $arrayid[] = $h->id;
            //         }
            //     } else if (count($h->DetailPesanan) <= 0 && count($h->DetailPesananPart) > 0) {
            //         if ($h->getJumlahPesananPartNonJasa() > $h->getJumlahCekPart("ok")) {
            //             $arrayid[] = $h->id;
            //         }
            //     } else {
            //         if (($h->getJumlahSeri() > 0 && $h->getJumlahPesanan() > $h->getJumlahCek()) || $h->getJumlahPesananPartNonJasa() > $h->getJumlahCekPart("ok")) {
            //             $arrayid[] = $h->id;
            //         }
            //     }
            // }

            // foreach ($cekhasil as $h) {
            //         if (count($h->DetailPesanan) > 0 && count($h->DetailPesananPart) <= 0) {
            //             if ($h->getJumlahSeri() > $h->getJumlahCek()) {
            //                 $arrayid[] = $h->id;
            //             }
            //         } else if (count($h->DetailPesanan) <= 0 && count($h->DetailPesananPart) > 0) {
            //             if ($h->getJumlahPesananPartNonJasa() > $h->getJumlahCekPart("ok")) {
            //                 $arrayid[] = $h->id;
            //             }
            //         } else {
            //             if (($h->getJumlahSeri() > $h->getJumlahCek()) || $h->getJumlahPesananPartNonJasa() > $h->getJumlahCekPart("ok")) {
            //                 $arrayid[] = $h->id;
            //             }
            //         }
            //     }

            // $data = Pesanan::whereIn('id', $arrayid)->get();

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

            $blm_uji_prd = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                    ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(t_gbj_noseri.id) > 0 AND NOT EXISTS (select *
                                    from noseri_detail_pesanan
                                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                                    where detail_pesanan.pesanan_id = pesanan.id)');
            })->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('sum(outgoing_pesanan_part.jumlah_ok)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->where('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->whereNotIn('log_id', ['7', '9', '10', '20'])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi']);

            $blm_uji_part = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw("m_sparepart.kode NOT LIKE '%JASA%'")
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > 0 AND NOT EXISTS (select * from outgoing_pesanan_part
                                left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                                left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                                where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
            })->whereNotIn('log_id', ['7', '10', '20'])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->addSelect([
                    'tgl_kontrak' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                            ->from('ekatalog')
                            ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    },
                    'cqcprd' => function ($q) {
                        $q->selectRaw('count(noseri_detail_pesanan.id)')
                            ->from('noseri_detail_pesanan')
                            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    },
                    'cqcpart' => function ($q) {
                        $q->selectRaw('sum(outgoing_pesanan_part.jumlah_ok)')
                            ->from('outgoing_pesanan_part')
                            ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                            ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                            ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                            ->where('detail_pesanan_part.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    }
                ])
                ->union($blm_uji_prd)
                ->orderBy('tgl_kontrak', 'asc')
                ->get();

            $data = $blm_uji_part;

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                                if ($hari > 7) {
                                    return '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return '<div id="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })

                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x = 'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x = 'spa';
                    } else {
                        $x = 'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</a>';
                })
                ->rawColumns(['button', 'batas'])
                ->make(true);
        } else if ($value == 'lewat_uji') {

            $data = Pesanan::with(['ekatalog.customer.provinsi'])
                ->whereIn('id', function ($q) {
                    $q->select('pesanan.id')
                        ->from('pesanan')
                        ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                        ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                        ->groupBy('pesanan.id')
                        ->havingRaw('count(t_gbj_noseri.id) > (select count(noseri_detail_pesanan.id)
                from noseri_detail_pesanan
                left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                where detail_pesanan.pesanan_id = pesanan.id)');
                })->whereNotIn('log_id', ['7', '9', '10'])->whereHas('Ekatalog.Provinsi', function ($q) {
                    $q->whereRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY) < CURDATE(), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY) < CURDATE())');
                })->addSelect([
                    'tgl_kontrak' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                            ->from('ekatalog')
                            ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    }
                ])->with(['Ekatalog.Customer.Provinsi'])->orderBy('tgl_kontrak', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                                if ($hari > 7) {
                                    return '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return '<div id="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('status', function ($data) {

                    $cdata = $data->cqcprd + $data->cqcpart;
                    if ($cdata <= 0) {
                        return '<span class="badge red-text">Belum diuji</span>';
                    } else {
                        return '<span class="badge yellow-text">Sedang Berlangsung</span>';
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $x = 'ekatalog';
                    } elseif ($name[1] == 'SPA') {
                        $x = 'spa';
                    } else {
                        $x = 'spb';
                    }
                    return '<a href="' . route('qc.so.detail', [$data->id, $x]) . '" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</a>';
                })
                ->rawColumns(['button', 'batas', 'status'])
                ->make(true);
        }
    }

    public function dashboard_so()
    {
        $data = Pesanan::addSelect([
            'cjumlahprd' => function ($q) {
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function ($q) {
                $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'clogpart' => function ($q) {
                $q->selectRaw('sum(detail_logistik_part.jumlah)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }
        ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
            ->whereNotIn('log_id', ['7', '20'])
            ->havingRaw('cjumlahprd > clogprd OR cjumlahpart > clogpart')
            ->get();
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
                $hitung = floor(((($data->clogprd + $data->clogpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                if ($hitung > 0) {
                    $datas = '<div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Terkirim</small>';
                } else {
                    $datas = '<div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Terkirim</small>';
                }
                return $datas;
            })
            ->addColumn('aksi', function ($data) {
                $id = "";
                $jenis = "";
                if ($data->Ekatalog) {
                    $id = $data->Ekatalog->id;
                    $jenis = "ekatalog";
                } else if ($data->Spa) {
                    $id = $data->Spa->id;
                    $jenis = "spa";
                } else if ($data->Spb) {
                    $id = $data->Spb->id;
                    $jenis = "spb";
                }
                return '<a data-toggle="modal" data-target="' . $jenis . '" class="somodal" data-attr="' . route('penjualan.penjualan.detail.' . $jenis, $id) . '"  data-id="' . $id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
            })
            ->rawColumns(['customer', 'status', 'aksi'])
            ->make(true);
    }
    //Laporan
    public function laporan_outgoing(Request $request)
    {
        return Excel::download(new LaporanQcOutgoing($request->produk_id ?? '', $request->no_so ?? '', $request->hasil_uji ?? '', $request->tanggal_mulai ?? '', $request->tanggal_akhir ?? ''), 'laporan_qc_outgoing.xlsx');
    }

    public function get_data_laporan_qc_2($jenis, $produk, $no_so, $hasil, $tgl_awal, $tgl_akhir)
    {
        $res = "";
        $so = "";
        if ($no_so != "0") {
            $so = str_replace("_", "/", $no_so);
        } else {
            $so = $no_so;
        }

        if ($jenis == "produk") {
            if ($produk != "0" && $so == "0") {
                if ($hasil != "semua") {
                    $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
                    })->get();
                } else {
                    $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
                    })->get();
                }
            } else if ($produk == "0" && $so != "0") {
                if ($hasil != "semua") {
                    $res = Pesanan::where('so', 'LIKE', '%' . $so . '%')->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
                    })->get();
                } else {
                    $res = Pesanan::where('so', 'LIKE', '%' . $so . '%')->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
                    })->get();
                }
            } else if ($produk != "0" && $so != "0") {
                if ($hasil != "semua") {
                    $res = Pesanan::where('so', 'LIKE', '%' . $so . '%')->whereHas('DetailPesanan.DetailPesananProduk', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
                    })->get();
                } else {
                    $res = Pesanan::where('so', 'LIKE', '%' . $so . '%')->whereHas('DetailPesanan.DetailPesananProduk', function ($q) use ($produk) {
                        $q->where('penjualan_produk_id', $produk);
                    })->whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
                    })->get();
                }
            } else if ($produk == "0" && $so == "0") {
                if ($hasil != "semua") {
                    $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
                    })->get();
                } else {
                    $res = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                        $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
                    })->get();
                }
            }
        } else if ($jenis == "part") {
            if ($produk != "0" && $so == '0') {
                $res = Pesanan::whereHas('DetailPesananPart', function ($q) use ($produk) {
                    $q->where('m_sparepart_id', $produk);
                })->whereHas('DetailPesananPart.OutgoingPesananPart', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
                })->get();
            } else if ($produk == "0" && $so != '0') {
                $res = Pesanan::where('so', 'LIKE', '%' . $so . '%')->whereHas('DetailPesananPart.OutgoingPesananPart', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
                })->get();
            } else if ($produk != "0" && $so != '0') {
                $res = Pesanan::where('so', 'LIKE', '%' . $so . '%')->whereHas('DetailPesananPart', function ($q) use ($produk) {
                    $q->where('m_sparepart_id', $produk);
                })->whereHas('DetailPesananPart.OutgoingPesananPart', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
                })->get();
            } else if ($produk == "0" && $so == '0') {
                $res = Pesanan::whereHas('DetailPesananPart.OutgoingPesananPart', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
                })->get();
            }
        }

        return datatables()->of($res)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_paket', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('no_po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('tgl_po', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            })
            ->addColumn('customer', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->Customer->nama;
                } else if ($data->Spa) {
                    return $data->Spa->Customer->nama;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->instansi . '<br><small class="text-muted">' . $data->Ekatalog->satuan . '</small>';
                } else {
                    return '-';
                }
            })
            ->addColumn('alamat', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->alamat;
                } else if ($data->Spa) {
                    return $data->Spa->Customer->alamat;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->alamat;
                }
            })
            ->addColumn('status', function ($data) {
                $datas = '';
                if (!empty($data->log_id)) {
                    if ($data->State->nama == "Penjualan") {
                        $datas .= '<span class="red-text badge">';
                        $datas .= '<span class="purple-text badge">';
                    } else if ($data->State->nama == "Gudang") {
                        $datas .= '<span class="orange-text badge">';
                    } else if ($data->State->nama == "QC") {
                        $datas .= '<span class="yellow-text badge">';
                    } else if ($data->State->nama == "Belum Terkirim") {
                        $datas .= '<span class="red-text badge">';
                    } else if ($data->State->nama == "Terkirim Sebagian") {
                        $datas .= '<span class="blue-text badge">';
                    } else if ($data->State->nama == "Kirim") {
                        $datas .= '<span class="green-text badge">';
                    }
                    $datas .= ucfirst($data->State->nama) . '</span>';
                } else {
                    $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                }
                return $datas;
            })
            ->rawColumns(['status', 'instansi'])
            ->make(true);
    }

    public function get_cetak_laporan_qc($jenis, $produk, $no_so, $hasil, $tgl_awal, $tgl_akhir)
    {
        $so = "";
        if ($no_so != "0") {
            $so = str_replace("_", "/", $no_so);
        } else {
            $so = $no_so;
        }
        $waktu = Carbon::now();
        if ($jenis == "produk") {
            return Excel::download(new LaporanQc($jenis, $produk, $so, $hasil, $tgl_awal, $tgl_akhir), 'Laporan QC Outgoing Produk ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == "part") {
            return Excel::download(new LaporanQc($jenis, $produk, $so, $hasil, $tgl_awal, $tgl_akhir), 'Laporan QC Outgoing Part ' . $waktu->toDateTimeString() . '.xlsx');
        }
    }

    public function get_cetak_noseri_qc($id)
    {
        $waktu = Carbon::now();
        return Excel::download(new NoseriQC($id), 'Noseri QC PO ' . $waktu->toDateTimeString() . '.xlsx');
    }

    public function monitoring_seri($id)
    {
        $dpp = DetailPesananProduk::find($id);

        $get_dpp = DetailPesananProduk::whereHas("DetailPesanan", function ($q) use ($dpp) {
            $q->where('pesanan_id', $dpp->DetailPesanan->pesanan_id);
        })->where('detail_pesanan_produk.gudang_barang_jadi_id', $dpp->gudang_barang_jadi_id)->pluck('detail_pesanan_produk.id')->toArray();

        $detail = UjiLabDetail::with('NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi')
            // ->addSelect([
            //     'belum' => function ($q) {
            //         $q->selectRaw('coalesce(count(uji.id),0)')
            //             ->from('uji_lab_detail as uji')
            //             ->where('uji.status', 'belum')
            //             ->where('uji.is_ready', 1)
            //             ->whereColumn('uji.id', 'uji_lab_detail.id');
            //     },])
            // ->havingRaw('belum !=0')
            ->whereIN('detail_pesanan_produk_id', $get_dpp)
            ->get();
        if ($detail->isEmpty()) {
            $seri = array();
        } else {

            foreach ($detail as $d) {
                $seri[] = array(
                    'id' => $d->id,
                    'no_seri' => $d->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                    'tgl_masuk' => $d->tgl_masuk,
                    'status' => $d->status == 'nok' ? 'not_ok' : $d->status,
                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $seri,
        ], 200);
    }
    public function monitoring_detail($id)
    {
        $ujilab_head = UjiLab::find($id);
        $pesanan = Pesanan::find($ujilab_head->pesanan_id);
        $ujilab = UjiLabDetail::select('uji_lab_detail.id as lab_id', 'detail_pesanan_produk.gudang_barang_jadi_id', 'produk.nama as tipe', 'kode_lab.nama as nama', 'gdg_barang_jadi.nama as variasi', 'detail_pesanan_produk.id as dpp_id')
            ->selectRaw(
                "(CASE
        WHEN uji_lab_detail.status = 'ok'  THEN 1
        ELSE 0
        END) AS ok",
            )
            ->selectRaw(
                "(CASE
        WHEN uji_lab_detail.status = 'nok'  THEN 1
        ELSE 0
        END) AS nok",
            )
            // ->selectRaw(
            //     "coalesce(count(uji_lab_detail.id),0) AS jumlah",
            // )
            // ->selectRaw(
            //     "coalesce(SUM(CASE WHEN uji_lab_detail.status != 'belum' THEN 1 ELSE 0 END),0) AS uji",
            // )
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('kode_lab', 'kode_lab.id', '=', 'produk.kode_lab_id')
            ->where('uji_lab_id', $id)
            // ->whereRaw("uji_lab_detail.status != 'belum'")
            ->get();

        if ($ujilab->isEmpty()) {
            $data = array();
        } else {
            $produks = [];
            foreach ($ujilab as $d) {
                $gudang_barang_jadi_id = $d['gudang_barang_jadi_id'];
                if (!isset($produks[$gudang_barang_jadi_id])) {
                    $produks[$gudang_barang_jadi_id] = array(
                        'id' => $d->dpp_id,
                        "nama" => $d->nama,
                        "tipe" => $d->tipe . $d->variasi,
                        "jumlah" => 0,
                        "jumlah_ok" => 0,
                        "jumlah_nok" => 0,
                    );
                }
                $produks[$gudang_barang_jadi_id]["jumlah"] += 1;
                $produks[$gudang_barang_jadi_id]["jumlah_nok"] += $d->nok;
                $produks[$gudang_barang_jadi_id]["jumlah_ok"] += $d->ok;
            }
            $produks = array_values($produks);

            if ($pesanan->Spa) {
                $c =  $pesanan->Spa->Customer->nama;
            }
            if ($pesanan->Ekatalog) {
                $c =  $pesanan->Ekatalog->Customer->nama;
            }
            if ($pesanan->Spb) {
                $c =  $pesanan->Spb->Customer->nama;
            }

            $data = array(
                'header' => array(
                    'id' => $pesanan->id,
                    'so' => $pesanan->so,
                    'po' => $pesanan->no_po,
                    'tgl_po' => $pesanan->tgl_po,
                    'customer' => $c,
                    'alamat' => $ujilab_head->alamat,
                    'status' =>   intval(($ujilab_head->GetUji()) / $ujilab_head->GetJumlah() * 100),
                ),
                'produk' => $produks
            );
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function monitoring_data()
    {
        $ujilab = UjiLab::addSelect([
            // 'ok' => function ($q) {
            //     $q->selectRaw('SUM(CASE WHEN status = "ok" THEN 1 ELSE 0 END) ')
            //         ->from('uji_lab_detail')
            //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            // },
            // 'nok' => function ($q) {
            //     $q->selectRaw('SUM(CASE WHEN status = "nok" THEN 1 ELSE 0 END)')
            //         ->from('uji_lab_detail')
            //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            // },
            'uji' => function ($q) {
                $q->selectRaw('coalesce(SUM(CASE WHEN status != "belum" THEN 1 ELSE 0 END),0)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            // 'belum' => function ($q) {
            //     $q->selectRaw(' coalesce(SUM(CASE WHEN status = "belum" THEN 1 ELSE 0 END),0)')
            //         ->from('uji_lab_detail')
            //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            // },
            'jumlah' => function ($q) {
                $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
        ])
            //  ->havingRaw('uji != 0')
            ->with(['Pesanan.Spa.Customer', 'Pesanan.Spb.Customer', 'Pesanan.Ekatalog.Customer'])
            ->get();


        if ($ujilab->isEmpty()) {
            $data = array();
        } else {
            foreach ($ujilab as $u) {
                if ($u->Pesanan->Spa) {
                    $c =  $u->Pesanan->Spa->Customer->nama;
                }
                if ($u->Pesanan->Ekatalog) {
                    $c =  $u->Pesanan->Ekatalog->Customer->nama;
                }
                if ($u->Pesanan->Spb) {
                    $c =  $u->Pesanan->Spb->Customer->nama;
                }
                $data[] = array(
                    'id' => $u->id,
                    'so' => $u->Pesanan->so,
                    'po' => $u->Pesanan->no_po,
                    'customer' => $c,
                    'status' => intval(($u->uji) / $u->jumlah * 100)
                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function get_data_detail_laporan_qc($id, Request $r)
    {
        $jenis = $r->jenis;
        $tgl_awal = $r->tgl_awal;
        $tgl_akhir = $r->tgl_akhir;
        $hasil = $r->hasil;
        $produk = $r->produk;

        $res = "";
        if ($jenis == "produk") {
            if ($produk != "0") {
                if ($hasil != "semua") {
                    $res = NoseriDetailPesanan::where('status', $hasil)
                        ->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                        ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk, $id) {
                            $q->where('penjualan_produk_id', $produk)->where('pesanan_id', $id);
                        })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                } else {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                        ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk, $id) {
                            $q->where('pesanan_id', $id)->where('penjualan_produk_id', $produk);
                        })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                }
            } else if ($produk == "0") {
                if ($r->hasil != "semua") {
                    $res = NoseriDetailPesanan::where('status', $hasil)
                        ->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                        ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                            $q->where('pesanan_id', $id);
                        })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                } else {
                    $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])
                        ->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                            $q->where('pesanan_id', $id);
                        })->orderBy('detail_pesanan_produk_id', 'ASC')->get();
                }
            }
        } else if ($jenis == "part") {
            if ($produk != "0") {
                $res = OutgoingPesananPart::whereHas('DetailPesananPart', function ($q) use ($produk, $id) {
                    $q->where('m_sparepart_id', $produk)->where('pesanan_id', $id);
                })->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            } else if ($produk == "0") {
                $res = OutgoingPesananPart::whereHas('DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->orderBy('detail_pesanan_part_id', 'ASC')->get();
            }
        }

        return datatables()->of($res)
            ->addIndexColumn()
            ->addColumn('produk', function ($data) use ($jenis) {
                if ($jenis == "produk") {
                    if (count($data->DetailPesananProduk->DetailPesanan->PenjualanProduk->Produk) > 1) {
                        if ($data->DetailPesananProduk->GudangBarangJadi->nama != '') {
                            $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' - <b>' . $data->DetailPesananProduk->GudangBarangJadi->nama . '</b> ';
                            return $datas;
                        } else {
                            $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . " ";
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
        $id = array();
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
            $jumlah +=  $i->jumlah_ok + $i->jumlah_nok;
        }
        $sisa = $d->jumlah - $jumlah;
        return $sisa;
    }

    function getYear()
    {
        $tahun = Carbon::nowtoDateString();
        return $tahun;
    }
    public function kirim_kalibrasi(Request $request)
    {
        //  $max_no = UjiLabDetail::whereYear('created_at', now()->year)->max('no');
        //  dd($max_no);
        // if ($request->pesanan_id != '' && count($request->produk) > 0) {
        //     $cek_lab = UjiLab::where('pesanan_id', $request->pesanan_id)->first();
        //     if(isset($cek_lab['id'])) {
        //         foreach ($request->produk as $dp) {
        //             for ($j = 0; $j < count($dp['seri']); $j++) {
        //                 $max_no = UjiLabDetail::whereYear('created_at', now()->year)->latest('id')->value('no');
        //                 UjiLabDetail::create([
        //                     'no' => $max_no + 1,
        //                     'uji_lab_id' => $cek_lab['id'],
        //                     'detail_pesanan_produk_id' => $dp['detail_pesanan_produk_id'],
        //                     'noseri_id' => $dp['seri'][$j]['id'],
        //                     'tgl_masuk' =>  Carbon::now()->toDateString()
        //                 ]);

        //                 NoseriDetailPesanan::where('id', $dp['seri'][$j]['id'])
        //                 ->update(['is_lab' => 1,'is_ready' => 1]);
        //             }
        //         }
        //     }else{
        //         $max_order = UjiLab::whereYear('created_at', now()->year)->latest('id')->value('no_order');
        //         $ujilab = UjiLab::create([
        //             'no_order' => $max_order + 1,
        //             'pesanan_id' => $request->pesanan_id
        //         ]);
        //         foreach ($request->produk as $dp) {
        //             for ($j = 0; $j < count($dp['seri']); $j++) {
        //                 $max_no = UjiLabDetail::whereYear('created_at', now()->year)->latest('id')->value('no');
        //                 UjiLabDetail::create([
        //                     'no' => $max_no + 1,
        //                     'uji_lab_id' => $ujilab->id,
        //                     'detail_pesanan_produk_id' => $dp['detail_pesanan_produk_id'],
        //                     'noseri_id' => $dp['seri'][$j]['id'],
        //                     'tgl_masuk' =>  Carbon::now()->toDateString(),
        //                 ]);
        //                 NoseriDetailPesanan::where('id', $dp['seri'][$j]['id'])
        //                 ->update(['is_lab' => 1,'is_ready' => 1]);
        //             }
        //         }
        //     }
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Berhasil'
        //     ], 200);
        DB::beginTransaction();
        try {
            //code...
            if ($request->pesanan_id != '' && $request->detail_pesanan_produk_id != '') {
                $cek_lab = UjiLab::where('pesanan_id', $request->pesanan_id)->first();
                if (isset($cek_lab['id'])) {
                    for ($i = 0; $i < count($request->noseri_id); $i++) {
                        # $max_no = UjiLabDetail::whereYear('created_at', now()->year)->latest('id')->value('no');
                        $get_dpp = NoseriTGbj::find($request->noseri_id[$i]['id']);
                        UjiLabDetail::create([
                            #    'no' => $max_no + 1,
                            'uji_lab_id' => $cek_lab['id'],
                            'detail_pesanan_produk_id' => $get_dpp->NoseriDetailPesanan->detail_pesanan_produk_id,
                            'noseri_id' => $get_dpp->NoseriDetailPesanan->id,
                            'tgl_masuk' => $request->tanggal_kirim,
                        ]);
                        NoseriDetailPesanan::where('id', $get_dpp->NoseriDetailPesanan->id)
                            ->update(['is_lab' => 1, 'is_ready' => 1]);
                    }
                } else {

                    $cek_pesanan = Pesanan::find($request->pesanan_id);

                    if ($cek_pesanan->Ekatalog) {
                        $nama = $cek_pesanan->Ekatalog->instansi;
                        $alamat = $cek_pesanan->Ekatalog->alamat;
                    } elseif ($cek_pesanan->Spa) {
                        $nama = $cek_pesanan->Spa->Customer->nama;
                        $alamat = $cek_pesanan->Spa->Customer->alamat;
                    } elseif ($cek_pesanan->Spb) {
                        $nama = $cek_pesanan->Spb->Customer->nama;
                        $alamat = $cek_pesanan->Spb->Customer->alamat;
                    }


                    $max_order = UjiLab::whereYear('created_at', now()->year)->latest('id')->value('no_order');
                    $ujilab = UjiLab::create([
                        'no_order' => $max_order + 1,
                        'pesanan_id' => $request->pesanan_id,
                        'nama' => $nama,
                        'alamat' => $alamat
                    ]);
                    for ($i = 0; $i < count($request->noseri_id); $i++) {
                        $get_dpp = NoseriTGbj::find($request->noseri_id[$i]['id']);
                        # $max_no = UjiLabDetail::whereYear('created_at', now()->year)->latest('id')->value('no');
                        UjiLabDetail::create([
                            #'no' => $max_no + 1,
                            'uji_lab_id' => $ujilab->id,
                            'detail_pesanan_produk_id' => $get_dpp->NoseriDetailPesanan->detail_pesanan_produk_id,
                            'noseri_id' => $get_dpp->NoseriDetailPesanan->id,
                            'tgl_masuk' => $request->tanggal_kirim,
                        ]);
                        NoseriDetailPesanan::where('id', $get_dpp->NoseriDetailPesanan->id)
                            ->update(['is_lab' => 1, 'is_ready' => 1]);
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil'
                ], 200);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Tidak ditemukan',
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Tidak ditemukan',
            ], 404);
        }
    }

    //Select
    public function getProdukPesananSelect($id)
    {
        $result = DetailPesananProduk::where('detail_pesanan_id', $id)->with('GudangBarangJadi.Produk')->get();
        return $result;
    }

    //MANAGER
    public function manager_qc_show()
    {
        return view('page.qc.manager.sales_order.show');
    }
}
