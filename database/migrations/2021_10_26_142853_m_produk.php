<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelompok_produk_id')->nullable();
            $table->string('kode')->nullable();
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->string('nama')->nullable();
            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->string('no_akd')->nullable();
            $table->foreign('kelompok_produk_id')->references('id')->on('kelompok_produk');
            $table->foreign('satuan_id')->references('id')->on('m_satuan');
            $table->string('ket')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('produk');
    }
}
