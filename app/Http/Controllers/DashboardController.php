<?php

namespace App\Http\Controllers;

use App\Services\AsignarService;
use App\Models\NotaVtaActualiza;
use App\Models\Asigna;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected AsignarService $asignarService;

    public function __construct(AsignarService $asignarService)
    {
        $this->asignarService = $asignarService;
        $this->middleware(['auth', 'active.instalador']);
    }

    /**
     * Mostrar dashboard principal
     */
    public function index(): View
    {
        /** @var \App\Models\Instalador $user */
        $user = auth()->user();
        
        // Si es admin, obtener todas las notas de venta
        // Si es instalador, solo las asignadas a él
        if ($user->esAdmin()) {
            $notasVenta = NotaVtaActualiza::orderBy('nv_femision', 'desc')
                ->paginate(10);
        } else {
            // Obtener solo mis asignaciones aceptadas o en proceso
            $asignaciones = Asigna::porInstalador($user->id)
                ->whereIn('estado', ['aceptada', 'en_proceso'])
                ->get();
            
            // Obtener los folios
            $folios = $asignaciones->pluck('nota_venta')->unique()->toArray();
            
            // Obtener las notas de venta correspondientes
            $notasVenta = NotaVtaActualiza::whereIn('nv_folio', $folios)
                ->orderBy('nv_femision', 'desc')
                ->paginate(10);
        }
        
        return view('dashboard.index', compact('notasVenta'));
    }

    /**
     * Buscar notas de venta (AJAX para el modal)
     */
    public function buscarNotasVenta(Request $request): JsonResponse
    {
        /** @var \App\Models\Instalador $user */
        $user = auth()->user();
        $buscar = $request->input('buscar', '');
        
        // Si es admin, buscar en todas
        if ($user->esAdmin()) {
            $query = NotaVtaActualiza::query();
        } else {
            // Si es instalador, solo en las asignadas
            $asignaciones = Asigna::porInstalador($user->id)
                ->whereIn('estado', ['aceptada', 'en_proceso'])
                ->get();
            
            $folios = $asignaciones->pluck('nota_venta')->unique()->toArray();
            
            $query = NotaVtaActualiza::whereIn('nv_folio', $folios);
        }
        
        // Aplicar búsqueda
        if (!empty($buscar)) {
            $query->buscar($buscar);
        }
        
        $notasVenta = $query->orderBy('nv_femision', 'desc')
            ->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $notasVenta
        ]);
    }

    /**
     * Obtener detalles de una nota de venta (AJAX)
     */
    public function obtenerDetallesNV(Request $request): JsonResponse
    {
        $folio = $request->input('folio');
        
        /** @var \App\Models\Instalador $user */
        $user = auth()->user();
        
        // Verificar permisos
        if (!$user->esAdmin()) {
            $tieneAsignacion = Asigna::porInstalador($user->id)
                ->where('nota_venta', $folio)
                ->whereIn('estado', ['aceptada', 'en_proceso'])
                ->exists();
            
            if (!$tieneAsignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para ver esta nota de venta'
                ], 403);
            }
        }
        
        // Obtener nota de venta
        $notaVenta = NotaVtaActualiza::where('nv_folio', $folio)->first();
        
        if (!$notaVenta) {
            return response()->json([
                'success' => false,
                'message' => 'Nota de venta no encontrada'
            ], 404);
        }
        
        // Obtener asignación si existe
        $asignacion = Asigna::where('nota_venta', $folio)
            ->with(['instalador1', 'instalador2', 'instalador3', 'instalador4'])
            ->first();
        
        $dataAsignacion = null;
        if ($asignacion) {
            $instaladores = [];
            
            if ($asignacion->instalador1) {
                $instaladores[] = $asignacion->instalador1->nombre;
            }
            if ($asignacion->instalador2) {
                $instaladores[] = $asignacion->instalador2->nombre;
            }
            if ($asignacion->instalador3) {
                $instaladores[] = $asignacion->instalador3->nombre;
            }
            if ($asignacion->instalador4) {
                $instaladores[] = $asignacion->instalador4->nombre;
            }
            
            $dataAsignacion = [
                'id' => $asignacion->id,
                'fecha_asigna' => $asignacion->fecha_asigna_formateada,
                'fecha_acepta' => $asignacion->fecha_acepta_formateada,
                'observaciones' => $asignacion->observaciones,
                'estado' => $asignacion->estado,
                'estado_badge' => $asignacion->estado_badge,
                'instaladores' => $instaladores,
                'cantidad_instaladores' => $asignacion->cantidadInstaladores(),
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'nota_venta' => [
                    'folio' => $notaVenta->nv_folio,
                    'folio_formateado' => $notaVenta->folio_formateado,
                    'cliente' => $notaVenta->nv_cliente,
                    'descripcion' => $notaVenta->nv_descripcion,
                    'vendedor' => $notaVenta->nv_vend,
                    'estado' => $notaVenta->nv_estado,
                    'fecha_emision' => $notaVenta->fecha_emision_formateada,
                    'fecha_entrega' => $notaVenta->fecha_entrega_formateada,
                    'direccion' => $notaVenta->nv_direccion,
                    'comuna' => $notaVenta->nv_comuna,
                    'ciudad' => $notaVenta->nv_ciudad,
                    'telefono' => $notaVenta->nv_telefono,
                ],
                'asignacion' => $dataAsignacion
            ]
        ]);
    }
}