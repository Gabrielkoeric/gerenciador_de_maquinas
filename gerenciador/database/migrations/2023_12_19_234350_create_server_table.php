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
        Schema::create('server', function (Blueprint $table) {
            $table->id('id_server');
            $table->string('nome_server');
            $table->string('usuario');
            $table->string('senha');
            $table->string('ip_lan');
            $table->string('ip_publico');
            $table->string('porta');
            $table->string('processador');
            $table->string('memoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server');
    }
};
