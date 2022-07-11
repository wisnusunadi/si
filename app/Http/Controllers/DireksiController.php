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

        $gudang = Pesanan::whereIn('id', function($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->groupBy('pesanan.id')
                ->havingRaw('NOT EXISTS(select *
                    from t_gbj_noseri
                    left join t_gbj_detail on t_gbj_detail.id = t_gbj_noseri.t_gbj_detail_id
                    left join t_gbj on t_gbj.id = t_gbj_detail.t_gbj_id
                    where t_gbj.pesanan_id = pesanan.id)');
            })->whereNotIn('log_id', ['7', '10', '20'])->count();

        $qc_prd = Pesanan::whereIn('id', function($q) {
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
            })->whereNotIn('log_id', ['7', '10', '20'])
              ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi']);

        $qc_part = Pesanan::whereIn('id', function($q) {
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
                ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
                ->union($qc_prd)
                ->orderBy('id', 'desc')
                ->count();

        $qc = $qc_part;


        $log_prd = Pesanan::whereIn('id', function($q) {
            $q->select('pesanan.id')
            ->from('pesanan')
            ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
            ->groupBy('pesanan.id')
            ->havingRaw('count(noseri_detail_pesanan.id) > (select count(noseri_logistik.id)
            from noseri_logistik
            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id)');
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '10', '20']);

        $log_part = Pesanan::whereIn('id', function($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->groupBy('pesanan.id')
                ->havingRaw("(sum(outgoing_pesanan_part.jumlah_ok) > (
                    select sum(detail_pesanan_part.jumlah)
                    from detail_pesanan_part
                    left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                    where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS
                       (select * from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)) AND SUM(outgoing_pesanan_part.jumlah_ok) > 0")
                ;
            })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10', '20']);

        $log_partjasa = Pesanan::whereIn('id', function($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                        select sum(detail_pesanan_part.jumlah)
                        from detail_pesanan_part
                        left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS(
                            select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
                })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10', '20'])->union($log_prd)->union($log_part)->orderBy('id', 'desc')->count();

        $log = $log_partjasa;

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
                })->count();



        return view('page.direksi.dashboard', ['penj' => $penj, 'gudang' => $gudang, 'qc' => $qc, 'log' => $log, 'dc' => $dc]);
    }
}
