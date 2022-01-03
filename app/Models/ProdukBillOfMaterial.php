<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\BillOfMaterial;

class ProdukBillOfMaterial extends Model
{
    protected $fillable = ['detail_produk_id', 'versi'];
}
