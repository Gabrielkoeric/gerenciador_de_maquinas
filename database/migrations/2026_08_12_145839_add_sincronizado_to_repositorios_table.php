<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repositorios', function (Blueprint $table) {
            $table->boolean('sincronizado')->nullable()->default(false)->after('diario');
        });
    }
    
    public function down(): void
    {
        Schema::table('repositorios', function (Blueprint $table) {
            $table->dropColumn('sincronizado');
        });
    }
};
