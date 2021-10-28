<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TTfbjNoseri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tfbj_noseri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tfbj_id');
            $table->foreign('tfbj_id')->references('id')->on('t_tfbj');
            $table->string('noseri');
            $table->integer('is_aktif');
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
        Schema::dropIfExists('t_tfbj_noseri');
    }
}
