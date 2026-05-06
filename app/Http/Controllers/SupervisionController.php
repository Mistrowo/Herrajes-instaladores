<?php

namespace App\Http\Controllers;

use App\Models\Asigna;
use App\Models\Herraje;
use App\Models\EvidenciaFotografica;
use App\Models\Checklist;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $query->where('nota_venta', 'like', '%' . $request->buscar . '%');
        }

        $asignaciones = $query->paginate(20)->withQueryString();

        $asignaIds = $asignaciones->pluck('id');
        $notasVenta = $asignaciones->pluck('nota_venta');

        $herrajesPorAsigna = Herraje::whereIn('asigna_id', $asignaIds)
            ->selectRaw('asigna_id, COUNT(*) as total, SUM(items_count) as items, SUM(total_estimado) as monto')
            ->groupBy('asigna_id')
            ->get()
            ->keyBy('asigna_id');

        $evidenciasPorNota = EvidenciaFotografica::whereIn('nota_venta', $notasVenta)
            ->selectRaw('nota_venta, COUNT(*) as total')
            ->groupBy('nota_venta')
            ->get()
            ->keyBy('nota_venta');

        $checklistsPorNota = Checklist::whereIn('nota_venta', $notasVenta)
            ->get()
            ->groupBy('nota_venta');

        return view('supervision.index', compact(
            'asignaciones',
            'herrajesPorAsigna',
            'evidenciasPorNota',
            'checklistsPorNota'
        ));
    }

    public function show($id)
    {
        $asigna = Asigna::with(['instalador1', 'instalador2', 'instalador3', 'instalador4', 'sucursal'])
            ->findOrFail($id);

        $herrajes = Herraje::with(['items', 'instalador', 'creador', 'sucursal'])
            ->where('asigna_id', $id)
            ->get();

        $evidencias = EvidenciaFotografica::with(['instalador', 'sucursal'])
            ->where('nota_venta', $asigna->nota_venta)
            ->orderBy('created_at', 'asc')
            ->get();

        $checklists = Checklist::with(['instalador', 'sucursal'])
            ->where('nota_venta', $asigna->nota_venta)
            ->get();

        return view('supervision.show', compact('asigna', 'herrajes', 'evidencias', 'checklists'));
    }
}
