<?php

namespace App\Models\teknik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'bill_of_material';
    protected $fillable = ['karyawan_id', 'tgl_cek', 'berat', 'lemak', 'kandungan_air', 'otot', 'tulang', 'kalori', 'suhu', 'spo2', 'pr', 'sistolik', 'diastolik', 'keterangan'];
}
