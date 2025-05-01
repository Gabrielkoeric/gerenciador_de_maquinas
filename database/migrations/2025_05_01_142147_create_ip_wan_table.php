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
        Schema::create('ip_wan', function (Blueprint $table) {
            $table->id('id_ip_wan');
            $table->string('ip');
        });
        $dadosPadraoIp = [
            [
                'id_ip_wan' => 1,
                'ip' => '0.0.0.0',
            ],
        ];
        DB::table('ip_wan')->insert($dadosPadraoIp);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_wan');
    }
};
