<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMeeting extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'jadwal_meeting';
    protected $fillable = ['urutan', 'judul', 'tgl_meeting', 'mulai', 'selesai', 'lokasi', 'status', 'status_app', 'tgl_app', 'notulen', 'moderator', 'pimpinan', 'deskripsi', 'ket_batal'];

    public function PesertaMeeting()
    {
        return $this->hasMany(PesertaMeeting::class, 'meeting_id');
    }

    public function RiwayatJadwalMeeting()
    {
        return $this->hasMany(RiwayatJadwalMeeting::class, 'meeting_id');
    }

    public function HasilMeeting()
    {
        return $this->hasMany(HasilMeeting::class, 'meeting_id');
    }
    public function HasilNotulen()
    {
        return $this->hasMany(HasilNotulen::class, 'meeting_id');
    }

    public function DokumenMeeting()
    {
        return $this->hasMany(DokumenMeeting::class, 'meeting_id');
    }
    public function Lokasi()
    {
        return $this->belongsTo(LokasiMeet::class, 'lokasi');
    }
}
