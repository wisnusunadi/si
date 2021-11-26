<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurEkspedisi extends Model
{
    protected $table = 'jalur_ekspedisi';

    public function Ekspedisi()
    {
        return $this->belongsToMany(Ekspedisi::class, 'ekspedisi_jalur_ekspedisi');
    }
}
