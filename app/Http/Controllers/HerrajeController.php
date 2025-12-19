<?php

namespace App\Http\Controllers;

use App\Models\Asigna;
use App\Models\Herraje;
use App\Models\HerrajeItem;
use App\Models\Instalador;
use App\Models\NotaVtaActualiza;
use App\Services\HerrajeService;
use App\Services\SucursalService;
use App\Http\Requests\StoreHerrajeItemRequest;
use App\Http\Requests\UpdateHerrajeItemRequest;
use App\Http\Requests\UpdateHerrajeHeaderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HerrajeController extends Controller
{
    protected HerrajeService $herrajeService;
    protected SucursalService $sucursalService;

    public function __construct(HerrajeService $herrajeService, SucursalService $sucursalService)
    {
        $this->herrajeService = $herrajeService;
        $this->sucursalService = $sucursalService;
    }

    /**
     * Mostrar formulario de herrajes por folio de nota de venta
     */
    public function showByFolio(int $folio): View
    {
        Log::info('=== INICIANDO showByFolio ===', [
            'folio' => $folio,
            'user_id' => Auth::id(),
        ]);

        try {
            // Buscar nota de venta
            $nota = NotaVtaActualiza::where('nv_folio', $folio)->first();
            
            // Buscar asignación
            $asigna = Asigna::where('nota_venta', $folio)
                ->with('sucursal')
                ->latest('fecha_asigna')
                ->first();

            // Obtener sucursal de la asignación si existe
            $sucursalId = $asigna ? $asigna->sucursal_id : null;

            // Obtener o crear herraje
            $herraje = $this->herrajeService->obtenerOCrearHerraje($folio, Auth::id(), $sucursalId);
            
            // Cargar relaciones
            $herraje->load(['items', 'instalador', 'asigna', 'sucursal']);

            // Obtener sucursales disponibles para el cliente
            $sucursales = collect();
            if ($nota && $nota->nv_cliente) {
                $sucursales = $this->sucursalService->buscarSucursalesPorNombreCliente($nota->nv_cliente);
            }

            // Instaladores activos
            $instaladores = Instalador::activo()
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'usuario']);

            Log::info('Datos cargados exitosamente', [
                'herraje_id' => $herraje->id,
                'sucursales_count' => $sucursales->count(),
                'instaladores_count' => $instaladores->count(),
            ]);

            return view('herrajes.show', compact(
                'herraje',
                'nota',
                'asigna',
                'instaladores',
                'sucursales'
            ));
        } catch (\Exception $e) {
            Log::error('Error en showByFolio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Actualizar encabezado del herraje
     */
    public function updateHeader(UpdateHerrajeHeaderRequest $request, Herraje $herraje): JsonResponse
    {
        Log::info('=== INICIANDO updateHeader ===', [
            'herraje_id' => $herraje->id,
            'user_id' => Auth::id(),
            'datos_recibidos' => $request->all()
        ]);

        try {
            $validated = $request->validated();
            
            Log::info('Datos validados correctamente', ['validated' => $validated]);

            $this->herrajeService->actualizarEncabezado(
                $herraje,
                $validated,
                Auth::id()
            );

            $herrajeActualizado = $herraje->fresh(['sucursal']);

            Log::info('Encabezado actualizado exitosamente', [
                'herraje_id' => $herraje->id,
                'nuevo_estado' => $herrajeActualizado->estado,
                'nuevo_instalador_id' => $herrajeActualizado->instalador_id,
                'nuevo_sucursal_id' => $herrajeActualizado->sucursal_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encabezado actualizado correctamente',
                'data' => [
                    'estado' => $herrajeActualizado->estado,
                    'instalador_id' => $herrajeActualizado->instalador_id,
                    'sucursal_id' => $herrajeActualizado->sucursal_id,
                    'sucursal_nombre' => $herrajeActualizado->sucursal_nombre,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en updateHeader', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en updateHeader', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el encabezado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo ítem
     */
    public function storeItem(StoreHerrajeItemRequest $request, Herraje $herraje): JsonResponse
    {
        Log::info('=== INICIANDO storeItem ===', [
            'herraje_id' => $herraje->id,
            'user_id' => Auth::id(),
            'datos_recibidos' => $request->all(),
        ]);

        try {
            $validated = $request->validated();
            $validated['precio'] = null;
            
            Log::info('Datos validados para crear ítem', ['validated' => $validated]);

            $item = $this->herrajeService->crearItem($herraje, $validated);

            Log::info('Ítem creado exitosamente', [
                'item_id' => $item->id,
                'descripcion' => $item->descripcion,
                'cantidad' => $item->cantidad,
            ]);

            $resumen = $this->herrajeService->obtenerResumen($herraje->fresh());

            return response()->json([
                'success' => true,
                'message' => 'Ítem agregado exitosamente',
                'data' => [
                    'item' => $item,
                    'resumen' => $resumen
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en storeItem', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en storeItem', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el ítem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar ítem existente
     */
    public function updateItem(UpdateHerrajeItemRequest $request, Herraje $herraje, HerrajeItem $item): JsonResponse
    {
        Log::info('=== INICIANDO updateItem ===', [
            'herraje_id' => $herraje->id,
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'datos_recibidos' => $request->all()
        ]);

        if ($item->herraje_id !== $herraje->id) {
            Log::warning('Intento de actualizar ítem que no pertenece al herraje');
            
            return response()->json([
                'success' => false,
                'message' => 'El ítem no pertenece a este herraje'
            ], 404);
        }

        try {
            $validated = $request->validated();
            $validated['precio'] = null;
            
            Log::info('Datos validados para actualizar ítem', ['validated' => $validated]);

            $this->herrajeService->actualizarItem($item, $validated);

            $itemActualizado = $item->fresh();
            $resumen = $this->herrajeService->obtenerResumen($herraje->fresh());

            return response()->json([
                'success' => true,
                'message' => 'Ítem actualizado correctamente',
                'data' => [
                    'item' => $itemActualizado,
                    'resumen' => $resumen
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en updateItem', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en updateItem', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el ítem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un ítem
     */
    public function destroyItem(Herraje $herraje, HerrajeItem $item): JsonResponse
    {
        Log::info('=== INICIANDO destroyItem ===', [
            'herraje_id' => $herraje->id,
            'item_id' => $item->id,
            'user_id' => Auth::id()
        ]);

        if ($item->herraje_id !== $herraje->id) {
            Log::warning('Intento de eliminar ítem que no pertenece al herraje');
            
            return response()->json([
                'success' => false,
                'message' => 'El ítem no pertenece a este herraje'
            ], 404);
        }

        try {
            $this->herrajeService->eliminarItem($item);

            $resumen = $this->herrajeService->obtenerResumen($herraje->fresh());

            return response()->json([
                'success' => true,
                'message' => 'Ítem eliminado correctamente',
                'data' => [
                    'resumen' => $resumen
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en destroyItem', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el ítem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener listado de ítems con resumen
     */
    public function items(Herraje $herraje): JsonResponse
    {
        Log::info('=== INICIANDO items ===', [
            'herraje_id' => $herraje->id,
            'user_id' => Auth::id()
        ]);

        try {
            $items = $herraje->items()
                ->orderBy('created_at', 'desc')
                ->get();

            $resumen = $this->herrajeService->obtenerResumen($herraje);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'resumen' => $resumen,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en items', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los ítems: ' . $e->getMessage()
            ], 500);
        }
    }



/**
 * Obtener ítems agrupados por sucursal
 */
public function itemsAgrupados(Herraje $herraje): JsonResponse
{
    Log::info('=== INICIANDO itemsAgrupados ===', ['herraje_id' => $herraje->id]);

    try {
        $agrupados = $this->herrajeService->obtenerItemsAgrupadosPorSucursal($herraje);
        $resumen = $this->herrajeService->obtenerResumen($herraje);

        return response()->json([
            'success' => true,
            'data' => [
                'agrupados' => $agrupados,
                'resumen' => $resumen,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error en itemsAgrupados', ['error' => $e->getMessage()]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener ítems agrupados: ' . $e->getMessage()
        ], 500);
    }
}
}