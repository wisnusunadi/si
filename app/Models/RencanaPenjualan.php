<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaPenjualan extends Model
{
    protected $table = 'rencana_penjualan';
    protected $fillable = ['customer_id', 'instansi', 'tahun'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function DetailRencanaPenjualan()
    {
        return $this->hasMany(DetailRencanaPenjualan::class);
    }
}
