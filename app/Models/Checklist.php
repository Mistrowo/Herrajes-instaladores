<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nombre de la tabla
     */
    protected $table = 'sh_checklist';

    /**
     * Atributos asignables
     */
    protected $fillable = [
        'asigna_id',
        'nota_venta',
        'instalador_id',
        
        // Sección 1: Proyecto/Pedido
        'rectificacion_medidas',
        'planos_actualizados',
        'planos_muebles_especiales',
        'modificaciones_realizadas',
        'mod_autorizadas_por',
        'despacho_integral',
        'telefono',
        
        // Sección 2: Errores
        'errores_ventas',
        'errores_diseno',
        'errores_rectificacion',
        'errores_produccion',
        'errores_proveedor',
        'errores_despacho',
        'errores_instalacion',
        'errores_otro',
        'observaciones',
        
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
        
        'fecha_completado',
    ];

    /**
     * Casts de atributos
     * ⚠️ NO usar 'boolean' para campos ENUM('SI', 'NO')
     * Solo para fecha_completado
     */
    protected function casts(): array
    {
        return [
            'fecha_completado' => 'datetime',
        ];
    }

    /**
     * Relación con Asigna
     */
    public function asignacion()
    {
        return $this->belongsTo(Asigna::class, 'asigna_id');
    }

    /**
     * Relación con Instalador
     */
    public function instalador()
    {
        return $this->belongsTo(Instalador::class, 'instalador_id');
    }

    /**
     * Verificar si hay algún error en el proyecto
     */
    public function hasAnyErrors(): bool
    {
        return $this->errores_ventas === 'SI'
            || $this->errores_diseno === 'SI'
            || $this->errores_rectificacion === 'SI'
            || $this->errores_produccion === 'SI'
            || $this->errores_proveedor === 'SI'
            || $this->errores_despacho === 'SI'
            || $this->errores_instalacion === 'SI'
            || $this->errores_otro === 'SI';
    }

    /**
     * Contar cantidad de errores
     */
    public function countErrors(): int
    {
        $count = 0;
        if ($this->errores_ventas === 'SI') $count++;
        if ($this->errores_diseno === 'SI') $count++;
        if ($this->errores_rectificacion === 'SI') $count++;
        if ($this->errores_produccion === 'SI') $count++;
        if ($this->errores_proveedor === 'SI') $count++;
        if ($this->errores_despacho === 'SI') $count++;
        if ($this->errores_instalacion === 'SI') $count++;
        if ($this->errores_otro === 'SI') $count++;
        return $count;
    }

    /**
     * Obtener porcentaje de completitud del checklist
     */
    public function getCompletionPercentage(): int
    {
        $fields = [
            'rectificacion_medidas', 'planos_actualizados', 'planos_muebles_especiales',
            'modificaciones_realizadas', 'despacho_integral', 'instalacion_cielo',
            'instalacion_piso', 'remate_muros', 'nivelacion_piso', 'muros_plomo',
            'instalacion_electrica', 'instalacion_voz_dato', 'paneles_alineados',
            'nivelacion_cubiertas', 'pasacables_instalados', 'limpieza_cubiertas',
            'limpieza_cajones', 'limpieza_piso', 'llaves_instaladas',
            'funcionamiento_mueble', 'puntos_electricos', 'sillas_ubicadas',
            'accesorios', 'check_herramientas'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field) && ($this->$field === 'SI' || $this->$field === 'NO')) {
                $completed++;
            }
        }

        return $fields ? round(($completed / count($fields)) * 100) : 0;
    }

    /**
     * Verificar si el checklist está completo
     */
    public function isComplete(): bool
    {
        return $this->getCompletionPercentage() === 100;
    }

    /**
     * Scope: Checklists con errores
     */
    public function scopeWithErrors($query)
    {
        return $query->where(function($q) {
            $q->where('errores_ventas', 'SI')
              ->orWhere('errores_diseno', 'SI')
              ->orWhere('errores_rectificacion', 'SI')
              ->orWhere('errores_produccion', 'SI')
              ->orWhere('errores_proveedor', 'SI')
              ->orWhere('errores_despacho', 'SI')
              ->orWhere('errores_instalacion', 'SI')
              ->orWhere('errores_otro', 'SI');
        });
    }

    /**
     * Scope: Checklists completos
     */
    public function scopeComplete($query)
    {
        return $query->whereNotNull('fecha_completado')
                     ->whereNotNull('rectificacion_medidas')
                     ->whereNotNull('planos_actualizados')
                     ->whereNotNull('despacho_integral');
    }

    /**
     * Scope: Por nota de venta
     */
    public function scopeByNotaVenta($query, $notaVenta)
    {
        return $query->where('nota_venta', $notaVenta);
    }

    /**
     * Scope: Por instalador
     */
    public function scopeByInstalador($query, $instaladorId)
    {
        return $query->where('instalador_id', $instaladorId);
    }

    /**
     * Accessor: Formato de fecha completado
     */
    public function getFechaCompletadoFormateadaAttribute(): string
    {
        return $this->fecha_completado 
            ? $this->fecha_completado->format('d/m/Y H:i') 
            : 'Pendiente';
    }

    /**
     * Accessor: Estado del checklist
     */
    public function getEstadoAttribute(): string
    {
        if (!$this->fecha_completado) {
            return 'En progreso';
        }

        if ($this->hasAnyErrors()) {
            return 'Completado con errores';
        }

        return 'Completado';
    }

    /**
     * Accessor: Badge de estado (para UI)
     */
    public function getEstadoBadgeAttribute(): array
    {
        if (!$this->fecha_completado) {
            return [
                'text' => 'En progreso',
                'color' => 'yellow'
            ];
        }

        if ($this->hasAnyErrors()) {
            return [
                'text' => 'Con errores',
                'color' => 'red'
            ];
        }

        return [
            'text' => 'Completado',
            'color' => 'green'
        ];
    }
}