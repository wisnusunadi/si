<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriBarangJadi extends Model
{
    use HasFactory;

    protected $table = "noseri_barang_jadi";

<<<<<<< HEAD
    protected $fillable = ['is_aktif'];

=======
>>>>>>> e3ed8d46af6187cffca008bb645795785e6aa7b5
    function from()
    {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function to()
    {
        return $this->belongsTo(Divisi::class, 'ke');
    }

    function gudang()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_barang_jadi_id');
    }
    function NoseriTGbj()
    {
        return $this->hasMany(NoseriTGbj::class);
    }
}
