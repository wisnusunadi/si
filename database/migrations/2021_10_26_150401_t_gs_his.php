<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TGsHis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_gs_his', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gs_id')->nullable();
            $table->foreign('gs_id')->references('id')->on('m_gs');
            $table->unsignedBigInteger('sparepart_id');
            $table->foreign('sparepart_id')->references('id')->on('m_sparepart');
            $table->string('nama')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('stok')->nullable();
            $table->unsignedBigInteger('layout_id')->nullable();
            $table->foreign('layout_id')->references('id')->on('m_layout');
            $table->string('status')->nullable()->comment('status sparepart');
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
        Schema::dropIfExists('t_gs_his');
    }
}
