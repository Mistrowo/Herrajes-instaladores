<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EvidenciaFotografica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sh_evidencia_fotografica';

    protected $fillable = [
        'asigna_id',
        'nota_venta',
        'sucursal_id', 
        'instalador_id',
        'imagen_path',
        'descripcion',
        'fecha_subida',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    // Relaciones existentes
    public function asignacion()
    {
        return $this->belongsTo(Asigna::class, 'asigna_id');
    }

    public function instalador()
    {
        return $this->belongsTo(Instalador::class, 'instalador_id');
    }

    public function notaVenta()
    {
        return $this->belongsTo(NotaVtaActualiza::class, 'nota_venta', 'nv_folio');
    }

    /**
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    /**
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->imagen_path);
    }

    /**
     */
    public function getSucursalNombreAttribute(): string
    {
        return $this->sucursal ? $this->sucursal->nombre : 'Sin sucursal especificada';
    }

    /**
     */
    public function getSucursalInfoAttribute(): ?array
    {
        if (!$this->sucursal) {
            return null;
        }

        return [
            'id' => $this->sucursal->id,
            'nombre' => $this->sucursal->nombre,
            'direccion' => $this->sucursal->direccion,
            'comuna' => $this->sucursal->comuna,
        ];
    }

    /**
     * Scopes
     */
    public function scopePorNotaVenta($query, $notaVenta)
    {
        return $query->where('nota_venta', $notaVenta);
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    public function scopePorInstalador($query, $instaladorId)
    {
        return $query->where('instalador_id', $instaladorId);
    }

    /**
     */
    protected static function booted(): void
    {
        static::deleting(function ($evidencia) {
            if ($evidencia->imagen_path && Storage::disk('public')->exists($evidencia->imagen_path)) {
                Storage::disk('public')->delete($evidencia->imagen_path);
            }
        });
    }
}