<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartEng extends Model
{
    protected $primaryKey = "kode_part";
    protected $fillable = ['kode_part', 'nama', 'foto', 'deskripsi', 'spesifikasi', 'status'];

    protected $keyType = "string";
    public $incrementing = false;
}
