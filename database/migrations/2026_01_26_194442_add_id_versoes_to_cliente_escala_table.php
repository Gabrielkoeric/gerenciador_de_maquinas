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
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->unsignedBigInteger('id_versoes')->nullable()->after('ativo');

            $table->foreign('id_versoes')
                ->references('id_versoes')
                ->on('versoes');
        });
    }

    public function down()
    {
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->dropForeign(['id_versoes']);
            $table->dropColumn('id_versoes');
        });
    }
};
