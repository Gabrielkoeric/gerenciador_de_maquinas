<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('secao_cloud', function (Blueprint $table) {
            $table->boolean('coletor')
                ->default(0)
                ->after('senha');
        });
    }

    public function down(): void
    {
        Schema::table('secao_cloud', function (Blueprint $table) {
            $table->dropColumn('coletor');
        });
    }
};
