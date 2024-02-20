<?php

namespace App\Console\Commands;

use App\Models\RiwayatAktifPeriode;
use Illuminate\Console\Command;

class updatePeriodePenjualan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otomatisasi:updatePeriodePenjualan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status periode penjualan jika sudah melewati hari dan jam tutup periode penjualan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $getAktifPeriode = RiwayatAktifPeriode::where('status', 'terima')->first();
        // kalkulasi hari tutup periode
        $hariTutup = json_decode($getAktifPeriode->isi);
        $hariTutup = $getAktifPeriode->created_at->addDays($hariTutup->maxOpenDay);
        // jika hari sesuai maka tutup
        if ($hariTutup->isToday()) {
            $getAktifPeriode->status = 'selesai';
            $getAktifPeriode->save();
        }
    }
}
