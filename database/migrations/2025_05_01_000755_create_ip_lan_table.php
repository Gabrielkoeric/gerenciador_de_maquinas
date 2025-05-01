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
        Schema::create('ip_lan', function (Blueprint $table) {
            $table->id('id_ip_lan');
            $table->string('ip');
            
            $table->unsignedBigInteger('id_rede');
            $table->foreign('id_rede')->references('id_rede')->on('rede');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_lan');
    }
};
