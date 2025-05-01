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
        Schema::create('ip_lan', function (Blueprint $table) {
            $table->id('id_ip_lan');
            $table->string('ip');
            
            $table->unsignedBigInteger('id_rede');
            $table->foreign('id_rede')->references('id_rede')->on('rede');
        });
        $dadosPadraoIp = [
            [
                'id_ip_lan' => 1,
                'ip' => '0.0.0.0',
                'id_rede' => 1,
            ],
        ];
        DB::table('ip_lan')->insert($dadosPadraoIp);
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
