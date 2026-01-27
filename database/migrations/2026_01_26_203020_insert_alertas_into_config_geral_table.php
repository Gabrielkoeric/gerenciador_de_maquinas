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
        DB::table('config_geral')->insert([
            [
                'nomeConfig'  => 'alerta_geral',
                'valorConfig' => null,
            ],
            [
                'nomeConfig'  => 'alerta_bkp',
                'valorConfig' => null,
            ],
        ]);
    }

    public function down()
    {
        DB::table('config_geral')
            ->whereIn('nomeConfig', ['alerta_geral', 'alerta_bkp'])
            ->delete();
    }
};
