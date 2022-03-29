<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = ['no_po', 'so', 'tgl_po', 'no_do', 'tgl_do', 'ket', 'log_id', 'checked_by', 'status_cek'];

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

    public function DetailPesananPart()
    {
        return $this->hasMany(DetailPesananPart::class);
    }

    public function DetailLogistikPart()
    {
        return $this->hasMany(DetailLogistikPart::class);
    }

    function TFProduksi()
    {
        return $this->hasOne(TFProduksi::class);
    }

    public function getJumlahPaket()
    {
        $id = $this->id;
        $jumlah = 0;
        $data = DetailPesanan::where('pesanan_id', $id)->get();
        foreach ($data as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $jumlah = $jumlah + 1;
            }
        }
        return $jumlah;
    }

    public function getJumlahPaketPesanan()
    {
        $id = $this->id;
        $jumlah = 0;
        $data = DetailPesanan::where('pesanan_id', $id)->get();
        foreach ($data as $d) {
            $jumlah += $d->jumlah;
        }
        return $jumlah;
    }


    public function getJumlahProduk()
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

    public function getJumlahSeri()
    {
        $id = $this->id;
        $jumlah = NoseriTGbj::whereHas('detail.header', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahCekSeri()
    {
        $id = $this->id;
        $jumlah = NoseriDetailPesanan::whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
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

    function cekJumlahkirim()
    {
        $id = $this->id;
        $jumlah = NoseriTGbj::whereHas('detail.header.pesanan', function ($q) use ($id) {
            $q->where('id', $id)->where('status_id', 2);
        })->count();
        return $jumlah;
    }

    public function getJumlahCoo()
    {
        $id = $this->id;
        $jumlah = NoseriCoo::whereHas('NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    function getJumlahKirim1()
    {
        $id = $this->id;
        $detail = TFProduksiDetail::where('t_gbj_id', $id)->get();
        $jumlah = 0;
        foreach ($detail as $d) {
            $jumlah += $d->qty;
        }
        return $jumlah;
    }

    public function getJumlahPesananPart()
    {
        $id = $this->id;
        $s = DetailPesananPart::where('pesanan_id', $id)->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah = $jumlah + $i->jumlah;
        }
        return $jumlah;
    }

    public function getJumlahCekPart($status)
    {
        $id = $this->id;
        $s = OutgoingPesananPart::whereHas('DetailPesananPart', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();
        $jumlah = 0;
        foreach ($s as $i) {
            if ($status == 'ok') {
                $jumlah = $jumlah + $i->jumlah_ok;
            } else if ($status == 'nok') {
                $jumlah = $jumlah + $i->jumlah_nok;
            }
        }
        return $jumlah;
    }

    public function getJumlahKirimPart()
    {
        $id = $this->id;
        $s = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah = $jumlah + $i->DetailPesananPart->jumlah;
        }
        return $jumlah;
    }

    public function LogistikLaporan($jenis, $eks, $awal, $akhir){
        $id = $this->id;

        $prd = "";
        $part = "";

        if($jenis == "ekspedisi"){
            if($eks != '0'){
                $prd = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->get();

                $part = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            }
            else{
                $prd = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->get();

                $part = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            }
        } else if($jenis == "nonekspedisi"){
            $prd = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $part = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->get();
        } else{
            $prd = Logistik::whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $part = Logistik::whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->get();
        }
        $data = $prd->merge($part);
        return $data;


    }

    public function AllProdukKirim($jenis, $eks, $awal, $akhir){
        $id = $this->id;

        $prd = "";
        $part = "";

        if($jenis == "ekspedisi"){
            if($eks != '0'){
                $prd = DetailLogistik::whereHas('Logistik', function($q) use($eks, $awal, $akhir){
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->count();

                $part = DetailLogistikPart::whereHas('Logistik', function($q) use($eks, $awal, $akhir){
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananPart', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->count();
            }
            else{
                $prd = DetailLogistik::whereHas('Logistik', function($q) use($awal, $akhir){
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->count();

                $part = DetailLogistikPart::whereHas('Logistik', function($q) use($awal, $akhir){
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananPart', function($q) use($id) {
                    $q->where('pesanan_id', $id);
                })->count();
            }
        } else if($jenis == "nonekspedisi"){
            $prd = DetailLogistik::whereHas('Logistik', function($q) use($awal, $akhir){
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->count();

            $part = DetailLogistikPart::whereHas('Logistik', function($q) use($awal, $akhir){
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananPart', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->count();
        } else{
            $prd = DetailLogistik::whereHas('Logistik', function($q) use($awal, $akhir){
                $q->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananProduk.DetailPesanan', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->count();

            $part = DetailLogistikPart::whereHas('Logistik', function($q) use($awal, $akhir){
                $q->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananPart', function($q) use($id) {
                $q->where('pesanan_id', $id);
            })->count();
        }

        return $prd + $part;
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
