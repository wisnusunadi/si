<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MProdukCoo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_produk_coo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('noseri_id');
            $table->foreign('noseri_id')->references('id')->on('noseri_barang_jadi');
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
        Schema::dropIfExists('m_produk_coo');
    }
}
