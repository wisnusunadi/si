<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananProduk extends Model
{
    protected $table = 'detail_pesanan_produk';
    protected $fillable = ['detail_pesanan_id', 'gudang_barang_jadi_id'];
    public $timestamps = false;

    public function GudangBarangJadi()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gudang_barang_jadi_id');
    }
    public function DetailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detail_pesanan_id');
    }
    public function NoseriDetailPesanan()
    {
        return $this->hasMany(NoseriDetailPesanan::class);
    }
    public function DetailLogistik()
    {
        return $this->hasMany(DetailLogistik::class, 'detail_pesanan_produk_id');
    }


    public function getJumlahPesanans()
    {
        $id = 1;
        $jumlah = 0;
        $data = DetailPesananProduk::where('detail_pesanan_id', $id)->get();
        foreach ($data as $d) {
            $jumlah += $d->jumlah;
        }
        return $jumlah;
    }
    public function getJumlahKirim()
    {
        $id = $this->id;
        $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
            $q->where('id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahPesanan()
    {
        $id = $this->detail_pesanan_id;
        $produk_id = $this->GudangBarangJadi->produk_id;
        $s = DetailPesanan::where('id', $id)->Has('DetailPesananProduk.NoSeriDetailPesanan')->get();
        $jumlah = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                if ($j->id == $produk_id) {
                    $jumlah = $i->jumlah * $j->pivot->jumlah;
                }
            }
        }
        return $jumlah;
    }

    function status()
    {
        return $this->belongsTo(Status::class, 'status_cek');
    }
}
