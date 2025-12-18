<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asigna extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sh_asigna';

    protected $fillable = [
        'nota_venta',
        'sucursal_id',
        'fecha_asigna',
        'asignado1',
        'asignado2',
        'asignado3',
        'asignado4',
        'solicita',
        'estado',
        'terminado',
        'fecha_acepta',
        'fecha_termino',
        'observaciones',
    ];

    protected $casts = [
        'fecha_asigna' => 'date',
        'fecha_acepta' => 'datetime',
        'fecha_termino' => 'datetime',
        'terminado' => 'boolean',
    ];

    // ===============================================
    // RELACIONES
    // ===============================================

    public function instalador1()
    {
        return $this->belongsTo(Instalador::class, 'asignado1');
    }

    public function instalador2()
    {
        return $this->belongsTo(Instalador::class, 'asignado2');
    }

    public function instalador3()
    {
        return $this->belongsTo(Instalador::class, 'asignado3');
    }

    public function instalador4()
    {
        return $this->belongsTo(Instalador::class, 'asignado4');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function notaVenta()
    {
        return $this->belongsTo(NotaVtaActualiza::class, 'nota_venta', 'nv_folio');
    }

    // ===============================================
    // SCOPES
    // ===============================================

    /**
     * Scope para filtrar asignaciones por instalador
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $instaladorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorInstalador($query, $instaladorId)
    {
        return $query->where(function($q) use ($instaladorId) {
            $q->where('asignado1', $instaladorId)
              ->orWhere('asignado2', $instaladorId)
              ->orWhere('asignado3', $instaladorId)
              ->orWhere('asignado4', $instaladorId);
        });
    }

    /**
     * Scope para filtrar por estado
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $estado
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por nota de venta
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $notaVenta
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorNotaVenta($query, $notaVenta)
    {
        return $query->where('nota_venta', $notaVenta);
    }

    /**
     * Scope para asignaciones activas (no completadas ni rechazadas)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivas($query)
    {
        return $query->whereNotIn('estado', ['completada', 'rechazada']);
    }

    // ===============================================
    // MÉTODOS AUXILIARES
    // ===============================================

    /**
     * Obtener cantidad de instaladores asignados
     * 
     * @return int
     */
    public function cantidadInstaladores(): int
    {
        $count = 0;
        if ($this->asignado1) $count++;
        if ($this->asignado2) $count++;
        if ($this->asignado3) $count++;
        if ($this->asignado4) $count++;
        return $count;
    }

    /**
     * Verificar si un instalador está asignado
     * 
     * @param int $instaladorId
     * @return bool
     */
    public function tieneInstalador(int $instaladorId): bool
    {
        return $this->asignado1 == $instaladorId
            || $this->asignado2 == $instaladorId
            || $this->asignado3 == $instaladorId
            || $this->asignado4 == $instaladorId;
    }

    /**
     * Obtener todos los instaladores asignados
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getInstaladoresAttribute()
    {
        $instaladores = collect();
        
        if ($this->instalador1) {
            $instaladores->push($this->instalador1);
        }
        if ($this->instalador2) {
            $instaladores->push($this->instalador2);
        }
        if ($this->instalador3) {
            $instaladores->push($this->instalador3);
        }
        if ($this->instalador4) {
            $instaladores->push($this->instalador4);
        }
        
        return $instaladores;
    }

    /**
     * Obtener badge de estado con color y texto
     * 
     * @return array
     */
    public function getEstadoBadgeAttribute(): array
    {
        $badges = [
            'pendiente' => ['color' => 'yellow', 'text' => 'Pendiente'],
            'aceptada' => ['color' => 'green', 'text' => 'Aceptada'],
            'en_proceso' => ['color' => 'blue', 'text' => 'En Proceso'],
            'completada' => ['color' => 'gray', 'text' => 'Completada'],
            'rechazada' => ['color' => 'red', 'text' => 'Rechazada'],
        ];

        return $badges[$this->estado] ?? ['color' => 'gray', 'text' => 'Desconocido'];
    }

    /**
     * Obtener fecha de asignación formateada
     * 
     * @return string
     */
    public function getFechaAsignaFormateadaAttribute(): string
    {
        return $this->fecha_asigna ? $this->fecha_asigna->format('d-m-Y') : '-';
    }

    /**
     * Obtener fecha de aceptación formateada
     * 
     * @return string|null
     */
    public function getFechaAceptaFormateadaAttribute(): ?string
    {
        return $this->fecha_acepta ? $this->fecha_acepta->format('d-m-Y H:i') : null;
    }

    /**
     * Obtener fecha de término formateada
     * 
     * @return string|null
     */
    public function getFechaTerminoFormateadaAttribute(): ?string
    {
        return $this->fecha_termino ? $this->fecha_termino->format('d-m-Y H:i') : null;
    }

    /**
     * Verificar si la asignación está vencida
     * 
     * @return bool
     */
    public function estaVencida(): bool
    {
        if (!$this->fecha_asigna || $this->estado == 'completada') {
            return false;
        }

        return $this->fecha_asigna->isPast() && in_array($this->estado, ['pendiente', 'aceptada']);
    }

    /**
     * Obtener días desde la asignación
     * 
     * @return int
     */
    public function diasDesdeAsignacion(): int
    {
        if (!$this->fecha_asigna) {
            return 0;
        }

        return abs($this->fecha_asigna->diffInDays(now()));
    }
}