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
        $this->checklistService->storeOrUpdate($folio, $request->except('_token'));
        return back()->with('success', 'Checklist guardado correctamente.');
    }

    
}