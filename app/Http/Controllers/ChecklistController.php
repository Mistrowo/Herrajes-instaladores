<?php

namespace App\Http\Controllers;

use App\Services\ChecklistService;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    protected ChecklistService $checklistService;

    public function __construct(ChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    /**
     * Mostrar formulario de checklist
     */
    public function index(int $folio)
    {
        try {
            $data = $this->checklistService->getByFolio($folio);
            
            return view('checklist.index', [
                'asignacion' => $data['asignacion'],
                'checklist' => $data['checklist']
            ]);

        } catch (\Exception $e) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No se encontró la asignación para este folio.');
        }
    }

    /**
     * Guardar checklist
     */
    public function store(Request $request, int $folio)
    {
        try {
            $this->checklistService->storeOrUpdate($folio, $request->all());

            return redirect()
                ->route('checklist.index', $folio)
                ->with('success', 'Checklist guardado correctamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al guardar el checklist. Por favor intenta nuevamente.');
        }
    }
}