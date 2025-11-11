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
        Schema::create('sh_evidencia_fotografica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asigna_id')
                  ->constrained('sh_asigna')
                  ->onDelete('cascade')
                  ->comment('Referencia a la asignación de instalación');

            $table->unsignedInteger('nota_venta')
                  ->comment('Folio de la nota de venta (NV)');

            $table->foreignId('instalador_id')
                  ->constrained('sh_instalador')
                  ->onDelete('cascade')
                  ->comment('Quién subió la foto');

            $table->string('imagen_path')
                  ->comment('Ruta del archivo en storage/app/public/evidencias/...');

            $table->text('descripcion')
                  ->nullable()
                  ->comment('Descripción opcional de la foto');

            $table->timestamp('fecha_subida')
                  ->useCurrent()
                  ->comment('Fecha y hora de subida');

            $table->softDeletes();
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index('nota_venta');
            $table->index(['asigna_id', 'nota_venta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sh_evidencia_fotografica');
    }
};