<?php

namespace App\Http\Controllers;

use App\Models\Ekatalog;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DireksiController extends Controller
{
    public function dashboard()
    {
        $penj = Ekatalog::whereHas('Pesanan', function($q){ $q->whereNull('so')->where('log_id', '7')->whereNotIn('log_id', ['10', '20']);})->where('status', 'sepakat')->count();

            $gudang = Pesanan::addSelect(['jumlah_produk' => function($q){
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            }, 'jumlah_gudang' => function($q){
                $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            }])->whereNotIn('log_id', ['7'])->havingRaw('jumlah_produk > jumlah_gudang')->count();

            $qc = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect(['tgl_kontrak' => function($q){
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                ->from('ekatalog')
                ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                ->limit(1);
            },
            'ctfprd' => function($q){
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                ->from('t_gbj_noseri')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            },
            'cqcprd' => function($q){
                $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                    ->from('noseri_detail_pesanan')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'ctfpart' => function($q){
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                ->from('detail_pesanan_part')
                ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'cqcpart' => function($q){
                $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                ->from('outgoing_pesanan_part')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->where('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function($q){
                $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                   ->from('noseri_logistik')
                   ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                   ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                   ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                   ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                   ->limit(1);
            },
            'clogpart' => function($q){
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                   ->from('detail_logistik_part')
                   ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                   ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                   ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                   ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                   ->limit(1);
            }])
            ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
            ->havingRaw('(ctfprd > cqcprd AND ctfprd > 0) OR (ctfpart > cqcpart AND ctfpart > 0)')
            ->orderBy('tgl_kontrak', 'asc')
            ->count();

            $log = Pesanan::addSelect(['cqcprd' => function($q){
                $q->selectRaw('count(noseri_detail_pesanan.id)')
                    ->from('noseri_detail_pesanan')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'cqcpart' => function($q){
                $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                ->from('outgoing_pesanan_part')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function($q){
                $q->selectRaw('count(noseri_logistik.id)')
                   ->from('noseri_logistik')
                   ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                   ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                   ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                   ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                   ->limit(1);
            },
            'clogpart' => function($q){
                $q->selectRaw('sum(detail_logistik_part.jumlah)')
                   ->from('detail_logistik_part')
                   ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                   ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                   ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                   ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            }])
            ->havingRaw('cqcprd > clogprd OR clogprd > clogpart')
            ->count();


        $dc = Pesanan::whereIn('id', function($q){
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->where('produk.coo', '=', '1')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_logistik.id) > (
                    select count(noseri_coo.id)
                    from noseri_coo
                    left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                    left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                    left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id)');
                })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                    ->addSelect(['tgl_kontrak' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                      ->from('ekatalog')
                      ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                      ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                      ->limit(1);
                },
                'ccoo' => function($q){
                    $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cseri' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }
                ])->orderBy('tgl_kontrak', 'desc')->has('Ekatalog')->count();




        return view('page.direksi.dashboard', ['penj' => $penj, 'gudang' => $gudang, 'qc' => $qc, 'log' => $log, 'dc' => $dc]);
    }
}
