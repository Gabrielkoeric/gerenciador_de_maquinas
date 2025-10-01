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
        Schema::table('rclone_execucoes', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('id_execucao');
            $table->text('comando_rclone')->nullable()->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rclone_execucoes', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('comando_rclone');
        });
    }
};
