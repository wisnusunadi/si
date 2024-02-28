<?php

namespace App\Models;

use App\Models\kesehatan\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjiLabDetail extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'uji_lab_detail';
    protected $fillable = ['no','no_sertifikat','uji_lab_id','detail_pesanan_produk_id','pemeriksa_id','noseri_id','no_sertifikat','tgl_masuk','tgl_kalibrasi','metode_id','status','is_ready','cetak_log'];

    public function NoseriDetailPesanan()
    {
        return $this->belongsTo(NoseriDetailPesanan::class, 'noseri_id');
    }

    public function DetailPesananProduk()
    {
        return $this->belongsTo(DetailPesananProduk::class, 'detail_pesanan_produk_id');
    }
    public function UjiLab()
    {
        return $this->belongsTo(UjiLab::class,'uji_lab_id');
    }

    public function DetailMetodeLab()
    {
        return $this->belongsTo(DetailMetodeLab::class,'metode_id');
    }

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class,'pemeriksa_id');
    }


}
