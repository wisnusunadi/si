<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = ['no_po', 'so', 'tgl_po', 'no_do', 'tgl_do', 'ket', 'log_id'];

    public function Ekatalog()
    {
        return $this->hasOne(Ekatalog::class);
    }
    public function Spa()
    {
        return $this->hasOne(Spa::class);
    }
    public function Spb()
    {
        return $this->hasOne(Spb::class);
    }
    public function DetailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
    function TFProduksi()
    {
        return $this->hasOne(TFProduksi::class);
    }
    public function getJumlahPesanan()
    {
        $id = $this->id;
        $s = DetailPesanan::where('pesanan_id', $id)->get();
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
        $jumlah = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahKirim()
    {
        $id = $this->id;
        $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    function log()
    {
        return $this->belongsTo(State::class, 'log_id');
    }

    public function State()
    {
        return $this->belongsTo(State::class, 'log_id');
    }
}
