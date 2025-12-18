<?php

namespace App\Http\Controllers;

use App\Models\Asigna;
use App\Models\Instalador;
use App\Models\NotaVtaActualiza;
use App\Services\AsignarService;
use App\Services\SucursalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AsignarController extends Controller
{
    protected AsignarService $asignarService;
    protected SucursalService $sucursalService;

    public function __construct(
        AsignarService $asignarService,
        SucursalService $sucursalService
    ) {
        $this->asignarService = $asignarService;
        $this->sucursalService = $sucursalService;
    }

    /**
     * Mostrar listado de notas de venta y asignaciones
     */
   public function index(Request $request)
{
    // Filtros para Notas de Venta
    $filtrosNV = [
        'buscar' => $request->input('buscar'),
        'estado_nv' => $request->input('estado_nv'),
    ];
    
    // Query Notas de Venta
    $queryNV = NotaVtaActualiza::query();
    
    if ($filtrosNV['buscar']) {
        $queryNV->where(function($q) use ($filtrosNV) {
            $q->where('nv_folio', 'like', '%' . $filtrosNV['buscar'] . '%')
              ->orWhere('nv_cliente', 'like', '%' . $filtrosNV['buscar'] . '%');
        });
    }
    
    if ($filtrosNV['estado_nv']) {
        $queryNV->where('nv_estado', $filtrosNV['estado_nv']);
    }
    
    $notasVenta = $queryNV->orderBy('nv_folio', 'desc')->paginate(15)->withQueryString();
    
    $filtros = [
        'nota_venta' => $request->input('nota_venta'),
        'estado' => $request->input('estado'),
    ];
    
    $queryAsignaciones = Asigna::with([
        'instalador1',
        'instalador2',
        'instalador3',
        'instalador4',
        'sucursal',
        'notaVenta',
    ]);
    
    if ($filtros['nota_venta']) {
        $queryAsignaciones->where('nota_venta', 'like', '%' . $filtros['nota_venta'] . '%');
    }
    
    if ($filtros['estado']) {
        $queryAsignaciones->where('estado', $filtros['estado']);
    }
    
    $asignacionesPaginadas = $queryAsignaciones->orderBy('fecha_asigna', 'desc')
        ->paginate(15, ['*'], 'asignaciones_page')
        ->withQueryString();
    
    $asignaciones = Asigna::with('sucursal')->get();
    
    $instaladores = Instalador::where('activo', 1)->orderBy('nombre')->get();
    
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
     * Endpoint AJAX para obtener sucursales por nombre de cliente
     */
    public function obtenerSucursales(Request $request): JsonResponse
    {
        try {
            $nombreCliente = $request->get('nombre_cliente');
            
            if (empty($nombreCliente)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar el nombre del cliente',
                    'sucursales' => []
                ], 400);
            }

            // Buscar sucursales usando el nombre del cliente
            $sucursales = $this->sucursalService->buscarSucursalesPorNombreCliente($nombreCliente);

            if ($sucursales->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No se encontraron sucursales para este cliente',
                    'sucursales' => [],
                    'debug' => [
                        'cliente_buscado' => $nombreCliente
                    ]
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
                    'empresa_nombre' => $sucursal->empresa_nombre,
                    'empresa_rut' => $sucursal->empresa_rut,
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
     * Almacenar nueva asignación
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nota_venta' => 'required|string',
                'sucursal_id' => 'nullable|integer',
                'asignado1' => 'nullable|exists:instaladores,id',
                'asignado2' => 'nullable|exists:instaladores,id',
                'asignado3' => 'nullable|exists:instaladores,id',
                'asignado4' => 'nullable|exists:instaladores,id',
                'fecha_asigna' => 'required|date',
                'observaciones' => 'nullable|string',
            ]);

            $asignacion = $this->asignarService->crearAsignacion($validatedData);

            return redirect()
                ->route('asignar.index')
                ->with('success', 'Asignación creada exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de una asignación
     */
  /**
 * Mostrar detalles de una asignación
 */
public function show($id)
{
    try {
        $asignacion = Asigna::with([
            'instalador1',
            'instalador2',
            'instalador3',
            'instalador4',
            'sucursal'
        ])->findOrFail($id);
        
        // Obtener nota de venta si existe
        $notaVenta = NotaVtaActualiza::where('nv_folio', $asignacion->nota_venta)->first();
        
        return view('asignar.show', compact('asignacion', 'notaVenta'));
        
    } catch (\Exception $e) {
        Log::error('Error al cargar detalles de asignación: ' . $e->getMessage());
        
        return response()->view('errors.custom', [
            'message' => 'No se pudo cargar la información de la asignación'
        ], 500);
    }
}


    /**
     * Actualizar asignación
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nota_venta' => 'required|string',
                'sucursal_id' => 'nullable|integer',
                'asignado1' => 'nullable|exists:instaladores,id',
                'asignado2' => 'nullable|exists:instaladores,id',
                'asignado3' => 'nullable|exists:instaladores,id',
                'asignado4' => 'nullable|exists:instaladores,id',
                'fecha_asigna' => 'required|date',
                'observaciones' => 'nullable|string',
            ]);

            $asignacion = $this->asignarService->actualizarAsignacion($id, $validatedData);

            return redirect()
                ->route('asignar.index', ['tab' => 'asignaciones'])
                ->with('success', 'Asignación actualizada exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar asignación
     */
    public function destroy($id)
    {
        try {
            $this->asignarService->eliminarAsignacion($id);

            return redirect()
                ->route('asignar.index', ['tab' => 'asignaciones'])
                ->with('success', 'Asignación eliminada exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cambiar estado de asignación
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $request->validate([
                'estado' => 'required|in:pendiente,aceptada,rechazada,en_proceso,completada'
            ]);

            $asignacion = $this->asignarService->cambiarEstadoAsignacion(
                $id,
                $request->estado
            );

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'asignacion' => $asignacion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}