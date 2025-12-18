<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sh_evidencia_fotografica', function (Blueprint $table) {
            $table->unsignedBigInteger('sucursal_id')->nullable()->after('nota_venta');
            
            // Agregar índice para mejorar rendimiento
            $table->index(['nota_venta', 'sucursal_id']);
            
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->dropIndex(['nota_venta', 'sucursal_id']);
            $table->dropColumn('sucursal_id');
        });
    }
};