<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sh_checklist';

    protected $fillable = [
        'asigna_id',
        'nota_venta',
        'instalador_id',
        'sucursal_id',  // ⭐ NUEVO
        
        // Sección 1: Proyecto/Pedido
        'rectificacion_medidas',
        'rectificacion_medidas_obs',
        'planos_actualizados',
        'planos_actualizados_obs',
        'planos_muebles_especiales',
        'planos_muebles_especiales_obs',
        'modificaciones_realizadas',
        'modificaciones_realizadas_obs',
        'mod_autorizadas_por',
        'mod_autorizadas_por_obs',
        'despacho_integral',
        'despacho_integral_obs',
        'telefono',
        'telefono_obs',
        
        // Sección 2: Errores
        'errores_ventas',
        'errores_ventas_obs',
        'errores_diseno',
        'errores_diseno_obs',
        'errores_rectificacion',
        'errores_rectificacion_obs',
        'errores_produccion',
        'errores_produccion_obs',
        'errores_proveedor',
        'errores_proveedor_obs',
        'errores_despacho',
        'errores_despacho_obs',
        'errores_instalacion',
        'errores_instalacion_obs',
        'errores_otro',
        'errores_otro_obs',
        'observaciones',
        
        // Sección 3: Estado Obra
        'instalacion_cielo',
        'instalacion_cielo_obs',
        'instalacion_piso',
        'instalacion_piso_obs',
        'remate_muros',
        'remate_muros_obs',
        'nivelacion_piso',
        'nivelacion_piso_obs',
        'muros_plomo',
        'muros_plomo_obs',
        'instalacion_electrica',
        'instalacion_electrica_obs',
        'instalacion_voz_dato',
        'instalacion_voz_dato_obs',
        
        // Sección 4: Inspección Final
        'paneles_alineados',
        'paneles_alineados_obs',
        'nivelacion_cubiertas',
        'nivelacion_cubiertas_obs',
        'pasacables_instalados',
        'pasacables_instalados_obs',
        'limpieza_cubiertas',
        'limpieza_cubiertas_obs',
        'limpieza_cajones',
        'limpieza_cajones_obs',
        'limpieza_piso',
        'limpieza_piso_obs',
        'llaves_instaladas',
        'llaves_instaladas_obs',
        'funcionamiento_mueble',
        'funcionamiento_mueble_obs',
        'puntos_electricos',
        'puntos_electricos_obs',
        'sillas_ubicadas',
        'sillas_ubicadas_obs',
        'accesorios',
        'accesorios_obs',
        'check_herramientas',
        'check_herramientas_obs',
        
        'fecha_completado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_completado' => 'datetime',
        ];
    }

    // Relaciones
    public function asignacion()
    {
        return $this->belongsTo(Asigna::class, 'asigna_id');
    }

    public function instalador()
    {
        return $this->belongsTo(Instalador::class, 'instalador_id');
    }

    // ⭐ NUEVA RELACIÓN
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    // ⭐ NUEVO ACCESSOR
    public function getSucursalNombreAttribute(): string
    {
        return $this->sucursal ? $this->sucursal->nombre : 'Sin sucursal';
    }

    // Métodos existentes...
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

    public function isComplete(): bool
    {
        return $this->getCompletionPercentage() === 100;
    }

    // Scopes
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

    public function scopeComplete($query)
    {
        return $query->whereNotNull('fecha_completado')
                     ->whereNotNull('rectificacion_medidas')
                     ->whereNotNull('planos_actualizados')
                     ->whereNotNull('despacho_integral');
    }

    public function scopeByNotaVenta($query, $notaVenta)
    {
        return $query->where('nota_venta', $notaVenta);
    }

    public function scopeByInstalador($query, $instaladorId)
    {
        return $query->where('instalador_id', $instaladorId);
    }

    // ⭐ NUEVO SCOPE
    public function scopeBySucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    // Accessors
    public function getFechaCompletadoFormateadaAttribute(): string
    {
        return $this->fecha_completado 
            ? $this->fecha_completado->format('d/m/Y H:i') 
            : 'Pendiente';
    }

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

    public function getEstadoBadgeAttribute(): array
    {
        if (!$this->fecha_completado) {
            return [
                'text' => 'En progreso',
                'color' => 'yellow',
            ];
        }

        if ($this->hasAnyErrors()) {
            return [
                'text' => 'Con errores',
                'color' => 'red',
            ];
        }

        return [
            'text' => 'Completado',
            'color' => 'green',
        ];
    }
}