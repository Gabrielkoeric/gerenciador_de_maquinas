<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_logs', function (Blueprint $table) {
            $table->id('id_check_logs');
            $table->boolean('checkIn');
            $table->boolean('checkOut');
            $table->string('ip_address');
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('id_controle_ingresso');
            $table->timestamps();

            $table->foreign('id')->references('id')->on('usuarios');
            $table->foreign('id_controle_ingresso')->references('id_controle_ingresso')->on('controle_ingressos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_logs');
    }
};
