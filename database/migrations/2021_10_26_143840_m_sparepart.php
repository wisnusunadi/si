<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MSparepart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sparepart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelompok_produk_id')->nullable();
            $table->string('kode')->nullable();
            $table->foreign('kelompok_produk_id')->references('id')->on('kelompok_produk');
            $table->string('nama')->nullable();
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
        Schema::dropIfExists('m_sparepart');
    }
}
