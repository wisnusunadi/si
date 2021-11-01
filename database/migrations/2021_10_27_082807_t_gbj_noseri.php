<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TGbjNoseri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noseri_barang_jadi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gdg_barang_jadi_id')->nullable();
            $table->foreign('gdg_barang_jadi_id')->references('id')->on('gdg_barang_jadi');
            $table->unsignedBigInteger('dari')->nullable();
            $table->foreign('dari')->references('id')->on('divisi');
            $table->unsignedBigInteger('ke')->nullable();
            $table->foreign('ke')->references('id')->on('divisi');
            $table->string('noseri');
            $table->enum('jenis', ['MASUK','KELUAR']);
            $table->integer('is_aktif')->nullable();
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
        Schema::dropIfExists('noseri_barang_jadi');
    }
}
