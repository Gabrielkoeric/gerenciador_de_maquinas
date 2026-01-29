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
                'nomeConfig'  => 'rota_padrao_ws',
                'valorConfig' => null,
            ],
            [
                'nomeConfig'  => 'rota_padrao_ws_cloud',
                'valorConfig' => null,
            ],
        ]);
    }

    public function down()
    {
        DB::table('config_geral')
            ->whereIn('nomeConfig', ['rota_padrao_ws', 'rota_padrao_ws_cloud'])
            ->delete();
    }
};
