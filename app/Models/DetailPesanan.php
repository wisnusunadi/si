<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use TGbjHis;

class DetailPesanan extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_pesanan';
    protected $fillable = ['pesanan_id', 'penjualan_produk_id', 'detail_rencana_penjualan_id', 'jumlah', 'harga', 'ongkir', 'ppn', 'kalibrasi'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id')
            ->orderBy('so', 'ASC');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
    public function RiwayatBatalPoPaket()
    {
        return $this->hasOne(RiwayatBatalPoPaket::class);
    }
    public function RiwayatReturPoPaket()
    {
        return $this->hasOne(RiwayatReturPoPaket::class);
    }
    public function RiwayatBatalPoPart()
    {
        return $this->hasOne(RiwayatBatalPoPart::class);
    }
    public function DetailPesananProduk()
    {
        return $this->hasMany(DetailPesananProduk::class);
    }
    public function DetailRencanaPenjualan()
    {
        return $this->belongsTo(DetailRencanaPenjualan::class);
    }
    public function DetailPesananProdukVariasi()
    {
        $id = $this->id;
        $detail_pesanan_produk = DetailPesananProduk::where('detail_pesanan_id', $id)->addSelect([
            'count_gudang' => function ($q) {
                $q->selectRaw('count(t_gbj_noseri.id)')
                    ->from('t_gbj_noseri')
                    ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                    ->limit(1);
            },
            'count_jumlah' => function ($q) {
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_pesanan.penjualan_produk_id', '=', 'detail_penjualan_produk.penjualan_produk_id')
                    ->join('gdg_barang_jadi', 'gdg_barang_jadi.produk_id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.id', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('gdg_barang_jadi.id', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->limit(1);
            },
            'count_qc' => function ($q) {
                $q->selectRaw('count(noseri_detail_pesanan.id)')
                    ->from('noseri_detail_pesanan')
                    ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                    ->limit(1);
            },
        ])->with('GudangBarangJadi.Produk')->get();

        return $detail_pesanan_produk;
    }

    public function GetVariasi()
    {
        $id = $this->id;
        $produk = DB::select('select gbj.nama  from detail_pesanan_produk dpp
        left join detail_pesanan dp on dpp.detail_pesanan_id  = dp.id
        left join gdg_barang_jadi gbj on gbj.id = dpp.gudang_barang_jadi_id
        left join produk p on p.id = gbj.produk_id
        where dpp.detail_pesanan_id = ? and p.coo = 1', [$id]);
        return  $produk;
    }


    public function getJumlahProgress()
    {
        $id = $this->id;
        $data = DetailPesanan::where('id', $id)->addSelect(['count_gudang' => function ($q) {
            $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
                ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                ->limit(1);
        }, 'count_qc' => function ($q) {
            $q->selectRaw('count(noseri_detail_pesanan.id)')
                ->from('noseri_detail_pesanan')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                // ->where('noseri_detail_pesanan.status', 'ok')
                ->limit(1);
        }, 'count_log' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                ->limit(1);
        }, 'count_jumlah' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan_produk')
                ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.produk_id', '=', 'gdg_barang_jadi.produk_id')
                ->whereColumn('detail_penjualan_produk.penjualan_produk_id', 'detail_pesanan.penjualan_produk_id')
                ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                ->limit(1);
        }])->with('PenjualanProduk')->first();

        return $data;
    }

    public function countNoSeri()
    {
        $id = $this->id;
        $c = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('detail_pesanan_id', $id);
        })->count();
        return $c;
    }
    public function getTanggalUji()
    {
        $id = $this->id;
        $date = NoseriDetailPesanan::selectRaw('MAX(noseri_detail_pesanan.tgl_uji) as tgl_selesai, MIN(noseri_detail_pesanan.tgl_uji) as tgl_mulai')->whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('detail_pesanan_id', $id);
        })->first();
        return $date;
    }
    public function getJumlahProduk()
    {
        $id = $this->id;
        $s = DetailPesanan::where('id', $id)->Has('DetailPesananProduk.NoSeriDetailPesanan')->get();
        $jumlah = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $jumlah++;
            }
        }
        return $jumlah;
    }
    public function getJumlahPesanan()
    {
        $id = $this->id;
        $s = DetailPesanan::where('id', $id)->Has('DetailPesananProduk.NoSeriDetailPesanan')->get();
        $jumlah = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            }
        }
        return $jumlah;
    }


    public function getJumlahCek()
    {
        $id = $this->id;
        $s = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('detail_pesanan_id', $id);
        })->count();

        return $s;
    }

    public function getJumlahRetur()
    {
        $id = $this->id;
        $k = RiwayatReturPoPaket::where(['detail_pesanan_id' => $id])
            ->sum('jumlah');

        return $k;
    }

    public function getJumlahPrdLog()
    {
        $id = $this->id;
        $s = DetailLogistik::leftJoin('detail_pesanan_produk', 'detail_logistik.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
            ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
            ->where('detail_pesanan_produk.detail_pesanan_id', $id)
            ->where('logistik.status_id', 10)
            ->groupBy('detail_logistik.detail_pesanan_produk_id')
            ->pluck('detail_logistik.detail_pesanan_produk_id')
            ->count('detail_logistik.id');

        return $s;
    }
    public function getJumlahPrdTf()
    {
        $id = $this->id;
        $s = TFProduksiDetail::leftJoin('detail_pesanan_produk', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
            ->where('detail_pesanan_produk.detail_pesanan_id', $id)
            ->count('t_gbj_detail.id');

        return $s;
    }
    public function getJumlahBatal()
    {
        $id = $this->id;
        $s = RiwayatBatalPoPaket::where(['detail_pesanan_id' => $id])->sum('jumlah');
        return $s;
    }



    public function LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($hasil != "semua") {
            $res = DetailPesananProduk::where('detail_pesanan_id', $id)->whereHas('NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
            })->get();
        } else {
            $res = DetailPesananProduk::where('detail_pesanan_id', $id)->whereHas('NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
            })->get();
        }
        return $res;
    }

    public function countLaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($hasil != "semua") {
            $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('detail_pesanan_id', $id);
            })->count();
        } else {
            $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('detail_pesanan_id', $id);
            })->count();
        }
        return $res;
    }

    public function DetailPesananProdukVariasiSet()
    {
        $id = $this->id;
        $detail_pesanan_produk = DetailPesananProduk::where('detail_pesanan_id', $id)->get();
        foreach ($detail_pesanan_produk as $dpp) {
            $x[] = array(
                'id' => $dpp->gudang_barang_jadi_id,
                'produk_id' => $dpp->GudangBarangJadi->Produk->id,
            );
        }

        return $x;
    }
}
