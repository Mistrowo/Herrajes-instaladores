<?php

namespace App\Services;

use App\Models\Asigna;
use App\Models\Instalador;
use App\Models\NotaVtaActualiza;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AsignarService
{
    /**
     * Obtener notas de venta paginadas con filtros
     */
    public function obtenerNotasVentaPaginadas(array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = NotaVtaActualiza::query();

        // Filtro de búsqueda general (folio o cliente)
        if (!empty($filtros['buscar'])) {
            $buscar = $filtros['buscar'];
            $query->where(function($q) use ($buscar) {
                $q->where('nv_folio', 'like', '%' . $buscar . '%')
                  ->orWhere('nv_cliente', 'like', '%' . $buscar . '%');
            });
        }

        // Filtro específico de folio
        if (!empty($filtros['folio'])) {
            $query->where('nv_folio', 'like', '%' . $filtros['folio'] . '%');
        }

        // Filtro específico de cliente
        if (!empty($filtros['cliente'])) {
            $query->where('nv_cliente', 'like', '%' . $filtros['cliente'] . '%');
        }

        // Filtro de estado
        if (!empty($filtros['estado_nv'])) {
            $query->where('nv_estado', $filtros['estado_nv']);
        }

        // Filtro de rango de fechas
        if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
            $query->whereBetween('nv_femision', [
                $filtros['fecha_desde'],
                $filtros['fecha_hasta']
            ]);
        }

        return $query->orderBy('nv_femision', 'desc')
            ->orderBy('nv_folio', 'desc')
            ->paginate($perPage)
            ->appends($filtros); // IMPORTANTE: Mantener filtros en paginación
    }

    /**
     * Obtener asignaciones paginadas con filtros
     */
    public function obtenerAsignacionesPaginadas(array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Asigna::with([
            'instalador1:id,nombre,usuario',
            'instalador2:id,nombre,usuario',
            'instalador3:id,nombre,usuario',
            'instalador4:id,nombre,usuario'
        ]);

        // Filtro de nota de venta
        if (!empty($filtros['nota_venta'])) {
            $query->where('nota_venta', 'like', '%' . $filtros['nota_venta'] . '%');
        }

        // Filtro de estado
        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        // Filtro de rango de fechas
        if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
            $query->whereBetween('fecha_asigna', [
                $filtros['fecha_desde'],
                $filtros['fecha_hasta']
            ]);
        }

        return $query->orderBy('fecha_asigna', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($filtros); // IMPORTANTE: Mantener filtros en paginación
    }

    /**
     * Obtener instaladores activos
     */
    public function obtenerInstaladoresActivos(): Collection
    {
        return Instalador::activo()
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'usuario', 'rol']);
    }

    /**
     * Obtener asignación por ID
     */
    public function obtenerAsignacionPorId(int $id): Asigna
    {
        return Asigna::with([
            'instalador1',
            'instalador2',
            'instalador3',
            'instalador4'
        ])->findOrFail($id);
    }

    /**
     * Obtener nota de venta por folio
     */
    public function obtenerNotaVentaPorFolio(string $folio): ?NotaVtaActualiza
    {
        return NotaVtaActualiza::where('nv_folio', $folio)->first();
    }

    /**
     * Verificar si tiene asignación activa
     */
    public function tieneAsignacionActiva(string $notaVenta): bool
    {
        return Asigna::where('nota_venta', $notaVenta)
            ->whereNotIn('estado', ['completada', 'rechazada'])
            ->exists();
    }

    /**
     * Crear asignación
     */
    public function crearAsignacion(array $datos): Asigna
    {
        return DB::transaction(function () use ($datos) {
            if ($this->tieneAsignacionActiva($datos['nota_venta'])) {
                throw new \Exception('Ya existe una asignación activa para esta nota de venta.');
            }

            $tieneInstalador = !empty($datos['asignado1']) || 
                             !empty($datos['asignado2']) || 
                             !empty($datos['asignado3']) || 
                             !empty($datos['asignado4']);

            if (!$tieneInstalador) {
                throw new \Exception('Debe asignar al menos un instalador.');
            }

            $asignacion = Asigna::create([
                'nota_venta' => $datos['nota_venta'],
                'solicita' => $datos['solicita'] ?? auth()->user()->nombre,
                'asignado1' => $datos['asignado1'] ?? null,
                'asignado2' => $datos['asignado2'] ?? null,
                'asignado3' => $datos['asignado3'] ?? null,
                'asignado4' => $datos['asignado4'] ?? null,
                'fecha_asigna' => $datos['fecha_asigna'] ?? now(),
                'estado' => 'pendiente',
                'observaciones' => $datos['observaciones'] ?? null,
                'terminado' => false,
            ]);

            return $asignacion->load(['instalador1', 'instalador2', 'instalador3', 'instalador4']);
        });
    }

    /**
     * Actualizar asignación
     */
    public function actualizarAsignacion(int $id, array $datos): Asigna
    {
        return DB::transaction(function () use ($id, $datos) {
            $asignacion = Asigna::findOrFail($id);

            if (isset($datos['nota_venta']) && $datos['nota_venta'] !== $asignacion->nota_venta) {
                $existeAsignacion = Asigna::where('nota_venta', $datos['nota_venta'])
                    ->where('id', '!=', $id)
                    ->whereNotIn('estado', ['completada', 'rechazada'])
                    ->exists();

                if ($existeAsignacion) {
                    throw new \Exception('Ya existe una asignación activa para esta nota de venta.');
                }
            }

            $asignado1 = $datos['asignado1'] ?? $asignacion->asignado1;
            $asignado2 = $datos['asignado2'] ?? $asignacion->asignado2;
            $asignado3 = $datos['asignado3'] ?? $asignacion->asignado3;
            $asignado4 = $datos['asignado4'] ?? $asignacion->asignado4;

            $tieneInstalador = !empty($asignado1) || !empty($asignado2) || 
                             !empty($asignado3) || !empty($asignado4);

            if (!$tieneInstalador) {
                throw new \Exception('Debe asignar al menos un instalador.');
            }

            $asignacion->update([
                'nota_venta' => $datos['nota_venta'] ?? $asignacion->nota_venta,
                'asignado1' => $asignado1,
                'asignado2' => $asignado2,
                'asignado3' => $asignado3,
                'asignado4' => $asignado4,
                'fecha_asigna' => $datos['fecha_asigna'] ?? $asignacion->fecha_asigna,
                'observaciones' => $datos['observaciones'] ?? $asignacion->observaciones,
            ]);

            return $asignacion->fresh(['instalador1', 'instalador2', 'instalador3', 'instalador4']);
        });
    }

    /**
     * Eliminar asignación
     */
    public function eliminarAsignacion(int $id): bool
    {
        $asignacion = Asigna::findOrFail($id);
        
        if (in_array($asignacion->estado, ['en_proceso', 'completada'])) {
            throw new \Exception('No se puede eliminar una asignación en proceso o completada.');
        }
        
        return $asignacion->delete();
    }

    /**
     * Cambiar estado de asignación
     */
    public function cambiarEstadoAsignacion(int $id, string $nuevoEstado): Asigna
    {
        $asignacion = Asigna::findOrFail($id);
        
        $estadosPermitidos = ['pendiente', 'aceptada', 'rechazada', 'en_proceso', 'completada'];
        
        if (!in_array($nuevoEstado, $estadosPermitidos)) {
            throw new \Exception('Estado no válido.');
        }

        $asignacion->estado = $nuevoEstado;
        
        if ($nuevoEstado === 'aceptada' && !$asignacion->fecha_acepta) {
            $asignacion->fecha_acepta = now();
        }
        
        if ($nuevoEstado === 'completada') {
            $asignacion->terminado = true;
            $asignacion->fecha_termino = now();
        }
        
        $asignacion->save();
        
        return $asignacion;
    }

    /**
     * Obtener asignaciones por instalador paginadas
     */
    public function obtenerAsignacionesPorInstaladorPaginadas(int $instaladorId, array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Asigna::where(function($q) use ($instaladorId) {
            $q->where('asignado1', $instaladorId)
              ->orWhere('asignado2', $instaladorId)
              ->orWhere('asignado3', $instaladorId)
              ->orWhere('asignado4', $instaladorId);
        })->with(['instalador1', 'instalador2', 'instalador3', 'instalador4']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
            $query->whereBetween('fecha_asigna', [$filtros['fecha_desde'], $filtros['fecha_hasta']]);
        }

        return $query->orderBy('fecha_asigna', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($filtros);
    }

    /**
     * Obtener estadísticas por instalador
     */
    public function obtenerEstadisticasPorInstalador(int $instaladorId): array
    {
        $query = Asigna::where(function($q) use ($instaladorId) {
            $q->where('asignado1', $instaladorId)
              ->orWhere('asignado2', $instaladorId)
              ->orWhere('asignado3', $instaladorId)
              ->orWhere('asignado4', $instaladorId);
        });

        return [
            'total' => (clone $query)->count(),
            'pendientes' => (clone $query)->where('estado', 'pendiente')->count(),
            'aceptadas' => (clone $query)->where('estado', 'aceptada')->count(),
            'en_proceso' => (clone $query)->where('estado', 'en_proceso')->count(),
            'completadas' => (clone $query)->where('estado', 'completada')->count(),
        ];
    }

    /**
     * Obtener asignaciones por instalador
     */
    public function obtenerAsignacionesPorInstalador(int $instaladorId): Collection
    {
        return Asigna::where(function($q) use ($instaladorId) {
            $q->where('asignado1', $instaladorId)
              ->orWhere('asignado2', $instaladorId)
              ->orWhere('asignado3', $instaladorId)
              ->orWhere('asignado4', $instaladorId);
        })
        ->with(['instalador1', 'instalador2', 'instalador3', 'instalador4'])
        ->orderBy('fecha_asigna', 'desc')
        ->get();
    }

    /**
     * Obtener estadísticas generales
     */
    public function obtenerEstadisticas(): array
    {
        return [
            'total' => Asigna::count(),
            'pendientes' => Asigna::where('estado', 'pendiente')->count(),
            'aceptadas' => Asigna::where('estado', 'aceptada')->count(),
            'en_proceso' => Asigna::where('estado', 'en_proceso')->count(),
            'completadas' => Asigna::where('estado', 'completada')->count(),
        ];
    }

    /**
     * Obtener notas de venta sin asignación
     */
    public function obtenerNotasVentaSinAsignacion(int $limit = 50): Collection
    {
        $foliosAsignados = Asigna::whereNotIn('estado', ['completada', 'rechazada'])
            ->pluck('nota_venta')
            ->toArray();

        return NotaVtaActualiza::whereNotIn('nv_folio', $foliosAsignados)
            ->orderBy('nv_femision', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener todas las asignaciones (sin paginar)
     */
    public function obtenerTodasLasAsignaciones(): Collection
    {
        return Asigna::with([
            'instalador1:id,nombre',
            'instalador2:id,nombre',
            'instalador3:id,nombre',
            'instalador4:id,nombre'
        ])->get();
    }
}