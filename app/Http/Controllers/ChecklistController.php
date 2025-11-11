<?php

namespace App\Http\Controllers;

use App\Services\ChecklistService;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    protected $checklistService;

    public function __construct(ChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    public function index($folio)
    {
        $data = $this->checklistService->getByFolio($folio);
        return view('checklist.index', $data);
    }

    public function store(Request $request, $folio)
    {
        $request->validate([
            'telefono' => 'nullable|string|max:20',
            'mod_autorizadas_por' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
            '*.rectificacion_medidas' => 'boolean',
        ]);

        $this->checklistService->storeOrUpdate($folio, $request->except('_token'));

        return back()->with('success', 'Checklist guardado correctamente.');
    }
}