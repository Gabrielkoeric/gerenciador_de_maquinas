<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('perfil', function (Blueprint $table) {
            $table->id('id_perfil');
            $table->string('nome');
            $table->timestamps();
        });

        DB::table('perfil')->insert([
            [
                'id_perfil' => 1,
                'nome' => 'Zerado',
            ],
            [
                'id_perfil' => 2,
                'nome' => 'ADM',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil');
    }
};
