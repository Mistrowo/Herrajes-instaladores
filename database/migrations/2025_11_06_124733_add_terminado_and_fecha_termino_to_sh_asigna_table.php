<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sh_asigna', function (Blueprint $table) {
            $table->boolean('terminado')
                  ->default(false)
                  ->after('estado');

            $table->date('fecha_termino')
                  ->nullable()
                  ->after('terminado');
        });
    }

    public function down(): void
    {
        Schema::table('sh_asigna', function (Blueprint $table) {
            $table->dropColumn(['terminado', 'fecha_termino']);
        });
    }
};
