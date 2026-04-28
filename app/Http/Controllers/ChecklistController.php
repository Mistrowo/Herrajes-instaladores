<?php

namespace App\Http\Controllers;

use App\Models\NotaVtaActualiza;
use App\Services\ChecklistService;
use App\Services\SucursalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ChecklistController extends Controller
{
    protected ChecklistService $checklistService;
    protected SucursalService $sucursalService;

    public function __construct(ChecklistService $checklistService, SucursalService $sucursalService)
    {
        $this->checklistService = $checklistService;
        $this->sucursalService = $sucursalService;
    }

    /**
     * Mostrar formulario de checklist
     */
    public function index(Request $request, int $folio)
    {
        Log::info('ChecklistController: index', ['folio' => $folio]);

        try {
            $asignacionId = $request->query('asignacion') ? (int) $request->query('asignacion') : null;
            $data = $this->checklistService->getByFolio($folio, $asignacionId);

            $nota = NotaVtaActualiza::where('nv_folio', $folio)->first();
            $lugarDespacho = $data['asignacion']->sucursal?->nombre
                ?? $data['asignacion']->lugar_despacho_nom
                ?? $nota?->nv_lugardespacho;

            Log::info('ChecklistController: Datos cargados', [
                'asignacion_id' => $data['asignacion']->id,
                'checklist_exists' => $data['checklist'] ? 'SI' : 'NO',
            ]);

            return view('checklist.index', [
                'asignacion' => $data['asignacion'],
                'checklist' => $data['checklist'],
                'nota' => $nota,
                'lugarDespacho' => $lugarDespacho,
            ]);

        } catch (\Exception $e) {
            Log::error('Error en ChecklistController::index', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'No se pudo cargar el checklist: ' . $e->getMessage());
        }
    }

    /**
     * Guardar checklist
     */
    public function store(Request $request, int $folio)
    {
        Log::info('ChecklistController: store', [
            'folio' => $folio,
            'sucursal_id' => $request->input('sucursal_id')
        ]);

        try {
            $asignacionId = $request->input('asignacion_id') ? (int) $request->input('asignacion_id') : null;
            $checklist = $this->checklistService->storeOrUpdate($folio, $request->all(), $asignacionId);

            Log::info('ChecklistController: Checklist guardado exitosamente', [
                'checklist_id' => $checklist->id,
                'sucursal_id' => $checklist->sucursal_id
            ]);

            return redirect()
                ->route('checklist.index', $folio)
                ->with('success', 'Checklist guardado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al guardar checklist', [
                'folio' => $folio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al guardar el checklist: ' . $e->getMessage());
        }
    }

    /**
     * Descargar PDF del checklist
     */
    public function downloadPdf(Request $request, int $folio)
    {
        Log::info('ChecklistController: downloadPdf', ['folio' => $folio]);

        try {
            $asignacionId = $request->query('asignacion') ? (int) $request->query('asignacion') : null;

            // Obtener datos del checklist
            $data = $this->checklistService->getByFolio($folio, $asignacionId);

            if (!$data['checklist']) {
                return redirect()
                    ->route('checklist.index', $folio)
                    ->with('error', 'No se ha guardado ningún checklist aún');
            }

            $checklist = $data['checklist'];
            $checklist->load(['instalador']);

            $nota = NotaVtaActualiza::where('nv_folio', $folio)->first();
            $asignacion = $data['asignacion'];

            $lugarDespacho = $asignacion?->sucursal?->nombre
                ?? $asignacion?->lugar_despacho_nom
                ?? $nota?->nv_lugardespacho;

            // Generar PDF
            $pdf = Pdf::loadView('checklist.pdf', [
                'checklist'    => $checklist,
                'asignacion'   => $asignacion,
                'lugarDespacho'=> $lugarDespacho,
            ]);

            // Configurar PDF
            $pdf->setPaper('letter', 'portrait');

            // Nombre del archivo
            $filename = 'Checklist_NV_' . str_pad($folio, 6, '0', STR_PAD_LEFT) . '.pdf';

            Log::info('ChecklistController: PDF generado exitosamente', [
                'folio' => $folio,
                'filename' => $filename
            ]);

            // Descargar PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error al generar PDF', [
                'folio' => $folio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('checklist.index', $folio)
                ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}