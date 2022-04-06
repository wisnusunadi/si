<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksiDetail extends Model
{
    use HasFactory;

    protected $table = 't_gbj_detail';

    protected $fillable = ['status_id', 'state_id', 'gdg_brg_jadi_id', 'detail_pesanan_produk_id', 't_gbj_id', 'qty', 'jenis', 'created_by', 'created_at'];

    function header()
    {
        return $this->belongsTo(TFProduksi::class, 't_gbj_id');
    }
    function produk()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_brg_jadi_id');
    }
    function seri()
    {
        return $this->hasMany(NoseriTGbj::class, 't_gbj_detail_id');
    }

    function noseri()
    {
        return $this->hasMany(NoseriTGbj::class, 't_gbj_detail_id');
    }

    function paket()
    {
        return $this->belongsTo(DetailPesananProduk::class, 'detail_pesanan_produk_id');
    }

    function getseriditerima()
    {
        $tgl = $this->tgl_masuk;
        $prd = $this->prd;
        $jumlah = NoseriTGbj::whereHas('detail.header', function ($q) use ($tgl) {
            $q->where('dari', 17);
            $q->where('tgl_masuk', $tgl);
        })
            ->whereHas('detail', function ($qq) use ($prd) {
                $qq->where('gdg_brg_jadi_id', $prd);
            })
            ->where('status_id', 3)
            ->with('detail.header')
            ->get();
        return $jumlah;
    }

    function getserisemua()
    {
        $tgl = $this->tgl_masuk;
        $prd = $this->prd;
        $jumlah = NoseriTGbj::whereHas('detail.header', function ($q) use ($tgl) {
            $q->where('dari', 17);
            $q->where('tgl_masuk', $tgl);
        })
            ->whereHas('detail', function ($qq) use ($prd) {
                $qq->where('gdg_brg_jadi_id', $prd);
            })
            ->with('detail.header')
            ->get();
        return $jumlah;
    }
}
