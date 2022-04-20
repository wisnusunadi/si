<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananPart extends Model
{
    protected $table = 'detail_pesanan_part';
    protected $fillable = ['pesanan_id', 'm_sparepart_id', 'jumlah', 'harga', 'ongkir'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function Sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'm_sparepart_id');
    }
    public function DetailLogistikPart()
    {
        return $this->hasOne(DetailLogistikPart::class);
    }
    public function OutgoingPesananPart()
    {
        return $this->hasMany(OutgoingPesananPart::class, 'detail_pesanan_part_id');
    }

    public function getJumlahCekPart($status)
    {
        $id = $this->id;
        $jumlah = 0;
        $s = OutgoingPesananPart::where('detail_pesanan_part_id', $id)->get();
        if ($s) {
            foreach ($s as $i) {
                if ($status == 'ok') {
                    $jumlah = $jumlah + $i->jumlah_ok;
                } else {
                    $jumlah = $jumlah + $i->jumlah_nok;
                }
            }
        } else {
            $jumlah = 0;
        }
        return $jumlah;
    }

    public function getTanggalUji()
    {
        $id = $this->id;
        $date = OutgoingPesananPart::selectRaw('MAX(tanggal_uji) as tgl_selesai, MIN(tanggal_uji) as tgl_mulai')->whereHas('DetailPesananPart', function ($q) use ($id) {
            $q->where('detail_pesanan_part_id', $id);
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
                $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
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

    public function LaporanQcPart($tgl_awal, $tgl_akhir){
        $id = $this->id;
        $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->where('detail_pesanan_part_id', $id)->get();
        return $res;
    }
}
