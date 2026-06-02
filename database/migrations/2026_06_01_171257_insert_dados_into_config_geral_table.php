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
                'nomeConfig'  => 'url_api_24',
                'valorConfig' => null,
            ]
        ]);
    }

    public function down()
    {
        DB::table('config_geral')
            ->whereIn('nomeConfig', ['url_api_24'])
            ->delete();
    }
};
