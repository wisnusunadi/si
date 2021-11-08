<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailSpa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_spa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spa_id')->nullable();
            $table->foreign('spa_id')->references('id')->on('spa');
            $table->unsignedBigInteger('penjualan_produk_id')->nullable();
            $table->foreign('penjualan_produk_id')->references('id')->on('penjualan_produk');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('ongkir');
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
        Schema::dropIfExists('detail_spa');
    }
}
