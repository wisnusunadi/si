<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UjiLab extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'uji_lab';
    protected $fillable = ['no_order', 'pesanan_id', 'jenis_pemilik_id', 'nama', 'alamat'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function JenisPemilik()
    {
        return $this->belongsTo(JenisPemilik::class, 'jenis_pemilik_id');
    }
    public function UjiLabDetail()
    {
        return $this->hasMany(UjiLabDetail::class);
    }

    public function GetJumlah()
    {
        $id = $this->id;
        $produk = DB::select('select coalesce(count(uji_lab_detail.id),0) as jumlah
        from uji_lab_detail
        where uji_lab_detail.uji_lab_id = ?', [$id]);
        return  $produk[0]->jumlah;
    }
    public function GetUji()
    {
        $id = $this->id;
        $produk = DB::select('select coalesce(count(uji_lab_detail.id),0) as jumlah
        from uji_lab_detail
        where uji_lab_detail.uji_lab_id = ? and uji_lab_detail.status != "belum"', [$id]);
        return  $produk[0]->jumlah;
    }
    public function GetDetail()
    {
        $id = $this->id;
        $detail = UjiLabDetail::with(['NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi','DetailPesananProduk.GudangBarangjadi.Produk','Karyawan'])
        ->leftjoin('detail_pesanan_produk','detail_pesanan_produk.id','=','uji_lab_detail.detail_pesanan_produk_id')
        ->where('uji_lab_id',$id)
        ->whereNotIN('status',['belum'])
        ->groupby('gudang_barang_jadi_id')
        ->get();
        return  $detail;
    }
    public function GetSeri($gbj)
    {
        $id = $this->id;
        $detail = UjiLabDetail::with(['NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi','DetailPesananProduk.GudangBarangjadi.Produk','Karyawan'])
        ->leftjoin('detail_pesanan_produk','detail_pesanan_produk.id','=','uji_lab_detail.detail_pesanan_produk_id')
        ->where('uji_lab_id',$id)
        ->where('detail_pesanan_produk.gudang_barang_jadi_id',$gbj)
        ->whereNotIN('status',['belum'])
        ->get();
        return  $detail;
    }
}
