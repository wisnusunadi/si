<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ProdukBillOfMaterial;

class DetailProduk extends Model
{
    protected $connection = 'erp';
    protected $fillable = ['produk_id', 'kode', 'nama', 'stok', 'harga', 'foto', 'berat'];

    public function ProdukBillOfMaterial()
    {
        return $this->hasMany(ProdukBillOfMaterial::class);
    }
}
