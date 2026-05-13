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
        Schema::create('usuario_email_config', function (Blueprint $table) {
            $table->id('id_usuario_email_config');
            $table->string('host');
            $table->integer('port');
            $table->string('username');
            $table->text('password');
            $table->string('criptografia')->nullable();
            $table->string('from_address');
            $table->string('from_name');
            $table->timestamps();

            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_email_config');
    }
};
