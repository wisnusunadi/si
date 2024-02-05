<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaPenjualan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "rencana_penjualan";

    protected $fillable = ['customer_id', 'tahun', 'instansi', 'created_at', 'updated_at'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function DetailRencanaPenjualan()
    {
        return $this->hasMany(DetailRencanaPenjualan::class);
    }
}
