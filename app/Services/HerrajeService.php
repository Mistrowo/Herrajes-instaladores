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

        return $herraje;
    }

    public function actualizarEncabezado(Herraje $herraje, array $datos, ?int $userId = null): bool
    {
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

            $herraje->update($datosActualizar);
            $herraje->refresh();

            return true;
        });
    }

    public function crearItem(Herraje $herraje, array $datos): HerrajeItem
    {
        $validated = $this->validateItemPayload($datos);

        return DB::transaction(function () use ($herraje, $validated) {
            $datosItem = [
                'herraje_id'   => $herraje->id,
                'sucursal_id'  => $validated['sucursal_id'] ?? null,  // ⭐ NUEVO
                'descripcion'  => $validated['descripcion'],
                'cantidad'     => (float) $validated['cantidad'],
                'codigo'       => $validated['codigo'] ?? null,
                'unidad'       => $validated['unidad'] ?? 'UN',
                'precio'       => array_key_exists('precio', $validated) ? (float) $validated['precio'] : null,
                'observaciones'=> $validated['observaciones'] ?? null,
            ];

            $item = HerrajeItem::create($datosItem);
            $herraje->recalcularTotales();
            $herraje->refresh();

            return $item;
        });
    }

    public function actualizarItem(HerrajeItem $item, array $datos): bool
    {
        $validated = $this->validateItemPayload($datos);

        return DB::transaction(function () use ($item, $validated) {
            $datosActualizar = [
                'sucursal_id'  => $validated['sucursal_id'] ?? null,  // ⭐ NUEVO
                'descripcion'  => $validated['descripcion'],
                'cantidad'     => (float) $validated['cantidad'],
                'codigo'       => $validated['codigo'] ?? null,
                'unidad'       => $validated['unidad'] ?? 'UN',
                'precio'       => array_key_exists('precio', $validated) ? (float) $validated['precio'] : null,
                'observaciones'=> $validated['observaciones'] ?? null,
            ];

            $item->update($datosActualizar);
            $item->herraje->recalcularTotales();

            return true;
        });
    }

    public function eliminarItem(HerrajeItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            $herraje = $item->herraje;
            $item->delete();
            $herraje->recalcularTotales();
            return true;
        });
    }

    // ⭐ NUEVO MÉTODO - Obtener items agrupados por sucursal
    public function obtenerItemsAgrupadosPorSucursal(Herraje $herraje): array
    {
        $items = $herraje->items()->with('sucursal')->get();
        
        $agrupados = $items->groupBy(function($item) {
            return $item->sucursal_id ?? 0;
        });

        $resultado = [];
        foreach ($agrupados as $sucursalId => $itemsSucursal) {
            $sucursal = null;
            if ($sucursalId > 0) {
                $sucursal = $itemsSucursal->first()->sucursal;
            }

            $resultado[] = [
                'sucursal_id' => $sucursalId,
                'sucursal' => $sucursal,
                'items' => $itemsSucursal,
                'total_items' => $itemsSucursal->count(),
            ];
        }

        return $resultado;
    }

    public function obtenerResumen(Herraje $herraje): array
    {
        $items = $herraje->items()->with('sucursal')->get();
        $itemsPorSucursal = $items->groupBy('sucursal_id')->map->count();

        return [
            'items_count'       => $items->count(),
            'total_estimado'    => $herraje->total_estimado ?? 0,
            'estado'            => $herraje->estado,
            'sucursal_nombre'   => $herraje->sucursal_nombre,
            'items_por_sucursal'=> $itemsPorSucursal,
            'ultimo_actualizado'=> optional($herraje->updated_at)->format('d-m-Y H:i'),
        ];
    }

    public function puedeEditar(Herraje $herraje): bool
    {
        return !in_array($herraje->estado, ['aprobado', 'completado'], true);
    }

    private function validateItemPayload(array $datos): array
    {
        $validator = Validator::make($datos, [
            'sucursal_id'  => ['nullable', 'integer'],  // ⭐ NUEVO
            'descripcion'  => ['required', 'string', 'max:500'],
            'cantidad'     => ['required', 'numeric', 'min:0.01'],
            'codigo'       => ['nullable', 'string', 'max:100'],
            'unidad'       => ['nullable', 'string', 'max:10'],
            'precio'       => ['nullable', 'numeric', 'min:0'],
            'observaciones'=> ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}