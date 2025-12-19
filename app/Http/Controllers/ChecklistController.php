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
    public function index(int $folio)
    {
        Log::info('ChecklistController: index', ['folio' => $folio]);

        try {
            $data = $this->checklistService->getByFolio($folio);
            
            $nota = NotaVtaActualiza::where('nv_folio', $folio)->first();
            
            $sucursales = collect();
            if ($nota && $nota->nv_cliente) {
                $sucursales = $this->sucursalService->buscarSucursalesPorNombreCliente($nota->nv_cliente);
            }

            Log::info('ChecklistController: Datos cargados', [
                'asignacion_id' => $data['asignacion']->id,
                'checklist_exists' => $data['checklist'] ? 'SI' : 'NO',
                'sucursales_count' => $sucursales->count()
            ]);

            return view('checklist.index', [
                'asignacion' => $data['asignacion'],
                'checklist' => $data['checklist'],
                'nota' => $nota,
                'sucursales' => $sucursales,
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
            $checklist = $this->checklistService->storeOrUpdate($folio, $request->all());

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
    public function downloadPdf(int $folio)
    {
        Log::info('ChecklistController: downloadPdf', ['folio' => $folio]);

        try {
            // Obtener datos del checklist
            $data = $this->checklistService->getByFolio($folio);
            
            if (!$data['checklist']) {
                return redirect()
                    ->route('checklist.index', $folio)
                    ->with('error', 'No se ha guardado ningún checklist aún');
            }

            // Cargar relaciones necesarias
            $checklist = $data['checklist'];
            $checklist->load(['instalador', 'sucursal']);

            // Generar PDF
            $pdf = Pdf::loadView('checklist.pdf', [
                'checklist' => $checklist,
                'asignacion' => $data['asignacion'],
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