<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalibrasi extends Model
{
    use HasFactory;

    protected $table = 'kalibrasi';
    protected $connection = 'inventory';
    protected  $primaryKey = 'id_kalibrasi';
    protected $fillable = ['serial_number_id', 'masalah', 'tgl_kirim', 'tgl_terima', 'pengirim_id', 'penerima_id', 'pelaksana_id', 'tindak_lanjut', 'hasil_fisik', 'hasil_fungsi', 'supplier_id', 'memo', 'surat_jalan', 'biaya', 'status_id', 'jenis_id', 'created_by'];
}
