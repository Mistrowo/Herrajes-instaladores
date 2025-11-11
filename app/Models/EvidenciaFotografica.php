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
        'instalador_id',
        'imagen_path',
        'descripcion',
        'fecha_subida',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    // Relaciones
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


public function getUrlAttribute(): string
{
    return Storage::url($this->imagen_path); // CORRECTO
}

    protected static function booted(): void
    {
        static::deleting(function ($evidencia) {
            if ($evidencia->imagen_path && Storage::disk('public')->exists($evidencia->imagen_path)) {
                Storage::disk('public')->delete($evidencia->imagen_path);
            }
        });
    }
}