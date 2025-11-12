<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lista de columnas que deben ser ENUM('SI', 'NO')
        $columns = [
            // Sección 1: Proyecto/Pedido
            'rectificacion_medidas',
            'planos_actualizados',
            'planos_muebles_especiales',
            'modificaciones_realizadas',
            'despacho_integral',
            
            // Sección 2: Errores
            'errores_ventas',
            'errores_diseno',
            'errores_rectificacion',
            'errores_produccion',
            'errores_proveedor',
            'errores_despacho',
            'errores_instalacion',
            'errores_otro',
            
            // Sección 3: Estado Obra
            'instalacion_cielo',
            'instalacion_piso',
            'remate_muros',
            'nivelacion_piso',
            'muros_plomo',
            'instalacion_electrica',
            'instalacion_voz_dato',
            
            // Sección 4: Inspección Final
            'paneles_alineados',
            'nivelacion_cubiertas',
            'pasacables_instalados',
            'limpieza_cubiertas',
            'limpieza_cajones',
            'limpieza_piso',
            'llaves_instaladas',
            'funcionamiento_mueble',
            'puntos_electricos',
            'sillas_ubicadas',
            'accesorios',
            'check_herramientas',
        ];

        // Modificar cada columna a ENUM
        foreach ($columns as $column) {
            DB::statement("ALTER TABLE `sh_checklist` MODIFY `{$column}` ENUM('SI', 'NO') NULL DEFAULT NULL");
        }

        echo "✅ Columnas corregidas a ENUM('SI', 'NO')\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios (opcional)
        $columns = [
            'rectificacion_medidas', 'planos_actualizados', 'planos_muebles_especiales',
            'modificaciones_realizadas', 'despacho_integral', 'errores_ventas',
            'errores_diseno', 'errores_rectificacion', 'errores_produccion',
            'errores_proveedor', 'errores_despacho', 'errores_instalacion',
            'errores_otro', 'instalacion_cielo', 'instalacion_piso', 'remate_muros',
            'nivelacion_piso', 'muros_plomo', 'instalacion_electrica',
            'instalacion_voz_dato', 'paneles_alineados', 'nivelacion_cubiertas',
            'pasacables_instalados', 'limpieza_cubiertas', 'limpieza_cajones',
            'limpieza_piso', 'llaves_instaladas', 'funcionamiento_mueble',
            'puntos_electricos', 'sillas_ubicadas', 'accesorios', 'check_herramientas'
        ];

        foreach ($columns as $column) {
            DB::statement("ALTER TABLE `sh_checklist` MODIFY `{$column}` TINYINT(1) NULL DEFAULT NULL");
        }
    }
};