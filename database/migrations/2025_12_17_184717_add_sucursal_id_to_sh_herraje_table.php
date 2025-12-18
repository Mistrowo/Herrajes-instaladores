<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sh_herraje', function (Blueprint $table) {
            $table->unsignedBigInteger('sucursal_id')->nullable()->after('asigna_id');
            
            // Índice para mejorar performance
            $table->index('sucursal_id');
        });
    }

    public function down(): void
    {
        Schema::table('sh_herraje', function (Blueprint $table) {
            $table->dropIndex(['sucursal_id']);
            $table->dropColumn('sucursal_id');
        });
    }
};