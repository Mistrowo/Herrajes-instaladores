<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsignarRequest;
use App\Services\AsignarService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AsignarController extends Controller
{
    protected AsignarService $asignarService;

    public function __construct(AsignarService $asignarService)
    {
        $this->asignarService = $asignarService;
        $this->middleware(['auth']);
    }

    /**
     * Vista principal con tabs
     */
    public function index(Request $request): View
    {
        // Filtros para Notas de Venta
        $filtrosNV = [
            'buscar' => $request->input('buscar'),
            'estado_nv' => $request->input('estado_nv'),
        ];

        // Filtros para Asignaciones
        $filtros = [
            'nota_venta' => $request->input('nota_venta'),
            'estado' => $request->input('estado'),
        ];

        // Obtener datos
        $notasVenta = $this->asignarService->obtenerNotasVentaPaginadas($filtrosNV);
        $asignaciones = $this->asignarService->obtenerTodasLasAsignaciones();
        $asignacionesPaginadas = $this->asignarService->obtenerAsignacionesPaginadas($filtros);
        $instaladores = $this->asignarService->obtenerInstaladoresActivos();

        return view('asignar.index', compact(
            'notasVenta', 
            'asignaciones', 
            'asignacionesPaginadas', 
            'instaladores', 
            'filtrosNV', 
            'filtros'
        ));
    }

    /**
     * Formulario de creación
     */
    public function create(Request $request): View
    {
        $filtrosNV = [
            'folio' => $request->input('folio'),
            'cliente' => $request->input('cliente'),
            'estado_nv' => $request->input('estado_nv'),
        ];

        $notasVenta = $this->asignarService->obtenerNotasVentaPaginadas($filtrosNV);
        $instaladores = $this->asignarService->obtenerInstaladoresActivos();
        
        return view('asignar.create', compact('notasVenta', 'instaladores', 'filtrosNV'));
    }

    /**
     * Guardar asignación
     */
    public function store(AsignarRequest $request): RedirectResponse
    {
        try {
            $asignacion = $this->asignarService->crearAsignacion($request->validated());
            
            return redirect()->route('asignar.index')
                ->with('success', '¡Asignación creada exitosamente!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de asignación
     */
    public function show(int $id): View
    {
        $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
        $notaVenta = $this->asignarService->obtenerNotaVentaPorFolio($asignacion->nota_venta);
        
        return view('asignar.show', compact('asignacion', 'notaVenta'));
    }

    /**
     * Formulario de edición
     */
    public function edit(int $id): View
    {
        $asignacion = $this->asignarService->obtenerAsignacionPorId($id);
        $instaladores = $this->asignarService->obtenerInstaladoresActivos();
        
        return view('asignar.edit', compact('asignacion', 'instaladores'));
    }

    /**
     * Actualizar asignación
     */
    public function update(AsignarRequest $request, int $id): RedirectResponse
    {
        try {
            $this->asignarService->actualizarAsignacion($id, $request->validated());
            
            return redirect()->route('asignar.index')
                ->with('success', '¡Asignación actualizada exitosamente!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar asignación
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->asignarService->eliminarAsignacion($id);
            
            return redirect()->route('asignar.index')
                ->with('success', '¡Asignación eliminada exitosamente!');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de asignación
     */
    public function cambiarEstado(int $id, string $estado): RedirectResponse
    {
        try {
            $this->asignarService->cambiarEstadoAsignacion($id, $estado);
            
            return redirect()->route('asignar.index')
                ->with('success', '¡Estado actualizado exitosamente!');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Buscar notas de venta (AJAX)
     */
    public function buscarNotasVenta(Request $request)
    {
        try {
            $filtros = [
                'folio' => $request->input('folio'),
                'cliente' => $request->input('cliente'),
            ];

            $notasVenta = $this->asignarService->obtenerNotasVentaSinAsignacion(20);

            return response()->json([
                'success' => true,
                'data' => $notasVenta
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar notas de venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar asignación (AJAX)
     */
    public function verificarAsignacion(Request $request)
    {
        try {
            $notaVenta = $request->input('nota_venta');
            $tieneAsignacion = $this->asignarService->tieneAsignacionActiva($notaVenta);

            return response()->json([
                'success' => true,
                'tiene_asignacion' => $tieneAsignacion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar asignación: ' . $e->getMessage()
            ], 500);
        }
    }
}