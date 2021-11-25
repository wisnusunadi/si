<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftGBJ extends Model
{
    use HasFactory;

    protected $table = 't_draft_gbj';

    protected $fillable = ['gbj_id', 'tgl_masuk', 'dari', 'tujuan', 'qty'];

    function divisi() {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    // function gbj() {
    //     return $this->belongsTo(GudangBarangJadi::class, 'gbj_id');
    // }

    function status() {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
