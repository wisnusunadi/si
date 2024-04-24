<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLogistik extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_logistik';
    protected $fillable = ['logistik_id', 'detail_pesanan_produk_id'];

    public function Logistik()
    {
        return $this->belongsTo(Logistik::class, 'logistik_id');
    }
    public function DetailPesananProduk()
    {
        return $this->belongsTo(DetailPesananProduk::class, 'detail_pesanan_produk_id');
    }
    public function NoseriDetailLogistik()
    {
        return $this->hasMany(NoseriDetailLogistik::class);
    }
    public function NoseriDetailLogistikBatal()
    {
        $id = $this->id;
        $data = NoseriDetailPesanan::select('noseri_barang_jadi.noseri')->whereHas('NoseriDetailLogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })
        ->leftJoin('riwayat_batal_po_seri', 'riwayat_batal_po_seri.t_tfbj_noseri_id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
        ->leftjoin('t_gbj_noseri','t_gbj_noseri.id','=','noseri_detail_pesanan.t_tfbj_noseri_id')
        ->leftjoin('noseri_barang_jadi','noseri_barang_jadi.id','=','t_gbj_noseri.noseri_id')
        ->whereNull('riwayat_batal_po_seri.id')
        ->get();

        return $data;
    }
}
