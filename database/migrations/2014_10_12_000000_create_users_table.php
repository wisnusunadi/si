<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // $table->integer('divisi_id');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('api_token')->nullable();
            $table->string('foto')->nullable();
            $table->string('password');
            $table->text('foto');
            $table->enum('status', ['online', 'offline']);
            $table->integer('is_admin')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
