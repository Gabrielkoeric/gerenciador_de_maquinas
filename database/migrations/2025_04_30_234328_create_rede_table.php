<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rede', function (Blueprint $table) {
            $table->id('id_rede');
            $table->string('ip');
            $table->string('mascara');
            $table->string('descricao');
        });
        $dadosPadraoRede = [
            [
                'id_rede' => 1,
                'ip' => '0.0.0.0',
                'mascara' => '255.255.255.254',
                'descricao' => 'default',
            ],
        ];    
        DB::table('rede')->insert($dadosPadraoRede);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rede');
    }
};
