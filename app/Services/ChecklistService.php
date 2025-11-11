<?php

namespace App\Services;

use App\Models\Asigna;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;

class ChecklistService
{
    public function getByFolio(int $folio)
    {
        $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();
        $checklist = Checklist::where('asigna_id', $asignacion->id)->first();

        return compact('asignacion', 'checklist');
    }

    public function storeOrUpdate(int $folio, array $data): Checklist
    {
        $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();
        $instalador = Auth::user();

        return Checklist::updateOrCreate(
            ['asigna_id' => $asignacion->id],
            array_merge($data, [
                'nota_venta' => $folio,
                'instalador_id' => $instalador->id,
                'fecha_completado' => now(),
            ])
        );
    }



    
}