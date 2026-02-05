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
                'nomeConfig'  => 'vcenterUrl',
                'valorConfig' => null,
            ],
            [
                'nomeConfig'  => 'idacessovmware',
                'valorConfig' => null,
            ],
        ]);
    }

    public function down()
    {
        DB::table('config_geral')
            ->whereIn('nomeConfig', ['vcenterUrl', 'idacessovmware'])
            ->delete();
    }
};
