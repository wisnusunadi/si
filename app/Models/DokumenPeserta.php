<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPeserta extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'dokumen_peserta';
    protected $fillable = ['peserta_meeting_id', 'nama'];

    public function PesertaMeeting()
    {
        return $this->belongsTo(PesertaMeeting::class, 'peserta_meeting_id');
    }
}
