<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananDsb extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_pesanan_dsb';
    protected $fillable = ['pesanan_id', 'penjualan_produk_id', 'jumlah', 'harga', 'ongkir', 'ppn'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
    public function DetailPesananProdukDsb()
    {
        return $this->hasMany(DetailPesananProdukDsb::class);
    }
    public function NoseriDsb()
    {
        return $this->hasMany(NoseriDsb::class, 'detail_pesanan_dsb');
    }
    public function DetailPesananProdukVariasiSet()
    {
        $id = $this->id;
        $detail_pesanan_produk = DetailPesananProdukDsb::where('detail_pesanan_dsb_id', $id)->get();
        $result = []; // Initialize an empty array to store the final result

        foreach ($detail_pesanan_produk as $dpp) {
            $datagbj = GudangBarangJadi::where('produk_id', $dpp->GudangBarangJadi->produk_id)->get();
            $gbjArray = []; // Initialize an empty array for storing `gbj` data

            foreach ($datagbj as $gbj) {
                $gbjArray[] = array(
                    'value' => $gbj->id,
                    // Remove spaces from the name like trim
                    'label' => trim($gbj->nama) ? $gbj->nama : $dpp->GudangBarangJadi->Produk->nama,
                    'stok' => $gbj->Stok(),
                );
            }

            $result[] = array(
                'label' => $dpp->GudangBarangJadi->Produk->nama,
                'variasiSelected' => $dpp->gudang_barang_jadi_id,
                'id' => $dpp->gudang_barang_jadi_id,
                'produk_id' => $dpp->GudangBarangJadi->Produk->id,
                'gudang_barang_jadi' => $gbjArray, // Use the gbjArray here
            );
        }

        return $result; // Return the final result
    }
}
