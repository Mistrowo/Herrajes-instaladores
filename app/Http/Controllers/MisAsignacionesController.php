<?php

namespace App\Http\Controllers;

use App\Services\AsignarService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MisAsignacionesController extends Controller
{
    protected AsignarService $asignarService;

    /**
     * Constructor del controlador
     */
    public function __construct(AsignarService $asignarService)
    {
        $this->asignarService = $asignarService;
        $this->middleware(['auth']);
    }

    /**
     * Mostrar mis asignaciones (para instaladores)
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $instaladorId = auth()->id();
        
        // Filtros
        $filtros = [
            'estado' => $request->input('estado'),
            'fecha_desde' => $request->input('fecha_desde'),
            'fecha_hasta' => $request->input('fecha_hasta'),
        ];

        // Obtener mis asignaciones paginadas
        $asignaciones = $this->asignarService->obtenerAsignacionesPorInstaladorPaginadas($instaladorId, $filtros);
        
        // Estadísticas del instalador
        $estadisticas = $this->asignarService->obtenerEstadisticasPorInstalador($instaladorId);

        return view('mis-asignaciones.index', compact('asignaciones', 'estadisticas', 'filtros'));
    }

    /**
     * Aceptar una asignación
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function aceptar(int $id): RedirectResponse
    {
        try {
            $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
            
            // Verificar que el instalador pertenece a esta asignación
            if (!$this->perteneceAsignacion($asignacion)) {
                return back()->with('error', 'No tienes permiso para modificar esta asignación.');
            }

            $this->asignarService->cambiarEstadoAsignacion($id, 'aceptada');
            
            return redirect()->route('mis-asignaciones.index')
                ->with('success', '¡Asignación aceptada exitosamente!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al aceptar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar una asignación
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function rechazar(int $id): RedirectResponse
    {
        try {
            $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
            
            // Verificar que el instalador pertenece a esta asignación
            if (!$this->perteneceAsignacion($asignacion)) {
                return back()->with('error', 'No tienes permiso para modificar esta asignación.');
            }

            $this->asignarService->cambiarEstadoAsignacion($id, 'rechazada');
            
            return redirect()->route('mis-asignaciones.index')
                ->with('success', 'Asignación rechazada.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al rechazar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Marcar asignación como en proceso
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function enProceso(int $id): RedirectResponse
    {
        try {
            $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
            
            // Verificar que el instalador pertenece a esta asignación
            if (!$this->perteneceAsignacion($asignacion)) {
                return back()->with('error', 'No tienes permiso para modificar esta asignación.');
            }

            $this->asignarService->cambiarEstadoAsignacion($id, 'en_proceso');
            
            return redirect()->route('mis-asignaciones.index')
                ->with('success', '¡Asignación marcada como en proceso!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Completar una asignación
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function completar(int $id): RedirectResponse
    {
        try {
            $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
            
            // Verificar que el instalador pertenece a esta asignación
            if (!$this->perteneceAsignacion($asignacion)) {
                return back()->with('error', 'No tienes permiso para modificar esta asignación.');
            }

            $this->asignarService->cambiarEstadoAsignacion($id, 'completada');
            
            return redirect()->route('mis-asignaciones.index')
                ->with('success', '¡Asignación completada exitosamente!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al completar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalles de una asignación
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
        
        // Verificar que el instalador pertenece a esta asignación
        if (!$this->perteneceAsignacion($asignacion)) {
            abort(403, 'No tienes permiso para ver esta asignación.');
        }
        
        // Obtener información de la nota de venta desde SQL Server
        $notaVenta = $this->asignarService->obtenerNotaVentaPorFolio($asignacion->nota_venta);
        
        return view('mis-asignaciones.show', compact('asignacion', 'notaVenta'));
    }

    /**
     * Verificar si el instalador autenticado pertenece a la asignación
     *
     * @param mixed $asignacion
     * @return bool
     */
    private function perteneceAsignacion($asignacion): bool
    {
        $instaladorId = auth()->id();
        
        return $asignacion->asignado1 === $instaladorId ||
               $asignacion->asignado2 === $instaladorId ||
               $asignacion->asignado3 === $instaladorId ||
               $asignacion->asignado4 === $instaladorId;
    }
}