<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;
use App\Models\NoseriBarangJadi;

class GudangBarangJadi extends Model
{
    use HasFactory;

    protected $table = "gdg_barang_jadi";
    protected $fillable = ['produk_id', 'variasi', 'stok', 'ruang', 'updated_by', 'created_by'];

    public function noseri()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'gdg_barang_jadi_id');
    }

    function history()
    {
        return $this->hasMany(GudangBarangJadiHis::class, 'gdg_brg_jadi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function DetailPesananProduk()
    {
        return $this->hasMany(DetailPesananProduk::class);
    }
    function Layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function DetailEkatalog()
    {
        return $this->belongsToMany(DetailEkatalog::class, 'detail_ekatalog_produk')
            ->withPivot('jumlah');
    }

    public function getJumlahPermintaanPesanan($jenis, $status)
    {
        $jumlah = 0;
        if ($jenis == "ekatalog") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->whereHas('DetailPesanan.Pesanan.Ekatalog', function ($q) use ($status) {
                $q->where('status', $status);
            })->get();
            $jumlah = 0;
            foreach ($s as $i) {
                foreach ($i->DetailPesanan->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah = $i->DetailPesanan->jumlah * $j->pivot->jumlah;
                    }
                }
            }
        } else if ($jenis == "spa") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->has('DetailPesanan.Pesanan.Spa')->get();
            $jumlah = 0;
            foreach ($s as $i) {
                foreach ($i->DetailPesanan->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah = $i->DetailPesanan->jumlah * $j->pivot->jumlah;
                    }
                }
            }
        }
        return $jumlah;
    }

    public function getJumlahTransferPesanan($jenis, $status)
    {
        $jumlah = 0;
        $id = $this->id;
        if ($jenis == "ekatalog") {
            $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
                $q->where('gdg_brg_jadi_id', $id);
            })->whereHas('detail.header.pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->whereHas('detail.header.pesanan.Ekatalog', function ($q) use ($status) {
                $q->where('status', $status);
            })->count();
        } else if ($jenis == "spa") {
            $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
                $q->where('gdg_brg_jadi_id', $id);
            })->whereHas('detail.header.pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->has('detail.header.pesanan.Spa')->count();
        }
        return $jumlah;
    }
}
