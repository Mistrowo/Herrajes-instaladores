<?php

namespace App\Http\Controllers;

use App\Models\Asigna;
use App\Models\Herraje;
use App\Models\HerrajeItem;
use App\Models\Instalador;
use App\Models\NotaVtaActualiza;
use App\Services\HerrajeService;
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

    /**
     * Constructor
     */
    public function __construct(HerrajeService $herrajeService)
    {
        $this->herrajeService = $herrajeService;
    }

    /**
     * Mostrar formulario de herrajes por folio de nota de venta
     */
    public function showByFolio(int $folio): View
    {
        Log::info('=== INICIANDO showByFolio ===', [
            'folio' => $folio,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->correo ?? 'N/A'
        ]);

        try {
            // Obtener o crear herraje
            $herraje = $this->herrajeService->obtenerOCrearHerraje($folio, Auth::id());
            
            Log::info('Herraje obtenido/creado', [
                'herraje_id' => $herraje->id,
                'estado' => $herraje->estado
            ]);

            // Cargar relaciones
            $herraje->load(['items', 'instalador', 'asigna']);

            // Buscar nota de venta en SQL Server (si existe el modelo)
            $nota = null;
            if (class_exists(NotaVtaActualiza::class)) {
                $nota = NotaVtaActualiza::where('nv_folio', $folio)->first();
                Log::info('Nota de venta buscada', ['encontrada' => $nota ? 'SI' : 'NO']);
            }

            // Buscar asignación relacionada
            $asigna = Asigna::where('nota_venta', $folio)
                ->latest('fecha_asigna')
                ->first();
            
            Log::info('Asignación buscada', ['encontrada' => $asigna ? 'SI' : 'NO']);

            // Instaladores activos para selector
            $instaladores = Instalador::activo()
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'usuario']);
            
            Log::info('Instaladores cargados', ['cantidad' => $instaladores->count()]);

            return view('herrajes.show', compact(
                'herraje',
                'nota',
                'asigna',
                'instaladores'
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
            
            Log::info('Datos validados correctamente', [
                'validated' => $validated
            ]);

            $this->herrajeService->actualizarEncabezado(
                $herraje,
                $validated,
                Auth::id()
            );

            $herrajeActualizado = $herraje->fresh();

            Log::info('Encabezado actualizado exitosamente', [
                'herraje_id' => $herraje->id,
                'nuevo_estado' => $herrajeActualizado->estado,
                'nuevo_instalador_id' => $herrajeActualizado->instalador_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encabezado actualizado correctamente',
                'data' => [
                    'estado' => $herrajeActualizado->estado,
                    'instalador_id' => $herrajeActualizado->instalador_id,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en updateHeader', [
                'errors' => $e->errors()
            ]);
            
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
            'raw_input' => file_get_contents('php://input')
        ]);

        try {
            $validated = $request->validated();
            
            // Forzar precio a null
            $validated['precio'] = null;
            
            Log::info('Datos validados para crear ítem', [
                'validated' => $validated
            ]);

            $item = $this->herrajeService->crearItem($herraje, $validated);

            Log::info('Ítem creado exitosamente', [
                'item_id' => $item->id,
                'descripcion' => $item->descripcion,
                'cantidad' => $item->cantidad,
                'precio' => $item->precio
            ]);

            $resumen = $this->herrajeService->obtenerResumen($herraje->fresh());

            Log::info('Resumen actualizado', [
                'resumen' => $resumen
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ítem agregado exitosamente',
                'data' => [
                    'item' => $item,
                    'resumen' => $resumen
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en storeItem', [
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en storeItem', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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

        // Verificar que el ítem pertenece al herraje
        if ($item->herraje_id !== $herraje->id) {
            Log::warning('Intento de actualizar ítem que no pertenece al herraje', [
                'herraje_id' => $herraje->id,
                'item_herraje_id' => $item->herraje_id,
                'item_id' => $item->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'El ítem no pertenece a este herraje'
            ], 404);
        }

        try {
            $validated = $request->validated();
            
            // Forzar precio a null
            $validated['precio'] = null;
            
            Log::info('Datos validados para actualizar ítem', [
                'validated' => $validated
            ]);

            $this->herrajeService->actualizarItem($item, $validated);

            Log::info('Ítem actualizado exitosamente', [
                'item_id' => $item->id
            ]);

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
            Log::warning('Error de validación en updateItem', [
                'errors' => $e->errors()
            ]);
            
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

        // Verificar que el ítem pertenece al herraje
        if ($item->herraje_id !== $herraje->id) {
            Log::warning('Intento de eliminar ítem que no pertenece al herraje', [
                'herraje_id' => $herraje->id,
                'item_herraje_id' => $item->herraje_id,
                'item_id' => $item->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'El ítem no pertenece a este herraje'
            ], 404);
        }

        try {
            $this->herrajeService->eliminarItem($item);

            Log::info('Ítem eliminado exitosamente', [
                'item_id' => $item->id
            ]);

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

            Log::info('Ítems cargados', [
                'cantidad' => $items->count()
            ]);

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
}