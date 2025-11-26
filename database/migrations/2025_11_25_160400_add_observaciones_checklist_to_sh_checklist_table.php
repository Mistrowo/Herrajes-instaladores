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
        Schema::table('sh_checklist', function (Blueprint $table) {
            // Sección 1: Proyecto/Pedido
            $table->text('rectificacion_medidas_obs')->nullable()->after('rectificacion_medidas');
            $table->text('planos_actualizados_obs')->nullable()->after('planos_actualizados');
            $table->text('planos_muebles_especiales_obs')->nullable()->after('planos_muebles_especiales');
            $table->text('modificaciones_realizadas_obs')->nullable()->after('modificaciones_realizadas');
            $table->text('mod_autorizadas_por_obs')->nullable()->after('mod_autorizadas_por');
            $table->text('despacho_integral_obs')->nullable()->after('despacho_integral');
            $table->text('telefono_obs')->nullable()->after('telefono');

            // Sección 2: Errores
            $table->text('errores_ventas_obs')->nullable()->after('errores_ventas');
            $table->text('errores_diseno_obs')->nullable()->after('errores_diseno');
            $table->text('errores_rectificacion_obs')->nullable()->after('errores_rectificacion');
            $table->text('errores_produccion_obs')->nullable()->after('errores_produccion');
            $table->text('errores_proveedor_obs')->nullable()->after('errores_proveedor');
            $table->text('errores_despacho_obs')->nullable()->after('errores_despacho');
            $table->text('errores_instalacion_obs')->nullable()->after('errores_instalacion');
            $table->text('errores_otro_obs')->nullable()->after('errores_otro');

            // Sección 3: Estado Obra
            $table->text('instalacion_cielo_obs')->nullable()->after('instalacion_cielo');
            $table->text('instalacion_piso_obs')->nullable()->after('instalacion_piso');
            $table->text('remate_muros_obs')->nullable()->after('remate_muros');
            $table->text('nivelacion_piso_obs')->nullable()->after('nivelacion_piso');
            $table->text('muros_plomo_obs')->nullable()->after('muros_plomo');
            $table->text('instalacion_electrica_obs')->nullable()->after('instalacion_electrica');
            $table->text('instalacion_voz_dato_obs')->nullable()->after('instalacion_voz_dato');

            // Sección 4: Inspección Final
            $table->text('paneles_alineados_obs')->nullable()->after('paneles_alineados');
            $table->text('nivelacion_cubiertas_obs')->nullable()->after('nivelacion_cubiertas');
            $table->text('pasacables_instalados_obs')->nullable()->after('pasacables_instalados');
            $table->text('limpieza_cubiertas_obs')->nullable()->after('limpieza_cubiertas');
            $table->text('limpieza_cajones_obs')->nullable()->after('limpieza_cajones');
            $table->text('limpieza_piso_obs')->nullable()->after('limpieza_piso');
            $table->text('llaves_instaladas_obs')->nullable()->after('llaves_instaladas');
            $table->text('funcionamiento_mueble_obs')->nullable()->after('funcionamiento_mueble');
            $table->text('puntos_electricos_obs')->nullable()->after('puntos_electricos');
            $table->text('sillas_ubicadas_obs')->nullable()->after('sillas_ubicadas');
            $table->text('accesorios_obs')->nullable()->after('accesorios');
            $table->text('check_herramientas_obs')->nullable()->after('check_herramientas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sh_checklist', function (Blueprint $table) {
            // Sección 1
            $table->dropColumn([
                'rectificacion_medidas_obs',
                'planos_actualizados_obs',
                'planos_muebles_especiales_obs',
                'modificaciones_realizadas_obs',
                'mod_autorizadas_por_obs',
                'despacho_integral_obs',
                'telefono_obs',
            ]);

            // Sección 2
            $table->dropColumn([
                'errores_ventas_obs',
                'errores_diseno_obs',
                'errores_rectificacion_obs',
                'errores_produccion_obs',
                'errores_proveedor_obs',
                'errores_despacho_obs',
                'errores_instalacion_obs',
                'errores_otro_obs',
            ]);

            // Sección 3
            $table->dropColumn([
                'instalacion_cielo_obs',
                'instalacion_piso_obs',
                'remate_muros_obs',
                'nivelacion_piso_obs',
                'muros_plomo_obs',
                'instalacion_electrica_obs',
                'instalacion_voz_dato_obs',
            ]);

            // Sección 4
            $table->dropColumn([
                'paneles_alineados_obs',
                'nivelacion_cubiertas_obs',
                'pasacables_instalados_obs',
                'limpieza_cubiertas_obs',
                'limpieza_cajones_obs',
                'limpieza_piso_obs',
                'llaves_instaladas_obs',
                'funcionamiento_mueble_obs',
                'puntos_electricos_obs',
                'sillas_ubicadas_obs',
                'accesorios_obs',
                'check_herramientas_obs',
            ]);
        });
    }
};
