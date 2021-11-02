<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MKategoriGs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelompok_produk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipe')->nullable();
            $table->string('nama')->nullable();
            $table->unsignedBigInteger('jenis_id')->nullable();
            $table->foreign('jenis_id')->references('id')->on('m_jenis_gks');
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
        Schema::dropIfExists('kelompok_produk');
    }
}
