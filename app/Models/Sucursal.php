<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Sucursal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Conexión a la base de datos de ventas (portal-clientes)
     */
    protected $connection = 'ventas';

    /**
     * Nombre de la tabla
     */
    protected $table = 'sucursals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empresa_id',
        'nombre',
        'direccion',
        'comuna',
        'region',
        'telefono',
        'email',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Obtener información de la empresa (sin modelo Empresa)
     */
    public function getEmpresaAttribute()
    {
        if (!$this->empresa_id) {
            return null;
        }

        $empresa = DB::connection('ventas')
            ->table('empresas')
            ->where('id', $this->empresa_id)
            ->whereNull('deleted_at')
            ->first();

        return $empresa;
    }

    /**
     * Obtener nombre de la empresa
     */
    public function getEmpresaNombreAttribute(): string
    {
        $empresa = $this->empresa;
        
        if (!$empresa) {
            return '-';
        }

        return $empresa->nombe_de_fantasia ?: $empresa->razon_social;
    }

    /**
     * Obtener RUT de la empresa
     */
    public function getEmpresaRutAttribute(): string
    {
        $empresa = $this->empresa;
        return $empresa ? $empresa->rut : '-';
    }

    /**
     * Scope para sucursales activas (no eliminadas)
     */
    public function scopeActivas($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope para filtrar por empresa
     */
    public function scopeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Obtener nombre completo de sucursal con empresa
     */
    public function getNombreCompletoAttribute(): string
    {
        $empresaNombre = $this->empresa_nombre;
        return "{$this->nombre} - {$empresaNombre}";
    }

    /**
     * Obtener dirección completa
     */
    public function getDireccionCompletaAttribute(): string
    {
        $partes = array_filter([
            $this->direccion,
            $this->comuna,
            $this->region
        ]);
        
        return implode(', ', $partes) ?: '-';
    }

    /**
     * Obtener información de contacto
     */
    public function getContactoAttribute(): string
    {
        $contacto = [];
        
        if ($this->telefono) {
            $contacto[] = "Tel: {$this->telefono}";
        }
        
        if ($this->email) {
            $contacto[] = "Email: {$this->email}";
        }
        
        return implode(' | ', $contacto) ?: 'Sin contacto';
    }

    /**
     * Buscar sucursales por nombre de cliente (búsqueda directa en tabla empresas)
     * 
     * @param string $nombreCliente
     * @return \Illuminate\Support\Collection
     */
    public static function buscarPorNombreCliente(string $nombreCliente)
    {
        // Normalizar búsqueda
        $nombreLimpio = trim($nombreCliente);
        $rutLimpio = preg_replace('/[^0-9kK]/', '', $nombreLimpio);

        // Buscar empresa por RUT o razón social
        $empresa = DB::connection('ventas')
            ->table('empresas')
            ->where(function($query) use ($rutLimpio, $nombreLimpio) {
                $query->where('rut', 'like', '%' . $rutLimpio . '%')
                      ->orWhere('razon_social', 'like', '%' . $nombreLimpio . '%')
                      ->orWhere('nombe_de_fantasia', 'like', '%' . $nombreLimpio . '%');
            })
            ->where('estado', 'activo')
            ->whereNull('deleted_at')
            ->first();

        if (!$empresa) {
            return collect();
        }

        // Obtener sucursales de esa empresa
        return self::where('empresa_id', $empresa->id)
                   ->activas()
                   ->orderBy('nombre')
                   ->get();
    }
}