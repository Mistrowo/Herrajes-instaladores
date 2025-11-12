<?php

namespace App\Http\Controllers;

use App\Services\ChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
            Log::error('Error al cargar checklist', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);

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
            Log::info('Recibiendo datos de checklist', [
                'folio' => $folio,
                'data_keys' => array_keys($request->all()),
                'user_id' => auth()->id()
            ]);

            $checklist = $this->checklistService->storeOrUpdate($folio, $request->all());

            return redirect()
                ->route('checklist.index', $folio)
                ->with('success', 'Checklist guardado correctamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error SQL al guardar checklist', [
                'folio' => $folio,
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? []
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error de base de datos: ' . $e->getMessage());

        } catch (\Exception $e) {
            Log::error('Error general al guardar checklist', [
                'folio' => $folio,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Descargar checklist en PDF
     */
    public function downloadPdf(int $folio)
    {
        try {
            $data = $this->checklistService->getByFolio($folio);
            
            if (!$data['checklist']) {
                return redirect()
                    ->route('checklist.index', $folio)
                    ->with('error', 'No hay checklist guardado para descargar.');
            }

            $pdf = Pdf::loadView('checklist.pdf', [
                'asignacion' => $data['asignacion'],
                'checklist' => $data['checklist']
            ]);

            $pdf->setPaper('letter', 'portrait');
            
            $filename = 'Checklist_NV_' . str_pad($folio, 6, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error al generar PDF', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('checklist.index', $folio)
                ->with('error', 'Error al generar el PDF.');
        }
    }
}