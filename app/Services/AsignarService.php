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
     * 
     * @param array $filtros
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerNotasVentaPaginadas(array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = NotaVtaActualiza::query();

        if (!empty($filtros['folio'])) {
            $query->folio($filtros['folio']);
        }

        if (!empty($filtros['cliente'])) {
            $query->cliente($filtros['cliente']);
        }

        if (!empty($filtros['estado'])) {
            $query->estado($filtros['estado']);
        }

        if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
            $query->whereBetween('nv_femision', [
                $filtros['fecha_desde'],
                $filtros['fecha_hasta']
            ]);
        }

        return $query->orderBy('nv_femision', 'desc')
            ->orderBy('nv_folio', 'desc')
            ->paginate($perPage);
    }

    /**
     *
     * @return Collection
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

    /**
     *
     * @param array $filtros
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerAsignacionesPaginadas(array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Asigna::with([
            'instalador1:id,nombre,usuario',
            'instalador2:id,nombre,usuario',
            'instalador3:id,nombre,usuario',
            'instalador4:id,nombre,usuario'
        ]);

        if (!empty($filtros['nota_venta'])) {
            $query->where('nota_venta', 'like', '%' . $filtros['nota_venta'] . '%');
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
            $query->fechaAsignaEntre($filtros['fecha_desde'], $filtros['fecha_hasta']);
        }

        return $query->orderBy('fecha_asigna', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     *
     * @return Collection
     */
    public function obtenerInstaladoresActivos(): Collection
    {
        return Instalador::activo()
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'usuario', 'rol']);
    }

    /**
     *
     * @param int $id
     * @return Asigna
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
     *
     * @param string $folio
     * @return NotaVtaActualiza|null
     */
    public function obtenerNotaVentaPorFolio(string $folio): ?NotaVtaActualiza
    {
        return NotaVtaActualiza::folio($folio)->first();
    }

    /**
     *
     * @param string $notaVenta
     * @return bool
     */
    public function tieneAsignacionActiva(string $notaVenta): bool
    {
        return Asigna::notaVenta($notaVenta)
            ->whereNotIn('estado', ['completada', 'rechazada'])
            ->exists();
    }

    /**
     *
     * @param array $datos
     * @return Asigna
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
     *
     * @param int $id
     * @param array $datos
     * @return Asigna
     */
    public function actualizarAsignacion(int $id, array $datos): Asigna
    {
        return DB::transaction(function () use ($id, $datos) {
            $asignacion = Asigna::findOrFail($id);

            if (isset($datos['nota_venta']) && $datos['nota_venta'] !== $asignacion->nota_venta) {
                $existeAsignacion = Asigna::notaVenta($datos['nota_venta'])
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
     *
     * @param int $id
     * @return bool
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
     *
     * @param int $id
     * @param string $nuevoEstado
     * @return Asigna
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
     *
     * @param int $instaladorId
     * @param array $filtros
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerAsignacionesPorInstaladorPaginadas(int $instaladorId, array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Asigna::porInstalador($instaladorId)
            ->with(['instalador1', 'instalador2', 'instalador3', 'instalador4']);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
            $query->fechaAsignaEntre($filtros['fecha_desde'], $filtros['fecha_hasta']);
        }

        return $query->orderBy('fecha_asigna', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     *
     * @param int $instaladorId
     * @return array
     */
    public function obtenerEstadisticasPorInstalador(int $instaladorId): array
    {
        $query = Asigna::porInstalador($instaladorId);

        return [
            'total' => $query->count(),
            'pendientes' => (clone $query)->pendientes()->count(),
            'aceptadas' => (clone $query)->aceptadas()->count(),
            'en_proceso' => (clone $query)->enProceso()->count(),
            'completadas' => (clone $query)->completadas()->count(),
        ];
    }

    /**
     *
     * @param int $instaladorId
     * @return Collection
     */
    public function obtenerAsignacionesPorInstalador(int $instaladorId): Collection
    {
        return Asigna::porInstalador($instaladorId)
            ->with(['instalador1', 'instalador2', 'instalador3', 'instalador4'])
            ->orderBy('fecha_asigna', 'desc')
            ->get();
    }

    /**
     *
     * @return array
     */
    public function obtenerEstadisticas(): array
    {
        return [
            'total' => Asigna::count(),
            'pendientes' => Asigna::pendientes()->count(),
            'aceptadas' => Asigna::aceptadas()->count(),
            'en_proceso' => Asigna::enProceso()->count(),
            'completadas' => Asigna::completadas()->count(),
        ];
    }

    /**
     *
     * @param int $limit
     * @return Collection
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
}