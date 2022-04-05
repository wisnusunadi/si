<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $fillable = ['pesanan_id', 'penjualan_produk_id', 'detail_rencana_penjualan_id', 'jumlah', 'harga', 'ongkir'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
    public function DetailPesananProduk()
    {
        return $this->hasMany(DetailPesananProduk::class);
    }
    public function DetailRencanaPenjualan()
    {
        return $this->belongsTo(DetailRencanaPenjualan::class);
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

    public function LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir){
        $id = $this->id;
        $res = "";
        if($hasil != "semua"){
            $res = DetailPesananProduk::where('detail_pesanan_id', $id)->whereHas('NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir, $hasil){
                $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
            })->get();
        } else {
            $res = DetailPesananProduk::where('detail_pesanan_id', $id)->whereHas('NoseriDetailPesanan', function($q) use($tgl_awal, $tgl_akhir){
                $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
            })->get();
        }
        return $res;
    }

    public function countLaporanQcProduk($hasil, $tgl_awal, $tgl_akhir){
        $id = $this->id;
        $res = "";
        if($hasil != "semua"){
            $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk', function($q) use($id){
                $q->where('detail_pesanan_id', $id);
            })->count();
        } else {
            $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk', function($q) use($id){
                $q->where('detail_pesanan_id', $id);
            })->count();
        }
        return $res;
    }
}
