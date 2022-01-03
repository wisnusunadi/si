<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    protected $fillable = ['produk_bill_of_material_id', 'part_eng_id', 'jumlah', 'satuan', 'status'];
}
