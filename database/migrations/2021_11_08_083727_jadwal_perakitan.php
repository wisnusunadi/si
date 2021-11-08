<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JadwalPerakitan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_perakitan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->foreign('produk_id')->references('id')->on('produk');
            $table->integer('jumlah')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', array('penyusunan', 'pelaksanaan', 'selesai'))->nullable();
            $table->enum('state', array('perencanaan', 'persetujuan', 'perubahan'))->nullable();
            $table->integer('konfirmasi')->nullable();
            $table->string('warna')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_perakitan');
    }
}
