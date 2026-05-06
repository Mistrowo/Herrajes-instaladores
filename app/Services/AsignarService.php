<?php

namespace App\Services;

use App\Mail\AsignacionInstaladorMail;
use App\Models\Asigna;
use App\Models\NotaVtaActualiza;
use App\Models\Instalador;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AsignarService
{
    /**
     * Obtener todas las asignaciones
     *
     * @return Collection
     */
    public function obtenerTodasAsignaciones(): Collection
    {
        return Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal', 'notaVenta'])
            ->orderBy('fecha_asigna', 'desc')
            ->get();
    }

    /**
     * Obtener asignación por ID
     *
     * @param int $id
     * @return Asigna
     */
    public function obtenerAsignacionPorId(int $id): Asigna
    {
        return Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal', 'notaVenta'])
            ->findOrFail($id);
    }

    /**
     * Obtener asignaciones por instalador (paginadas)
     *
     * @param int $instaladorId
     * @param array $filtros
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerAsignacionesPorInstaladorPaginadas(
        int $instaladorId, 
        array $filtros = [], 
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal', 'notaVenta'])
            ->porInstalador($instaladorId);

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $query->where('fecha_asigna', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->where('fecha_asigna', '<=', $filtros['fecha_hasta']);
        }

        return $query->orderBy('fecha_asigna', 'desc')->paginate($perPage);
    }

    /**
     * Obtener estadísticas de asignaciones por instalador
     *
     * @param int $instaladorId
     * @return array
     */
    public function obtenerEstadisticasPorInstalador(int $instaladorId): array
    {
        $asignaciones = Asigna::porInstalador($instaladorId)->get();

        return [
            'total' => $asignaciones->count(),
            'pendientes' => $asignaciones->where('estado', 'pendiente')->count(),
            'aceptadas' => $asignaciones->where('estado', 'aceptada')->count(),
            'en_proceso' => $asignaciones->where('estado', 'en_proceso')->count(),
            'completadas' => $asignaciones->where('estado', 'completada')->count(),
            'rechazadas' => $asignaciones->where('estado', 'rechazada')->count(),
        ];
    }

    /**
     * Crear una nueva asignación
     *
     * @param array $datos
     * @return Asigna
     */
    public function crearAsignacion(array $datos): Asigna
    {
        $asignacion = Asigna::create([
            'nota_venta'         => $datos['nota_venta'],
            'sucursal_id'        => $datos['sucursal_id'] ?? null,
            'lugar_despacho_cod' => $datos['lugar_despacho_cod'] ?? null,
            'lugar_despacho_nom' => $datos['lugar_despacho_nom'] ?? null,
            'fecha_asigna'       => $datos['fecha_asigna'],
            'asignado1'          => $datos['asignado1'] ?? null,
            'asignado2'          => $datos['asignado2'] ?? null,
            'asignado3'          => $datos['asignado3'] ?? null,
            'asignado4'          => $datos['asignado4'] ?? null,
            'solicita'           => $datos['solicita'] ?? auth()->user()?->nombre ?? auth()->user()?->name ?? 'Admin',
            'estado'             => 'pendiente',
            'observaciones'      => $datos['observaciones'] ?? null,
        ]);

        $this->notificarInstaladores($asignacion, [
            $datos['asignado1'] ?? null,
            $datos['asignado2'] ?? null,
            $datos['asignado3'] ?? null,
            $datos['asignado4'] ?? null,
        ]);

        return $asignacion;
    }

    /**
     * Actualizar una asignación
     *
     * @param int $id
     * @param array $datos
     * @return Asigna
     */
    public function actualizarAsignacion(int $id, array $datos): Asigna
    {
        $asignacion = Asigna::findOrFail($id);

        $instaladoresAnteriores = [
            $asignacion->asignado1,
            $asignacion->asignado2,
            $asignacion->asignado3,
            $asignacion->asignado4,
        ];

        $asignacion->update([
            'nota_venta'         => $datos['nota_venta'] ?? $asignacion->nota_venta,
            'sucursal_id'        => array_key_exists('sucursal_id', $datos) ? $datos['sucursal_id'] : $asignacion->sucursal_id,
            'lugar_despacho_cod' => array_key_exists('lugar_despacho_cod', $datos) ? $datos['lugar_despacho_cod'] : $asignacion->lugar_despacho_cod,
            'lugar_despacho_nom' => array_key_exists('lugar_despacho_nom', $datos) ? $datos['lugar_despacho_nom'] : $asignacion->lugar_despacho_nom,
            'fecha_asigna'       => $datos['fecha_asigna'] ?? $asignacion->fecha_asigna,
            'asignado1'          => $datos['asignado1'] ?? $asignacion->asignado1,
            'asignado2'          => $datos['asignado2'] ?? $asignacion->asignado2,
            'asignado3'          => $datos['asignado3'] ?? $asignacion->asignado3,
            'asignado4'          => $datos['asignado4'] ?? $asignacion->asignado4,
            'observaciones'      => $datos['observaciones'] ?? $asignacion->observaciones,
        ]);

        $nuevosIds = array_filter([
            $datos['asignado1'] ?? null,
            $datos['asignado2'] ?? null,
            $datos['asignado3'] ?? null,
            $datos['asignado4'] ?? null,
        ]);

        $recienAgregados = array_diff($nuevosIds, array_filter($instaladoresAnteriores));

        if (!empty($recienAgregados)) {
            $this->notificarInstaladores($asignacion->fresh(), $recienAgregados);
        }

        return $asignacion->fresh();
    }

    /**
     * Cambiar estado de una asignación
     *
     * @param int $id
     * @param string $nuevoEstado
     * @return Asigna
     */
    public function cambiarEstadoAsignacion(int $id, string $nuevoEstado): Asigna
    {
        $asignacion = Asigna::findOrFail($id);
        
        $datosActualizacion = ['estado' => $nuevoEstado];

        // Si se acepta, registrar fecha de aceptación
        if ($nuevoEstado === 'aceptada' && !$asignacion->fecha_acepta) {
            $datosActualizacion['fecha_acepta'] = now();
        }

        // Si se completa, registrar fecha de término
        if ($nuevoEstado === 'completada') {
            $datosActualizacion['terminado'] = true;
            $datosActualizacion['fecha_termino'] = now();
        }

        $asignacion->update($datosActualizacion);

        return $asignacion->fresh();
    }

    /**
     * Eliminar una asignación (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function eliminarAsignacion(int $id): bool
    {
        $asignacion = Asigna::findOrFail($id);
        return $asignacion->delete();
    }

    /**
     * Verificar si una nota de venta ya tiene asignación
     *
     * @param string $notaVenta
     * @return bool
     */
    public function notaVentaTieneAsignacion(string $notaVenta): bool
    {
        return Asigna::where('nota_venta', $notaVenta)->exists();
    }

    /**
     * Obtener asignación por nota de venta
     *
     * @param string $notaVenta
     * @return Asigna|null
     */
    public function obtenerAsignacionPorNotaVenta(string $notaVenta): ?Asigna
    {
        return Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal'])
            ->where('nota_venta', $notaVenta)
            ->first();
    }

    /**
     * Obtener notas de venta disponibles (sin asignación)
     *
     * @param array $filtros
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerNotasVentaDisponibles(array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = NotaVtaActualiza::query();

        // Aplicar filtros
        if (!empty($filtros['buscar'])) {
            $query->where(function($q) use ($filtros) {
                $q->where('nv_folio', 'like', '%' . $filtros['buscar'] . '%')
                  ->orWhere('nv_cliente', 'like', '%' . $filtros['buscar'] . '%');
            });
        }

        if (!empty($filtros['estado_nv'])) {
            $query->where('nv_estado', $filtros['estado_nv']);
        }

        return $query->orderBy('nv_folio', 'desc')->paginate($perPage);
    }

    /**
     * Obtener nota de venta por folio
     *
     * @param string $folio
     * @return NotaVtaActualiza|null
     */
    public function obtenerNotaVentaPorFolio(string $folio): ?NotaVtaActualiza
    {
        return NotaVtaActualiza::where('nv_folio', $folio)->first();
    }

    /**
     * Obtener instaladores disponibles
     *
     * @return Collection
     */
    public function obtenerInstaladoresDisponibles(): Collection
    {
        return Instalador::where('activo', 1)
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener resumen de asignaciones por estado
     *
     * @return array
     */
    public function obtenerResumenEstados(): array
    {
        return [
            'total' => Asigna::count(),
            'pendientes' => Asigna::where('estado', 'pendiente')->count(),
            'aceptadas' => Asigna::where('estado', 'aceptada')->count(),
            'en_proceso' => Asigna::where('estado', 'en_proceso')->count(),
            'completadas' => Asigna::where('estado', 'completada')->count(),
            'rechazadas' => Asigna::where('estado', 'rechazada')->count(),
        ];
    }

    /**
     * Obtener asignaciones con filtros avanzados
     *
     * @param array $filtros
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function obtenerAsignacionesConFiltros(array $filtros = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal', 'notaVenta']);

        // Filtro por nota de venta
        if (!empty($filtros['nota_venta'])) {
            $query->where('nota_venta', 'like', '%' . $filtros['nota_venta'] . '%');
        }

        // Filtro por estado
        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        // Filtro por instalador
        if (!empty($filtros['instalador_id'])) {
            $query->porInstalador($filtros['instalador_id']);
        }

        // Filtro por sucursal
        if (!empty($filtros['sucursal_id'])) {
            $query->where('sucursal_id', $filtros['sucursal_id']);
        }

        // Filtro por rango de fechas
        if (!empty($filtros['fecha_desde'])) {
            $query->where('fecha_asigna', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->where('fecha_asigna', '<=', $filtros['fecha_hasta']);
        }

        return $query->orderBy('fecha_asigna', 'desc')->paginate($perPage);
    }

    /**
     * Verificar si un instalador puede modificar una asignación
     *
     * @param int $asignacionId
     * @param int $instaladorId
     * @return bool
     */
    public function instaladorPuedeModificar(int $asignacionId, int $instaladorId): bool
    {
        $asignacion = Asigna::findOrFail($asignacionId);
        
        return $asignacion->asignado1 === $instaladorId ||
               $asignacion->asignado2 === $instaladorId ||
               $asignacion->asignado3 === $instaladorId ||
               $asignacion->asignado4 === $instaladorId;
    }

    /**
     * Obtener asignaciones activas de un instalador
     *
     * @param int $instaladorId
     * @return Collection
     */
    public function obtenerAsignacionesActivasInstalador(int $instaladorId): Collection
    {
        return Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal', 'notaVenta'])
            ->porInstalador($instaladorId)
            ->activas()
            ->orderBy('fecha_asigna', 'desc')
            ->get();
    }

    /**
     * Enviar correo de notificación a los instaladores indicados
     *
     * @param Asigna $asignacion
     * @param array $instaladorIds
     */
    private function notificarInstaladores(Asigna $asignacion, array $instaladorIds): void
    {
        $ids = array_filter($instaladorIds);

        if (empty($ids)) {
            return;
        }

        $notaVenta = NotaVtaActualiza::where('nv_folio', $asignacion->nota_venta)->first();

        $instaladores = Instalador::whereIn('id', $ids)->get();

        foreach ($instaladores as $instalador) {
            if (empty($instalador->correo)) {
                continue;
            }

            try {
                Mail::to($instalador->correo)
                    ->send(new AsignacionInstaladorMail($asignacion, $instalador, $notaVenta));
            } catch (\Exception $e) {
                Log::error("Error al enviar email de asignación al instalador {$instalador->id}: " . $e->getMessage());
            }
        }
    }
}