<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeLab extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'metode_lab';
    public $timestamps = false;
    protected $fillable = ['metode','no_dokumen'];

    public function DetailMetodeLab()
    {
        return $this->hasMany(DetailMetodeLab::class);
    }
    public function RuangKalibrasi()
    {
        return $this->belongsToMany(RuangKalibrasi::class,'detail_metode_lab','metode_lab_id','ruang');
    }
}
