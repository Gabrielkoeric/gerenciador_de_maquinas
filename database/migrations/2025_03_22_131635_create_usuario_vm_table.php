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
        Schema::create('usuario_vm', function (Blueprint $table) {
            $table->id('id_usuario_vm');
            $table->string('usuario');
            $table->string('senha');
            $table->timestamps();

            $table->unsignedBigInteger('id_vm');

            $table->foreign('id_vm')->references('id_vm')->on('vm');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_vm');
    }
};
