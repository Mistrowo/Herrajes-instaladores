<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asigna extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $table = 'sh_asigna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nota_venta',
        'solicita',
        'asignado1',
        'asignado2',
        'asignado3',
        'asignado4',
        'fecha_asigna',
        'fecha_acepta',
        'estado',
        'observaciones',
        'terminado',        
        'fecha_termino',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_asigna' => 'datetime',
            'fecha_acepta' => 'datetime',
            'terminado' => 'boolean',
            'fecha_termino' => 'datetime',
        ];
    }

    
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

    
    public function instaladoresAsignados()
    {
        $instaladores = collect();
        
        if ($this->asignado1) {
            $instaladores->push($this->instalador1);
        }
        if ($this->asignado2) {
            $instaladores->push($this->instalador2);
        }
        if ($this->asignado3) {
            $instaladores->push($this->instalador3);
        }
        if ($this->asignado4) {
            $instaladores->push($this->instalador4);
        }
        
        return $instaladores->filter();
    }

    
    public function scopeNotaVenta($query, $notaVenta)
    {
        return $query->where('nota_venta', $notaVenta);
    }

    
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    
    public function scopeAceptadas($query)
    {
        return $query->where('estado', 'aceptada');
    }

        public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    
    public function scopePorInstalador($query, $instaladorId)
    {
        return $query->where(function($q) use ($instaladorId) {
            $q->where('asignado1', $instaladorId)
              ->orWhere('asignado2', $instaladorId)
              ->orWhere('asignado3', $instaladorId)
              ->orWhere('asignado4', $instaladorId);
        });
    }

    
    public function scopeFechaAsignaEntre($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_asigna', [$desde, $hasta]);
    }

    
    public function estaPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    
    public function estaAceptada(): bool
    {
        return $this->estado === 'aceptada';
    }

    
    public function estaEnProceso(): bool
    {
        return $this->estado === 'en_proceso';
    }

    
    public function estaCompletada(): bool
    {
        return $this->estado === 'completada';
    }

   
    public function cantidadInstaladores(): int
    {
        $count = 0;
        if ($this->asignado1) $count++;
        if ($this->asignado2) $count++;
        if ($this->asignado3) $count++;
        if ($this->asignado4) $count++;
        return $count;
    }

    
    public function getFechaAsignaFormateadaAttribute(): string
    {
        if (!$this->fecha_asigna) {
            return '-';
        }
        
        if (is_string($this->fecha_asigna)) {
            return \Carbon\Carbon::parse($this->fecha_asigna)->format('d-m-Y');
        }
        
        return $this->fecha_asigna->format('d-m-Y');
    }

    
    public function getFechaAceptaFormateadaAttribute(): string
    {
        if (!$this->fecha_acepta) {
            return '-';
        }
        
        if (is_string($this->fecha_acepta)) {
            return \Carbon\Carbon::parse($this->fecha_acepta)->format('d-m-Y');
        }
        
        return $this->fecha_acepta->format('d-m-Y');
    }

    public function getEstadoBadgeAttribute(): array
    {
        return match($this->estado) {
            'pendiente' => ['text' => 'Pendiente', 'color' => 'yellow'],
            'aceptada' => ['text' => 'Aceptada', 'color' => 'green'],
            'rechazada' => ['text' => 'Rechazada', 'color' => 'red'],
            'en_proceso' => ['text' => 'En Proceso', 'color' => 'blue'],
            'completada' => ['text' => 'Completada', 'color' => 'gray'],
            default => ['text' => 'Desconocido', 'color' => 'gray'],
        };
    }
}