<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sh_asigna', function (Blueprint $table) {
            $table->string('lugar_despacho_cod', 50)->nullable()->after('sucursal_id');
            $table->string('lugar_despacho_nom', 200)->nullable()->after('lugar_despacho_cod');
        });
    }

    public function down(): void
    {
        Schema::table('sh_asigna', function (Blueprint $table) {
            $table->dropColumn(['lugar_despacho_cod', 'lugar_despacho_nom']);
        });
    }
};
