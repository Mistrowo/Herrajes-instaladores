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
        'rectificacion_medidas',
        'planos_actualizados',
        'planos_muebles_especiales',
        'modificaciones_realizadas',
        'mod_autorizadas_por',
        'despacho_integral',
        'errores_ventas',
        'errores_diseno',
        'errores_rectificacion',
        'errores_produccion',
        'errores_proveedor',
        'errores_despacho',
        'errores_instalacion',
        'errores_otro',
        'observaciones',
        'instalacion_cielo',
        'instalacion_piso',
        'remate_muros',
        'nivelacion_piso',
        'muros_plomo',
        'instalacion_electrica',
        'instalacion_voz_dato',
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
        'telefono',
        'fecha_completado',
    ];

    protected $casts = [
        'fecha_completado' => 'datetime',
        'rectificacion_medidas' => 'boolean',
        'planos_actualizados' => 'boolean',
        'despacho_integral' => 'boolean',
        'instalacion_cielo' => 'boolean',
    ];

    public function asignacion()
    {
        return $this->belongsTo(Asigna::class, 'asigna_id');
    }

    public function instalador()
    {
        return $this->belongsTo(Instalador::class, 'instalador_id');
    }

    public function hasAnyErrors(): bool
{
    return $this->errores_ventas ||
           $this->errores_diseno ||
           $this->errores_rectificacion ||
           $this->errores_produccion ||
           $this->errores_proveedor ||
           $this->errores_despacho ||
           $this->errores_instalacion ||
           $this->errores_otro;
}

public function countErrors(): int
{
    return (int) (
        $this->errores_ventas +
        $this->errores_diseno +
        $this->errores_rectificacion +
        $this->errores_produccion +
        $this->errores_proveedor +
        $this->errores_despacho +
        $this->errores_instalacion +
        $this->errores_otro
    );
}







}