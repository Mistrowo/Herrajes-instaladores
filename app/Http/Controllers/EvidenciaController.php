<?php

namespace App\Http\Controllers;

use App\Services\EvidenciaService;
use Illuminate\Http\Request;

class EvidenciaController extends Controller
{
    protected $evidenciaService;

    public function __construct(EvidenciaService $evidenciaService)
    {
        $this->evidenciaService = $evidenciaService;
    }

    /**
     * Mostrar evidencias de un folio (con filtro opcional de sucursal)
     */
    public function index(Request $request, $folio)
    {
        $sucursalId = $request->get('sucursal_id');
        
        // Convertir "0" string a int 0 para filtrar los que no tienen sucursal
        if ($sucursalId === '0') {
            $sucursalId = 0;
        }
        
        $data = $this->evidenciaService->getEvidenciasByFolio($folio, $sucursalId);
        
        return view('evidencia.index', $data);
    }

    /**
     * Guardar nueva evidencia (con sucursal opcional)
     */
    public function store(Request $request, $folio)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'descripcion' => 'nullable|string|max:500',
            'sucursal_id' => 'nullable|integer',
        ]);

        try {
            $evidencia = $this->evidenciaService->storeEvidencia(
                $folio,
                $request->file('imagen'),
                $request->descripcion,
                $request->sucursal_id,
                $request->asigna_id
            );

            return redirect()->back()->with('success', 'Evidencia subida correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Eliminar evidencia
     */
    public function destroy($id)
    {
        try {
            $this->evidenciaService->deleteEvidencia($id);
            return redirect()->back()->with('success', 'Evidencia eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar evidencia: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ NUEVO - Cambiar sucursal de una evidencia (AJAX)
     */
    public function cambiarSucursal(Request $request, $id)
    {
        $request->validate([
            'sucursal_id' => 'nullable|integer',
        ]);

        try {
            $evidencia = $this->evidenciaService->cambiarSucursal(
                $id, 
                $request->sucursal_id ?: null
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Sucursal actualizada correctamente',
                'evidencia' => [
                    'id' => $evidencia->id,
                    'sucursal_id' => $evidencia->sucursal_id,
                    'sucursal_nombre' => $evidencia->sucursal_nombre,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}