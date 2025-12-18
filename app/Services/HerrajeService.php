<?php

namespace App\Services;

use App\Models\Herraje;
use App\Models\HerrajeItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HerrajeService
{
    protected SucursalService $sucursalService;

    public function __construct(SucursalService $sucursalService)
    {
        $this->sucursalService = $sucursalService;
    }

    /**
     * Obtener o crear herraje por folio de nota de venta
     */
    public function obtenerOCrearHerraje(int $folio, ?int $userId = null, ?int $sucursalId = null): Herraje
    {
        Log::info('SERVICE: obtenerOCrearHerraje', compact('folio', 'userId', 'sucursalId'));

        $herraje = Herraje::firstOrCreate(
            ['nv_folio' => $folio],
            [
                'estado'         => 'en_revision',
                'sucursal_id'    => $sucursalId,
                'created_by'     => $userId,
                'items_count'    => 0,
            ]
        );

        Log::info('SERVICE: Herraje obtenido/creado', [
            'herraje_id'         => $herraje->id,
            'nv_folio'           => $herraje->nv_folio,
            'sucursal_id'        => $herraje->sucursal_id,
            'estado'             => $herraje->estado,
            'wasRecentlyCreated' => $herraje->wasRecentlyCreated,
        ]);

        return $herraje;
    }

    /**
     * Actualizar encabezado del herraje
     */
    public function actualizarEncabezado(Herraje $herraje, array $datos, ?int $userId = null): bool
    {
        Log::info('SERVICE: actualizarEncabezado - INICIO', [
            'herraje_id' => $herraje->id,
            'datos'      => $datos,
            'user_id'    => $userId,
        ]);

        $validator = Validator::make($datos, [
            'instalador_id' => ['nullable', 'integer'],
            'sucursal_id'   => ['nullable', 'integer'],
            'estado'        => ['nullable', 'in:en_revision,aprobado,completado'],
            'observaciones' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return DB::transaction(function () use ($herraje, $datos, $userId) {
            $datosActualizar = [
                ...Arr::only($datos, ['instalador_id', 'sucursal_id', 'estado', 'observaciones']),
                'updated_by' => $userId,
            ];

            if (!array_key_exists('estado', $datosActualizar)) {
                $datosActualizar['estado'] = $herraje->estado ?? 'en_revision';
            }

            Log::info('SERVICE: Datos a actualizar', ['datosActualizar' => $datosActualizar]);

            $herraje->update($datosActualizar);
            $herraje->refresh();

            Log::info('SERVICE: Encabezado actualizado exitosamente', [
                'herraje_id'         => $herraje->id,
                'nuevo_estado'       => $herraje->estado,
                'nuevo_instalador_id'=> $herraje->instalador_id,
                'nuevo_sucursal_id'  => $herraje->sucursal_id,
            ]);

            return true;
        });
    }

    /**
     * Crear nuevo ítem de herraje
     */
    public function crearItem(Herraje $herraje, array $datos): HerrajeItem
    {
        Log::info('SERVICE: crearItem - INICIO', [
            'herraje_id' => $herraje->id,
            'datos'      => $datos,
        ]);

        $validated = $this->validateItemPayload($datos);

        return DB::transaction(function () use ($herraje, $validated) {
            $datosItem = [
                'herraje_id'   => $herraje->id,
                'descripcion'  => $validated['descripcion'],
                'cantidad'     => (float) $validated['cantidad'],
                'codigo'       => $validated['codigo'] ?? null,
                'unidad'       => $validated['unidad'] ?? 'UN',
                'precio'       => array_key_exists('precio', $validated) ? (float) $validated['precio'] : null,
                'observaciones'=> $validated['observaciones'] ?? null,
            ];

            Log::info('SERVICE: Datos del ítem a crear', ['datosItem' => $datosItem]);

            $item = HerrajeItem::create($datosItem);

            Log::info('SERVICE: Ítem creado en DB', [
                'item_id'     => $item->id,
                'descripcion' => $item->descripcion,
                'cantidad'    => $item->cantidad,
            ]);

            $herraje->recalcularTotales();
            $herraje->refresh();

            Log::info('SERVICE: Totales recalculados', [
                'items_count'    => $herraje->items_count,
                'total_estimado' => $herraje->total_estimado,
            ]);

            return $item;
        });
    }

    /**
     * Actualizar ítem existente
     */
    public function actualizarItem(HerrajeItem $item, array $datos): bool
    {
        Log::info('SERVICE: actualizarItem - INICIO', [
            'item_id'    => $item->id,
            'herraje_id' => $item->herraje_id,
            'datos'      => $datos,
        ]);

        $validated = $this->validateItemPayload($datos);

        return DB::transaction(function () use ($item, $validated) {
            $datosActualizar = [
                'descripcion'  => $validated['descripcion'],
                'cantidad'     => (float) $validated['cantidad'],
                'codigo'       => $validated['codigo'] ?? null,
                'unidad'       => $validated['unidad'] ?? 'UN',
                'precio'       => array_key_exists('precio', $validated) ? (float) $validated['precio'] : null,
                'observaciones'=> $validated['observaciones'] ?? null,
            ];

            Log::info('SERVICE: Datos a actualizar del ítem', ['datosActualizar' => $datosActualizar]);

            $item->update($datosActualizar);

            Log::info('SERVICE: Ítem actualizado en DB', ['item_id' => $item->id]);

            $item->herraje->recalcularTotales();

            Log::info('SERVICE: Totales recalculados', ['herraje_id' => $item->herraje_id]);

            return true;
        });
    }

    /**
     * Eliminar ítem
     */
    public function eliminarItem(HerrajeItem $item): bool
    {
        Log::info('SERVICE: eliminarItem - INICIO', [
            'item_id'    => $item->id,
            'herraje_id' => $item->herraje_id,
        ]);

        return DB::transaction(function () use ($item) {
            $herraje = $item->herraje;
            $deletedId = $item->id;

            $item->delete();

            Log::info('SERVICE: Ítem eliminado, recalculando totales', ['item_deleted_id' => $deletedId]);

            $herraje->recalcularTotales();

            Log::info('SERVICE: Ítem eliminado exitosamente', [
                'item_deleted_id' => $deletedId,
                'herraje_id'      => $herraje->id,
            ]);

            return true;
        });
    }

    /**
     * Obtener resumen del herraje
     */
    public function obtenerResumen(Herraje $herraje): array
    {
        Log::info('SERVICE: obtenerResumen', ['herraje_id' => $herraje->id]);

        $resumen = [
            'items_count'       => $herraje->items()->count(),
            'total_estimado'    => $herraje->total_estimado ?? 0,
            'estado'            => $herraje->estado,
            'sucursal_nombre'   => $herraje->sucursal_nombre,
            'ultimo_actualizado'=> optional($herraje->updated_at)->format('d-m-Y H:i'),
        ];

        Log::info('SERVICE: Resumen generado', ['resumen' => $resumen]);

        return $resumen;
    }

    /**
     * Validar si se puede editar el herraje
     */
    public function puedeEditar(Herraje $herraje): bool
    {
        $puedeEditar = !in_array($herraje->estado, ['aprobado', 'completado'], true);

        Log::info('SERVICE: puedeEditar', [
            'herraje_id'   => $herraje->id,
            'estado'       => $herraje->estado,
            'puede_editar' => $puedeEditar,
        ]);

        return $puedeEditar;
    }

    /**
     * Validar payload para crear/actualizar ítems
     */
    private function validateItemPayload(array $datos): array
    {
        $validator = Validator::make($datos, [
            'descripcion'  => ['required', 'string', 'max:500'],
            'cantidad'     => ['required', 'numeric', 'min:0.01'],
            'codigo'       => ['nullable', 'string', 'max:100'],
            'unidad'       => ['nullable', 'string', 'max:10'],
            'precio'       => ['nullable', 'numeric', 'min:0'],
            'observaciones'=> ['nullable', 'string'],
        ], [], [
            'descripcion' => 'descripción',
            'cantidad'    => 'cantidad',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}