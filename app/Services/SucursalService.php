<?php

namespace App\Services;

use App\Models\Sucursal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SucursalService
{
    /**
     * Buscar sucursales por nombre de cliente (de NotaVta_Actualiza)
     * 
     * @param string $nombreCliente Nombre del cliente de la nota de venta
     * @return Collection
     */
    public function buscarSucursalesPorNombreCliente(string $nombreCliente): Collection
    {
        return Sucursal::buscarPorNombreCliente($nombreCliente);
    }

    /**
     * Obtener sucursales por empresa ID
     * 
     * @param int $empresaId
     * @return Collection
     */
    public function obtenerSucursalesPorEmpresa(int $empresaId): Collection
    {
        return Sucursal::where('empresa_id', $empresaId)
                      ->activas()
                      ->orderBy('nombre')
                      ->get();
    }

    /**
     * Obtener información de sucursal por ID
     * 
     * @param int $sucursalId
     * @return Sucursal|null
     */
    public function obtenerSucursalPorId(int $sucursalId): ?Sucursal
    {
        return Sucursal::find($sucursalId);
    }

    /**
     * Buscar empresa por nombre (búsqueda directa en tabla)
     * 
     * @param string $nombreCliente
     * @return object|null
     */
    public function buscarEmpresaPorNombre(string $nombreCliente): ?object
    {
        $nombreLimpio = trim($nombreCliente);
        $rutLimpio = preg_replace('/[^0-9kK]/', '', $nombreLimpio);

        return DB::connection('ventas')
            ->table('empresas')
            ->where(function($query) use ($rutLimpio, $nombreLimpio) {
                $query->where('rut', 'like', '%' . $rutLimpio . '%')
                      ->orWhere('razon_social', 'like', '%' . $nombreLimpio . '%')
                      ->orWhere('nombe_de_fantasia', 'like', '%' . $nombreLimpio . '%');
            })
            ->where('estado', 'activo')
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * Verificar si una empresa tiene sucursales
     * 
     * @param int $empresaId
     * @return bool
     */
    public function empresaTieneSucursales(int $empresaId): bool
    {
        return Sucursal::where('empresa_id', $empresaId)
                      ->activas()
                      ->exists();
    }

    /**
     * Obtener estadísticas de sucursales por empresa
     * 
     * @param int $empresaId
     * @return array
     */
    public function obtenerEstadisticasSucursales(int $empresaId): array
    {
        $sucursales = $this->obtenerSucursalesPorEmpresa($empresaId);
        
        return [
            'total' => $sucursales->count(),
            'con_email' => $sucursales->filter(fn($s) => !empty($s->email))->count(),
            'con_telefono' => $sucursales->filter(fn($s) => !empty($s->telefono))->count(),
        ];
    }

    /**
     * Buscar empresas por criterios (búsqueda directa)
     * 
     * @param array $criterios
     * @return Collection
     */
    public function buscarEmpresas(array $criterios = []): Collection
    {
        $query = DB::connection('ventas')
            ->table('empresas')
            ->where('estado', 'activo')
            ->whereNull('deleted_at');
        
        if (!empty($criterios['rut'])) {
            $rutLimpio = preg_replace('/[^0-9kK]/', '', $criterios['rut']);
            $query->where('rut', 'like', '%' . $rutLimpio . '%');
        }
        
        if (!empty($criterios['razon_social'])) {
            $query->where('razon_social', 'like', '%' . $criterios['razon_social'] . '%');
        }
        
        if (!empty($criterios['rubro'])) {
            $query->where('rubro', 'like', '%' . $criterios['rubro'] . '%');
        }
        
        return collect($query->orderBy('razon_social')->get());
    }

    /**
     * Obtener todas las sucursales activas
     * 
     * @return Collection
     */
    public function obtenerTodasLasSucursales(): Collection
    {
        return Sucursal::activas()
                      ->orderBy('nombre')
                      ->get();
    }

    /**
     * Validar que una sucursal existe y está activa
     * 
     * @param int $sucursalId
     * @return bool
     */
    public function validarSucursalExiste(int $sucursalId): bool
    {
        return Sucursal::where('id', $sucursalId)
                      ->activas()
                      ->exists();
    }

    /**
     * Obtener información de empresa por sucursal
     * 
     * @param int $sucursalId
     * @return object|null
     */
    public function obtenerEmpresaPorSucursal(int $sucursalId): ?object
    {
        $sucursal = Sucursal::find($sucursalId);
        
        if (!$sucursal || !$sucursal->empresa_id) {
            return null;
        }

        return DB::connection('ventas')
            ->table('empresas')
            ->where('id', $sucursal->empresa_id)
            ->whereNull('deleted_at')
            ->first();
    }
}