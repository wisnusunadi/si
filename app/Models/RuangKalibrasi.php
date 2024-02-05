<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangKalibrasi extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'ruang_kalibrasi';
    public $timestamps = false;
    protected $fillable = ['nama'];


    public function DetailMetodeLab()
    {
        return $this->hasMany(DetailMetodeLab::class,'ruang');
    }

    public function MetodeLab()
    {
        return $this->belongsToMany(MetodeLab::class,'detail_metode_lab','metode_lab_id')
        ->withPivot('metode_lab_id', 'ruang');
    }
}
