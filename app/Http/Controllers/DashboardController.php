<?php

namespace App\Http\Controllers;

use App\Services\AsignarService;
use App\Services\SucursalService; // ⭐ AGREGAR
use App\Models\NotaVtaActualiza;
use App\Models\Asigna;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected AsignarService $asignarService;
    protected SucursalService $sucursalService; // ⭐ AGREGAR

    public function __construct(
        AsignarService $asignarService,
        SucursalService $sucursalService // ⭐ AGREGAR
    ) {
        $this->asignarService = $asignarService;
        $this->sucursalService = $sucursalService; // ⭐ AGREGAR
        $this->middleware(['auth', 'active.instalador']);
    }

    /**
     * Mostrar dashboard principal
     */
    public function index(): View
    {
        /** @var \App\Models\Instalador $user */
        $user = auth()->user();
        
        if ($user->esAdmin()) {
            $notasVenta = NotaVtaActualiza::orderBy('nv_femision', 'desc')
                ->paginate(10);
        } else {
            $asignaciones = Asigna::porInstalador($user->id)
                ->whereIn('estado', ['aceptada', 'en_proceso'])
                ->get();
            
            $folios = $asignaciones->pluck('nota_venta')->unique()->toArray();
            
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
        
        if ($user->esAdmin()) {
            $query = NotaVtaActualiza::query();
        } else {
            $asignaciones = Asigna::porInstalador($user->id)
                ->whereIn('estado', ['aceptada', 'en_proceso'])
                ->get();
            
            $folios = $asignaciones->pluck('nota_venta')->unique()->toArray();
            
            $query = NotaVtaActualiza::whereIn('nv_folio', $folios);
        }
        
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
     * ⭐ NUEVO MÉTODO - Obtener sucursales por nombre de cliente
     */
    public function obtenerSucursales(Request $request): JsonResponse
    {
        try {
            $nombreCliente = $request->input('nombre_cliente');
            
            if (empty($nombreCliente)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar el nombre del cliente',
                    'sucursales' => []
                ], 400);
            }

            $sucursales = $this->sucursalService->buscarSucursalesPorNombreCliente($nombreCliente);

            if ($sucursales->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No se encontraron sucursales para este cliente',
                    'sucursales' => []
                ]);
            }

            $sucursalesFormateadas = $sucursales->map(function ($sucursal) {
                return [
                    'id' => $sucursal->id,
                    'nombre' => $sucursal->nombre,
                    'direccion' => $sucursal->direccion,
                    'comuna' => $sucursal->comuna,
                    'region' => $sucursal->region,
                    'direccion_completa' => $sucursal->direccion_completa,
                    'telefono' => $sucursal->telefono,
                    'email' => $sucursal->email,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Sucursales encontradas',
                'sucursales' => $sucursalesFormateadas,
                'total' => $sucursalesFormateadas->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener sucursales: ' . $e->getMessage(),
                'sucursales' => []
            ], 500);
        }
    }

    /**
     * Obtener detalles de una nota de venta (AJAX)
     */
    public function obtenerDetallesNV(Request $request): JsonResponse
    {
        $folio = $request->input('folio');
        
        /** @var \App\Models\Instalador $user */
        $user = auth()->user();
        
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
        
        $notaVenta = NotaVtaActualiza::where('nv_folio', $folio)->first();
        
        if (!$notaVenta) {
            return response()->json([
                'success' => false,
                'message' => 'Nota de venta no encontrada'
            ], 404);
        }
        
        $asignacion = Asigna::where('nota_venta', $folio)
            ->with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal'])
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
            
            $sucursalData = null;
            if ($asignacion->sucursal) {
                $sucursalData = [
                    'id' => $asignacion->sucursal->id,
                    'nombre' => $asignacion->sucursal->nombre,
                    'direccion' => $asignacion->sucursal->direccion,
                    'comuna' => $asignacion->sucursal->comuna,
                    'direccion_completa' => $asignacion->sucursal->direccion_completa,
                ];
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
                'sucursal' => $sucursalData, // ⭐ NUEVO
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