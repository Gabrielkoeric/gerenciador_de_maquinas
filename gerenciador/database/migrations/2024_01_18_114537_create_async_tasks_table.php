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
        Schema::create('async_tasks', function (Blueprint $table) {
            $table->id('id_async_tasks');
            $table->timestamp('horario_disparo')->useCurrent();
            $table->timestamp('horario_inicio')->nullable();
            $table->timestamp('horario_fim')->nullable();
            $table->string('status')->default('pendente');
            $table->text('log')->nullable();
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
        Schema::dropIfExists('async_tasks');
    }
};
