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
        Schema::create('ip_incidente', function (Blueprint $table) {
            $table->id('id_ip_incidente');
            $table->integer('quantidade');

            $table->unsignedBigInteger('id_ip');
            $table->unsignedBigInteger('id_incidente');

            $table->foreign('id_ip')->references('id_ip')->on('ip');
            $table->foreign('id_incidente')->references('id_incidente')->on('incidente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_incidente');
    }
};
