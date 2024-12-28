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
        Schema::create('temp_ip', function (Blueprint $table) {
            $table->id('id_temp_ip');
            $table->string('ip');
            
            $table->unsignedBigInteger('id_incidente');
            $table->foreign('id_incidente')->references('id_incidente')->on('incidente');
         

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
        Schema::dropIfExists('temp_ip');
    }
};
