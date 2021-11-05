<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TTfbj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tfbj', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ke');
            $table->string('deskripsi')->nullable();
            $table->foreign('ke')->references('id')->on('divisi');
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
        Schema::dropIfExists('t_tfbj');
    }
}
