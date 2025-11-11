<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sh_herraje', function (Blueprint $table) {
            $table->id();
            // Folio de Nota de Venta (entero, coincide con nv_folio de NotaVta_Actualiza)
            $table->unsignedBigInteger('nv_folio')->index();

            // Vinculación opcional a Asigna (sh_asigna.id)
            $table->unsignedBigInteger('asigna_id')->nullable()->index();
            $table->foreign('asigna_id')->references('id')->on('sh_asigna');

            // Responsable (instalador)
            $table->unsignedBigInteger('instalador_id')->nullable()->index();
            $table->foreign('instalador_id')->references('id')->on('sh_instalador');

            // Estado del documento de herraje
            $table->string('estado', 30)->default('borrador'); // borrador | en_revision | aprobado | rechazado

            // Campos de control/resumen
            $table->integer('items_count')->default(0);
            $table->decimal('total_estimado', 12, 2)->nullable(); // opcional si se usa precio

            // Observaciones/Nótese
            $table->text('observaciones')->nullable();

            // Metadata
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Si quieres uno por NV, descomenta la siguiente línea:
            // $table->unique('nv_folio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sh_herraje');
    }
};
