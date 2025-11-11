<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sh_checklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asigna_id')->constrained('sh_asigna')->onDelete('cascade');
            $table->unsignedInteger('nota_venta');
            $table->foreignId('instalador_id')->constrained('sh_instalador')->onDelete('cascade');

            // SECCIÓN 1: NÚMERO PROYECTO/PEDIDO
            $table->boolean('rectificacion_medidas')->default(false);
            $table->boolean('planos_actualizados')->default(false);
            $table->boolean('planos_muebles_especiales')->default(false);
            $table->boolean('modificaciones_realizadas')->default(false);
            $table->string('mod_autorizadas_por')->nullable();
            $table->boolean('despacho_integral')->default(false);

            // ERRORES PROYECTO
            $table->boolean('errores_ventas')->default(false);
            $table->boolean('errores_diseno')->default(false);
            $table->boolean('errores_rectificacion')->default(false);
            $table->boolean('errores_produccion')->default(false);
            $table->boolean('errores_proveedor')->default(false);
            $table->boolean('errores_despacho')->default(false);
            $table->boolean('errores_instalacion')->default(false);
            $table->boolean('errores_otro')->default(false);
            $table->text('observaciones')->nullable();

            // ESTADO OBRA
            $table->boolean('instalacion_cielo')->default(false);
            $table->boolean('instalacion_piso')->default(false);
            $table->boolean('remate_muros')->default(false);
            $table->boolean('nivelacion_piso')->default(false);
            $table->boolean('muros_plomo')->default(false);
            $table->boolean('instalacion_electrica')->default(false);
            $table->boolean('instalacion_voz_dato')->default(false);

            // INSPECCIÓN FINAL
            $table->boolean('paneles_alineados')->default(false);
            $table->boolean('nivelacion_cubiertas')->default(false);
            $table->boolean('pasacables_instalados')->default(false);
            $table->boolean('limpieza_cubiertas')->default(false);
            $table->boolean('limpieza_cajones')->default(false);
            $table->boolean('limpieza_piso')->default(false);
            $table->boolean('llaves_instaladas')->default(false);
            $table->boolean('funcionamiento_mueble')->default(false);
            $table->boolean('puntos_electricos')->default(false);
            $table->boolean('sillas_ubicadas')->default(false);
            $table->boolean('accesorios')->default(false);
            $table->boolean('check_herramientas')->default(false);

            $table->string('telefono')->nullable();
            $table->timestamp('fecha_completado')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['asigna_id']);
            $table->index('nota_venta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sh_checklist');
    }
};