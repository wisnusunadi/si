<?php

namespace App\Http\Controllers;

use App\Exports\GBJExportSPB;
use App\Exports\ImportNoseri;
use App\Exports\NonsoExport;
use App\Exports\NoseriGudangExport;
use App\Exports\SpbExport;
use App\Models\DetailEkatalog;
use App\Models\DetailEkatalogProduk;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Divisi;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\JadwalPerakitan;
use App\Models\JadwalPerakitanRw;
use App\Models\JadwalRakitNoseri;
use App\Models\JadwalRakitNoseriRw;
use App\Models\kesehatan\Karyawan;
use App\Models\Layout;
use App\Models\LogSurat;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriBrgJadiLog;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\PinjamintaBrg;
use App\Models\PinjamintaBrgDetail;
use App\Models\Produk;
use App\Models\RiwayatBatalPo;
use App\Models\RiwayatBatalPoPaket;
use App\Models\RiwayatBatalPoPrd;
use App\Models\RiwayatBatalPoSeri;
use App\Models\RiwayatGagalKalibrasi;
use App\Models\RiwayatReturPo;
use App\Models\RiwayatReturPoPrd;
use App\Models\RiwayatReturPoSeri;
use App\Models\Satuan;
use App\Models\SeriGanti;
use App\Models\SeriDetailRw;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\SystemLog;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\UjiLab;
use App\Models\UjiLabDetail;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use Mockery\Undefined;
use stdClass;


class GudangController extends Controller
{
    function kirim_seri_batal_po(Request $request)
    {
        $obj = json_decode(json_encode($request->all()), FALSE);
        DB::beginTransaction();
        try {
            //code...
            foreach ($obj->item as $item) {
                foreach ($item->noseri as $noseri) {
                    RiwayatBatalPoSeri::create([
                        'detail_riwayat_batal_prd_id' => $item->riwayat_batal_po_prd_id,
                        't_tfbj_noseri_id' => $noseri->id,
                        'noseri_id' => $noseri->noseri_id,
                        'status' => 1,
                        'posisi' => $noseri->posisi
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal Dikirim',
            ], 500);
        }
    }

    function get_detail_seri_batal_po($id)
    {
        $data = NoseriTGbj::select('t_gbj_noseri.id', 'noseri_barang_jadi.id as noseri_id', 'noseri_barang_jadi.noseri')

            ->addSelect([
                'c_on_uji' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                        ->from('noseri_detail_pesanan')
                        ->whereColumn('noseri_detail_pesanan.t_tfbj_noseri_id', 't_gbj_noseri.id');
                },
                'c_uji' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                        ->from('noseri_detail_pesanan')
                        ->where('noseri_detail_pesanan.is_ready', '1')
                        ->whereColumn('noseri_detail_pesanan.t_tfbj_noseri_id', 't_gbj_noseri.id');
                },
                'c_log' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                        ->from('noseri_detail_pesanan')
                        ->where('noseri_detail_pesanan.is_ready', '0')
                        ->whereColumn('noseri_detail_pesanan.t_tfbj_noseri_id', 't_gbj_noseri.id');
                },
                'c_sj' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->whereColumn('noseri_detail_pesanan.t_tfbj_noseri_id', 't_gbj_noseri.id')
                        ->limit(1);
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id')
                        ->limit(1);
                },
                'c_batal_terima' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_seri.t_tfbj_noseri_id', 't_gbj_noseri.id')
                        ->limit(1);
                }
            ])
            ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->leftjoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            //  ->havingRaw('c_batal_terima > 0')
            ->where('t_gbj_detail.detail_pesanan_produk_id', $id)
            ->get();

        $posisi = ['QC', 'QC', 'LOGISTIK', 'LOGISTIK'];

        $o = 0;
        $obj = array();
        foreach ($data as $d) {
            if ($d->c_on_uji > 0) {
                $o = 1;
            } else {
                $o = $d->c_uji;
            }


            $obj[] = array(
                'id' => $d->id,
                'noseri_id' => $d->noseri_id,
                'noseri' => $d->noseri,
                'posisi' => 99,
                'posisi' => $posisi[$o + $d->c_log + $d->c_sj],
                'status' => $d->c_batal  > 0 ? false : true
            );
        }
        return response()->json($obj);
    }

    function get_detail_batal_po($id)
    {
        $data = RiwayatBatalPoPaket::select('riwayat_batal_po_paket.id', 'penjualan_produk.nama', 'riwayat_batal_po_paket.jumlah')
            ->addSelect([
                'cseri' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftjoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', '=', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->whereColumn('riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'riwayat_batal_po_paket.id')
                        ->limit(1);
                },
                'cseri_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftjoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', '=', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'riwayat_batal_po_paket.id')
                        ->limit(1);
                },

                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_prd.id),0)')
                        ->from('riwayat_batal_po_prd')
                        ->whereColumn('riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'riwayat_batal_po_paket.id');
                },
            ])
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'riwayat_batal_po_paket.detail_pesanan_id')
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
            //->havingRaw('cseri = 0 OR (cseri > 0 AND cseri_batal > 0)')
            ->havingRaw('cjumlahprd * jumlah  - cseri > 0  ')
            ->where('riwayat_batal_po_id', $id);

        $item = RiwayatBatalPoPrd::select('riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'riwayat_batal_po_prd.id as riwayat_batal_po_prd_id', 'riwayat_batal_po_prd.detail_pesanan_produk_id as id', 'produk.nama', 'gdg_barang_jadi.nama as variasi', 'produk.merk')
            ->addSelect([
                'jumlah_seri' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'riwayat_batal_po_prd.detail_pesanan_produk_id')
                        ->limit(1);
                },
                'jumlah_tf' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                },
                'jumlah_tf_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                },
                'jumlah_batal_paket' => function ($q) {
                    $q->selectRaw('coalesce(sum(riwayat_batal_po_paket.jumlah),0)')
                        ->from('riwayat_batal_po_paket')
                        ->whereColumn('riwayat_batal_po_paket.id', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->limit(1);
                }
            ])
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'riwayat_batal_po_prd.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            //  ->havingRaw('jumlah_tf = 0 OR (jumlah_tf > 0 AND jumlah_tf_batal > 0)')
            ->havingRaw('jumlah_batal_paket -  jumlah_tf > 0')
            ->whereIN('detail_riwayat_batal_paket_id', $data->pluck('id')->toArray())->get();

        $obj = array();
        foreach ($data->get() as $key_p => $d) {
            $obj[$key_p] = array(
                'id' => $d->id,
                'nama' => $d->nama,
                'produk' => array(),
            );
            foreach ($item as  $s) {
                if ($d->id == $s['detail_riwayat_batal_paket_id']) {
                    $s['jumlah_sisa'] = $d->jumlah - $s->jumlah_tf;
                    //  $s['jumlah_sisax'] =  $s->jumlah_tf;
                    $s['jumlah'] = $d->jumlah;
                    $s['is_seri'] = $d->jumlah_seri > 0 ? true : false;
                    $obj[$key_p]['produk'][] = $s;
                }
            }
        }
        return response()->json($obj);
    }

    function get_batal_po()
    {
        $data = RiwayatBatalPo::select('riwayat_batal_po.pesanan_id', 'riwayat_batal_po.id', 'pesanan.so', 'pesanan.no_po', 'c_ekat.nama as c_ekat', 'c_spa.nama as c_spa', 'c_spb.nama as c_spb', 'riwayat_batal_po.ket')
            ->addSelect([
                'cseri' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftjoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', '=', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->leftjoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', '=', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->whereColumn('riwayat_batal_po_paket.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'cseri_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftjoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', '=', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->leftjoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', '=', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_paket.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'cgbj' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'riwayat_batal_po.pesanan_id')
                        ->limit(1);
                },
                'cgbj_tidak_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereNull('riwayat_batal_po_seri.id')
                        ->whereColumn('t_gbj.pesanan_id', 'riwayat_batal_po.pesanan_id')
                        ->limit(1);
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(riwayat_batal_po_paket.jumlah),0)')
                        ->from('riwayat_batal_po_paket')
                        ->join('riwayat_batal_po_prd', 'riwayat_batal_po_paket.id', '=', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->whereColumn('riwayat_batal_po_paket.riwayat_batal_po_id', 'riwayat_batal_po.id');
                },

            ])
            ->leftJoin('pesanan', 'pesanan.id', '=', 'riwayat_batal_po.pesanan_id')
            ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
            ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
            ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spa.customer_id')
            // ->havingRaw('(cseri = 0 AND cgbj > 0) OR (cseri > 0 AND cseri_batal > 0)')
            ->havingRaw('(cjumlahprd - cseri > 0) AND (cgbj_tidak_batal > 0)')
            ->get();

        $obj = array();
        foreach ($data as $d) {
            # code...
            $customer = '';
            if ($d->c_ekat != null) {
                $customer = $d->c_ekat;
            }
            if ($d->c_spb != null) {
                $customer = $d->c_spb;
            }
            if ($d->c_spa != null) {
                $customer = $d->c_spa;
            }

            // if ($d->cseri > 0) {
            //     if ($d->cseri != $d->cjumlahprd) {
            //         $status = 'Sebagian di Transfer';
            //     } else {
            //         $status = 'Sudah di Transfer';
            //     }
            // } else {
            //     $status = 'Belum Di transfer';
            // }

            $obj[] = array(
                'id' => $d->id,
                'so' => $d->so,
                'no_po' => $d->no_po,
                'customer' => $customer,
                'jumlah' => $d->cjumlahprd,
                'jumlah_tf' => $d->cseri,
                'ket' => $d->ket,
                'cseri' => $d->cseri,
                'cgbj' => $d->cgbj,
                'cseri_batal' => $d->cseri_batal,
                'cjumlahprd' => $d->cjumlahprd,
                'cgbj_tidak_batal' => $d->cgbj_tidak_batal
            );
        }

        return response()->json($obj);
    }

    function kirim_permintaan(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $obj =  json_decode(json_encode($request->all()), FALSE);
            //   dd($obj);
            foreach ($obj->produk as $p) {
                for ($j = 0; $j < count($p->noseri); $j++) {
                    JadwalRakitNoseriRw::create([
                        'jadwal_id' => $p->id,
                        'noseri_id' => $p->noseri[$j]->id,
                        'noseri' => $p->noseri[$j]->noseri,
                        'status' => 11
                    ]);

                    NoseriBarangJadi::where('id',  $p->noseri[$j]->id)
                        ->update([
                            'is_ready' => 1,
                            'used_by' => $p->id,
                            'reworks_id' => 'Perakitan ' . $obj->no_urut,
                        ]);
                }
            }

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Kirim Permintaan Rework',
                'response' => json_encode($request->all()),
                'user_id' => auth()->user()->id
            ]);



            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Transaksi Update Gagal' . $th,
            ], 500);
        }
    }

    function terima_perakitan_detail_rw($id)
    {
        $data = SeriDetailRw::select('seri_detail_rw.noseri as noseri', 'seri_detail_rw.noseri_id', 'produk.nama as nama', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'seri_detail_rw.noseri_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->where('noseri_barang_jadi.is_prd', 0)
            ->where('noseri_barang_jadi.is_aktif', 0)
            ->where('noseri_barang_jadi.is_ready', 0)
            ->where('seri_detail_rw.urutan', $id)
            ->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $obj[] = array(
                    'id' => $d->noseri_id,
                    'produk' => $d->nama,
                    'noseri' => $d->noseri,
                    'tgl_buat' => $d->created_at->format('Y-m-d'),
                    'packer' => $d->packer,
                    'seri' => json_decode($d->isi)
                );
            }
        }



        return response()->json($obj);
    }
    function store_perakitan_rw(Request $request)
    {
        DB::beginTransaction();
        $obj =  json_decode(json_encode($request->all()), FALSE);

        try {

            foreach ($obj->item as $o) {
                if (isset($o->layout) && $o->layout != null) {
                    $l = $o->layout->id;
                } else {
                    $l = NULL;
                }

                NoseriBarangJadi::where('id', $o->id)
                    ->update([
                        'is_prd' => 0,
                        'is_aktif' => 1,
                        'layout_id' => $l
                    ]);
            }

            SystemLog::where('id', $obj->id)->update([
                'status' => 0,
            ]);

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Terima Reworks',
                'user_id' => auth()->user()->id,
                'response' => json_encode($obj)
            ]);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' =>  'Berhasil Diterima',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' =>  'Gagal Diterima' . $th,
            ], 500);
        }
    }

    function surat_penyerahan_rw($id)
    {

        $seridetail = SeriDetailRw::select('seri_detail_rw.urutan as id', 'produk.nama as nama')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'seri_detail_rw.noseri_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->where('noseri_barang_jadi.is_prd', 0)
            ->where('noseri_barang_jadi.is_aktif', 0)
            ->where('noseri_barang_jadi.is_ready', 0)
            ->where('seri_detail_rw.urutan', $id)
            ->get();

        if ($seridetail->isEmpty()) {
            $obj = array();
        } else {
            foreach ($seridetail as $d) {
                $obj[] = array(
                    'id' => $d->id,
                    'produk_id' => $d->id,
                    'noseri' => $d->id
                );
            }
        }
        return response()->json($obj);
    }
    function terima_perakitan_rw()
    {

        $data = SystemLog::where(['tipe' => 'Produksi', 'subjek' => 'Kirim Reworks', 'status' => 1])->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            $res = $data->first()->response;
            foreach ($data as $d) {

                $max = SystemLog::where('tipe', 'Produksi')
                    ->where('subjek', 'Kirim Reworks')
                    ->where('tbl_log.id', '<', $d->id)
                    ->whereYear('created_at', $d->created_at->format('Y'))
                    ->count();

                $x = json_decode($d->response);
                $obj[] = array(
                    'id' => $d->id,
                    'no_surat' =>   'BPBJ' . '/' . $this->toRomawi($d->created_at->format('m')) . '/' . (strtoupper($d->created_at->format('Y')) % 100) . '/' . str_pad($max + 1, 6, '0', STR_PAD_LEFT),
                    'diserahkan' =>  $d->user_id != NULL ? User::find($d->user_id)->Karyawan->nama : '-',
                    'urutan' => 'PRD-' . $x->urutan,
                    'tgl_mulai' => $x->tanggal_mulai,
                    'tgl_selesai' => $x->tanggal_selesai,
                    'tgl_tf' => $d->created_at->format('Y-m-d'),
                    'jumlah' => $x->jumlah,
                    'item' => $x->item,
                );
            }
        }
        return response()->json($obj);
    }
    function belum_kirim_rw_seri($id)
    {

        $data = NoseriBarangJadi::select('noseri_barang_jadi.id', 'noseri_barang_jadi.noseri', 'gdg_barang_jadi.nama as variasi')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
            ->where('noseri_barang_jadi.is_ready', '0')
            ->where('gdg_barang_jadi.produk_id', $id)
            ->whereNull('noseri_barang_jadi.used_by')
            ->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $obj[] = array(
                    'id' => $d->id,
                    'noseri' => $d->noseri,
                    'variasi' => $d->variasi

                );
            }
        }
        return response()->json($obj);
    }
    function belum_kirim_rw_produk(Request $request)
    {
        $jumlah_tf = JadwalPerakitanRw::where('urutan', $request->urutan)->where('produk_reworks_id', $request->produk_reworks_id)->whereRaw('status_tf != 11')->count();
        $data = JadwalPerakitanRw::addSelect([
            'ctfgbj' => function ($q) {
                $q->selectRaw('coalesce(count(jadwal_rakit_noseri_rw.id), 0)')
                    ->from('jadwal_rakit_noseri_rw')
                    ->whereColumn('jadwal_rakit_noseri_rw.jadwal_id', 'jadwal_perakitan_rw.id');
            },
        ])
            ->havingRaw('ctfgbj != jadwal_perakitan_rw.jumlah')
            ->where('urutan', $request->urutan)
            ->where('produk_reworks_id', $request->produk_reworks_id)->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $obj[] = array(
                    'id' => $d->id,
                    'produk_id' => $d->produk_id,
                    'nama' => $d->Produk->nama,
                    'belum' => $d->jumlah - $d->ctfgbj,
                    'jumlah' => $d->jumlah
                );
            }
        }
        return response()->json($obj);
    }
    function riwayat_rw_permintaan_detail($id)
    {
        $data = SystemLog::where(['tipe' => 'GBJ', 'subjek' => 'Kirim Permintaan Rework', 'id' => $id])->orderBy('created_at', 'DESC')->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $x = json_decode($d->response);
                foreach ($x->produk as $produk) {
                    if (isset($produk->noseri) && is_array($produk->noseri)) {
                        foreach ($produk->noseri as $noseri) {
                            if (isset($noseri->noseri)) {
                                $noseriArray[] = array(
                                    'noseri' => $noseri->noseri,
                                    'nama' => $produk->nama,
                                    'varian' => $noseri->variasi
                                );
                            }
                        }
                    }



                    // $obj[] = array(
                    //     'id' => $->id,
                    //     'urutan' => $x->urutan,
                    //     'nama' => $x->nama,
                    //     'tgl_mulai' => $x->tgl_mulai,
                    //     'tgl_selesai' => $x->tgl_selesai,
                    //     'jumlah' => $noseriCount
                    // );
                }
            }
        }
        return response()->json($noseriArray);
    }
    function riwayat_rw_penerimaan()
    {
        $data = SystemLog::where(['tipe' => 'GBJ', 'subjek' => 'Terima Reworks'])->get();


        if ($data->isEmpty()) {
            $obj = array();
        }
        // }else{
        //     $res = $data->first()->response;
        //     $getUrut = json_decode($res);
        //     $jadwal = JadwalPerakitanRw::where('urutan',$getUrut->urutan)->first()->produk_reworks_id;
        //     $produk = Produk::find($jadwal);
        foreach ($data as $d) {
            $x = json_decode($d->response);
            $obj[] = array(
                'id' => $d->id,
                "nama" =>  $x->nama,
                "urutan" =>  $x->urutan,
                "no_surat" => $x->no_surat,
                "diserahkan" =>  $x->diserahkan,
                "urutan" =>  $x->urutan,
                "tgl_mulai" =>  $x->tgl_mulai,
                "tgl_selesai" =>  $x->tgl_selesai,
                "tgl_tf" => $d->created_at,
                "jumlah" =>  $x->jumlah,
                "item" => $x->item
            );
        }
        // }

        return response()->json($obj);
    }

    function surat_pengiriman($id)
    {

        $data = SystemLog::where(['tipe' => 'GBJ', 'subjek' => 'Kirim Permintaan Rework', 'id' => $id])->orderBy('created_at', 'DESC')->first();


        if (!$data) {
            $datas = array();
        } else {
            $result = [];
            $date = Carbon::now();
            $x = json_decode($data->response);
            foreach ($x->produk as $produk) {
                if (isset($produk->noseri) && is_array($produk->noseri)) {
                    foreach ($produk->noseri as $noseri) {
                        if (isset($noseri->noseri)) {
                            $noseriArray[] = array(
                                'id' => $noseri->noseri,
                                'noseri' => $noseri->noseri,
                                'nama' => $produk->nama,
                                'varian' => $noseri->variasi
                            );
                        }
                    }
                }
            }

            foreach ($noseriArray as $item) {
                $key = $item['nama'] . '_' . $item['varian'];

                if (!array_key_exists($key, $result)) {
                    $result[$key] = [
                        'nama' => $item['nama'],
                        'varian' => $item['varian'] ?? '',
                        'jumlah' => 0,
                        'noseri' => []
                    ];
                }

                $result[$key]['noseri'][] = $item['noseri'];
                $result[$key]['jumlah']++;
            }

            // Reindex the array to start index from 0
            $result = array_values($result);

            // Convert keys to numeric arrays
            foreach ($result as &$item) {
                $item['noseri'] = array_values($item['noseri']);
            }

            $max = SystemLog::where('tipe', 'GBJ')
                ->where('subjek', 'Kirim Permintaan Rework')
                ->where('tbl_log.id', '<', $id)
                ->whereYear('created_at', $data->created_at->format('Y'))
                ->count();

            $datas = new stdClass();
            $thn_gbj = $date->format('Y') % 100;
            $urutans_gbj = str_pad($max + 1, 6, '0', STR_PAD_LEFT);
            $urutans_prd = str_pad($x->no, 6, '0', STR_PAD_LEFT);
            $datas->tgl_dibuat = $data->created_at;
            $datas->no_surat = 'FPBJ/' . $this->toRomawi($date->format('m')) . '/' . $thn_gbj . '/' . $urutans_gbj;
            $datas->no_referensi = $urutans_prd . '/' . $this->toRomawi(Carbon::createFromFormat('Y-m-d', $x->tgl_mulai)->month) . '/' . Carbon::createFromFormat('Y-m-d', $x->tgl_mulai)->year;
            $datas->diserahkan_oleh = $data->user_id != NULL ? User::find($data->user_id)->Karyawan->nama : '-';
            $datas->items = $result;
        }
        return $datas;
    }

    function cetakSuratPengantar($id)
    {
        $data = $this->surat_pengiriman($id);
        $pdf = PDF::loadview('page.produksi.printreworks.cetakpengantarbarangjadi', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->stream();
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

    function riwayat_rw_permintaan()
    {
        $data = SystemLog::where(['tipe' => 'GBJ', 'subjek' => 'Kirim Permintaan Rework'])->orderBy('created_at', 'ASC')->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $noseriCount = 0;
                $x = json_decode($d->response);
                foreach ($x->produk as $produk) {
                    // Check if the "noseri" key exists and is an array
                    if (isset($produk->noseri) && is_array($produk->noseri)) {
                        // Increment the noseri count by the number of entries in the "noseri" array
                        $noseriCount += count($produk->noseri);
                    }
                }

                $obj[] = array(
                    'id' => $d->id,
                    'urutan' => $x->urutan,
                    'nama' => $x->nama,
                    'tgl_mulai' => $x->tgl_mulai,
                    'tgl_selesai' => $x->tgl_selesai,
                    'tgl_tf' => $d->created_at,
                    'jumlah' => $noseriCount
                );
            }
        }



        // $object = new stdClass();
        // $object->produk_reworks_id = $jadwal->produk_reworks_id;
        // $object->set = $jadwal->set;


        return response()->json($obj);
    }
    function belum_kirim_rw()
    {
        $data = JadwalPerakitanRw::addSelect([
            'ctfgbj' => function ($q) {
                $q->selectRaw('coalesce(count(jadwal_rakit_noseri_rw.id), 0)')
                    ->from('jadwal_perakitan_rw as jp')
                    ->leftJoin('jadwal_rakit_noseri_rw', 'jp.id', '=', 'jadwal_rakit_noseri_rw.jadwal_id')
                    ->whereColumn('jp.urutan', 'jadwal_perakitan_rw.urutan')
                    ->whereColumn('jp.produk_reworks_id', 'jadwal_perakitan_rw.produk_reworks_id');
            },
            'cset' => function ($q) {
                $q->selectRaw('coalesce(count(detail_produks_rw.id), 0) * jadwal_perakitan_rw.jumlah ')
                    ->from('detail_produks_rw')
                    ->whereColumn('detail_produks_rw.produk_parent_id', 'jadwal_perakitan_rw.produk_reworks_id');
            },
        ])
            ->havingRaw('ctfgbj != cset')
            ->where('state', 18)
            ->where('status_tf', 16)
            ->groupBy('urutan')->get();
        if ($data->isempty()) {
            $obj = array();
        } else {


            foreach ($data as $d) {

                switch ($d->status_tf) {
                    case "11":
                        $status =  "Belum Dikirim";
                        break;
                    case "16":
                        $status = "Proses";
                        break;
                    default:
                        $status = "Error";
                }


                $obj[] = array(
                    'id' => $d->id,
                    'urutan' => $d->urutan,
                    'no' => $d->no_permintaan,
                    'produk_reworks_id' => $d->produk_reworks_id,
                    'tgl_mulai' => $d->tanggal_mulai,
                    'tgl_selesai' => $d->tanggal_selesai,
                    'nama' => $d->ProdukRw->nama,
                    'jumlah' => $d->jumlah,
                    'status' => $status,
                    'belum' => $d->cset - $d->ctfgbj,
                    'selesai' => $d->ctfgbj
                );
            }
        }

        return response()->json($obj);
    }
    function updateStokGudang()
    {
        // $d = NoseriBarangJadi::whereHas('gudang', function ($q) use ($id) {
        //     $q->where('gdg_barang_jadi_id', $id);
        // })->where('is_aktif', 1)->count();
        // $a = NoseriBarangJadi::whereHas('gudang', function ($q) use ($id) {
        //     $q->where('gdg_barang_jadi_id', $id);
        // })->where('is_aktif', 1)->where('is_ready', 0)->count();
        //         GudangBarangJadi::find($id)->update(['stok' => $d, 'stok_siap' => $a]);

        $data = NoseriBarangJadi::count()->where('is_aktif');
        return response()->json(['id' => 1]);
    }
    // get
    function get_rekap_so_produk()
    {
        try {
            $data = GudangBarangJadi::select(DB::raw('concat(p.nama," ",gdg_barang_jadi.nama) as produkk'), 'gdg_barang_jadi.id')
                ->leftJoin('produk as p', 'p.id', '=', 'gdg_barang_jadi.produk_id')
                ->addSelect([
                    'count_transfer' => function ($query) {
                        $query->selectRaw('count(t_gbj_noseri.id)')
                            ->from('t_gbj_noseri')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->leftjoin('pesanan', 'pesanan.id', '=', 't_gbj.pesanan_id')
                            ->whereNotIn('pesanan.log_id', ["7", "10", "20"])
                            ->whereColumn('t_gbj_detail.gdg_brg_jadi_id', 'gdg_barang_jadi.id')
                            ->limit(1);
                    },
                    'count_ekat_sepakat' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                            ->limit(1);
                    },
                    'count_ekat_po' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereNotNull('pesanan.so')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereRaw('pesanan.log_id not in ("7", "10","20") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                            ->limit(1);
                    },
                    'count_ekat_nego' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                            ->limit(1);
                    }, 'count_ekat_draft' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                            ->limit(1);
                    },
                    'count_spa_po' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id not in (7, 10,20) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                            ->limit(1);
                    }, 'count_spb_po' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id not in (7, 10,20) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                            ->limit(1);
                    }
                ])
                ->has('DetailPesananProduk')
                // ->havingRaw('(coalesce(count_ekat_sepakat, 0) + coalesce(count_ekat_po,0) + coalesce(count_spa_po, 0) + coalesce(count_spb_po, 0)) != 0')
                // ->havingRaw('(coalesce(count_ekat_sepakat, 0) + coalesce(count_ekat_po,0) + coalesce(count_spa_po, 0) + coalesce(count_spb_po, 0)) > count_transfer')
                ->havingRaw('(coalesce(count_ekat_sepakat, 0) + coalesce(count_ekat_nego, 0) + coalesce(count_ekat_draft, 0) + coalesce(count_ekat_po, 0) + coalesce(count_spa_po, 0) + coalesce(count_spb_po, 0)) != 0')
                ->orderBy(DB::raw('concat(p.nama," ",gdg_barang_jadi.nama)'))
                ->get()->map(function ($item) {
                    $item->permintaan = intval($item->count_ekat_sepakat) + intval($item->count_ekat_po) + intval($item->count_ekat_nego) + intval($item->count_ekat_draft) + intval($item->count_spa_po) + intval($item->count_spb_po);
                    $item->sisa = $item->permintaan - intval($item->count_transfer);
                    return $item;
                });

            return response()->json($data);

            $dt = datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('permintaan', function ($d) {
                    $minta = intval($d->count_ekat_sepakat) + intval($d->count_ekat_po) + intval($d->count_ekat_nego) + intval($d->count_ekat_draft) + intval($d->count_spa_po) + intval($d->count_spb_po);
                    return $minta;
                })
                ->editColumn('sisa', function ($d) {
                    $minta = intval($d->count_ekat_sepakat) + intval($d->count_ekat_po) + intval($d->count_ekat_nego) + intval($d->count_ekat_draft) + intval($d->count_spa_po) + intval($d->count_spb_po);
                    $tf = intval($d->count_transfer);
                    return $minta - $tf;
                })
                ->editColumn('transfer', function ($d) {
                    return intval($d->count_transfer);
                })
                ->editColumn('aksi', function ($d) {
                    $a = '<button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-id="' . $d->id . '" class="btn btn-outline-info btn-sm detailBrg"><i class="far fa-eye"></i> Detail</button>';
                    return $a;
                })
                ->rawColumns(['aksi'])
                ->make(true);

            return $dt;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function get_detail_rekap_so_produk($id)
    {
        try {
            function customer($data)
            {
                if ($data->Ekatalog) {
                    if (isset($data->Ekatalog->Customer)) {
                        return $data->Ekatalog->Customer->nama;
                    }
                } else if ($data->Spa) {
                    if (isset($data->Spa->Customer)) {
                        return $data->Spa->Customer->nama;
                    }
                } else {
                    if (isset($data->Spb->Customer)) {
                        return $data->Spb->Customer->nama;
                    }
                }
            }

            $data = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
                $q->where('id', $id);
            })->addSelect([
                'count_pesanan' => function ($q) use ($id) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('gdg_barang_jadi', 'gdg_barang_jadi.produk_id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereRaw('gdg_barang_jadi.id = ' . $id . ' AND detail_pesanan_produk.gudang_barang_jadi_id = ' . $id)
                        // ->whereNotNull('pesanan.no_po')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'count_transfer' => function ($q) use ($id) {
                    $q->selectRaw('count(t_gbj_noseri.id)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', 't_gbj_detail.t_gbj_id')
                        // ->where('t_gbj_noseri.jenis', '"keluar"')
                        ->where('t_gbj_detail.gdg_brg_jadi_id', $id)
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->whereNotIn('log_id', ['10', '20'])
                ->whereNotNull('pesanan.so')
                // ->havingRaw('count_pesanan > count_transfer')
                ->get()->map(function ($item) {
                    $item->customer = customer($item);
                    return $item;
                });

            return response()->json($data);

            $dt = datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('jumlah', function ($data) {
                    $jumlah = $data->count_pesanan;
                    return $jumlah;
                })
                ->addColumn('status', function ($data) {
                    $hitung = $data->count_transfer;
                    return $hitung;
                })
                ->addColumn('so', function ($data) {
                    if (!empty($data->so)) {
                        return $data->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('po', function ($data) {
                    if (!empty($data->no_po)) {
                        return $data->no_po;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('customer', function ($data) {
                    if ($data->Ekatalog) {
                        if (isset($data->Ekatalog->Customer)) {
                            return $data->Ekatalog->Customer->nama;
                        }
                    } else if ($data->Spa) {
                        if (isset($data->Spa->Customer)) {
                            return $data->Spa->Customer->nama;
                        }
                    } else {
                        if (isset($data->Spb->Customer)) {
                            return $data->Spb->Customer->nama;
                        }
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if (isset($data->Ekatalog)) {
                        if ($data->status != 'draft') {
                            return  '<a data-toggle="modal" data-target="ekatalog" class="penjualanmodal" data-value="ekatalog"  data-id="' . $data->id . '">
                                  <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                        }
                    } else if (isset($data->Spa)) {
                        return  '<a data-toggle="modal" data-target="spa" class="penjualanmodal" data-value="spa"  data-id="' . $data->id . '">
                                  <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    } else {
                        return  '<a data-toggle="modal" data-target="spb" class="penjualanmodal" data-value="spb"  data-id="' . $data->id . '">
                                  <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    }
                })
                ->rawColumns(['aksi'])
                ->make(true);

            return $dt;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    public function get_data_barang_jadi(Request $request)
    {
        // dd($request->all());
        try {
            $data = GudangBarangJadi::leftJoin('produk as p', 'p.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftJoin('m_satuan as s', 's.id', '=', 'gdg_barang_jadi.satuan_id')
                ->leftJoin('kelompok_produk as kp', 'kp.id', '=', 'p.kelompok_produk_id')
                ->select(DB::raw('concat(p.nama," ", gdg_barang_jadi.nama) as produkk'), 'p.merk', 'kp.nama as kel_produk', 'gdg_barang_jadi.id', 's.nama as satuan', 'gdg_barang_jadi.stok', 'gdg_barang_jadi.stok_siap')
                ->orderBy(DB::raw('concat(p.nama," ", gdg_barang_jadi.nama)'))
                ->addSelect(['count_barang' => function ($query) {
                    $query->selectRaw('count(noseri_barang_jadi.id)')
                        ->from('noseri_barang_jadi')
                        ->where('noseri_barang_jadi.is_ready', '0')
                        ->where('noseri_barang_jadi.is_aktif', '1')
                        ->whereColumn('noseri_barang_jadi.gdg_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->limit(1);
                }, 'count_ekat_sepakat' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                        ->limit(1);
                }, 'count_ekat_nego' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                        ->limit(1);
                }, 'count_ekat_draft' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7)  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                        ->limit(1);
                }, 'count_ekat_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                        ->limit(1);
                }, 'count_spa_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }, 'count_spb_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }])->with('Produk')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    return $data->produkk;
                })
                ->addColumn('kode_produk', function ($data) {
                    // return $data->produk->product->kode . '' . $data->produk->kode;
                    return '-';
                })
                ->addColumn('jumlah', function ($data) {
                    //   $this->updateStokGudang($data->id);
                    // return $data->stok . ' ' . $data->satuan . '<br><span class="badge badge-dark">Stok Siap: ' . $data->count_barang . ' ' . $data->satuan . '</span>';
                    return $data->count_barang;
                })
                ->addColumn('jumlah1', function ($data) {
                    $ss = $data->count_ekat_sepakat + $data->count_ekat_nego + $data->count_ekat_draft + $data->count_spa_po + $data->count_spb_po + $data->count_ekat_po;
                    return $data->stok - $ss . ' ' . $data->satuan;
                })
                ->addColumn('kelompok', function ($data) {
                    return $data->kel_produk;
                })
                ->addColumn('merk', function ($data) {
                    return $data->merk;
                })
                ->addColumn('action', function ($data) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Edit
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#stokmodal" class="stokmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-warning btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Daftar Stok
                                </button>
                            </a>';
                })
                ->addColumn('action_direksi', function ($data) {
                    return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'action_direksi', 'jumlah'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function GetBarangJadiByID(Request $request)
    {
        try {
            $data = GudangBarangJadi::with('produk', 'satuan')->where('id', $request->id)->get();
            $dataid = $data->pluck('produk_id');
            $datas = Produk::with('product')->where('id', $dataid)->get();
            return response()->json([
                'data' => $data,
                'nama_produk' => $datas
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getNoseri(Request $request, $id)
    {
        try {
            $data = NoseriBarangJadi::select('noseri_barang_jadi.id', 'noseri_barang_jadi.noseri', 'noseri_barang_jadi.layout_id', 'seri_detail_rw.isi as isi', 'seri_detail_rw.created_at', 'seri_detail_rw.packer')
                ->addSelect([
                    'cek_rw' => function ($q) {
                        $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                            ->from('seri_detail_rw')
                            ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                    }
                ])
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->where([
                    'is_aktif' => 1,
                    'is_ready' => 0,
                    'is_change' => 1,
                    'is_delete' => 0,
                    'gdg_barang_jadi_id' => $id
                ])->get();


            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('ids', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('seri', function ($d) {
                    return '<input type="hidden" class="form-control" id="noseriOri[]" value="' . $d->noseri . '"><input type="text" class="form-control" id="noseri[]" value="' . $d->noseri . '" disabled>';
                })
                ->addColumn('nomor', function ($d) {
                    return $d->noseri;
                })
                ->addColumn('item', function ($d) {
                    if ($d->isi == null) {
                        return  array();
                    } else {
                        return json_decode($d->isi);
                    }
                })
                ->addColumn('Layout', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->rawColumns(['ids', 'Layout', 'aksi', 'seri'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getNoseriDone(Request $request, $id)
    {
        try {

            $data = NoseriBarangJadi::where([
                'is_aktif' => 1,
                'is_ready' => 1,
                'is_change' => 1,
                'is_delete' => 0
            ])->with(['Pesanan'])->where('gdg_barang_jadi_id', $id)->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('ids', function ($d) {
                    return '<input type="checkbox" class="cb-child1" value="' . $d->id . '">';
                })
                ->addColumn('seri', function ($d) {
                    return '<input type="hidden" class="form-control" id="noseriOri[]" value="' . $d->noseri . '"><input type="text" class="form-control" id="noseri[]" value="' . $d->noseri . '" disabled>';
                })
                ->addColumn('nomor', function ($d) {
                    return $d->noseri;
                })
                ->addColumn('used', function ($d) {
                    if ($d->reworks_id != NULL) {
                        return 'Perakitan Rework ' . $d->reworks_id;
                    } else {
                        if (isset($d->Pesanan->so)) {
                            return $d->Pesanan->so;
                        } else {
                            return '-';
                        }
                    }
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
                })
                ->rawColumns(['ids', 'Layout', 'aksi', 'seri'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getHistory($id)
    {
        try {
            $data = NoseriBarangJadi::with('from', 'to')->where('noseri', $id)->get()->unique(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($d) {
                    return Carbon::parse($d->created_at)->isoFormat('dddd, D MMMM Y');
                })
                ->addColumn('dari', function ($d) {
                    return $d->from->nama;
                })->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getHistorybyProduk()
    {
        try {
            $data = GudangBarangJadi::distinct()
                ->select(
                    'gdg_barang_jadi.id',
                    DB::raw("concat(p.nama,' ',gdg_barang_jadi.nama) as produkk"),
                    DB::raw("concat(gdg_barang_jadi.stok,'',ms.nama) as stok_gudang"),
                    'gdg_barang_jadi.stok',
                    'kp.nama as kel_produk',
                    'ms.nama as satuan'
                )
                ->leftjoin(DB::raw('t_gbj_detail tgd'), 'tgd.gdg_brg_jadi_id', '=', 'gdg_barang_jadi.id')
                ->join(DB::raw('produk as p'), 'p.id', '=', 'gdg_barang_jadi.produk_id')
                ->join(DB::raw('kelompok_produk as kp'), 'kp.id', '=', 'p.kelompok_produk_id')
                ->join(DB::raw('m_satuan as ms'), 'ms.id', '=', 'gdg_barang_jadi.satuan_id')
                ->whereNotNull('tgd.gdg_brg_jadi_id')
                ->orderBy(DB::raw("concat(p.nama,' ',gdg_barang_jadi.nama)"), 'ASC')
                ->addSelect(['count_barang' => function ($query) {
                    $query->selectRaw('count(noseri_barang_jadi.id)')
                        ->from('noseri_barang_jadi')
                        ->where('noseri_barang_jadi.is_ready', '0')
                        ->whereColumn('noseri_barang_jadi.gdg_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->limit(1);
                }, 'count_ekat_sepakat' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                        ->limit(1);
                }, 'count_ekat_nego' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                        ->limit(1);
                }, 'count_ekat_draft' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7)  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                        ->limit(1);
                }, 'count_ekat_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                        ->limit(1);
                }, 'count_spa_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }, 'count_spb_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }])->with('Produk')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('stock', function ($d) {
                    return $d->stok . ' ' . $d->satuan;
                })
                ->addColumn('stok_jual', function ($data) {
                    if ($data->id) {
                        $ss = $data->count_ekat_sepakat + $data->count_ekat_nego + $data->count_ekat_draft + $data->count_spa_po + $data->count_spb_po + $data->count_ekat_po;
                        return $data->stok - $ss . ' ' . $data->satuan;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->kel_produk;
                })
                ->addColumn('product', function ($d) {
                    return $d->produkk;
                })
                ->addColumn('kode_produk', function ($d) {
                    return '-';
                })
                ->addColumn('action', function ($d) {
                    return '<a class="btn btn-outline-primary" href="' . url('gbj/tp/' . $d->id . '') . '"><i
                        class="far fa-eye"></i> Detail</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getAllTransaksi()
    {
        try {
            $data1 = DB::table('t_gbj_noseri')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('t_gbj as h', 'h.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('gdg_barang_jadi as g', 'g.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                ->leftjoin('m_satuan as satuan', 'satuan.id', '=', 'g.satuan_id')
                ->leftjoin('produk as prd', 'prd.id', '=', 'g.produk_id')
                ->leftjoin('pesanan as p', 'p.id', '=', 'h.pesanan_id')
                ->leftjoin('m_state as stt', 'stt.id', '=', 'p.log_id')
                ->leftjoin('divisi as d', 'd.id', '=', 'h.dari')
                ->leftjoin('divisi as dd', 'dd.id', '=', 'h.ke')
                ->select('p.so as so', 'h.batal_pesanan_id', 'h.retur_pesanan_id', 't_gbj_noseri.created_at as tgl_keluar', 'h.pesanan_id as p_id', 'h.tgl_masuk', 'h.jenis', 't_gbj_detail.qty', 'dd.nama as ke', 'd.nama as dari', DB::raw('concat(prd.nama, " ", g.nama) as produkk'),   DB::raw('COUNT(t_gbj_noseri.id) as qty'), 't_gbj_detail.id', DB::raw('group_concat(t_gbj_noseri.id) as id_seri'), (DB::raw("DATE_FORMAT(t_gbj_noseri.created_at, '%Y-%m-%d') as tgl_keluar_seri")))
                ->orderByDesc('t_gbj_noseri.created_at')
                ->groupBy(DB::raw("DATE_FORMAT(t_gbj_noseri.created_at, '%d-%m-%Y')"), "t_gbj_noseri.t_gbj_detail_id")
                ->get();


            $g = datatables()->of($data1)
                ->addIndexColumn()
                // ->addColumn('so', function ($d) {
                //     if (isset($d->so)) {
                //         return $d->so;
                //     } else {
                //         return '-';
                //     }
                // })
                // ->addColumn('po', function ($d) {
                //     if (isset($d->no_po)) {
                //         return $d->no_po;
                //     } else {
                //         return '-';
                //     }
                // })
                // ->addColumn('logs', function($d) {
                //     if (isset($d->so)) {
                //         if ($d->log_id == 9) {
                //             $ax = "<span class='badge badge-pill badge-secondary'>".$d->nama."</span>";
                //         } else if ($d->log_id == 6) {
                //             $ax = "<span class='badge badge-pill badge-warning'>".$d->nama."</span>";
                //         } elseif ($d->log_id == 8) {
                //             $ax = "<span class='badge badge-pill badge-info'>".$d->nama."</span>";
                //         } elseif ($d->log_id == 11) {
                //             $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                //         } elseif ($d->log_id == 10) {
                //             $ax = "<span class='badge badge-pill badge-success'>".$d->nama."</span>";
                //         } else {
                //             $ax = "<span class='badge badge-pill badge-danger'>".$d->nama."</span>";
                //         }

                //         return $ax;
                //     } else {
                //         return '-';
                //     }
                // })
                ->addColumn('so', function ($d) {
                    if ($d->batal_pesanan_id != NULL) {
                        $data = Pesanan::join('riwayat_batal_po as rpo', 'rpo.pesanan_id', '=', 'pesanan.id')
                            ->where('rpo.id', $d->batal_pesanan_id)
                            ->first()->no_po;
                        return $data;
                    } else if ($d->retur_pesanan_id != NULL) {
                        $data = RiwayatReturPo::where('id', $d->retur_pesanan_id)->first()->no_retur;
                        return $data;
                    } else {
                        return $d->p_id != NULL ? $d->so : '-';
                    }
                })
                ->addColumn('date_in', function ($d) {
                    if (isset($d->tgl_masuk)) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('DD-MM-Y');
                    } else {
                        return "-";
                    }
                })
                ->addColumn('date_out', function ($d) {
                    if (isset($d->tgl_keluar)) {
                        return Carbon::parse($d->tgl_keluar)->isoFormat('DD-MM-Y');
                    } else {
                        return "-";
                    }
                })
                ->addColumn('divisi', function ($d) {
                    // if ($d->jenis == 'keluar') {
                    //     return '<span class="badge badge-info">' . $d->ke . '</span>';
                    // } else {
                    return '<span class="badge badge-success">' . $d->dari . '</span>';
                    // }
                })
                ->addColumn('tujuan', function ($d) {
                    return $d->dari == NULL ? '<span class="badge badge-success">' . $d->ke . '</span>' : '';
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' Unit';
                })
                ->addColumn('product', function ($d) {
                    return $d->produkk;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-tanggal ="' . $d->tgl_keluar_seri . '" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '" >
                    <button class="btn btn-outline-primary"><i
                    class="far fa-eye"></i> Detail</button>
                            </a>';
                })
                ->rawColumns(['divisi', 'action', 'logs', 'tujuan'])
                ->make(true);

            return $g;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }

        // try {
        //     $data1 = DB::table('t_gbj_detail')
        //         ->leftjoin('t_gbj as h', 'h.id', '=', 't_gbj_detail.t_gbj_id')
        //         ->leftjoin('gdg_barang_jadi as g', 'g.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
        //         ->leftjoin('m_satuan as satuan', 'satuan.id', '=', 'g.satuan_id')
        //         ->leftjoin('produk as prd', 'prd.id', '=', 'g.produk_id')
        //         ->leftjoin('pesanan as p', 'p.id', '=', 'h.pesanan_id')
        //         ->leftjoin('m_state as stt', 'stt.id', '=', 'p.log_id')
        //         ->leftjoin('divisi as d', 'd.id', '=', 'h.dari')
        //         ->leftjoin('divisi as dd', 'dd.id', '=', 'h.ke')
        //         // ->select('p.so', 'p.no_po', 'p.log_id', 'h.tgl_masuk', 'h.tgl_keluar', 'h.jenis', 'h.deskripsi', 't_gbj_detail.qty', 'stt.nama', 'd.nama as dari', 'dd.nama as ke', DB::raw('concat(prd.nama, " ", g.nama) as produkk'), 't_gbj_detail.id')
        //         ->select('p.no_po as po', 'h.tgl_keluar', 'h.pesanan_id as p_id', 'h.tgl_masuk', 'h.jenis', 't_gbj_detail.qty', 'dd.nama as ke', 'd.nama as dari', DB::raw('concat(prd.nama, " ", g.nama) as produkk'),     DB::raw('(select count(id) from t_gbj_noseri where t_gbj_detail_id = t_gbj_detail.id )  as qty'), 't_gbj_detail.id')
        //         // ->where('h.jenis', '=', 'keluar')
        //         ->orderByDesc('h.created_at')
        //         ->get();
        //     $g = datatables()->of($data1)
        //         ->addIndexColumn()
        //         // ->addColumn('so', function ($d) {
        //         //     if (isset($d->so)) {
        //         //         return $d->so;
        //         //     } else {
        //         //         return '-';
        //         //     }
        //         // })
        //         // ->addColumn('po', function ($d) {
        //         //     if (isset($d->no_po)) {
        //         //         return $d->no_po;
        //         //     } else {
        //         //         return '-';
        //         //     }
        //         // })
        //         // ->addColumn('logs', function($d) {
        //         //     if (isset($d->so)) {
        //         //         if ($d->log_id == 9) {
        //         //             $ax = "<span class='badge badge-pill badge-secondary'>".$d->nama."</span>";
        //         //         } else if ($d->log_id == 6) {
        //         //             $ax = "<span class='badge badge-pill badge-warning'>".$d->nama."</span>";
        //         //         } elseif ($d->log_id == 8) {
        //         //             $ax = "<span class='badge badge-pill badge-info'>".$d->nama."</span>";
        //         //         } elseif ($d->log_id == 11) {
        //         //             $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
        //         //         } elseif ($d->log_id == 10) {
        //         //             $ax = "<span class='badge badge-pill badge-success'>".$d->nama."</span>";
        //         //         } else {
        //         //             $ax = "<span class='badge badge-pill badge-danger'>".$d->nama."</span>";
        //         //         }

        //         //         return $ax;
        //         //     } else {
        //         //         return '-';
        //         //     }
        //         // })
        //         ->addColumn('po', function ($d) {
        //             return $d->p_id != NULL ? $d->po : '-';
        //         })
        //         ->addColumn('date_in', function ($d) {
        //             if (isset($d->tgl_masuk)) {
        //                 return Carbon::parse($d->tgl_masuk)->isoFormat('D MMMM Y');
        //             } else {
        //                 return "-";
        //             }
        //         })
        //         ->addColumn('date_out', function ($d) {
        //             if (isset($d->tgl_keluar)) {
        //                 return Carbon::parse($d->tgl_keluar)->isoFormat('D MMMM Y');
        //             } else {
        //                 return "-";
        //             }
        //         })
        //         ->addColumn('divisi', function ($d) {
        //             // if ($d->jenis == 'keluar') {
        //             //     return '<span class="badge badge-info">' . $d->ke . '</span>';
        //             // } else {
        //             return '<span class="badge badge-success">' . $d->dari . '</span>';
        //             // }
        //         })
        //         ->addColumn('tujuan', function ($d) {
        //             return $d->dari == NULL ? '<span class="badge badge-success">' . $d->ke . '</span>' : '';
        //         })
        //         ->addColumn('jumlah', function ($d) {
        //             return $d->qty . ' Unit';
        //         })
        //         ->addColumn('product', function ($d) {
        //             return $d->produkk;
        //         })
        //         ->addColumn('action', function ($d) {
        //             return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
        //         <button class="btn btn-outline-primary"><i
        //         class="far fa-eye"></i> Detail</button>
        //                 </a>';
        //         })
        //         ->rawColumns(['divisi', 'action', 'logs', 'tujuan'])
        //         ->make(true);

        //     return $g;
        // } catch (\Exception $e) {
        //     return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        // }
    }

    function getDetailAll($id, $tanggal)
    {
        try {
            $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->whereDate('created_at', $tanggal)->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('layout', function ($d) {
                    if (isset($d->layout->ruang)) {
                        return $d->layout->ruang;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('seri', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->rawColumns(['checkbox', 'layout'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistory($id)
    {
        try {
            $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();

            return datatables()->of($data1)
                ->addIndexColumn()
                ->addColumn('so', function ($d) {
                    if (isset($d->header->pesanan_id)) {
                        return $d->header->pesanan->so;
                    } else {
                        if ($d->header->retur_pesanan_id != '' || $d->header->batal_pesanan_id) {
                            return $d->header->deskripsi;
                        } else {
                            return '-';
                        }
                    }
                })
                ->addColumn('logs', function ($d) {
                    if (isset($d->header->pesanan_id)) {
                        if ($d->header->pesanan->log_id == 9) {
                            $ax = "<span class='badge badge-pill badge-secondary'>" . $d->header->pesanan->log->nama . "</span>";
                        } else if ($d->header->pesanan->log_id == 6) {
                            $ax = "<span class='badge badge-pill badge-warning'>" . $d->header->pesanan->log->nama . "</span>";
                        } elseif ($d->header->pesanan->log_id == 8) {
                            $ax = "<span class='badge badge-pill badge-info'>" . $d->header->pesanan->log->nama . "</span>";
                        } elseif ($d->header->pesanan->log_id == 11) {
                            $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                        } elseif ($d->header->pesanan->log_id == 10) {
                            $ax = "<span class='badge badge-pill badge-success'>" . $d->header->pesanan->log->nama . "</span>";
                        } else {
                            $ax = "<span class='badge badge-pill badge-danger'>" . $d->header->pesanan->log->nama . "</span>";
                        }

                        return $ax;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('date_in', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D-MM-YYYY');
                    } else {
                        return "-";
                    }
                })
                ->addColumn('date_out', function ($d) {
                    if (isset($d->header->tgl_keluar)) {
                        return date('d-m-Y', strtotime($d->header->tgl_keluar));
                    } else {
                        return "-";
                    }
                })
                ->addColumn('divisi', function ($d) {
                    if ($d->header->jenis == 'keluar') {
                        return '<span class="badge badge-info">' . $d->header->divisi->nama . '</span>';
                    } else {
                        return '<span class="badge badge-success">' . $d->header->darii->nama . '</span>';
                    }
                })
                ->addColumn('tujuan', function ($d) {

                    if ($d->header->retur_pesanan_id != '' || $d->header->batal_pesanan_id) {
                        return '-';
                    } else {
                        return $d->header->deskripsi;
                    }

                    // if ($d->header->retur_pesanan_id) {
                    //     // return $d->header->deskripsi;
                    //     // $r = RiwayatReturPo::find($d->header->retur_pesanan_id);
                    //     return 'Retur';
                    // } elseif ($d->header->batal_pesanan_id) {
                    //     // $b = RiwayatBatalPo::find($d->header->batal_pesanan_id);
                    //     // return Pesanan::find($b->pesanan_id)->no_po;
                    //     return 'Batal';
                    // } else {
                    //     return $d->header->deskripsi;
                    // }
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
                })
                ->rawColumns(['divisi', 'action', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistorySeri($id)
    {
        try {
            $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noser', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) {
                    return $d->layout_id == null ? '-' : $d->layout->ruang;
                    // return $d
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistory1($id)
    {
        $header = GudangBarangJadi::with('produk')->where('id', $id)->first();
        $data = GudangBarangJadi::with('produk')->where('id', $id)->get();
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();
        return view('page.gbj.tp.show', compact('data', 'data1', 'header'));
    }

    function getNonSODone()
    {
        try {
            $data = TFProduksi::leftJoin('divisi as p', 'p.id', '=', 't_gbj.ke')
                ->leftJoin('t_gbj_detail as tgd', 'tgd.t_gbj_id', '=', 't_gbj.id')
                ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin('produk as pp', 'pp.id', '=', 'gbj.produk_id')
                ->where([
                    ['t_gbj.jenis', '=', 'keluar'],
                    // ['t_gbj.status_id', '=', 2],
                ])->whereNull('t_gbj.pesanan_id')
                ->selectRaw('t_gbj.id, tgd.gdg_brg_jadi_id,
                                concat(pp.nama," ",gbj.nama) as produkk, sum(tgd.qty) as qty')
                ->groupBy('gbj.id')
                ->orderByRaw('concat(pp.nama," ",gbj.nama)')
                ->get();
            $dt = datatables()->of($data)
                ->addIndexColumn()
                // ->editColumn('tgl_keluar', function($d){
                //     return Carbon::parse($d->tgl_keluar)->isoFormat('D MMM YYYY');
                // })
                ->editColumn('qty', function ($d) {
                    return $d->qty . ' Unit';
                })
                ->editColumn('aksi', function ($d) {
                    return '<a href="export_nonso/' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary"><i class="fas fa-eye"></i> Cetak</button>
                        </a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);

            return $dt;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function exportNonso($id)
    {
        try {
            $data = TFProduksiDetail::leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
                ->leftJoin('divisi as p', 'p.id', '=', 'tg.ke')
                ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                ->leftJoin('produk as pp', 'pp.id', '=', 'gbj.produk_id')
                ->where([
                    ['tg.jenis', '=', 'keluar'],
                ])->whereNull('tg.pesanan_id')
                ->selectRaw('p.nama as nm_divisi, tg.tgl_keluar, tg.deskripsi, tg.id as tgdid, t_gbj_detail.id,
                                concat(pp.nama," ",gbj.nama) as produkk, t_gbj_detail.qty')
                ->where("t_gbj_detail.gdg_brg_jadi_id", "=", $id)
                ->first();

            return Excel::download(new NonsoExport($id), 'Laporan Tanpa SO ' . $data->produkk . '.xlsx');
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function get_data_waiting_approve(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting'
            ])->whereHas('noseri', function ($d) use ($request) {
                $d->where('gdg_barang_jadi_id', $request->gbjid);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" id="noseriid" name="id" value="' . $d->noseri_id . '">';
                })
                ->addColumn('noseri_lama', function ($d) {
                    return $d->data_lama;
                })
                ->addColumn('noseri', function ($d) {
                    return $d->data_baru == null ? '-' : $d->data_baru;
                })
                ->editColumn('action', function ($d) {
                    return $d->action == 'delete' ? '<span class="badge badge-danger">Hapus</span>' : '<span class="badge badge-info">Ubah</span>';
                })
                ->addColumn('requested', function ($d) {
                    return $d->actionn->nama;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function delete_noseri(Request $request)
    {
        try {
            // dd($request->all());
            $check = NoseriTGbj::whereIn('noseri_id', $request->noseriid);
            $dataseri = [];

            foreach ($check->get() as $c) {
                $nbj = NoseriBarangJadi::find($c->noseri_id);
                array_push($dataseri, $nbj->is_ready);
            }
            if (count($dataseri) == 0) {
                $cek = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                foreach ($cek as $cc) {
                    $nbjj = NoseriBarangJadi::find($cc->id);
                    // return $nbjj;
                    NoseriBrgJadiLog::create([
                        'gbj_id' => $request->gbjid,
                        'noseri_id' => $cc->id,
                        'data_lama' => $nbjj->noseri,
                        'action' => 'delete',
                        'action_by' => $request->actionby,
                        'status' => 'waiting',
                        'remark' => $request->alasan
                    ]);
                    NoseriBarangJadi::find($cc->id)->update(['is_change' => 0, 'is_delete' => 1]);
                }
                return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manajer']);
            } else {
                if (empty(array_filter($dataseri))) {
                    if (count($check->get()) > 0) {
                        foreach ($check->get() as $d) {
                            $nbjj = NoseriBarangJadi::find($d->noseri_id);

                            NoseriBrgJadiLog::create([
                                'gbj_id' => $request->gbjid,
                                'noseri_id' => $d->noseri_id,
                                'data_lama' => $nbjj->noseri,
                                'action' => 'delete',
                                'action_by' => $request->actionby,
                                'status' => 'waiting',
                                'remark' => $request->alasan
                            ]);
                            NoseriBarangJadi::find($d->noseri_id)->update(['is_change' => 0, 'is_delete' => 1]);
                        }
                    }
                    return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manajer']);
                } else {
                    return response()->json(['error' => true, 'msg' => 'Noseri Ada yang Sedang Digunakan']);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_approve_noseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where([
                'is_change' => 0,
                'is_delete' => 1
            ])->groupBy('gdg_barang_jadi_id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('merk', function ($d) {
                    return $d->gudang->produk->merk;
                })
                ->addColumn('produk', function ($d) {
                    return $d->gudang->nama == null ? $d->gudang->produk->nama : $d->gudang->produk->nama . ' <b>' . $d->gudang->nama . '</b>';
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#deletemodal" class="deletemodal" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-eye"></i> Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_update_noseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where([
                'is_change' => 0,
                'is_delete' => 0
            ])->groupBy('gdg_barang_jadi_id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('merk', function ($d) {
                    return $d->gudang->produk->merk;
                })
                ->addColumn('produk', function ($d) {
                    return $d->gudang->nama == null ? $d->gudang->produk->nama : $d->gudang->produk->nama . ' <b>' . $d->gudang->nama . '</b>';
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-eye"></i> Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detail_list_delete_noseri(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting',
                'action' => 'delete'
            ])->whereHas('noseri', function ($d) use ($request) {
                $d->where('gdg_barang_jadi_id', $request->gbj);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child1" id="noseriid" name="id" value="' . $d->noseri_id . '">';
                })
                ->addColumn('produk', function ($d) {
                    return $d->noseri->gudang->nama == null ? $d->noseri->gudang->produk->nama : $d->noseri->gudang->produk->nama . ' <b>' . $d->noseri->gudang->nama . '</b>';
                })
                ->addColumn('merk', function ($d) {
                    return $d->noseri->gudang->produk->merk;
                })
                ->addColumn('noseri', function ($d) {
                    return $d->noseri->noseri;
                })
                ->addColumn('requested', function ($d) {
                    return $d->actionn->nama;
                })
                ->addColumn('tgl_aju', function ($d) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('action', function ($d) {
                    return '<button class="btn btn-outline-success btn-sm btnAlasan" type="button" data-id="' . $d->id . '">
                            <i class="far fa-eye"></i> Detail
                            </button>';
                })
                ->rawColumns(['checkbox', 'produk', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detail_list_update_noseri(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting',
                'action' => 'update',
            ])->whereHas('noseri', function ($d) use ($request) {
                $d->where('gdg_barang_jadi_id', $request->gbj);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" id="noseriid" name="id" value="' . $d->noseri_id . '">';
                })
                ->addColumn('produk', function ($d) {
                    return $d->noseri->gudang->nama == null ? $d->noseri->gudang->produk->nama : $d->noseri->gudang->produk->nama . ' <b>' . $d->noseri->gudang->nama . '</b>';
                })
                ->addColumn('merk', function ($d) {
                    return $d->noseri->gudang->produk->merk;
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->noseri->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('lama', function ($d) {
                    return $d->data_lama;
                })
                ->addColumn('baru', function ($d) {
                    return $d->data_baru;
                })
                ->addColumn('tgl_aju', function ($d) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('requested', function ($d) {
                    return $d->actionn->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<button class="btn btn-outline-success btn-sm btnAlasan" type="button" data-id="' . $d->id . '">
                            <i class="far fa-eye"></i> Detail
                            </button>';
                })
                ->rawColumns(['checkbox', 'produk', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }



    function edit_noseri(Request $request)
    {
        try {
            // dd($request->all());
            $dataseri = [];
            $data = NoseriBarangJadi::whereIn('id', $request->data);
            $check = NoseriBarangJadi::whereIn('noseri', $request->new)->get();
            if (count($check) > 0) {
                foreach ($check as $d) {
                    array_push($dataseri, $d->noseri);
                }
                return response()->json(['error' => true, 'msg' => 'Noseri ' . implode(', ', $dataseri) . ' Sudah Terdaftar']);
            } else {
                foreach ($data->get() as $k => $c) {
                    NoseriBrgJadiLog::create([
                        'gbj_id' => $request->gbjid,
                        'noseri_id' => $c->id,
                        'data_lama' => $c->noseri,
                        'data_baru' => $request->new[$k],
                        'action' => 'update',
                        'action_by' => $request->actionby,
                        'status' => 'waiting',
                        'remark' => $request->alasan
                    ]);
                    NoseriBarangJadi::find($c->id)->update(['noseri' => $request->new[$k], 'is_change' => 0]);
                }

                return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manager']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_delete_noseri(Request $request)
    {
        try {
            $check = NoseriTGbj::whereIn('noseri_id', $request->noseriid);
            $dataseri = [];

            foreach ($check->get() as $c) {
                $nbj = NoseriBarangJadi::find($c->noseri_id);
                array_push($dataseri, $nbj->is_ready);
            }
            if ($request->is_acc == 'rejected') {
                $obj = [
                    'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                    'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                    'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                    'komentar' => $request->komentar,
                    'oleh' => User::find($request->accby)->nama
                ];
                if (count($dataseri) == 0) {
                    $cek = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                    foreach ($cek as $cc) {
                        NoseriBrgJadiLog::where('noseri_id', $cc->id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                        NoseriBarangJadi::find($cc->id)->update(['is_change' => 1, 'is_delete' => 0]);
                    }
                } else {
                    foreach ($check->get() as $ddd) {
                        NoseriBrgJadiLog::where('noseri_id', $ddd->noseri_id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                        NoseriBarangJadi::find($ddd->noseri_id)->update(['is_change' => 1, 'is_delete' => 0]);
                    }
                }

                SystemLog::create([
                    'tipe' => 'GBJ - Manager',
                    'subjek' => 'Penolakan Hapus Noseri Gudang',
                    'response' => json_encode($obj),
                    'user_id' => $request->accby
                ]);
                return response()->json(['error' => false, 'msg' => 'Penolakan Berhasil Dilakukan']);
            } else {
                $obj = [
                    'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                    'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                    'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                    'komentar' => $request->komentar,
                    'oleh' => User::find($request->accby)->nama
                ];
                if (count($dataseri) == 0) {
                    $cekk = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();
                    SystemLog::create([
                        'tipe' => 'GBJ - Manager',
                        'subjek' => 'Persetujuan Hapus Noseri Gudang',
                        'response' => json_encode($obj),
                        'user_id' => $request->accby
                    ]);
                    foreach ($cekk as $ckc) {
                        NoseriBrgJadiLog::where('noseri_id', $ckc->id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'approve', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                        NoseriBarangJadi::find($ckc->id)->delete();
                    }
                    return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Dihapus']);
                } else {
                    if (empty(array_filter($dataseri))) {
                        SystemLog::create([
                            'tipe' => 'GBJ - Manager',
                            'subjek' => 'Persetujuan Hapus Noseri Gudang',
                            'response' => json_encode($obj),
                            'user_id' => $request->accby
                        ]);
                        foreach ($check->get() as $d) {
                            NoseriBrgJadiLog::where('noseri_id', $d->noseri_id)->where([
                                'action' => 'delete',
                                'status' => 'waiting'
                            ])->update(['status' => 'approved', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                            NoseriTGbj::where('noseri_id', $d->noseri_id)->delete();
                            NoseriBarangJadi::find($d->noseri_id)->delete();
                        }
                        return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Dihapus']);
                    } else {
                        $obj1 = [
                            'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                            'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                            'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                            'komentar' => $request->komentar,
                            'oleh' => User::find($request->accby)->nama,
                            'alasan' => 'Noseri Sedang Digunakan'
                        ];
                        SystemLog::create([
                            'tipe' => 'GBJ - Manager',
                            'subjek' => 'Persetujuan Hapus Noseri Gudang Gagal',
                            'response' => json_encode($obj1),
                            'user_id' => $request->accby
                        ]);
                        foreach ($check->get() as $dd) {
                            NoseriBrgJadiLog::where('noseri_id', $dd->noseri_id)->where([
                                'action' => 'delete',
                                'status' => 'waiting'
                            ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                            NoseriBarangJadi::find($dd->noseri_id)->update(['is_change' => 1]);
                        }
                        return response()->json(['error' => true, 'msg' => 'Noseri Ada yang Sedang Digunakan']);
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_update_noseri(Request $request)
    {
        try {
            $obj = [
                'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                'komentar' => $request->komentar,
                'oleh' => User::find($request->accby)->nama
            ];
            if ($request->is_acc == 'rejected') {
                $a = NoseriBrgJadiLog::whereIn('noseri_id', $request->noseriid)->where([
                    'action' => 'update',
                    'status' => 'waiting'
                ])->get()->pluck('data_lama');
                for ($i = 0; $i < count($a); $i++) {
                    NoseriBrgJadiLog::where('noseri_id', $request->noseriid[$i])->where([
                        'action' => 'update',
                        'status' => 'waiting'
                    ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                    NoseriBarangJadi::where('id', $request->noseriid[$i])->update(['is_change' => 1, 'noseri' => $a[$i]]);
                }
                SystemLog::create([
                    'tipe' => 'GBJ - Manager',
                    'subjek' => 'Penolakan Perubahan Noseri',
                    'response' => json_encode($obj),
                    'user_id' => $request->accby
                ]);
                return response()->json(['error' => false, 'msg' => 'Noseri Batal Diubah']);
            } else {
                // return 'acc';
                $data = NoseriBarangJadi::whereIn('id', $request->noseriid);
                foreach ($data->get() as $k => $c) {
                    NoseriBrgJadiLog::where('noseri_id', $c->id)->where([
                        'action' => 'update',
                        'status' => 'waiting'
                    ])->update(['status' => 'approved', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                    NoseriBarangJadi::find($c->id)->update(['is_change' => 1]);
                }
                SystemLog::create([
                    'tipe' => 'GBJ - Manager',
                    'subjek' => 'Persetujuan Perubahan Noseri',
                    'response' => json_encode($obj),
                    'user_id' => $request->accby
                ]);
                return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Diubah']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getRakit(Request $request)
    {
        try {
            // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
            //     $q->where('gdg_brg_jadi_id', $id);
            //     $q->whereHas('header', function ($a) use ($value) {
            //         $a->where('tgl_masuk', $value)->where('dari', 17)->where('ke', 13);
            //     });
            // })->where('status_id', null)->where('jenis', 'masuk')->get();
            // jika tahun null atau kosong maka tahun sekarang
            $parameter = $request->tahun == "null" ? Carbon::now()->year : $request->tahun;
            $prd = DB::select("select
            tgd.id,
            (select jp.no_bppb  from jadwal_perakitan jp
            join jadwal_rakit_noseri jrn ON jrn.jadwal_id = jp.id
            where jrn.noseri = ( SELECT nbj.noseri
             from noseri_barang_jadi nbj
             left join t_gbj_noseri tgn on tgn.noseri_id = nbj.id
             where tgn.t_gbj_detail_id  = tgd.id
             limit 1)
            ) AS no_ref,
            (SELECT COUNT(t_gbj_noseri.id) FROM t_gbj_noseri WHERE t_gbj_noseri.t_gbj_detail_id = tgd.id AND t_gbj_noseri.status_id is null and t_gbj_noseri.jenis = 'masuk') AS jumlah,
            gbj.id as gbj_id,
            tg.tgl_masuk,
            tg.created_at,
            concat(p.nama, ' ', gbj.nama) as nama
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join t_gbj_noseri tgn on tgn.t_gbj_detail_id = tgd.id
            where  tgn.status_id is null and tg.dari = 17 and tg.ke = 13 and year(tg.tgl_masuk) = ?
            group by  tgd.id
            ", [$parameter]);

            $batal = DB::select("select
            tgd.id,
            pesanan.so AS no_ref,
            (SELECT COUNT(t_gbj_noseri.id) FROM t_gbj_noseri WHERE t_gbj_noseri.t_gbj_detail_id = tgd.id AND t_gbj_noseri.status_id is null and t_gbj_noseri.jenis = 'masuk') AS jumlah,
            gbj.id as gbj_id,
            tg.tgl_masuk,
            tg.created_at,
            riwayat_batal_po.ket,
            divisi.nama as divisi,
            concat(p.nama, ' ', gbj.nama) as nama
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join divisi on tg.dari = divisi.id
            left join riwayat_batal_po on riwayat_batal_po.id = tg.batal_pesanan_id
            left join pesanan on pesanan.id = riwayat_batal_po.pesanan_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join t_gbj_noseri tgn on tgn.t_gbj_detail_id = tgd.id
            where  tgn.status_id is null and  (tg.dari = 23 OR tg.dari = 15)  and tg.ke = 13 and year(tg.tgl_masuk) = ?
            group by  tgd.id
            ", [$parameter]);

            // $retur = RiwayatReturPoPrd::select('riwayat_retur_po_prd.id','pesanan.no_po','produk.nama','gdg_barang_jadi.nama as variasi','riwayat_retur_po_paket.updated_at as tgl_masuk')
            // ->addSelect([
            //     'cterjadwal' => function ($q){
            //         $q->selectRaw('coalesce(count(riwayat_retur_po_seri.id),0)')
            //             ->from('riwayat_retur_po_seri')
            //             ->where('status', 1)
            //             ->whereColumn('riwayat_retur_po_seri.detail_riwayat_retur_prd_id', 'riwayat_retur_po_prd.id');
            //     },
            // ])
            // ->leftJoin('riwayat_retur_po_paket','riwayat_retur_po_paket.id','=','riwayat_retur_po_prd.detail_riwayat_retur_paket_id')
            // ->leftJoin('detail_pesanan','riwayat_retur_po_paket.detail_pesanan_id','=','detail_pesanan.id')
            // ->leftJoin('pesanan','pesanan.id','=','detail_pesanan.pesanan_id')
            // ->leftJoin('gdg_barang_jadi','gdg_barang_jadi.id','=','riwayat_retur_po_prd.gudang_barang_jadi_id')
            // ->leftJoin('produk','produk.id','=','gdg_barang_jadi.produk_id')
            // ->havingRaw('cterjadwal > 0')
            // ->get();


            $retur = DB::select("select
            tgd.id,
            (select jp.no_bppb  from jadwal_perakitan jp
            join jadwal_rakit_noseri jrn ON jrn.jadwal_id = jp.id
            where jrn.noseri = ( SELECT nbj.noseri
             from noseri_barang_jadi nbj
             left join t_gbj_noseri tgn on tgn.noseri_id = nbj.id
             where tgn.t_gbj_detail_id  = tgd.id
             limit 1)
            ) AS no_ref,
            (SELECT COUNT(t_gbj_noseri.id) FROM t_gbj_noseri WHERE t_gbj_noseri.t_gbj_detail_id = tgd.id AND t_gbj_noseri.status_id is null and t_gbj_noseri.jenis = 'masuk') AS jumlah,
            gbj.id as gbj_id,
            tg.tgl_masuk,
            tg.created_at,
            tg.deskripsi as so,
            concat(p.nama, ' ', gbj.nama) as nama
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join pesanan  on pesanan.id = tg.retur_pesanan_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join t_gbj_noseri tgn on tgn.t_gbj_detail_id = tgd.id
            where  tgn.status_id is null and tg.dari = 26 and tg.ke = 13 and year(tg.tgl_masuk) = ?
            group by  tgd.id
            ", [$parameter]);
            $obj = [];

            foreach ($retur as $r) {
                $obj[] = array(
                    'id' => $r->id,
                    'gbj_id' => $r->gbj_id,
                    'no_ref' => $r->so,
                    'nama' => $r->nama,
                    'tgl_masuk' => $r->tgl_masuk,
                    'timestamp' => $r->created_at,
                    'jumlah' => $r->jumlah,
                    'status' => 'retur',
                    'bagian' => 'Penjualan',
                    'keterangan' => ''
                );
            }

            foreach ($batal as $r) {
                $obj[] = array(
                    'id' => $r->id,
                    'gbj_id' => $r->gbj_id,
                    'no_ref' => $r->no_ref,
                    'nama' => $r->nama,
                    'tgl_masuk' => $r->tgl_masuk,
                    'timestamp' => $r->created_at,
                    'jumlah' => $r->jumlah,
                    'status' => 'batal',
                    'bagian' => $r->divisi,
                    'keterangan' => $r->ket
                );
            }

            foreach ($prd as $r) {
                $obj[] = array(
                    'id' => $r->id,
                    'gbj_id' => $r->gbj_id,
                    'no_ref' => $r->no_ref,
                    'nama' => $r->nama,
                    'tgl_masuk' => $r->tgl_masuk,
                    'timestamp' => $r->created_at,
                    'jumlah' => $r->jumlah,
                    'status' => 'produksi',
                    'bagian' => 'Produksi',
                    'keterangan' => ''
                );
            }



            return response()->json($obj);

            //Query Lama
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('bppb', function ($d) {
                    return $d->bppb;
                })
                ->addColumn('tgl_masuk', function ($d) {
                    return $d->tgl_masuk;
                })
                ->addColumn('product', function ($d) {
                    return $d->product;
                })
                ->addColumn('jumlah', function ($d) {
                    // $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    //     $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    //     $q->whereHas('header', function ($a) use ($d) {
                    //         $a->where('tgl_masuk', $d->tgl_masuk)->where('ke', 13)->where('dari', 17);
                    //     });
                    // })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                    return $d->jumlah;
                })
                ->addColumn('action', function ($d) {
                    return  '
                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-produk="' . $d->product . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gbj_id . '">
                                <button class="btn btn-outline-primary btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                            </a>
                           ';
                    // $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    //     $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    //     $q->whereHas('header', function ($a) use ($d) {
                    //         $a->where('tgl_masuk', $d->tgl_masuk);
                    //         $a->where('dari', 17);
                    //     });
                    // })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                    // $seri = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    //     $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    //     $q->whereHas('header', function ($a) use ($d) {
                    //         $a->where('tgl_masuk', $d->tgl_masuk);
                    //         $a->where('dari', 17);
                    //     });
                    // })->where('jenis', 'masuk')->get()->count();

                    // if ($seri == $seri_done) {
                    //     return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produkk . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                    //             <button class="btn btn-outline-info btn-sm" type="button" >
                    //             <i class="far fa-eye"></i>&nbsp;Detail
                    //             </button>
                    //         </a>';
                    // } else {
                    //     return  '
                    //         <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produkk . '"data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                    //             <button class="btn btn-outline-info btn-sm" type="button" >
                    //             <i class="far fa-eye"></i>&nbsp;Detail
                    //             </button>
                    //         </a>
                    //         <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-produk="' . $d->produkk . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                    //             <button class="btn btn-outline-primary btn-sm" type="button" >
                    //             <i class="far fa-edit"></i>&nbsp;Terima
                    //             </button>
                    //         </a>
                    //        ';
                    // }
                })
                ->rawColumns(['action', 'jumlah'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getRakitNoseri($id, $value)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                $q->where('gdg_brg_jadi_id', $id);
                $q->whereHas('header', function ($a) use ($value) {
                    $a->where('tgl_masuk', $value);
                });
            })->where('status_id', 3)->where('jenis', 'masuk')->get();
            return datatables()->of($data)
                ->addColumn('layout', function ($d) {
                    return $d->layout->ruang;
                })
                ->addColumn('noserii', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getTerimaRakit($id, $value, $jenis)
    {
        try {
            if ($jenis == 'produksi') {
                // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                //     $q->where('id', $id);
                //     $q->whereHas('header', function ($a) use ($value) {
                //         $a->where('tgl_masuk', $value)->where('dari', 17)->where('ke', 13);
                //     });
                // })->where('status_id', null)->where('jenis', 'masuk')->get();
                // $layout = Layout::where('jenis_id', 1)->orderBy('ruang')->get();
                // $a = 0;

                $data = NoseriTGbj::select('t_gbj_noseri.id', 't_gbj_noseri.noseri_id', 'noseri_barang_jadi.noseri')
                    ->addSelect(DB::raw('IF(t_gbj_noseri.status_id IS NULL, "true", "false") AS status'))
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                    ->where('t_gbj_detail.gdg_brg_jadi_id', $value)
                    ->where('t_gbj_detail.id', $id)
                    ->where('t_gbj.dari', 17)
                    ->where('t_gbj.ke', 13)
                    ->whereNull('t_gbj_noseri.status_id')
                    ->where('t_gbj_noseri.jenis', 'masuk')
                    ->get();
            } else if ($jenis == 'batal') {
                // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                //     $q->where('id', $id);
                //     $q->whereHas('header', function ($a) use ($value) {
                //         $a->where('tgl_masuk', $value)->where('dari', 17)->where('ke', 13);
                //     });
                // })->where('status_id', null)->where('jenis', 'masuk')->get();
                // $layout = Layout::where('jenis_id', 1)->orderBy('ruang')->get();
                // $a = 0;

                $data = NoseriTGbj::select('t_gbj_noseri.id', 't_gbj_noseri.noseri_id', 'noseri_barang_jadi.noseri')
                    ->addSelect(DB::raw('IF(t_gbj_noseri.status_id IS NULL, "true", "false") AS status'))
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                    ->where('t_gbj_detail.gdg_brg_jadi_id', $value)
                    ->where('t_gbj_detail.id', $id)
                    ->where(function ($query) {
                        $query->where('t_gbj.dari', 23)
                            ->orWhere('t_gbj.dari', 15);
                    })
                    ->where('t_gbj.ke', 13)
                    ->whereNull('t_gbj_noseri.status_id')
                    ->where('t_gbj_noseri.jenis', 'masuk')
                    ->get();
            } else if ($jenis == 'retur') {

                $data = NoseriTGbj::select('t_gbj_noseri.id', 't_gbj_noseri.noseri_id', 'noseri_barang_jadi.noseri')
                    ->addSelect(DB::raw('IF(t_gbj_noseri.status_id IS NULL, "true", "false") AS status'))
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                    ->where('t_gbj_detail.gdg_brg_jadi_id', $value)
                    ->where('t_gbj_detail.id', $id)
                    ->where('t_gbj.dari', 26)
                    ->where('t_gbj.ke', 13)
                    ->whereNull('t_gbj_noseri.status_id')
                    ->where('t_gbj_noseri.jenis', 'masuk')
                    ->get();
                // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                //     $q->where('id', $id);
                //     $q->whereHas('header', function ($a) use ($value) {
                //         $a->where('tgl_masuk', $value)->where('dari', 26)->where('ke', 13);
                //     });
                // })->where('status_id', null)->where('jenis', 'masuk')->get();
                // $layout = Layout::where('jenis_id', 1)->orderBy('ruang')->get();
                // $a = 0;

            }



            return response()->json($data);
            return datatables()->of($data)
                ->addColumn('layout', function ($d) use ($layout, $a) {
                    $opt = '';
                    $selected = 7;
                    foreach ($layout as $l) {
                        $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                    }
                    $a++;
                    return '<select name="layout_id[]" id="layout_id" class="form-control layout">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('noserii', function ($d) {
                    return $d->seri->noseri . '<input type="hidden" name="noseri[]" id="noseri[]" value="' . $d->seri->noseri . '">';
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->rawColumns(['checkbox', 'layout', 'noserii'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getDraftPerakitan(Request $request)
    {
        // detail
        try {
            if ($request->id) {
                $data = TFProduksiDetail::whereHas('header', function ($q) {
                    $q->where('status_id', 1);
                })->with('header', 'produk', 'noseri')->where('t_gbj_id', $request->id)->get();
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('nama_produk', function ($d) {
                        return $d->produk->produk->nama . ' ' . $d->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $d->gdg_brg_jadi_id . '">';
                    })
                    ->addColumn('jml', function ($d) {
                        return $d->qty . ' ' . $d->produk->satuan->nama . '<input type="hidden" name="qty[]" id="qty" value="' . $d->qty . '"><input type="hidden" name="tfid[]" id="tfid" value="' . $d->id . '">';
                    })
                    ->addColumn('kode_prd', function ($d) {
                        return $d->gdg_brg_jadi_id;
                    })
                    ->addColumn('action', function ($d) {
                        return '<a data-toggle="modal" data-target="#detail" class="detail" data-attr="" data-var="' . $d->produk->nama . '" data-nama="' . $d->produk->produk->nama . '" data-gbj=' . $d->gdg_brg_jadi_id . ' data-id="' . $d->id . '">
                                    <button class="btn btn-info"><i
                                    class="far fa-eye"></i> Detail</button>
                                </a>';
                    })
                    ->addColumn('in', function ($d) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM Y');
                    })
                    ->addColumn('from', function ($d) {
                        return $d->header->darii->nama;
                    })
                    ->addColumn('tujuan', function ($d) {
                        return $d->header->deskripsi;
                    })
                    ->rawColumns(['action', 'nama_produk', 'jml'])
                    ->make(true);
            } else {
                #header awal
                $data = TFProduksi::with('detail', 'darii')->where(['jenis' => 'masuk', 'status_id' => 1])->get();
                return datatables()->of($data)
                    ->addColumn('in', function ($d) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM Y');
                    })
                    ->addColumn('from', function ($d) {
                        return $d->darii->nama;
                    })
                    ->addColumn('tujuan', function ($d) {
                        return $d->deskripsi;
                    })
                    ->addColumn('action', function ($d) {
                        return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                                    <button class="btn btn-info"><i
                                    class="far fa-eye"></i> Detail</button>
                                </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getNoseriDraftRakit(Request $request)
    {
        try {
            //code...
            $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $request->t_gbj_detail_id)->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addColumn('serii', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child-rancang" value="' . $d->id . '" data-id="' . $d->noseri_id . '">';
                })
                ->rawColumns(['checkbox', 'posisi'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function terimaRakit()
    {
        $layout = Layout::where('jenis_id', 1)->get();
        return view('page.gbj.dp', compact('layout'));
    }

    function ceknoseri(Request $request)
    {
        try {
            //code...
            $data = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get();
            $datarakit = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get();
            $arr_seri = [];
            $arr_rakit = [];


            // if (count($data) == 0 && count($datarakit) == 0) {
            if (count($data) == 0) {
                return response()->json(['msg' => 'Noseri tersimpan']);
            } else {
                foreach ($data as $d) {
                    array_push($arr_seri, $d->noseri);
                }

                foreach ($datarakit as $c) {
                    array_push($arr_rakit, $c->noseri);
                }

                if (count($data) > 0) {
                    return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_seri) . ' sudah terdaftar di gudang']);
                }

                // if (count($datarakit) > 0) {
                //     return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_rakit) . ' sudah terdaftar di perakitan']);
                // }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function allTp()
    {
        $data1 = TFProduksi::with('pesanan')->where([
            ['jenis', '=', 'keluar'],
            ['status_id', '=', 2],
        ])->whereNotNull('pesanan_id')->get();
        return view('page.gbj.tp.tp', compact('data1'));
    }

    function getSODone()
    {
        try {
            //code...
            $data = TFProduksi::leftJoin('pesanan as p', 'p.id', '=', 't_gbj.pesanan_id')
                ->leftJoin('m_state as ms', 'ms.id', '=', 'p.log_id')
                ->leftJoin('ekatalog as e', 'e.pesanan_id', '=', 'p.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'p.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'p.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->leftJoin('provinsi as prov', 'prov.id', '=', 'e.provinsi_id')
                ->where([
                    ['t_gbj.jenis', '=', 'keluar'],
                    ['t_gbj.status_id', '=', 2],
                ])->whereNotNull('t_gbj.pesanan_id')
                ->select(
                    't_gbj.tgl_keluar',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    'ms.nama as log_nama',
                    DB::raw("case
                                when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
                                when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
                                when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
                            end as divisi"),
                    'e.tgl_kontrak',
                    'p.id',
                    'prov.status'
                )->orderby('tgl_keluar', 'DESC')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noso', function ($d) {
                    return $d->so;
                })
                ->addColumn('nopo', function ($d) {
                    return $d->no_po;
                })
                ->addColumn('tgl_keluar', function ($d) {
                    return  Carbon::createFromFormat('Y-m-d', $d->tgl_keluar)
                        ->format('d-m-Y');
                })
                ->addColumn('logs', function ($d) {
                    if (isset($d->id)) {
                        if ($d->log_id == 9) {
                            $ax = "<span class='badge badge-pill badge-secondary'>" . $d->log_nama . "</span>";
                        } else if ($d->log_id == 6) {
                            $ax = "<span class='badge badge-pill badge-warning'>" . $d->log_nama . "</span>";
                        } elseif ($d->log_id == 8) {
                            $ax = "<span class='badge badge-pill badge-info'>" . $d->log_nama . "</span>";
                        } elseif ($d->log_id == 11) {
                            $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                        } elseif ($d->log_id == 10) {
                            $ax = "<span class='badge badge-pill badge-success'>" . $d->log_nama . "</span>";
                        } else {
                            $ax = "<span class='badge badge-pill badge-danger'>" . $d->log_nama . "</span>";
                        }

                        return $ax;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_kontrak', function ($d) {
                    if ($d->tgl_kontrak) {

                        if ($d->status == 1) {
                            return Carbon::parse($d->tgl_kontrak)->subWeeks(5)->format('d-m-Y');
                        }

                        if ($d->status == 2) {
                            return Carbon::parse($d->tgl_kontrak)->subWeeks(4)->format('d-m-Y');
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('aksi', function ($d) {

                    return '<td><a href="' . url('gbj/export_spb/' . $d->id . '') . '">
                            <button class="btn btn-outline-primary"><i class="fas fa-print"></i> Cetak</button>
                            </a> </td>';
                })
                ->rawColumns(['aksi', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    // Export Excell
    function exportSpb($id)
    {
        $header = TFProduksi::where('pesanan_id', $id)->with('pesanan')->get()->pluck('pesanan.so');
        return Excel::download(new SpbExport($id), 'SPB.xlsx');
    }

    function download_template_noseri(Request $request)
    {
        try {
            $no = 1;

            $produk = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get()->sortBy('produk.nama');

            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            // workshet noseri
            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Noseri');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Noseri');

            $validation = $spreadsheet->getActiveSheet()->getCell('B2')
                ->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');

            // $validation->setFormula1('\'Produk\'!$C$2:$C$288');
            $validation->setFormula1('\'Produk\'!$C:$C');
            // $validation->setFormula1('"Item A,Item B,Item C"');
            $validation->setSqref('B2:B10000');

            // check duplicate input noseri
            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->setConditionalStyles($conditionalStyles);

            // workshet master
            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Produk');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Merk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noo = 2;
            foreach ($produk as $p) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noo, $p->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noo, $p->produk->merk);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $noo, $p->produk->nama . ' ' . $p->nama);
                $noo++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Template Noseri.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function import_noseri(Request $request)
    {
        try {
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();

            $file->move(public_path('upload/noseri/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/noseri/' . $filename));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('C', $column_limit);
            $startcount = 2;
            $data = array();
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'produk' => $sheet->getCell('B' . $row)->getValue(),
                    'noseri' => $sheet->getCell('C' . $row)->getValue()
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $aa[] = $d['noseri'];
                $bb[] = $d['produk'];
            }

            $check = NoseriBarangJadi::whereIn('noseri', $aa)->get()->pluck('noseri');
            $check_rakit = JadwalRakitNoseri::whereIn('noseri', $aa)->get()->pluck('noseri');
            $seri = [];
            $seri_rakit = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                if ($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            if (count($check) > 0 || count($check_rakit) > 0) {
                foreach ($check as $item) {
                    array_push($seri, $item);
                }

                foreach ($check_rakit as $itemm) {
                    array_push($seri_rakit, $itemm);
                }
                return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar di gudang', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri)]);

                return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri_rakit) . ' sudah terdaftar di perakitan', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri_rakit)]);
            } else {
                return response()->json(['msg' => 'Noseri Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function import_noseri_to_db(Request $request)
    {
        try {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/noseri/' . $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('C', $column_limit);
            $startcount = 2;
            $data = array();
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'produk' => $sheet->getCell('B' . $row)->getValue(),
                    'noseri' => $sheet->getCell('C' . $row)->getValue()
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $aa[] = $d['noseri'];
                $bb[] = $d['produk'];
            }

            // array_unique($bb);
            $check = GudangBarangJadi::select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as produk"))
                ->whereIn(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $bb)
                ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                ->get()->pluck('id');
            // return ;
            foreach ($aa as $key => $nn) {
                $dat_arr[] = [
                    'gdg_barang_jadi_id' => GudangBarangJadi::select('gdg_barang_jadi.id')
                        ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $bb[$key])
                        ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                        ->first()->id,
                    'dari' => 13,
                    'noseri' => strtoupper($nn),
                    'jenis' => 'MASUK',
                    'is_ready' => 0,
                    'is_aktif' => 1,
                    'created_by' => $request->userid,
                    'created_at' => Carbon::now(),
                ];
            }
            NoseriBarangJadi::insert($dat_arr);
            $obj = [
                'produk' => $bb,
                'noseri' => $aa
            ];
            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Upload Noseri Excel',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);
            return response()->json(['msg' => 'Data Berhasil Diunggah', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function download_template_so(Request $request, $id)
    {
        try {
            $no = 1;

            $dataso = Pesanan::where('id', $id)->first();

            $dpp = DB::table(DB::raw('detail_pesanan dp'))
                ->select('dp.id', 'p.so', 'pp.nama as nama_paket', DB::raw('concat(p2.nama," ",gbj.nama) as produkk'), 'dp.jumlah', 'dpp.id as dppid', 'p.id as pesid', 'gbj.id as gbjid')
                ->leftJoin(DB::raw('detail_pesanan_produk dpp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('penjualan_produk pp'), 'pp.id', '=', 'dp.penjualan_produk_id')
                ->leftJoin(DB::raw('pesanan p'), 'p.id', '=', 'dp.pesanan_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'dpp.gudang_barang_jadi_id')
                ->leftJoin(DB::raw('produk p2'), 'p2.id', '=', 'gbj.produk_id')
                ->where('dp.pesanan_id', $id)
                ->get();

            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Template');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama SO');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Paket');
            $spreadsheet->getActiveSheet()->setCellValue('D1', 'Produk');
            $spreadsheet->getActiveSheet()->setCellValue('E1', 'Noseri');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);

            $so_val = $spreadsheet->getActiveSheet()->getCell('B2')
                ->getDataValidation();
            $so_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $so_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $so_val->setAllowBlank(false);
            $so_val->setShowInputMessage(true);
            $so_val->setShowErrorMessage(true);
            $so_val->setShowDropDown(true);
            $so_val->setErrorTitle('Input error');
            $so_val->setError('Value is not in list.');
            $so_val->setPromptTitle('Pilih SO');
            $so_val->setPrompt('Tolong pilih SO yang tersedia.');

            $so_val->setFormula1('\'Master Detail Sales Order\'!$B$2:$B$688');
            $so_val->setSqref('B2:B10000');

            $paket_val = $spreadsheet->getActiveSheet()->getCell('C2')
                ->getDataValidation();
            $paket_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $paket_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $paket_val->setAllowBlank(false);
            $paket_val->setShowInputMessage(true);
            $paket_val->setShowErrorMessage(true);
            $paket_val->setShowDropDown(true);
            $paket_val->setErrorTitle('Input error');
            $paket_val->setError('Value is not in list.');
            $paket_val->setPromptTitle('Pilih Paket');
            $paket_val->setPrompt('Tolong pilih paket yang tersedia.');

            $paket_val->setFormula1('\'Master Detail Sales Order\'!$C$2:$C$688');
            $paket_val->setSqref('C2:C10000');

            $produk_val = $spreadsheet->getActiveSheet()->getCell('D2')
                ->getDataValidation();
            $produk_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $produk_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $produk_val->setAllowBlank(false);
            $produk_val->setShowInputMessage(true);
            $produk_val->setShowErrorMessage(true);
            $produk_val->setShowDropDown(true);
            $produk_val->setErrorTitle('Input error');
            $produk_val->setError('Value is not in list.');
            $produk_val->setPromptTitle('Pilih produk');
            $produk_val->setPrompt('Tolong pilih produk yang tersedia.');

            $produk_val->setFormula1('\'Master Detail Sales Order\'!$D$2:$D$688');
            // $validation->setFormula1('"Item A,Item B,Item C"');
            $produk_val->setSqref('D2:D10000');

            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('E2:E10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('E2:E10000')->setConditionalStyles($conditionalStyles);


            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Master Detail Sales Order');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nomor SO');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Paket');
            $spreadsheet->getActiveSheet()->setCellValue('D1', 'Produk');
            $spreadsheet->getActiveSheet()->setCellValue('E1', 'Jumlah');
            $spreadsheet->getActiveSheet()->setCellValue('F1', 'Jumlah Terkirim');
            $spreadsheet->getActiveSheet()->setCellValue('G1', 'Jumlah Sisa');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

            $detail_no = 2;
            $gbjid = [];
            foreach ($dpp as $dpp) {
                $datacek = NoseriTGbj::whereHas('detail', function ($q) use ($dpp) {
                    $q->where('gdg_brg_jadi_id', $dpp->gbjid);
                    $q->where('detail_pesanan_produk_id', $dpp->dppid);
                })->whereHas('detail.header', function ($q) use ($dpp) {
                    $q->where('pesanan_id', $dpp->pesid);
                })->get()->count();
                $spreadsheet->getActiveSheet()->setCellValue('A' . $detail_no, $dpp->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $detail_no, $dpp->so);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $detail_no, $dpp->nama_paket);
                $spreadsheet->getActiveSheet()->setCellValue('D' . $detail_no, $dpp->produkk);
                $spreadsheet->getActiveSheet()->setCellValue('E' . $detail_no, $dpp->jumlah);
                $spreadsheet->getActiveSheet()->setCellValue('F' . $detail_no, $datacek);
                $spreadsheet->getActiveSheet()->setCellValue('G' . $detail_no, $dpp->jumlah - $datacek);
                $detail_no++;
                $no++;
                $gbjid[] = $dpp->gbjid;
            }

            $noseri = DB::table(DB::raw('noseri_barang_jadi tn'))
                ->select('tn.id', DB::raw('concat(p.nama," ",gbj.nama) as produkk'), 'tn.noseri')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tn.gdg_barang_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where([
                    // 'is_rakit' => 0,
                    'is_aktif' => 1,
                    'is_ready' => 0,
                    // 'is_repair' => 0,
                    'is_change' => 1,
                    'is_delete' => 0,
                    // 'log_id' => 13,
                ])
                ->whereIn('gbj.id', $gbjid)->get();

            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(2);
            $spreadsheet->getActiveSheet()->setTitle('Master Noseri Sales Order');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Noseri');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noseri_no = 2;
            foreach ($noseri as $ns) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noseri_no, $ns->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noseri_no, $ns->produkk);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $noseri_no, $ns->noseri);
                $noseri_no++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $dataso->so . '.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function preview_so(Request $request)
    {
        try {
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();

            $file->move(public_path('upload/so/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/so/' . $filename));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'so' => $sheet->getCell('B' . $row)->getValue(),
                    'paket' => $sheet->getCell('C' . $row)->getValue(),
                    'produk' => $sheet->getCell('D' . $row)->getValue(),
                    'noseri' => $sheet->getCell('E' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $seri[] = $d['noseri'];
                $produk[] = $d['produk'];
                $paket[] = $d['paket'];
                $so[] = $d['so'];
            }
            // $cek_rakit = JadwalRakitNoseri::whereIn('noseri', $seri)->get()->pluck('noseri');
            $cek = NoseriBarangJadi::whereIn('noseri', $seri)->get()->pluck('noseri');
            // $cek = $cek_gbj->merge($cek_rakit)->toArray();
            // return array_unique($cek);
            $no_seri = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $haserror = false;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Sales Order</th>
                    <th>Paket</th>
                    <th>Produk</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                $d = $row['D'];
                $e = $row['E'];
                if ($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $nis_td = (!empty($d)) ? "" : " style='background: #E07171;'" . $haserror = true;
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "<td" . $nis_td . ">" . $d . "</td>";
                    $html .= "<td" . $nis_td . ">" . $e . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            if ($haserror) {
                return response()->json(
                    [
                        'msg' => 'Ada data yang kosong, Silahkan lengkapi data',
                        'error' => true,
                        'data' => $html
                    ],
                    500
                );
            }

            $a = Pesanan::where('id', $request->soid1)->first();
            if ($a->so == implode("", array_unique($so))) {
                if (count($cek) != count($seri)) {
                    $seri_final = [];
                    foreach ($cek as $item) {
                        array_push($no_seri, $item);
                    }

                    foreach ($seri as $ns) {
                        if (!in_array($ns, $no_seri)) {
                            array_push($seri_final, $ns);
                        }
                    }
                    return response()->json([
                        'msg' => 'Nomor seri ' . implode(', ', $seri_final) . ' belum terdaftar',
                        'error' => true,
                        'data' => $html,
                        'noseri' => implode(', ', $seri_final)
                    ], 500);
                } else {
                    return response()->json(['msg' => 'Sales Order Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'success' => false,
                    'msg' => 'Nomor Sales Order Tidak Sesuai'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function groupBy($arr, $criteria): array
    {
        return array_reduce($arr, function ($accumulator, $item) use ($criteria) {
            $key = (is_callable($criteria)) ? $criteria($item) : $item[$criteria];
            if (!array_key_exists($key, $accumulator)) {
                $accumulator[$key] = [];
            }

            array_push($accumulator[$key], $item);
            return $accumulator;
        }, []);
    }

    function store_so_to_db(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/so/' . $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();
            $dat_arr = array();
            $new_arr = array();
            $detail_arr = array();
            $noseri_arr = array();
            $dat_log = [];
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'so' => $sheet->getCell('B' . $row)->getValue(),
                    'paket' => $sheet->getCell('C' . $row)->getValue(),
                    'produk' => $sheet->getCell('D' . $row)->getValue(),
                    'noseri' => $sheet->getCell('E' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $kh => $d) {
                $seri[] = $d['noseri'];
                $produk[] = $d['produk'];
                $paket[] = $d['paket'];
            }

            foreach ($produk as $key => $prd) {
                $dat_arr[] = [
                    'paket' => DetailPesananProduk::join('detail_pesanan as dp', 'dp.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->join('penjualan_produk as pp', 'pp.id', '=', 'dp.penjualan_produk_id')
                        ->where('dp.pesanan_id', $request->soid)
                        ->where('pp.nama', $paket[$key])
                        ->where('detail_pesanan_produk.gudang_barang_jadi_id', GudangBarangJadi::join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                            ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $prd)
                            ->select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as name"))
                            ->first()->id)
                        ->select('pp.nama', 'detail_pesanan_produk.id')
                        ->first()->id,
                    'produk' => GudangBarangJadi::join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                        ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $prd)
                        ->select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as name"))
                        ->first()->id,
                    'serii' => $seri[$key],
                    'noseri' => NoseriBarangJadi::where('noseri', $seri[$key])->first()->id,
                ];
            }

            $arr = [];
            $arrm = [];
            foreach ($dat_arr as $da) {
                $arr[$da['paket']]['produk'] = $da['produk'];
                $arr[$da['paket']]['noseri'][] = $da['noseri'];
            }


            $a = TFProduksi::where('pesanan_id', $request->soid)->first();
            // return $a;
            if ($a) {
                foreach ($arr as $key => $values) {
                    $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $values['produk'])->where('detail_pesanan_produk_id', $key)->first();
                    if ($c) {
                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $c->id,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $user
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->soid]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['produk']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - count($values['noseri']);
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => count($values['noseri']),
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $user,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }

                        $obj = [
                            'data' => $arr,
                            'pesanan_so' => Pesanan::find($request->soid)->so,
                            'pesanan_po' => Pesanan::find($request->soid)->no_po,
                            'tgl_keluar' => Carbon::now()
                        ];

                        SystemLog::create([
                            'tipe' => 'GBJ',
                            'subjek' => 'Sales Order Noseri By Upload',
                            'response' => json_encode($obj),
                            'user_id' => $user
                        ]);
                    } else {
                        $detail = TFProduksiDetail::create([
                            't_gbj_id' => $a->id,
                            'detail_pesanan_produk_id' => $key,
                            'gdg_brg_jadi_id' => $values['produk'],
                            'qty' => count($values['noseri']),
                            'jenis' => 'keluar',
                            'status_id' => 2,
                            'state_id' => 8,
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $detail->id,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $user
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->soid]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['produk']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - count($values['noseri']);
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => count($values['noseri']),
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $user,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }

                        $obj = [
                            'data' => $arr,
                            'pesanan_so' => Pesanan::find($request->soid)->so,
                            'pesanan_po' => Pesanan::find($request->soid)->no_po,
                            'tgl_keluar' => Carbon::now()
                        ];

                        SystemLog::create([
                            'tipe' => 'GBJ',
                            'subjek' => 'Sales Order Noseri By Upload',
                            'response' => json_encode($obj),
                            'user_id' => $user
                        ]);
                    }
                }
            } else {
                $header = TFProduksi::create([
                    'pesanan_id' => $request->soid,
                    'tgl_keluar' => Carbon::now(),
                    'ke' => 23,
                    'jenis' => 'keluar',
                    'status_id' => 2,
                    'state_id' => 8,
                    'created_at' => Carbon::now(),
                    'created_by' => $user
                ]);

                foreach ($arr as $key1 => $value1) {
                    $detail = TFProduksiDetail::create([
                        't_gbj_id' => $header->id,
                        'detail_pesanan_produk_id' => $key1,
                        'gdg_brg_jadi_id' => $value1['produk'],
                        'qty' => count($value1['noseri']),
                        'jenis' => 'keluar',
                        'status_id' => 2,
                        'state_id' => 8,
                        'created_at' => Carbon::now(),
                        'created_by' => $user
                    ]);

                    foreach ($value1['noseri'] as $k => $v) {
                        NoseriTGbj::create([
                            't_gbj_detail_id' => $detail->id,
                            'noseri_id' => $v,
                            'status_id' => 2,
                            'state_id' => 8,
                            'jenis' => 'keluar',
                            'created_at' => Carbon::now(),
                            'created_by' => $user
                        ]);

                        NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->soid]);
                    }

                    $gdg = GudangBarangJadi::whereIn('id', [$value1['produk']])->get()->toArray();
                    $i = 0;
                    foreach ($gdg as $vv) {
                        $vv['stok'] = $vv['stok'] - count($value1['noseri']);
                        // print_r($vv['stok']);
                        $i++;
                        GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        GudangBarangJadiHis::create([
                            'gdg_brg_jadi_id' => $vv['id'],
                            'stok' => count($value1['noseri']),
                            'tgl_masuk' => Carbon::now(),
                            'jenis' => 'KELUAR',
                            'created_by' => $user,
                            'created_at' => Carbon::now(),
                            'ke' => 23,
                            'tujuan' => $request->deskripsi,
                        ]);
                    }
                }

                $obj = [
                    'data' => $arr,
                    'pesanan_so' => Pesanan::find($request->soid)->so,
                    'pesanan_po' => Pesanan::find($request->soid)->no_po,
                    'tgl_keluar' => Carbon::now()
                ];

                SystemLog::create([
                    'tipe' => 'GBJ',
                    'subjek' => 'Sales Order Noseri By Upload',
                    'response' => json_encode($obj),
                    'user_id' => $user
                ]);
            }

            $po = Pesanan::find($request->soid);

            if ($po->getJumlahPesanan() == $po->cekJumlahkirim()) {
                Pesanan::find($request->soid)->update(['log_id' => 8]);
            } else {
                Pesanan::find($request->soid)->update(['log_id' => 6]);
            }

            $del = new Filesystem;
            $del->cleanDirectory(public_path('upload/so/'));
            File::delete(public_path('upload/so/' . $request->namafile));
            return response()->json(['msg' => 'Data Terkirim ke QC']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function getListSODone()
    {
        try {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function template_tanpa_so()
    {
        try {
            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Template');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'Tujuan');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Deskripsi');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Produk');
            $spreadsheet->getActiveSheet()->setCellValue('D1', 'Noseri');
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);

            $tujuan_val = $spreadsheet->getActiveSheet()->getCell('A2')
                ->getDataValidation();
            $tujuan_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $tujuan_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $tujuan_val->setAllowBlank(false);
            $tujuan_val->setShowInputMessage(true);
            $tujuan_val->setShowErrorMessage(true);
            $tujuan_val->setShowDropDown(true);
            $tujuan_val->setErrorTitle('Input error');
            $tujuan_val->setError('Value is not in list.');
            $tujuan_val->setPromptTitle('Pilih Tujuan');
            $tujuan_val->setPrompt('Tolong pilih Tujuan yang tersedia.');

            $tujuan_val->setFormula1('\'Master Tujuan\'!$B$2:$B$688');
            $tujuan_val->setSqref('A2:A10000');

            $produk_val = $spreadsheet->getActiveSheet()->getCell('C2')
                ->getDataValidation();
            $produk_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $produk_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $produk_val->setAllowBlank(false);
            $produk_val->setShowInputMessage(true);
            $produk_val->setShowErrorMessage(true);
            $produk_val->setShowDropDown(true);
            $produk_val->setErrorTitle('Input error');
            $produk_val->setError('Value is not in list.');
            $produk_val->setPromptTitle('Pilih Produk');
            $produk_val->setPrompt('Tolong pilih produk yang tersedia.');

            $produk_val->setFormula1('\'Master Produk\'!$B$2:$B$888');
            $produk_val->setSqref('C2:C10000');

            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('D2:D10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('D2:D10000')->setConditionalStyles($conditionalStyles);

            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Master Tujuan');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $tujuan = Divisi::whereNotIn('id', [1, 2, 3, 4, 5, 31])->get();
            $noo = 2;
            foreach ($tujuan as $t) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noo, $t->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noo, $t->nama);
                $noo++;
            }

            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(2);
            $spreadsheet->getActiveSheet()->setTitle('Master Produk');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $produk = DB::table('gdg_barang_jadi as gbj')
                ->join('produk as p', 'p.id', '=', 'gbj.produk_id')
                ->select('gbj.id', DB::raw('concat(p.nama," ",gbj.nama) as produkk'))
                // ->orderBy(DB::raw('concat(p.nama," ",gbj.nama) as produkk'))
                ->get();

            $no = 2;
            foreach ($produk as $p) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $no, $p->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $no, $p->produkk);
                $no++;
            }

            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(3);
            $spreadsheet->getActiveSheet()->setTitle('Master Noseri');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nomor Seri');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noseri = DB::table(DB::raw('noseri_barang_jadi tn'))
                ->select('tn.id', DB::raw('concat(p.nama," ",gbj.nama) as produkk'), 'tn.noseri')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tn.gdg_barang_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where([
                    // 'is_rakit' => 0,
                    'is_aktif' => 1,
                    'is_ready' => 0,
                    // 'is_repair' => 0,
                    'is_change' => 1,
                    'is_delete' => 0,
                    // 'log_id' => 13,
                ])
                ->get();

            $noseri_no = 2;
            foreach ($noseri as $ns) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noseri_no, $ns->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noseri_no, $ns->produkk);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $noseri_no, $ns->noseri);
                $noseri_no++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Template.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function preview_tanpa_so(Request $request)
    {
        try {
            // dd($request->all());
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();

            $file->move(public_path('upload/nonso/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/nonso/' . $filename));

            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();

            foreach ($row_range as $row) {
                $data[] = [
                    'tujuan' => $sheet->getCell('A' . $row)->getValue(),
                    'desk' => $sheet->getCell('B' . $row)->getValue(),
                    'produk' => $sheet->getCell('C' . $row)->getValue(),
                    'noseri' => $sheet->getCell('D' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $tujuan[] = $d['tujuan'];
                $desk[] = $d['desk'];
                $produk[] = $d['produk'];
                $noseri[] = $d['noseri'];
            }

            $cek = NoseriBarangJadi::whereIn('noseri', $noseri)->get()->pluck('noseri');
            $no_seri = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>Tujuan</th>
                    <th>Deskripsi</th>
                    <th>Produk</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                $d = $row['D'];
                if ($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "<td" . $nis_td . ">" . $d . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            if (count($cek) != count($noseri)) {
                $seri_final = [];
                foreach ($cek as $item) {
                    array_push($no_seri, $item);
                }

                foreach ($noseri as $ns) {
                    if (!in_array($ns, $no_seri)) {
                        array_push($seri_final, $ns);
                    }
                }
                return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri_final) . ' belum terdaftar', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri_final)]);
            } else {
                return response()->json(['msg' => 'File Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function store_nonso_to_db(Request $request)
    {
        try {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/nonso/' . $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();
            $dat_arr = array();
            $new_arr = array();
            $detail_arr = array();
            $noseri_arr = array();
            $dat_log = [];
            foreach ($row_range as $row) {
                $data[] = [
                    'tujuan' => $sheet->getCell('A' . $row)->getValue(),
                    'desk' => $sheet->getCell('B' . $row)->getValue(),
                    'produk' => $sheet->getCell('C' . $row)->getValue(),
                    'noseri' => $sheet->getCell('D' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $kh => $d) {
                $tujuan[] = $d['tujuan'];
                $desk[] = $d['desk'];
                $produk[] = $d['produk'];
                $noseri[] = $d['noseri'];
            }

            foreach ($produk as $key => $prd) {
                $dat_arr[] = [
                    'tujuan' => Divisi::where('nama', $tujuan[$key])->first()->id,
                    'produk' => GudangBarangJadi::join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                        ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $prd)
                        ->select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as name"))
                        ->first()->id,
                    'serii' => $noseri[$key],
                    'desk' => $desk[$key],
                    'noseri' => NoseriBarangJadi::where('noseri', $noseri[$key])->first()->id,
                ];
            }
            // return $dat_arr;
            $arr = [];
            foreach ($dat_arr as $da) {
                $arr[$da['produk']]['tujuan'] = $da['tujuan'];
                $arr[$da['produk']]['desk'] = $da['desk'];
                $arr[$da['produk']]['noseri'][] = $da['noseri'];
            }

            // return $arr;
            foreach ($arr as $key => $value) {
                $header = TFProduksi::create([
                    'tgl_keluar' => Carbon::now(),
                    'ke' => $value['tujuan'],
                    'deskripsi' => $value['desk'],
                    'jenis' => 'keluar',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                $detail = TFProduksiDetail::create([
                    't_gbj_id' => $header->id,
                    'gdg_brg_jadi_id' => $key,
                    'qty' => count($value['noseri']),
                    'jenis' => 'keluar',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                foreach ($value['noseri'] as $k => $v) {
                    NoseriTGbj::create([
                        't_gbj_detail_id' => $detail->id,
                        'noseri_id' => $v,
                        'layout_id' => 1,
                        'status_id' => 2,
                        'jenis' => 'keluar',
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $value['tujuan']]);
                    // NoseriLog::create([
                    //     'gbj_id' => $value['prd'],
                    //     'noseri_id' => $v,
                    //     'nonso' => $header->id,
                    //     'log_id' => $key,
                    //     'created_by' => $request->userid
                    // ]);
                }
            }

            $obj = [
                'data' => $arr,
                'tgl_keluar' => Carbon::now()
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Pengeluaran Tanpa SO By Upload',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);

            $del = new Filesystem;
            $del->cleanDirectory(public_path('upload/nonso/'));
            File::delete(public_path('upload/nonso/' . $request->namafile));

            return response()->json(['msg' => 'Data Terkirim']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // store
    function storeNoseri(Request $request)
    {
        try {
            foreach ($request->noseri as $key => $value) {
                $detail = TFProduksiDetail::find($request->t_gbj_detail_id);
                $detail->status_id = 3;
                $detail->updated_at = Carbon::now();
                $detail->save();

                $header = TFProduksi::find($detail->t_gbj_id);
                $header->tgl_masuk = Carbon::now();
                $header->status_id = 3;
                $header->updated_at = Carbon::now();
                $header->save();

                $noserigbj = new NoseriBarangJadi();
                $noserigbj->gdg_barang_jadi_id = $detail->gdg_brg_jadi_id;
                $noserigbj->ke = $header->ke;
                $noserigbj->noseri = $value;
                $noserigbj->jenis = 'MASUK';
                $noserigbj->created_at = Carbon::now();
                $noserigbj->save();
            }

            $gdg = GudangBarangJadi::find($detail->gdg_brg_jadi_id);
            $gdg->stok = $gdg->stok + $detail->qty;
            $gdg->save();

            return response()->json(['msg', 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function StoreBarangJadi(Request $request)
    {
        try {
            $id = $request->id;
            if (($request->produk_id && $request->satuan_id) == null) {
                return response()->json(['msg' => 'Produk dan Satuan Harus Diisi', 'error' => true]);
            } else {
                if ($id) {
                    $brg_jadi = GudangBarangJadi::find($id);

                    if (empty($brg_jadi->id)) {
                        return response()->json(['msg' => 'Data not found']);
                    }

                    $brg_jadi->produk_id = $request->produk_id;
                    $brg_jadi->satuan_id = $request->satuan_id;
                    $brg_jadi->nama = $request->nama == null ? ' ' : $request->nama;
                    $brg_jadi->deskripsi = $request->deskripsi;
                    $image = $request->file('gambar');
                    if ($image) {
                        $path = 'upload/gbj/';
                        $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($path, $nameImage);
                        $brg_jadi->gambar = $nameImage;
                    }
                    $brg_jadi->dim_p = $request->dim_p;
                    $brg_jadi->dim_l = $request->dim_l;
                    $brg_jadi->dim_t = $request->dim_t;
                    $brg_jadi->status = $request->status;
                    $brg_jadi->updated_at = Carbon::now();
                    $brg_jadi->updated_by = $request->userid;
                    $brg_jadi->save();

                    $obj =  [
                        'produk' => Produk::find($request->produk_id)->nama,
                        'variasi' => $request->nama,
                        'satuan' => Satuan::find($request->satuan_id)->nama,
                    ];
                    SystemLog::create([
                        'tipe' => 'GBJ',
                        'subjek' => 'Perubahan Produk Gudang',
                        'response' => json_encode($obj),
                        'user_id' => $request->userid,
                    ]);
                } else {
                    $brg_jadi = new GudangBarangJadi();
                    $brg_jadi->produk_id = $request->produk_id;
                    $brg_jadi->satuan_id = $request->satuan_id;
                    $brg_jadi->nama = $request->nama == null ? ' ' : $request->nama;
                    $brg_jadi->stok = 0;
                    $brg_jadi->deskripsi = $request->deskripsi;
                    $image = $request->file('gambar');
                    if ($image) {
                        $path = 'upload/gbj/';
                        $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($path, $nameImage);
                        $brg_jadi->gambar = $nameImage;
                    }
                    $brg_jadi->dim_p = $request->dim_p;
                    $brg_jadi->dim_l = $request->dim_l;
                    $brg_jadi->dim_t = $request->dim_t;
                    $brg_jadi->status = $request->status;
                    $brg_jadi->created_at = Carbon::now();
                    $brg_jadi->created_by = $request->userid;
                    $brg_jadi->save();

                    $obj =  [
                        'produk' => Produk::find($request->produk_id)->nama,
                        'variasi' => $request->nama,
                        'satuan' => Satuan::find($request->satuan_id)->nama,
                    ];
                    SystemLog::create([
                        'tipe' => 'GBJ',
                        'subjek' => 'Penambahan Produk Gudang',
                        'response' => json_encode($obj),
                        'user_id' => $request->userid,
                    ]);
                }
                return response()->json(['msg' => 'Successfully']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeDraftRancang(Request $request)
    {
        try {
            $h = new TFProduksi();
            $h->tgl_masuk = $request->tgl_masuk;
            $h->dari = $request->dari;
            $h->deskripsi = $request->deskripsi;
            $h->status_id = 1;
            $h->jenis = 'masuk';
            $h->created_at = Carbon::now();
            $h->created_by = $request->userid;
            $h->save();

            foreach ($request->data as $key => $value) {
                $d = new TFProduksiDetail();
                $d->t_gbj_id = $h->id;
                $d->gdg_brg_jadi_id = $key;
                $d->qty = $value['jumlah'];
                $d->status_id = 1;
                $d->jenis = 'masuk';
                $d->created_at = Carbon::now();
                $d->created_by = $request->userid;
                $d->save();

                foreach ($value['noseri'] as $key1 => $value1) {
                    $nn = new NoseriBarangJadi();
                    $nn->gdg_barang_jadi_id = $key;
                    $nn->dari = $request->dari;
                    $nn->noseri = $value1;
                    $nn->layout_id = $value['layout'][$key1];
                    $nn->jenis = 'MASUK';
                    $nn->is_aktif = 0;
                    $nn->created_by = $request->userid;
                    $nn->save();

                    $n = new NoseriTGbj();
                    $n->t_gbj_detail_id = $d->id;
                    $n->noseri_id = $nn->id;
                    $n->layout_id = $value['layout'][$key1];
                    $n->jenis = 'masuk';
                    $n->status_id = 1;
                    $n->state_id = 2;
                    $n->created_by = $request->userid;
                    $n->save();
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeFinalRancang(Request $request)
    {
        try {
            $h = new TFProduksi();
            $h->tgl_masuk = $request->tgl_masuk;
            $h->dari = $request->dari;
            $h->deskripsi = $request->deskripsi;
            $h->status_id = 2;
            $h->jenis = 'masuk';
            $h->created_at = Carbon::now();
            $h->created_by = $request->userid;
            $h->save();

            foreach ($request->data as $key => $value) {
                $d = new TFProduksiDetail();
                $d->t_gbj_id = $h->id;
                $d->gdg_brg_jadi_id = $key;
                $d->qty = $value['jumlah'];
                $d->status_id = 2;
                $d->jenis = 'masuk';
                $d->created_at = Carbon::now();
                $d->created_by = $request->userid;
                $d->save();

                foreach ($value['noseri'] as $key1 => $value1) {
                    $nn = new NoseriBarangJadi();
                    $nn->gdg_barang_jadi_id = $key;
                    $nn->dari = $request->dari;
                    $nn->noseri = strtoupper($value1);
                    $nn->layout_id = $value['layout'][$key1];
                    $nn->jenis = 'MASUK';
                    $nn->is_aktif = 1;
                    $nn->created_by = $request->userid;
                    $nn->save();

                    $n = new NoseriTGbj();
                    $n->t_gbj_detail_id = $d->id;
                    $n->noseri_id = $nn->id;
                    $n->layout_id = $value['layout'][$key1];
                    $n->jenis = 'masuk';
                    $n->status_id = 2;
                    $n->state_id = 3;
                    $n->created_by = $request->userid;
                    $n->save();
                }

                $gdg = GudangBarangJadi::whereIn('id', [$key])->get()->toArray();

                foreach ($gdg as $vv) {
                    $vv['stok'] = $vv['stok'] + $value['jumlah'];

                    GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok'], 'updated_by' => $request->userid]);
                    GudangBarangJadiHis::create([
                        'gdg_brg_jadi_id' => $vv['id'],
                        'stok' => $value['jumlah'],
                        'tgl_masuk' => $request->tgl_masuk,
                        'jenis' => 'MASUK',
                        'created_by' => $request->userid,
                        'created_at' => Carbon::now(),
                        'dari' => $request->dari,
                        'tujuan' => $request->deskripsi,
                    ]);
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function finalDraftRakit(Request $request)
    {
        try {
            $header = TFProduksi::create([
                'tgl_keluar' => Carbon::now(),
                'ke' => Divisi::find($request->divisi)->id,
                'deskripsi' => $request->deskripsi,
                'jenis' => 'keluar',
                'created_at' => Carbon::now(),
                'created_by' => $request->userid
            ]);

            foreach ($request->produk as $key => $value) {
                $detail = TFProduksiDetail::create([
                    't_gbj_id' => $header->id,
                    'gdg_brg_jadi_id' => $value['prd'],
                    'qty' => $value['jml'],
                    'jenis' => 'masuk',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                foreach ($value['noseri'] as $k => $v) {
                    $seri = NoseriBarangJadi::create([
                        'gdg_barang_jadi_id' =>  $value['prd'],
                        'dari' => $request->divisi,
                        'noseri' => strtoupper($v),
                        'layout_id' => $value['layout'][$k],
                        'jenis' => 'MASUK',
                        'is_aktif' => 1,
                        'created_by' => $request->userid,
                    ]);

                    NoseriTGbj::create([
                        't_gbj_detail_id' => $detail->id,
                        'noseri_id' => $seri->id,
                        'layout_id' => $value['layout'][$k],
                        'status_id' => 2,
                        'jenis' => 'masuk',
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);
                }

                $gdg = GudangBarangJadi::find($value['prd']);
                $stok = $gdg->stok + $value['jml'];
                $gdg->update(['stok' => $stok]);
            }

            $obj = [
                'dari' => Divisi::find($request->divisi)->nama,
                'deskripsi' => $request->deskripsi,
                'tgl_masuk' => $request->tgl_masuk,
                'data' => $request->produk
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Penerimaan Selain Perakitan',
                'response' => json_encode($obj),
                'user_id' => $request->userid,
            ]);

            return response()->json(['msg' => 'Data Berhasil Diterima', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function get_alasan_from_staff(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::find($request->id);

            return response()->json([
                'error' => false,
                'msg' => 'Data Berhasil Ditemukan',
                'data' => $data->remark
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getNoseriHistoryPerubahan(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where('gbj_id', $request->gbj)->orderByDesc('id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($q) {
                    if ($q->action == 'update') {
                        switch ($q->status) {
                            case 'rejected':
                                return '<span class="badge badge-danger">Perubahan Ditolak</span>';
                                break;

                            case 'approved':
                                return '<span class="badge badge-success">Perubahan Diterima</span>';
                                break;

                            default:
                                return '<span class="badge badge-dark">Menunggu Persetujuan</span>';
                                break;
                        }
                    } else {
                        switch ($q->status) {
                            case 'rejected':
                                return '<span class="badge badge-danger">Hapus Ditolak</span>';
                                break;

                            case 'approved':
                                return '<span class="badge badge-success">Hapus Diterima</span>';
                                break;

                            default:
                                return '<span class="badge badge-dark">Menunggu Persetujuan</span>';
                                break;
                        }
                    }
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#openModalHistory" class="openModalHistory" data-attr="" data-id="' . $d->id . '">
                                <button class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i>Detail</button>
                            </a>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function headerCountNoseri($id)
    {
        $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
            $d->where('id', $id);
        })
            ->where([
                'is_aktif' => 1,
                'is_ready' => 0,
                'is_change' => 1,
                'is_delete' => 0
            ])->get()->count();

        $data_done = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
            $d->where('id', $id);
        })->where([
            'is_aktif' => 1,
            'is_ready' => 1,
            'is_change' => 1,
            'is_delete' => 0
        ])->get()->count();

        $data_wait = NoseriBrgJadiLog::where([
            'status' => 'waiting'
        ])->whereHas('noseri', function ($d) use ($id) {
            $d->where('gdg_barang_jadi_id', $id);
        })->get()->count();

        return response()->json([
            'belum' => $data,
            'sudah' => $data_done,
            'wait' => $data_wait
        ]);
    }

    function detailNoseriHistoryPerubahan(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::find($request->id);
            if (!$data->komentar) {
                return response()->json([
                    'error' => false,
                    'msg' => 'Komentar Kosong Belum Di ACC',
                    'data_mgr' => '-',
                    'data_stf' => $data->remark
                ]);
            } else {
                return response()->json([
                    'error' => false,
                    'msg' => 'Komentar ada sudah Di ACC',
                    'data_mgr' => $data->komentar,
                    'data_stf' => $data->remark
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function deleteCekSO(Request $request)
    {
        try {
            // dd($request->all());
            $h = Pesanan::find($request->pesanan_id);
            foreach ($request->data as $key => $value) {
                $dpid = DetailPesananProduk::whereIn('id', [$key])->get()->pluck('detail_pesanan_id');
                DetailPesanan::whereIn('id', $dpid)->delete();
            }
            return response()->json(['msg' => 'Successfully', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeCekSO(Request $request)
    {
        $user = auth()->user()->id;
        $obj =  json_decode(json_encode($request->all()), FALSE);
        // dd($obj);
        try {
            foreach ($obj->produk as $value) {
                // $dpid = DetailPesananProduk::where('id', $key)->get()->pluck('detail_pesanan_id');
                // DetailPesanan::whereIn('id', $dpid)->update(['jumlah' => $value[1]]);
                // DetailPesananProduk::whereIn('id', [$key])
                //     ->update(['status_cek' => 4, 'checked_by' => $user, 'gudang_barang_jadi_id' => $value[0]]);
                $dpp = DetailPesananProduk::find($value->id);
                $dpp->gudang_barang_jadi_id = $value->variasiSelected->id;
                $dpp->status_cek = 4;
                $dpp->checked_by = $user;
                $dpp->save();
            }

            // $h->status_cek = 4;
            // $h->checked_by = $user;
            // $h->log_id = 6;
            // $h->save();

            // $obj = [
            //     'pesanan_so' => $h->so,
            //     'pesanan_po' => $h->no_po,
            //     'data' => $request->data,
            //     'status_cek' => 'Sudah Dicek'
            // ];

            // SystemLog::create([
            //     'tipe' => 'GBJ',
            //     'subjek' => 'Persiapan Produk Gudang',
            //     'response' => json_encode($obj),
            //     'user_id' => $user
            // ]);
            return response()->json(['msg' => 'Successfully', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => 'a' . $e->getMessage(),
            ]);
        }
    }
    // select
    function select_layout()
    {
        $data = Layout::where('jenis_id', 1)->get();
        return response()->json($data);
    }

    function select_product()
    {
        $data = Produk::with('product')->get();
        return response()->json($data);
    }

    function select_product_by_id($id)
    {
        $data = Produk::with('product')->find($id);
        return response()->json($data);
    }

    function select_satuan()
    {
        $data = Satuan::all();
        return response()->json($data);
    }

    function select_divisi()
    {
        $data = Divisi::whereNotIn('id', [1, 2, 3, 4, 5, 31])->get();
        return response()->json($data);
    }

    function select_gbj()
    {
        $data = GudangBarangJadi::with('produk')->get();
        return response()->json($data);
    }

    // dashboard

    function getNoseriTerima(Request $request, $id)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
                $q->where('t_gbj_detail_id', $id);
            })->get();
            return datatables()->of($data)
                ->addColumn('noser', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) {
                    if (isset($d->layout->ruang)) {
                        return $d->layout->ruang;
                    } else {
                        return '-';
                    }
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    // produk
    function h1()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h2()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h3()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h4()
    {

        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 6 MONTH ) and date_sub(now(), INTERVAL 3 MONTH)');

        return count($data);
    }

    function h5()
    {
        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 12 MONTH ) and date_sub(now(), INTERVAL 6 MONTH)');

        return count($data);
    }

    function h6()
    {
        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 36 MONTH ) and date_sub(now(), INTERVAL 12 MONTH)');

        return count($data);
    }

    function h7()
    {
        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
        from t_gbj_detail tgd
        left join t_gbj tg on tg.id = tgd.t_gbj_id
        left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
        left join produk p on p.id = gbj.produk_id
        left join m_satuan ms on ms.id = gbj.satuan_id
        where tgd.jenis = "masuk" and tg.tgl_masuk = date_sub(now(), interval 3 YEAR)');

        return count($data);
    }

    function getProdukstok1020()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->produk->nama . ' ' . $d->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->stok . ' ' . $d->satuan->nama;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukstok59()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->produk->nama . ' ' . $d->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->stok . ' ' . $d->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal2" data-attr=""  data-id="' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukstok14()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->produk->nama . ' ' . $d->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->stok . ' ' . $d->satuan->nama;
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukIn36()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 6 MONTH ) and date_sub(now(), INTERVAL 3 MONTH)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukIn612()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 12 MONTH ) and date_sub(now(), INTERVAL 6 MONTH)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProduk1236()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 36 MONTH ) and date_sub(now(), INTERVAL 12 MONTH)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProduk36Plus()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
                from t_gbj_detail tgd
                left join t_gbj tg on tg.id = tgd.t_gbj_id
                left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
                left join produk p on p.id = gbj.produk_id
                left join m_satuan ms on ms.id = gbj.satuan_id
                where tgd.jenis = "masuk" and tg.tgl_masuk = date_sub(now(), interval 3 YEAR)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukByLayout(Request $request)
    {
        try {
            $data = DB::select('SELECT CONCAT(p.nama," ",gbj.nama) as prduk, CONCAT(COUNT(nbj.layout_id)," ",ms.nama)  as jumlah, nbj.layout_id, ml.ruang
            from noseri_barang_jadi nbj
            left join m_layout ml on ml.id = nbj.layout_id
            left join gdg_barang_jadi gbj on gbj.id = nbj.gdg_barang_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            group by nbj.gdg_barang_jadi_id, nbj.layout_id');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jml', function ($d) {
                    return $d->jumlah;
                })
                ->addColumn('layout', function ($d) {
                    if (isset($d->layout_id)) {
                        return $d->ruang;
                    } else {
                        return '-';
                    }
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // penerimaan
    function hh1()
    {
        $data = DB::table(DB::raw('t_gbj_detail tgd'))
            ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
            ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
            ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
            ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
            ->where('tgd.jenis', '=', 'masuk')
            ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 1 day)')
            ->get();
        return count($data);
    }

    function hh2()
    {
        $data = DB::table(DB::raw('t_gbj_detail tgd'))
            ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
            ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
            ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
            ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
            ->where('tgd.jenis', '=', 'masuk')
            ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 2 day)')
            ->get();
        return count($data);
    }

    function hh3()
    {
        $data = DB::table(DB::raw('t_gbj_detail tgd'))
            ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
            ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
            ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
            ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
            ->where('tgd.jenis', '=', 'masuk')
            ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 3 day)')
            ->get();
        return count($data);
    }

    function getPenerimaanProduk1(Request $request)
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 1 day)')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->tgl_masuk);
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->prduk . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getPenerimaanProduk2(Request $request)
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 2 day)')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->tgl_masuk);
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->prduk . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getPenerimaanProduk3(Request $request)
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 3 day)')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->tgl_masuk);
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->prduk . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getPenerimaanAll()
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if ($d->tgl_masuk) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY');
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="' . $d->prduk . '" data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // penjualan
    function he1()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select(
                'p.id',
                'p.so',
                'p.no_po',
                'p.log_id',
                DB::raw('count(dpp.gudang_barang_jadi_id)'),
                DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                'ms.nama as log_nama',
                DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
            )
            ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
            ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
            ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
            ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
            ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 1 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function he2()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select(
                'p.id',
                'p.so',
                'p.no_po',
                'p.log_id',
                DB::raw('count(dpp.gudang_barang_jadi_id)'),
                DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                'ms.nama as log_nama',
                DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
            )
            ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
            ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
            ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
            ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
            ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 2 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function he3()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select(
                'p.id',
                'p.so',
                'p.no_po',
                'p.log_id',
                DB::raw('count(dpp.gudang_barang_jadi_id)'),
                DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                'ms.nama as log_nama',
                DB::raw("case
        when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
        when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
        when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
        when p.so is null then c_ekat.nama
        end as divisi")
            )
            ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
            ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
            ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
            ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
            ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end <= date_sub(now(), interval 3 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function list_tf1()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
                ->select(
                    'p.id',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    DB::raw('count(dpp.gudang_barang_jadi_id)'),
                    DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                    DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                    DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                    'ms.nama as log_nama',
                    DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
                )
                ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
                ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
                ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
                ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
                ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 1 day)')
                ->groupBy('p.id')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_batas', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log_nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_tf2()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
                ->select(
                    'p.id',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    DB::raw('count(dpp.gudang_barang_jadi_id)'),
                    DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                    DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                    DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                    'ms.nama as log_nama',
                    DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
                )
                ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
                ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
                ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
                ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
                ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 2 day)')
                ->groupBy('p.id')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_batas', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log_nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_tf3()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
                ->select(
                    'p.id',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    DB::raw('count(dpp.gudang_barang_jadi_id)'),
                    DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                    DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                    DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                    'ms.nama as log_nama',
                    DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
                )
                ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
                ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
                ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
                ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
                ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end <= date_sub(now(), interval 3 day)')
                ->groupBy('p.id')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_batas', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log_nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detailsale($id, $value)
    {
        try {
            if ($value == "ekatalog") {
                $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $detail_id = array();
                foreach ($detail_pesanan as $d) {
                    $detail_id[] = $d->id;
                }

                $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
            } else if ($value == "spa") {
                $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $detail_id = array();
                foreach ($detail_pesanan as $d) {
                    $detail_id[] = $d->id;
                }

                $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
            } else if ($value == "spb") {
                $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $detail_id = array();
                foreach ($detail_pesanan as $d) {
                    $detail_id[] = $d->id;
                }

                $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
            }

            return datatables()->of($g)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    if (empty($data->gudangbarangjadi->nama)) {
                        return $data->gudangbarangjadi->produk->nama;
                    } else {
                        return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->detailpesanan->jumlah . ' ' . $data->gudangbarangjadi->satuan->nama;
                })
                ->addColumn('tipe', function ($data) {
                    return $data->gudangbarangjadi->produk->nama;
                })
                ->addColumn('merk', function ($data) {
                    return $data->gudangbarangjadi->produk->merk;
                })
                ->addColumn('button', function ($data) {
                    return '<a type="button" class="noserishow" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function outSO()
    {
        try {
            $data = DB::table('detail_pesanan_produk as dpp')
                ->select(DB::raw('concat(p.nama, " ", gbj.nama) as prd'), DB::raw('sum(dp.jumlah) as jumlah'), 'gbj.stok')
                ->join('gdg_barang_jadi as gbj', 'gbj.id', '=', 'dpp.gudang_barang_jadi_id')
                ->join('detail_pesanan as dp', 'dp.id', '=', 'dpp.detail_pesanan_id')
                ->join('produk as p', 'p.id', '=', 'gbj.produk_id')
                ->where('jumlah', '>', 'gbj.stok')
                ->groupBy('dpp.gudang_barang_jadi_id')
                ->orderBy(DB::raw('concat(p.nama, " ", gbj.nama)'))
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('produk', function ($d) {
                    return $d->prd;
                })
                ->addColumn('permintaan', function ($d) {
                    return $d->jumlah;
                })
                ->addColumn('current_stok', function ($d) {
                    return $d->stok;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function updateSeriLayout(Request $request)
    {
        try {
            $data = NoseriBarangJadi::whereIn('id', $request->cekid)->get();
            foreach ($data as $d) {
                for ($i = 0; $i < count($request->layout); $i++) {
                    NoseriBarangJadi::where('id', $request->cekid[$i])->update(['layout_id' => json_decode($request->layout[$i], true)]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function addSeri(Request $request)
    {
        try {
            $count = count($request->no_seri);
            for ($i = 0; $i < $count; $i++) {
                NoseriBarangJadi::create([
                    'noseri' => strtoupper($request->no_seri[$i]),
                    'layout_id' => $request->layout[$i],
                    'gdg_barang_jadi_id' => $request->id,
                    'dari' => $request->dari,
                    'created_by' => $request->created_by,
                    'jenis' => 'MASUK',
                    'is_aktif' => 1
                ]);
            }

            $a = GudangBarangJadi::where('id', $request->id)->first();
            $stok = $a->stok + $count;
            // return $stok;
            GudangBarangJadi::where('id', $request->id)->update(['stok' => $stok]);

            $pid = GudangBarangJadi::find($request->id)->produk_id;
            $obj_seri = [
                $request->no_seri
            ];
            $obj = [
                'produk' => Produk::find($pid)->nama . ' ' . GudangBarangJadi::find($request->id)->nama,
                'jumlah' => $count,
                'noseri' => $request->no_seri,
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Penambahan Noseri Produk',
                'response' => json_encode($obj),
                'user_id' => $request->created_by,
            ]);
            return response()->json(['success' => 'Sukses', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '35';
        } else {
            $days = '28';
        }
        return Carbon::parse($value)->subDays($days);
    }

    function TfbySOFinal(Request $request)
    {

        $obj =  json_decode(json_encode($request->all()), FALSE);

        try {
            $a = TFProduksi::where('pesanan_id', $request->pesanan_id)->first();

            if ($a) {

                foreach ($request->produk as $values) {
                    $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $values['gudang_id'])->where('detail_pesanan_produk_id', $values['id'])->first();

                    if ($c) {

                        // return 'aa';
                        foreach ($values['noseri'] as $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $c->id,
                                'noseri_id' => $v['id'],
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => auth()->user()->id
                            ]);
                            NoseriBarangJadi::find($v['id'])->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }
                        // $gdg = GudangBarangJadi::whereIn('id', [$values['gudang_id']])->get()->toArray();
                        // $i = 0;
                        // foreach ($gdg as $vv) {
                        //     $vv['stok'] = $vv['stok'] - $values['jumlah'];
                        //     // print_r($vv['stok']);
                        //     $i++;
                        //     GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        //     GudangBarangJadiHis::create([
                        //         'gdg_brg_jadi_id' => $vv['id'],
                        //         'stok' => $values['jumlah'],
                        //         'tgl_masuk' => Carbon::now(),
                        //         'jenis' => 'KELUAR',
                        //         'created_by' => $request->userid,
                        //         'created_at' => Carbon::now(),
                        //         'ke' => 23,
                        //         'tujuan' => $request->deskripsi,
                        //     ]);
                        // }

                        // $obj = [
                        //     'produk' => Produk::find(GudangBarangJadi::find($values['prd'])->produk_id)->nama . ' ' . GudangBarangJadi::find($values['prd'])->nama,
                        //     'dpp_id' => $key,
                        //     'pesanan_so' => Pesanan::find($request->pesanan_id)->so,
                        //     'pesanan_po' => Pesanan::find($request->pesanan_id)->no_po,
                        //     'jumlah' => count($values['noseri']),
                        //     'noseri' => NoseriBarangJadi::whereIn('id', $values['noseri'])->get()->pluck('noseri'),
                        //     'tgl_keluar' => Carbon::now()
                        // ];

                        // SystemLog::create([
                        //     'tipe' => 'GBJ',
                        //     'subjek' => 'Sales Order Noseri By Sistem',
                        //     'response' => json_encode($obj),
                        //     'user_id' => auth()->user()->id
                        // ]);
                    } else {

                        $dd = TFProduksiDetail::create([
                            't_gbj_id' => $a->id,
                            'detail_pesanan_produk_id' => $values['id'],
                            'gdg_brg_jadi_id' => $values['gudang_id'],
                            'qty' => count($values['noseri']),
                            'jenis' => 'keluar',
                            'status_id' => 2,
                            'state_id' => 8,
                            'created_at' => Carbon::now(),
                            'created_by' => auth()->user()->id
                        ]);

                        $did = $dd->id;
                        $checked = $request->noseri_id;
                        foreach ($values['noseri'] as $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $did,
                                'noseri_id' => $v['id'],
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => auth()->user()->id
                            ]);

                            NoseriBarangJadi::find($v['id'])->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        // $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                        // $i = 0;
                        // foreach ($gdg as $vv) {
                        //     $vv['stok'] = $vv['stok'] - $values['jumlah'];
                        //     // print_r($vv['stok']);
                        //     $i++;
                        //     GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        //     GudangBarangJadiHis::create([
                        //         'gdg_brg_jadi_id' => $vv['id'],
                        //         'stok' => $values['jumlah'],
                        //         'tgl_masuk' => Carbon::now(),
                        //         'jenis' => 'KELUAR',
                        //         'created_by' => $request->userid,
                        //         'created_at' => Carbon::now(),
                        //         'ke' => 23,
                        //         'tujuan' => $request->deskripsi,
                        //     ]);
                        // }

                        // $obj = [
                        //     'produk' => Produk::find(GudangBarangJadi::find($values['prd'])->produk_id)->nama . ' ' . GudangBarangJadi::find($values['prd'])->nama,
                        //     'dpp_id' => $key,
                        //     'pesanan_so' => Pesanan::find($request->pesanan_id)->so,
                        //     'pesanan_po' => Pesanan::find($request->pesanan_id)->no_po,
                        //     'jumlah' => count($values['noseri']),
                        //     'noseri' => NoseriBarangJadi::whereIn('id', $values['noseri'])->get()->pluck('noseri'),
                        //     'tgl_keluar' => Carbon::now()
                        // ];

                        // SystemLog::create([
                        //     'tipe' => 'GBJ',
                        //     'subjek' => 'Sales Order Noseri By Sistem',
                        //     'response' => json_encode($obj),
                        //     'user_id' => auth()->user()->id
                        // ]);
                    }
                }
            } else {

                // return 'b';
                $d = TFProduksi::create([
                    'pesanan_id' => $request->pesanan_id,
                    'tgl_keluar' => Carbon::now(),
                    'ke' => 23,
                    'jenis' => 'keluar',
                    'status_id' => 2,
                    'state_id' => 8,
                    'created_at' => Carbon::now(),
                    'created_by' => auth()->user()->id
                ]);

                $hid = $d->id;
                foreach ($request->produk as $key1 => $value1) {
                    $dd = TFProduksiDetail::create([
                        't_gbj_id' => $hid,
                        'detail_pesanan_produk_id' => $value1['id'],
                        'gdg_brg_jadi_id' => $value1['gudang_id'],
                        'qty' => count($value1['noseri']),
                        'jenis' => 'keluar',
                        'status_id' => 2,
                        'state_id' => 8,
                        'created_at' => Carbon::now(),
                        'created_by' => auth()->user()->id
                    ]);

                    $did = $dd->id;
                    $checked = $request->noseri_id;
                    foreach ($value1['noseri'] as $k => $v) {
                        NoseriTGbj::create([
                            't_gbj_detail_id' => $did,
                            'noseri_id' => $v['id'],
                            'status_id' => 2,
                            'state_id' => 8,
                            'jenis' => 'keluar',
                            'created_at' => Carbon::now(),
                            'created_by' => auth()->user()->id
                        ]);

                        NoseriBarangJadi::find($v['id'])->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                    }

                    // $gdg = GudangBarangJadi::whereIn('id',  $value1['gudang_id'])->get()->toArray();
                    // $i = 0;
                    // foreach ($gdg as $vv) {
                    //     $vv['stok'] = $vv['stok'] - $value1['jumlah'];
                    //     // print_r($vv['stok']);
                    //     $i++;
                    //     GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                    //     GudangBarangJadiHis::create([
                    //         'gdg_brg_jadi_id' => $vv['id'],
                    //         'stok' => $value1['jumlah'],
                    //         'tgl_masuk' => Carbon::now(),
                    //         'jenis' => 'KELUAR',
                    //         'created_by' => $request->userid,
                    //         'created_at' => Carbon::now(),
                    //         'ke' => 23,
                    //         'tujuan' => $request->deskripsi,
                    //     ]);
                    // }
                }

                // $obj = [
                //     'data' => $request->data,
                //     'pesanan_so' => Pesanan::find($request->pesanan_id)->so,
                //     'pesanan_po' => Pesanan::find($request->pesanan_id)->no_po,
                //     'tgl_keluar' => Carbon::now()
                // ];

                // SystemLog::create([
                //     'tipe' => 'GBJ',
                //     'subjek' => 'Sales Order Noseri By Sistem',
                //     'response' => json_encode($obj),
                //     'user_id' => auth()->user()->id
                // ]);
            }



            $po = Pesanan::find($request->pesanan_id);

            if ($po->getJumlahPesanan() == $po->cekJumlahkirim()) {
                Pesanan::find($request->pesanan_id)->update(['log_id' => 8]);
            } else {
                Pesanan::find($request->pesanan_id)->update(['log_id' => 6]);
            }
            return response()->json(['msg' => 'Data Terkirim ke QC']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function TfbySOBatal($id)
    {
        try {
            //code...
            $dpp = DB::select('select group_concat(dpp.id) as id from detail_pesanan_produk dpp
            left join detail_pesanan dp
            on dpp.detail_pesanan_id = dp.id
            left join pesanan p
            on p.id = dp.pesanan_id
            where p.id = ? AND
             NOT EXISTS (
                SELECT *
                FROM t_gbj_detail tgd
                WHERE tgd.detail_pesanan_produk_id  = dpp.id
            )', [$id]);


            $cek_dpp = DB::select('select count(dpp.id) as id from detail_pesanan_produk dpp
            left join detail_pesanan dp
            on dpp.detail_pesanan_id = dp.id
            left join pesanan p
            on p.id = dp.pesanan_id
            where p.id = ? and  dpp.status_cek is not null ', [$id]);

            $dppArray = explode(',', $dpp[0]->id);

            DetailPesananProduk::whereIn('id', $dppArray)
                ->update(['status_cek' => NULL, 'checked_by' => NULL]);


            if ($cek_dpp[0]->id > 0) {
                return response()->json([
                    'msg' => 'Berhasil'
                ], 200);
            } else {
                return response()->json([
                    'error' => true,
                    'msg' => 'Gagal'
                ], 500);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'error' => true,
                'msg' => 'Gagal'
            ], 500);
        }
    }

    function get_so_batal()
    {
        try {
            // $Ekatalog = collect(Pesanan::has('Ekatalog')->whereIn('log_id', [20])->get());
            // $Spa = collect(Pesanan::has('Spa')->whereIn('log_id', [20])->get());
            // $Spb = collect(Pesanan::has('Spb')->whereIn('log_id', [20])->get());

            // $data = $Ekatalog->merge($Spa)->merge($Spb);
            // $x = [];
            // foreach ($data as $k) {
            //     $x[] = $k->id;
            // }

            // $datax = TFProduksi::whereIn('pesanan_id', $x)->get();
            $data = TFProduksi::whereHas('Pesanan', function ($q) {
                $q->where('log_id', 20);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->pesanan->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->pesanan->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    if ($data->Pesanan->Ekatalog) {
                        return $data->pesanan->Ekatalog->Customer->nama;
                    } elseif ($data->Pesanan->Spa) {
                        return $data->pesanan->Spa->Customer->nama;
                    } elseif ($data->Pesanan->Spb) {
                        return $data->pesanan->Spb->Customer->nama;
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if ($data->Pesanan->Ekatalog) {
                        return '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="ekatalog"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                                        <button class="btn btn-outline-info btn-sm" type="button">
                                            <i class="fas fa-eye"></i>&nbsp;Detail
                                        </button>
                                    </a>';
                    } elseif ($data->Pesanan->Spa) {
                        return $data->pesanan->tgl_batal;
                    } elseif ($data->Pesanan->Spb) {
                        return '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="spb"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                                        <button class="btn btn-outline-info btn-sm" type="button">
                                            <i class="fas fa-eye"></i>&nbsp;Detail
                                        </button>
                                    </a>';
                    }
                    // $name = explode('/', $data->pesanan->so);
                    // for ($i = 1; $i < count($name); $i++) {
                    //     if ($name[1] == 'EKAT') {
                    //         $a = '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="ekatalog"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                    //                 <button class="btn btn-outline-info btn-sm" type="button">
                    //                     <i class="fas fa-eye"></i>&nbsp;Detail
                    //                 </button>
                    //             </a>';
                    //     } elseif ($name[1] == 'SPA') {
                    //         $a = '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="spa"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                    //                 <button class="btn btn-outline-info btn-sm" type="button">
                    //                     <i class="fas fa-eye"></i>&nbsp;Detail
                    //                 </button>
                    //             </a>';
                    //     } elseif ($name[1] == 'SPB') {
                    //         $a = '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="spb"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                    //                 <button class="btn btn-outline-info btn-sm" type="button">
                    //                     <i class="fas fa-eye"></i>&nbsp;Detail
                    //                 </button>
                    //             </a>';
                    //     }
                    // }
                    // return $a;
                })
                ->addColumn('logs', function ($d) {
                    // if ($d->pesanan->log_id == 9) {
                    //     $ax = "<span class='badge badge-pill badge-secondary'>".$d->pesanan->log->nama."</span>";
                    // } else if ($d->pesanan->log_id == 6) {
                    //     $ax = "<span class='badge badge-pill badge-warning'>".$d->pesanan->log->nama."</span>";
                    // } elseif ($d->pesanan->log_id == 8) {
                    //     $ax = "<span class='badge badge-pill badge-info'>".$d->pesanan->log->nama."</span>";
                    // } elseif ($d->pesanan->log_id == 11) {
                    //     $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                    // } else {
                    //     $ax = "<span class='badge badge-pill badge-danger'>".$d->pesanan->log->nama."</span>";
                    // }

                    // return $ax;
                })
                ->rawColumns(['aksi', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_so_batal(Request $request)
    {
        try {
            $check = TFProduksi::where('pesanan_id', $request->pesananid)->first();
            if ($check) {
                $chk_detail = TFProduksiDetail::whereIn('t_gbj_id', [$check->id])->get();
                $did = [];
                foreach ($chk_detail as $detail) {
                    $did[] = $detail->id;
                }
                $nid = [];
                $nidd = [];
                $chk_noseri = NoseriTGbj::whereIn('t_gbj_detail_id', $did)->get();
                foreach ($chk_noseri as $noseri) {
                    $nid[] = $noseri->id;
                    $nidd[] = $noseri->noseri_id;
                }

                $seri = NoseriBarangJadi::whereIn('id', $nidd)->get();
                $seri_qc = NoseriDetailPesanan::whereIn('t_tfbj_noseri_id', $nid)->get();
                if (count($seri_qc) != 0) {
                    return response()->json([
                        'error' => true,
                        'msg' => 'Mohon Tunggu Proses Batal dari QC'
                    ]);
                } else {
                    NoseriBarangJadi::whereIn('id', $nidd)->update(['is_ready' => 0, 'used_by' => null]);
                    NoseriTGbj::whereIn('t_gbj_detail_id', $did)->delete();
                    TFProduksiDetail::where('t_gbj_id', $check->id)->delete();
                    TFProduksi::where('pesanan_id', $request->pesananid)->delete();
                    return response()->json([
                        'error' => false,
                        'msg' => 'Proses Restock Berhasil'
                    ]);
                }
            } else {
                return 'tidak ada';
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }


    function export_noseri_gudang(Request $request)
    {
        return Excel::download(new NoseriGudangExport(), 'NoseriBarangJadi.xlsx');
    }

    function history_modal_data_seri_tf($id)
    {
        $data = NoseriBarangJadi::select('noseri_barang_jadi.id', 'noseri_barang_jadi.noseri', 'seri_detail_rw.isi', 'seri_detail_rw.created_at')
            ->leftjoin('t_gbj_noseri', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
            ->where('t_gbj_detail.detail_pesanan_produk_id', $id)->get();

        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $obj[] = array(
                    'id' => $d->id,
                    'noseri' => $d->noseri,
                    'tgl_dibuat' => $d->created_at ? $d->created_at->format('Y-m-d') : '-',
                    'packer' => $d->packer ? $d->packer : '-',
                    'item' => $d->isi == null ? array() : json_decode($d->isi)
                );
            }
        }

        return response()->json($obj);
    }
    function history_modal_gbj($id)
    {
        $data = Pesanan::find($id);
        return view('page.gbj.tp.modal_data', ['data' => $data]);
    }
    function history_modal_gbj_non($id)
    {
        $data = TFProduksi::find($id);
        return view('page.gbj.tp.modal_data_non', ['data' => $data]);
    }
    function history_modal_gbj_seri($id)
    {
        $tf = TFProduksi::where('pesanan_id', $id)->first('id');
        $data = TFProduksiDetail::whereHas('header', function ($q) use ($tf) {
            $q->where('id', $tf->id);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($data) {
                return $data->produk->nama != '' ?  $data->produk->produk->nama . ' ' . $data->produk->nama : $data->produk->produk->nama;
            })
            ->addColumn('seri', function ($data) {
                if (isset($data->noseri)) {
                    $array = array();
                    foreach ($data->noseri as $i) {
                        $array[] = $i->NoseriBarangJadi->noseri;
                    }
                    return implode(", ", $array);
                } else {
                    return '-';
                }
            })
            ->make(true);
    }
    function history_modal_gbj_seri_non($id)
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($data) {
                return $data->produk->nama != '' ?  $data->produk->produk->nama . ' ' . $data->produk->nama : $data->produk->produk->nama;
            })
            ->addColumn('seri', function ($data) {
                if (isset($data->noseri)) {
                    $array = array();
                    foreach ($data->noseri as $i) {
                        $array[] = $i->NoseriBarangJadi->noseri;
                    }
                    return implode(", ", $array);
                } else {
                    return '-';
                }
            })
            ->make(true);
    }
    function ganti_unit_data_detail_seri($id)
    {

        $did = DetailPesananProduk::select('detail_pesanan.pesanan_id as id', 'detail_pesanan_produk.gudang_barang_jadi_id as gbj_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->where('detail_pesanan_produk.id', $id)
            ->first();

        $data = NoseriTGbj::select('uji_lab_detail.status', 't_gbj_noseri.id', 't_gbj_noseri.noseri_id as noseri_id', 'noseri_barang_jadi.noseri', 'noseri_detail_pesanan.id as ndp_id', 't_gbj_detail.detail_pesanan_produk_id')
            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->leftJoin('noseri_detail_pesanan', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('uji_lab_detail', 'uji_lab_detail.noseri_id', '=', 'noseri_detail_pesanan.id')
            ->where('t_gbj_noseri.status_id', 10)
            ->where('t_gbj_detail.gdg_brg_jadi_id', $did->gbj_id)
            ->where('t_gbj.pesanan_id', $did->id)
            ->get();

        // $data = NoseriDetailPesanan::select('noseri_detail_pesanan.id','detail_pesanan.pesanan_id as p_id')
        // ->leftJoin('detail_pesanan_produk','detail_pesanan_produk.id' ,'=','noseri_detail_pesanan.detail_pesanan_produk_id')
        // ->leftJoin('detail_pesanan','detail_pesanan_produk.id' ,'=','noseri_detail_pesanan.detail_pesanan_produk_id')
        // ->leftJoin('t_gbj_noseri','t_gbj_noseri.id' ,'=','noseri_detail_pesanan.t_tfbj_noseri_id')
        // ->where('t_gbj_noseri.status_id',10)
        // // ->where('noseri_detail_pesanan.status','nok')
        // // ->where('detail_pesanan.pesanan_id',$did->id)
        // // ->where('detail_pesanan_produk.gudang_barang_jadi_id',$did->gbj_id)
        // // ->groupBy('noseri_detail_pesanan.id')
        // ->get();

        foreach ($data as $d) {
            $obj[] = array(
                'id' => $d->ndp_id,
                'noseri_id' => $d->noseri_id,
                'detail_pesanan_produk_id' => $d->detail_pesanan_produk_id,
                't_tfbj_noseri_id' => $d->id,
                'noseri' => $d->noseri,
                'state' => $d->status == 'nok' ? 'lab' : 'qc'
            );
        }
        return $obj;
    }

    function ganti_unit_data_detail($id)
    {
        $data = DetailPesananProduk::select('gdg_barang_jadi.id as gbj_id', 'detail_pesanan_produk.id', 'produk.nama', 'gdg_barang_jadi.nama as variasi')
            ->addSelect([
                'qc' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                        ->from('noseri_detail_pesanan')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->where('noseri_detail_pesanan.is_lab', 0)
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
                'kalibrasi' => function ($q) {
                    $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                        ->from('uji_lab_detail')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                        ->where('uji_lab_detail.status', 'nok')
                        ->where('uji_lab_detail.is_ready', 0)
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->whereColumn('uji_lab_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id');
                },
            ])
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->where('detail_pesanan.pesanan_id', $id)
            ->havingRaw('qc > 0 OR kalibrasi > 0')
            ->get();

        foreach ($data as $d) {
            $obj[] = array(
                'id' => $d->id,
                'gbj_id' => $d->gbj_id,
                'nama' => $d->nama . ' ' . $d->variasi,
                'jumlah' => $d->qc + $d->kalibrasi,
                'seri_ganti' => $this->ganti_unit_data_detail_seri($d->id)
            );
        }

        $groupedData = [];
        foreach ($obj as $item) {
            $gbjId = $item['gbj_id'];
            $jumlah = $item['jumlah'];

            if (array_key_exists($gbjId, $groupedData)) {
                $groupedData[$gbjId]['jumlah'] += $jumlah;
            } else {
                $groupedData[$gbjId] = $item;
            }
        }

        return response()->json(array_values($groupedData));
    }

    function riwayat_ganti_unit()
    {
        $data = SystemLog::where(['tipe' => 'GBJ', 'subjek' => 'Penggantian Noseri'])->get();
        $obj = [];
        foreach ($data as $d) {
            $x = json_decode($d->response);
            $obj[] = array(
                'id' => $d->id,
                'so' => $x->so,
                'po' => $x->no_po,
                'tgl_tf' => $d->created_at,
                'jumlah_unit' => count($x->produk),
                'isi' => $x->produk
            );
        }

        return response()->json([
            'data' => $obj
        ]);
    }
    function riwayat_ganti_unit_produk($id)
    {
        $data = SeriGanti::join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'seri_ganti.detail_pesanan_produk_id')
            ->where('detail_pesanan_produk.gudang_barang_jadi_id', $id)->get();

        $obj = [];
        foreach ($data as $d) {
            $x = json_decode($d->isi);
            $obj[] = array(
                'seri_lama' => $x[0]->noseri,
                'seri_baru' => $x[0]->noseri_baru,
                'tgl_kirim' => $x[0]->tgl_kirim,
                'state' => $x[0]->state
            );
        }

        return response()->json([
            'data' => $obj
        ]);
    }


    function tf_ganti_unit_data(Request $request)
    {

        //BELUM SELESAI GUYS JGN DI RUBAH YA
        DB::beginTransaction();
        try {
            //code...
            $obj =  json_decode(json_encode($request->all()), FALSE);
            foreach ($obj->produk as $o) {
                if (count($o->noseri) != count($o->seri_ganti)) {
                    DB::rollback();
                    return response()->json([
                        'status' => 404,
                        'message' => 'Jumlah noseri tidak sama',
                    ], 500);
                }
                $noseri_lama[] = $o->seri_ganti;
                $noseri_baru[] = $o->noseri;
            }

            foreach ($noseri_lama as $key_a => $a) {
                foreach ($a as $key_b => $b) {
                    $id_seri_compact[] = $b;
                }
            }

            foreach ($noseri_baru as $key_a => $a) {
                foreach ($a as $key_b => $b) {
                    NoseriTGbj::where('id', $noseri_lama[$key_a][$key_b]->t_tfbj_noseri_id)->update([
                        'status_id' => 2,
                        'noseri_id' =>  $b->id
                    ]);
                    NoseriBarangJadi::where('id', $noseri_lama[$key_a][$key_b]->noseri_id)->update([
                        'is_ready' => 0,
                        'is_aktif' => 0,
                        'used_by' => NULL,
                        'keterangan' => 'Tidak OK Dari QC',
                    ]);
                    NoseriBarangJadi::where('id', $b->id)->update([
                        'is_ready' => 1,
                        'used_by' => $obj->id,
                        'keterangan' => '',
                    ]);
                    $noseri_filter[] =  $noseri_lama[$key_a][$key_b];
                    $c[] = $noseri_lama[$key_a][$key_b]->noseri_id;

                    $riwayat[] = array(
                        'detai_pesanan_produk_id' =>  $noseri_lama[$key_a][$key_b]->detail_pesanan_produk_id,
                        'noseri' =>  $noseri_lama[$key_a][$key_b]->noseri,
                        'noseri_baru' =>  $noseri_baru[$key_a][$key_b]->noseri,
                        'state' =>  $noseri_lama[$key_a][$key_b]->state,
                        'tgl_kirim' => Carbon::now()->format('Y-m-d')
                    );
                }
            }

            $id_seri = array_map(function ($item) {
                return $item->id;
            }, array_filter($noseri_filter, function ($item) {
                return $item->state == 'lab';
            }));

            $id_seri = array_values($id_seri);

            $resultArray = [];
            foreach ($riwayat as $item) {
                $parentId = $item["detai_pesanan_produk_id"];
                if (!isset($resultArray[$parentId])) {
                    // If not, create the parent
                    $resultArray[$parentId] = [
                        "detai_pesanan_produk_id" => $parentId,
                        "item" => []
                    ];
                }
                $resultArray[$parentId]["item"][] = [
                    "noseri" => $item["noseri"],
                    "noseri_baru" => $item["noseri_baru"],
                    "tgl_kirim" => $item["tgl_kirim"],
                    "state" => $item["state"]
                ];
            }

            // Reset array indices
            $resultArray = array_values($resultArray);


            foreach ($resultArray as $a) {
                SeriGanti::create([
                    'detail_pesanan_produk_id' => $a["detai_pesanan_produk_id"],
                    'isi' => json_encode($a["item"]),
                    'state' => '-'
                ]);
            }

            if (count($id_seri) > 0) {
                $uji = UjiLabDetail::select(
                    'uji_lab_detail.uji_lab_id',
                    'uji_lab_detail.id',
                    'uji_lab_detail.detail_pesanan_produk_id',
                    'uji_lab_detail.no_sertifikat',
                    'uji_lab_detail.noseri_id',
                    'uji_lab_detail.tgl_masuk',
                    'uji_lab_detail.created_at',
                    'uji_lab_detail.tgl_kalibrasi',
                    'uji_lab_detail.metode_id',
                    'uji_lab.no_order',
                    'uji_lab.pesanan_id',
                    'uji_lab.jenis_pemilik_id',
                    'uji_lab.nama',
                    'uji_lab.alamat'
                )
                    ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                    ->whereIN('uji_lab_detail.noseri_id', $id_seri);

                $lab_id = $uji->pluck('uji_lab_id')->first();


                if ($uji->count() > 0) {
                    foreach ($uji->get() as $u) {
                        RiwayatGagalKalibrasi::create([
                            'detail_pesanan_produk_id' => $u->detail_pesanan_produk_id,
                            'isi' => json_encode(
                                array(
                                    'id' => $u->id,
                                    'no_order' => $u->no_order,
                                    'pesanan_id' => $u->pesanan_id,
                                    'jenis_pemilik_id' => $u->jenis_pemilik_id,
                                    'nama' => $u->nama,
                                    'alamat' => $u->alamat,
                                    'detail_pesanan_produk_id' => $u->detail_pesanan_produk_id,
                                    'no_sertifikat' => $u->no_sertifikat,
                                    'noseri' => collect($id_seri_compact)->where('id', $u->noseri_id)->pluck('noseri')->first(),
                                    'tgl_masuk' => $u->tgl_masuk,
                                    'tgl_buat' =>  $u->created_at,
                                    'tgl_kalibrasi' => $u->tgl_kalibrasi,
                                    'metode_id' => $u->metode_id
                                )
                            ),
                        ]);
                    }
                    $cek = UjiLabDetail::where('uji_lab_id', $uji->pluck('uji_lab_id')->first())->count();


                    if ($uji->count() == $cek) {
                        UjiLabDetail::whereIN('uji_lab_detail.noseri_id', $id_seri)->delete();
                        UjiLab::find($lab_id)->delete();
                    } else {
                        UjiLabDetail::whereIN('uji_lab_detail.noseri_id', $id_seri)->delete();
                    }
                }
            }
            foreach ($noseri_baru as $key_a => $a) {
                foreach ($a as $key_b => $b) {
                    NoseriDetailPesanan::where('id', $noseri_lama[$key_a][$key_b]->id)->delete();
                }
            }
            SystemLog::create([
                'tipe' => 'GBJ',
                'header' => NULL,
                'subjek' => 'Penggantian Noseri',
                'response' => json_encode($obj),
            ]);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Transaksi gagal',
            ], 500);
        }
        //     $obj =  json_decode(json_encode($request->all()), FALSE);
        //    dd($obj);
        //     DB::beginTransaction();
        //     try {
        //         //code...
        //         $obj =  json_decode(json_encode($request->all()), FALSE);
        //          //return $obj;
        //          foreach($obj as $o){
        //             if(count($o->noseri) != count($o->seri_ganti)){
        //                 DB::rollback();
        //                 return response()->json([
        //                             'status' => 404,
        //                             'message' => 'Jumlah noseri tidak sama',
        //                         ], 500);
        //             }else{
        //                 SeriGanti::create([
        //                 'detail_pesanan_produk_id' => $o->detail_pesanan_produk_id,
        //                 'isi' => json_encode(
        //                     array(
        //                         'noseri_lama' => $o->seri,
        //                         'noseri_ganti' => $o->seri_ganti,
        //                     )
        //                     ),
        //                     'state' => 'qc'
        //                 ]);
        //             $noseri_lama[] =  $o->seri;
        //             $noseri_baru[] = array('id' => $o->detail_pesanan_produk_id,
        //                                 'seri' => $o->seri_ganti
        //                                 );
        //             }
        //          }

        //          foreach($noseri_lama as $key_a => $a){
        //             foreach($a as $key_b => $b){
        //                 $id_seri_compact[] = $b;
        //             }
        //          }
        //          foreach($noseri_baru as $key_a => $a){
        //             foreach($a['seri'] as $key_b => $b){
        //                 NoseriTGbj::where('id',$noseri_lama[$key_a][$key_b]->t_tfbj_noseri_id)->update([
        //                             'status_id' => 2,
        //                             'noseri_id' =>  $b->id
        //                          ]);
        //                 NoseriBarangJadi::where('id',$noseri_lama[$key_a][$key_b]->noseri_id)->update([
        //                     'is_ready' => 0,
        //                     'used_by' => NULL,
        //                     'keterangan' => 'Tidak OK',
        //                 ]);
        //                 NoseriBarangJadi::where('id',$b->id)->update([
        //                     'is_ready' => 1,
        //                     'used_by' => NULL,
        //                     'keterangan' => '',
        //                 ]);
        //                 $id_seri[] = $noseri_lama[$key_a][$key_b]->id;
        //                 $c[] = $noseri_lama[$key_a][$key_b]->noseri_id;
        //             }
        //          }

        //             $uji = UjiLabDetail::select('uji_lab_detail.uji_lab_id','uji_lab_detail.id','uji_lab_detail.detail_pesanan_produk_id','uji_lab_detail.no_sertifikat',
        //             'uji_lab_detail.noseri_id','uji_lab_detail.tgl_masuk','uji_lab_detail.created_at','uji_lab_detail.tgl_kalibrasi','uji_lab_detail.metode_id',
        //             'uji_lab.no_order','uji_lab.pesanan_id','uji_lab.jenis_pemilik_id','uji_lab.nama','uji_lab.alamat'
        //             )
        //             ->leftJoin('uji_lab','uji_lab.id','=','uji_lab_detail.uji_lab_id')
        //             ->whereIN('uji_lab_detail.noseri_id',$id_seri);

        //             if($uji->count() > 0 ){
        //                 foreach($uji->get() as $u){
        //                     SeriGanti::create([
        //                     'detail_pesanan_produk_id' => $u->detail_pesanan_produk_id,
        //                     'isi' => json_encode(
        //                         array(
        //                             'id' => $u->id,
        //                             'no_order' => $u->no_order,
        //                             'pesanan_id' => $u->pesanan_id,
        //                             'jenis_pemilik_id' => $u->jenis_pemilik_id,
        //                             'nama' => $u->nama,
        //                             'alamat' => $u->alamat,
        //                             'detail_pesanan_produk_id' => $u->detail_pesanan_produk_id,
        //                             'no_sertifikat' => $u->no_sertifikat,
        //                             'noseri' => collect($id_seri_compact)->where('id',$u->noseri_id)->pluck('seri')->first(),
        //                             'tgl_masuk' => $u->tgl_masuk,
        //                             'tgl_buat' =>  $u->created_at,
        //                             'tgl_kalibrasi' => $u->tgl_kalibrasi,
        //                             'metode_id' => $u->metode_id
        //                         )
        //                         ),
        //                         'state' => 'lab'
        //                     ]);
        //                 }
        //                 $cek = UjiLabDetail::where('uji_lab_id',$uji->pluck('uji_lab_id')->first())->count();
        //                 if($uji->count() == $cek){
        //                     UjiLabDetail::whereIN('uji_lab_detail.noseri_id',$id_seri)->delete();
        //                     UjiLab::find($uji->pluck('uji_lab_id')->first())->delete();
        //                 }else{
        //                     UjiLabDetail::whereIN('uji_lab_detail.noseri_id',$id_seri)->delete();
        //                 }
        //             }

        //             foreach($noseri_baru as $key_a => $a){
        //                 foreach($a['seri'] as $key_b => $b){
        //                     NoseriDetailPesanan::where('id',$noseri_lama[$key_a][$key_b]->id)->delete();
        //                 }
        //              }

        //         DB::commit();
        //         return response()->json([
        //             'status' => 200,
        //             'message' => 'Berhasil Ditambahkan'
        //         ], 200);
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //         DB::rollBack();
        //         return response()->json([
        //             'error' => $th->getMessage(),
        //             'message' => 'Transaksi gagal',
        //         ], 500);
        //     }
    }

    function ganti_unit_data()
    {
        // $tfp = TFProduksi::with(['Pesanan.Ekatalog.Customer','Pesanan.Spa.Customer','Pesanan.Spb.Customer'])
        // ->leftJoin()
        // ->addSelect([
        //     'belum' => function ($q) {
        //         $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
        //             ->from('t_gbj_noseri')
        //             ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
        //             ->where('t_gbj_noseri.status_id', '10')
        //             ->whereColumn('t_gbj_detail.t_gbj_id', 't_gbj.id');
        //     },
        //     // 'uji' => function ($q) {
        //     //     $q->selectRaw('coalesce(count(uji.id),0)')
        //     //         ->from('uji_lab_detail as uji')
        //     //         ->where('uji.status', 'belum')
        //     //         ->where('uji.is_ready', 1)
        //     //         ->whereColumn('uji.id', 'uji_lab_detail.id');
        //     // },
        // ])

        // // ->havingRaw('belum > 0')
        // ->whereNotNull('pesanan_id')
        // ->get();

        $data = Pesanan::addSelect([
            'not_ok' => function ($q) {
                $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                    ->from('t_gbj_noseri')
                    ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->where('t_gbj_noseri.status_id', '10')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            },
            'kalibrasi' => function ($q) {
                $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                    ->from('uji_lab_detail')
                    ->leftjoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                    ->where('uji_lab_detail.status', 'nok')
                    ->where('uji_lab_detail.is_ready', 0)
                    ->where('noseri_detail_pesanan.is_ready', 0)
                    ->whereColumn('uji_lab.pesanan_id', 'pesanan.id');
            },
            'qc' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_detail_pesanan.id),0)')
                    ->from('noseri_detail_pesanan')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'nok')
                    ->where('noseri_detail_pesanan.is_ready', 0)
                    ->where('noseri_detail_pesanan.is_lab', 0)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
        ])
            ->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
            ->havingRaw('not_ok > 0');


        if ($data->get()->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data->get() as $d) {

                if ($d->Ekatalog) {
                    $p = $d->Ekatalog->Customer->nama;
                } elseif ($d->Spa) {
                    $p = $d->Spa->Customer->nama;
                } else {
                    $p = $d->Spb->Customer->nama;
                }

                $obj[] = array(
                    'id' => $d->id,
                    'so' => $d->so,
                    'no_po' => $d->no_po,
                    'customer' => $p,
                    //  'status' => $data->belums,
                    'jumlah_pengujian' => $d->qc,
                    'jumlah_kalibrasi' => $d->kalibrasi
                );
            }
        }
        return response()->json($obj);
    }
    function getNonSODone_new()
    {
        try {
            //code...
            $data = TFProduksi::whereNull('pesanan_id')
                ->whereNull('dari')
                ->orderby('updated_at', 'DESC')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()

                ->addColumn('tgl_keluar', function ($data) {
                    return  $data->tgl_keluar != NULL ? Carbon::createFromFormat('Y-m-d', $data->tgl_keluar)->format('d M Y') : '-';
                })
                ->addColumn('tgl_masuk', function ($data) {
                    return  $data->tgl_masuk != NULL ? Carbon::createFromFormat('Y-m-d', $data->tgl_masuk)->format('d M Y') : '-';
                })
                ->addColumn('deskripsi', function ($data) {
                    return  $data->deskripsi != NULL ?  $data->deskripsi : '-';
                })
                ->addColumn('dari', function ($data) {
                    return $data->dari != NULL ? '<span class="badge badge-success">' . $data->darii->nama . '</span>' : '-';
                })
                ->addColumn('ke', function ($data) {
                    return $data->ke != NULL ? '<span class="badge badge-success">' . $data->divisi->nama . '</span>' : '';
                })

                ->editColumn('aksi', function ($d) {
                    return '<a href="export_nonso/' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary"><i class="fas fa-eye"></i> Cetak</button>
                        </a>';
                })

                ->rawColumns(['ke', 'dari'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function pinjaminta_detail($id)
    {
        $data =  PinjamintaBrg::find($id);
        $detail = array();
        foreach ($data->PinjamintaBrgDetail as $d) {
            $detail[] = array(
                'id' => $d->id,
                'nama' => $d->Produk->nama,
                'jumlah' => $d->jumlah
            );
        }
        $obj = array(
            'id' => $data->id,
            'no' => $data->no,
            'no_permintaan' => $data->no_permintaan,
            'jenis' => $data->jenis,
            'divisi_id' => $data->divisi_id,
            'tgl_kebutuhan' => $data->tgl_kebutuhan,
            'tgl_kembali' => $data->tgl_kembali,
            'ket' => $data->ket,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
            'detail' => $detail
        );
        return response()->json($obj);
    }

    public function pinjaminta_update_status(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            if (Auth::user()->divisi_id == 13) {
                $data = PinjamintaBrg::find($request->id);
                $data->status_gdg = $request->status;
                $data->save();
            } else {

                $atasan = ['supervisor', 'manager', 'direkur'];
                if (in_array(Auth::user()->Karyawan->jabatan, $atasan)) {
                    if (isset($request->status)) {
                        $data = PinjamintaBrg::find($request->id);
                        $data->status_atasan = $request->status;
                        if ($request->status == 'setuju') {
                            $data->status_gdg = 'permintaan';
                        }
                        $data->save();
                    } else {
                        $data = PinjamintaBrg::find($request->id);
                        $data->status = $request->status_permintaan;
                        $data->save();
                    }
                } else {
                    $jenis = 'staff';
                    $data = PinjamintaBrg::find($request->id);
                    $data->status = $request->status_permintaan;
                    $data->save();
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Update Gagal' . $th->getMessage(),
            ], 500);
        }
    }

    public function pinjaminta_update(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            if (Auth::user()->divisi_id == 13) {
                $jenis = 'gudang';
            } else {
                $atasan = ['supervisor', 'manager', 'direkur'];
                if (in_array(Auth::user()->Karyawan->jabatan, $atasan)) {
                    $jenis = 'atasan';
                } else {
                    $jenis = 'staff';
                }
            }

            $data = PinjamintaBrg::find($request->id);
            $data->jenis =  $request->jenis['value'];
            $data->status_atasan =  $jenis == 'atasan' ? 'setuju' : 'permintaan';
            $data->status_gdg =  $jenis == 'atasan' ?  'permintaan' : NULL;
            $data->tgl_kebutuhan = $request->tgl_kebutuhan;
            $data->tgl_kembali = $request->tgl_pengembalian;
            $data->ket = $request->tujuan;
            $data->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Update Gagal' . $th->getMessage(),
            ], 500);
        }
    }

    public function pinjaminta_show_gbj(Request $request)
    {
        $data = array();
        if ($request->status == "permintaan") {
            $data = PinjamintaBrg::select(
                'pinjaminta_brg.id',
                'no',
                'no_permintaan',
                'jenis',
                'divisi.nama',
                'tgl_kebutuhan',
                'tgl_kembali',
                'ket',
                'status_gdg',
                'pinjaminta_brg.created_at',
                'pinjaminta_brg.updated_at'
            )->leftJoin('divisi', 'divisi.id', '=', 'pinjaminta_brg.divisi_id')
                ->where('status_gdg', 'permintaan')
                ->whereNull('status')
                ->get();
        } else if ($request->status == "proses") {
            $data = PinjamintaBrg::select(
                'pinjaminta_brg.id',
                'no',
                'no_permintaan',
                'jenis',
                'divisi.nama',
                'tgl_kebutuhan',
                'tgl_kembali',
                'ket',
                'status_gdg',
                'pinjaminta_brg.created_at',
                'pinjaminta_brg.updated_at'
            )->leftJoin('divisi', 'divisi.id', '=', 'pinjaminta_brg.divisi_id')
                ->where('status_gdg', 'setuju')
                ->whereNull('status')
                ->get();
        }
        return response()->json($data);
    }

    public function pinjaminta_atasan_show(Request $request)
    {
        $data = array();
        if ($request->status == "permintaan") {
            $data = PinjamintaBrg::where('divisi_id', Auth::user()->divisi_id)
                ->where('status_atasan', 'permintaan')
                ->whereNULL('status')
                ->get();

            $data->each(function ($item) {
                $item->is_edit = $item->status_gdg == 'permintaan' || $item->status_atasan == 'permintaan' ? false : true;
            });
        } else if ($request->status == "proses") {
            $data = PinjamintaBrg::where('divisi_id', Auth::user()->divisi_id)
                ->where('status_atasan', 'setuju')
                ->where('status_gdg', 'setuju')
                ->whereNULL('status')
                ->get();

            $data->each(function ($item) {
                $item->is_edit = false;
            });
        }
        return response()->json($data);
    }
    public function pinjaminta_show(Request $request)
    {
        $data = array();
        if ($request->status == "permintaan") {
            $data = PinjamintaBrg::where('divisi_id', Auth::user()->divisi_id)
                ->where(function ($query) {
                    $query->where('status_atasan', '!=', 'setuju')
                        ->orWhere('status_gdg', '!=', 'setuju');
                })
                ->whereNull('status')
                ->get();

            $data->each(function ($item) {
                $item->is_edit = $item->status_gdg == 'permintaan' || $item->status_atasan == 'permintaan' ? false : true;
            });
        } else if ($request->status == "proses") {
            $data = PinjamintaBrg::where('divisi_id', Auth::user()->divisi_id)
                ->where('status_atasan', 'setuju')
                ->where('status_gdg', 'setuju')
                ->whereNull('status')
                ->get();

            $data->each(function ($item) {
                $item->is_edit = false;
            });
        } else if ($request->status == "selesai") {
            $data = PinjamintaBrg::where('divisi_id', Auth::user()->divisi_id)
                ->where('status', 'batal')
                ->get();

            $data->each(function ($item) {
                $item->is_edit = false;
            });
        }

        return response()->json($data);
    }

    public function pinjaminta_selectitem()
    {
        $data = Produk::addselect([
            'stok' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_barang_jadi.id),0)')
                    ->from('noseri_barang_jadi')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                    ->where('noseri_barang_jadi.is_ready', '0')
                    ->where('noseri_barang_jadi.divisi_id', Auth::user()->divisi_id)
                    ->whereNull('noseri_barang_jadi.used_by')
                    ->whereColumn('gdg_barang_jadi.produk_id', 'produk.id');
            },
        ])->get();

        $obj = array();
        foreach ($data as $d) {
            $obj[] = array(
                'id' => $d->id,
                'nama' => $d->nama,
                'stok' => $d->stok
            );
        }
        return response()->json($obj);
    }

    public function pinjaminta_kirim(Request $request)
    {

        $obj =  json_decode(json_encode($request->all()), FALSE);
        DB::beginTransaction();
        try {
            //code...
            $atasan = ['supervisor', 'manager', 'direkur'];
            if (in_array(Auth::user()->Karyawan->jabatan, $atasan)) {
                $status_atasan = 'setuju';
                $status_gdg = 'permintaan';
            } else {
                $status_atasan = 'permintaan';
                $status_gdg = NULL;
            }

            $no_urut = PinjamintaBrg::whereYear('created_at', now()->year)->max('no') + 1;
            $p = PinjamintaBrg::create([
                'no' => $no_urut,
                'no_permintaan' => 'NSO-' . now()->year . sprintf("%02d", now()->month) . sprintf("%03d", $no_urut),
                'jenis' => $obj->jenis->value,
                'divisi_id' => Auth::user()->divisi_id,
                'tgl_kebutuhan' => $obj->tgl_kebutuhan,
                'tgl_kembali' => $obj->tgl_pengembalian,
                'ket' => $obj->tujuan,
                'status_atasan' => $status_atasan,
                'status_gdg' => $status_gdg,
            ]);

            foreach ($obj->items as $items) {
                PinjamintaBrgDetail::create([
                    'pinjaminta_brg_id' => $p->id,
                    'produk_id' => $items->nama_produk->value,
                    'jumlah' => $items->jumlah,
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Transaksi Update Gagal' . $th,
            ], 500);
        }
    }
}
