<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TGbjHis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdg_barang_jadi_his', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdg_brg_jadi_id')->nullable();
            $table->foreign('gdg_brg_jadi_id')->references('id')->on('gdg_barang_jadi');
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->foreign('produk_id')->references('id')->on('produk');
            $table->string('nama')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('stok');
            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->foreign('satuan_id')->references('id')->on('m_satuan');
            $table->unsignedBigInteger('layout_id')->nullable();
            $table->foreign('layout_id')->references('id')->on('m_layout');
            $table->unsignedBigInteger('dari')->nullable();
            $table->foreign('dari')->references('id')->on('divisi');
            $table->unsignedBigInteger('ke')->nullable();
            $table->foreign('ke')->references('id')->on('divisi');
            $table->string('status')->nullable()->comment('status barang jadi');
            $table->enum('jenis', ['MASUK', 'KELUAR'])->nullable();
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
        //
    }
}
