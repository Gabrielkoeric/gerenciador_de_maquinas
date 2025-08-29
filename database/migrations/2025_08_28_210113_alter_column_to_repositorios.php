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
        // 1️⃣ Adiciona a coluna como nullable temporariamente
        Schema::table('repositorios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_server_bkp')->nullable()->after('id_vm');
        });

        // 2️⃣ Verifica se há registros existentes
        $hasRecords = DB::table('repositorios')->exists();

        if ($hasRecords) {
            // Atualiza todos os registros existentes com valor 1
            DB::table('repositorios')->update(['id_server_bkp' => 1]);
        }

        // 3️⃣ Adiciona a foreign key
        Schema::table('repositorios', function (Blueprint $table) {
            $table->foreign('id_server_bkp')
                  ->references('id_vm')
                  ->on('vm');
        });
    }

    public function down()
    {
        Schema::table('repositorios', function (Blueprint $table) {
            $table->dropForeign(['id_server_bkp']);
            $table->dropColumn('id_server_bkp');
        });
    }
};
