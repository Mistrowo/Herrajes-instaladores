<?php

namespace App\Http\Controllers;

use App\Services\EvidenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenciaController extends Controller
{
    protected $evidenciaService;

    public function __construct(EvidenciaService $evidenciaService)
    {
        $this->evidenciaService = $evidenciaService;
    }

    public function index($folio)
    {
        $data = $this->evidenciaService->getEvidenciasByFolio($folio);
        return view('evidencia.index', $data);
    }

    public function store(Request $request, $folio)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $evidencia = $this->evidenciaService->storeEvidencia(
            $folio,
            $request->file('imagen'),
            $request->descripcion
        );

        return redirect()->back()->with('success', 'Evidencia subida correctamente.');
    }

    public function destroy($id)
    {
        $this->evidenciaService->deleteEvidencia($id);
        return redirect()->back()->with('success', 'Evidencia eliminada.');
    }
}