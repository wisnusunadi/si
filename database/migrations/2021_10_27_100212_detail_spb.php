<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailSpb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_spb', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spb_id')->nullable();
            $table->foreign('spb_id')->references('id')->on('spb');
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
        Schema::dropIfExists('detail_spb');
    }
}
