<?php

namespace App\Http\Controllers;

use App\Models\Asigna;
use App\Models\Herraje;
use App\Models\HerrajeItem;
use App\Models\Instalador;
use App\Models\NotaVtaActualiza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HerrajeController extends Controller
{
    /**
     * Mostrar o crear el formulario de herrajes asociado a una Nota de Venta
     */
    public function showByFolio(Request $request, int $folio)
    {
        // Buscar o crear encabezado
        $herraje = Herraje::firstOrCreate(
            ['nv_folio' => $folio],
            [
                'estado' => 'borrador',
                'created_by' => optional(Auth::user())->id,
            ]
        );

        // Nota de Venta (SQL Server)
        $nota = NotaVtaActualiza::where('nv_folio', $folio)->first();

        // Asignación (si existe) por nv_folio
        $asigna = Asigna::where('nota_venta', $folio)->latest('fecha_asigna')->first();

        // Instaladores activos para selector
        $instaladores = Instalador::activo()
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'usuario']);

        return view('herrajes.show', compact('herraje', 'nota', 'asigna', 'instaladores'));
    }

    /**
     * Actualiza datos generales del encabezado (estado, instalador, observaciones)
     */
    public function updateHeader(Request $request, Herraje $herraje)
    {
        $data = $request->validate([
            'instalador_id' => ['nullable', 'exists:sh_instalador,id'],
            'estado'        => ['required', 'in:borrador,en_revision,aprobado,rechazado'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $data['updated_by'] = optional(Auth::user())->id;

        $herraje->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Encabezado actualizado correctamente',
        ]);
    }

    /**
     * Crear nuevo ítem de herraje (solo nombre + cantidad)
     */
    public function storeItem(Request $request, Herraje $herraje)
    {
        $data = $request->validate([
            'descripcion'  => ['required', 'string', 'max:255'],
            'cantidad'     => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () use ($herraje, $data) {
            HerrajeItem::create([
                'herraje_id'   => $herraje->id,
                'descripcion'  => $data['descripcion'],
                'cantidad'     => $data['cantidad'],
                'codigo'       => null,
                'unidad'       => 'UN',
                'precio'       => null,
                'observaciones'=> null,
            ]);
            $herraje->recalcularTotales();
        });

        return response()->json([
            'success' => true,
            'message' => 'Ítem agregado exitosamente',
        ]);
    }

    /**
     * Actualizar ítem existente (solo nombre + cantidad)
     */
    public function updateItem(Request $request, Herraje $herraje, HerrajeItem $item)
    {
        abort_unless($item->herraje_id === $herraje->id, 404);

        $data = $request->validate([
            'descripcion'  => ['required', 'string', 'max:255'],
            'cantidad'     => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () use ($item, $herraje, $data) {
            $item->update([
                'descripcion'  => $data['descripcion'],
                'cantidad'     => $data['cantidad'],
                'codigo'       => null,
                'unidad'       => $item->unidad ?? 'UN',
                'precio'       => null,
                'observaciones'=> $item->observaciones,
            ]);
            $herraje->recalcularTotales();
        });

        return response()->json([
            'success' => true,
            'message' => 'Ítem actualizado correctamente',
        ]);
    }

    /**
     * Eliminar un ítem
     */
    public function destroyItem(Request $request, Herraje $herraje, HerrajeItem $item)
    {
        abort_unless($item->herraje_id === $herraje->id, 404);

        DB::transaction(function () use ($item, $herraje) {
            $item->delete();
            $herraje->recalcularTotales();
        });

        return response()->json([
            'success' => true,
            'message' => 'Ítem eliminado correctamente',
        ]);
    }

    /**
     * Listado de ítems (para recarga dinámica en la vista)
     */
    public function items(Herraje $herraje)
    {
        $items = $herraje->items()->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'resumen' => [
                    'items_count' => $items->count(),
                    'total' => $herraje->total_estimado,
                ],
            ],
        ]);
    }
}
