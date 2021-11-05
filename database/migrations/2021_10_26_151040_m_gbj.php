<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MGbj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdg_barang_jadi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->foreign('produk_id')->references('id')->on('produk');
            $table->string('nama')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('stok');
            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->unsignedBigInteger('layout_id')->nullable();
            $table->foreign('layout_id')->references('id')->on('m_layout');
            $table->foreign('satuan_id')->references('id')->on('m_satuan');
            $table->string('gambar')->nullable();
            $table->string('dim_p')->nullable();
            $table->string('dim_l')->nullable();
            $table->string('dim_t')->nullable();
            $table->string('status')->nullable()->comment('status barang jadi');
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
        Schema::dropIfExists('gdg_barang_jadi');
    }
}
