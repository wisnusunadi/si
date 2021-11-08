<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TTfbjHis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tfbj_his', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tfbj_id');
            $table->foreign('tfbj_id')->references('id')->on('t_tfbj');
            $table->unsignedBigInteger('gdg_brg_jadi_id')->nullable();
            $table->foreign('gdg_brg_jadi_id')->references('id')->on('gdg_barang_jadi');
            $table->integer('qty');
            $table->string('noseri');
            $table->integer('is_aktif')->default(0);
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
        Schema::dropIfExists('t_tfbj_his');
    }
}
