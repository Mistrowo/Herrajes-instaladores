<?php

namespace App\Services;

use App\Models\Asigna;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChecklistService
{
    protected SucursalService $sucursalService;

    public function __construct(SucursalService $sucursalService)
    {
        $this->sucursalService = $sucursalService;
    }

    /**
     * Obtener asignación y checklist por folio
     */
    public function getByFolio(int $folio): array
    {
        $asignacion = Asigna::where('nota_venta', $folio)
            ->with('sucursal')
            ->firstOrFail();
            
        $checklist = Checklist::where('asigna_id', $asignacion->id)
            ->with('sucursal')
            ->first();

        return [
            'asignacion' => $asignacion,
            'checklist' => $checklist
        ];
    }

    /**
     * Guardar o actualizar checklist
     */
    public function storeOrUpdate(int $folio, array $data): Checklist
    {
        try {
            DB::beginTransaction();

            Log::info('ChecklistService: storeOrUpdate - INICIO', [
                'folio' => $folio,
                'data_keys' => array_keys($data),
                'sucursal_id' => $data['sucursal_id'] ?? null
            ]);

            // Obtener asignación
            $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();
            
            // Obtener instalador autenticado
            $instalador = Auth::user();

            // Limpiar datos
            $cleanData = $this->cleanData($data);

            Log::info('ChecklistService: Datos limpios', [
                'clean_data_keys' => array_keys($cleanData),
                'sucursal_id' => $cleanData['sucursal_id'] ?? null
            ]);

            // Crear o actualizar checklist
            $checklist = Checklist::updateOrCreate(
                ['asigna_id' => $asignacion->id],
                array_merge($cleanData, [
                    'nota_venta' => $folio,
                    'instalador_id' => $instalador->id,
                    'sucursal_id' => $cleanData['sucursal_id'] ?? $asignacion->sucursal_id ?? null, // ⭐ IMPORTANTE
                    'fecha_completado' => now(),
                ])
            );

            Log::info('ChecklistService: Checklist guardado', [
                'checklist_id' => $checklist->id,
                'sucursal_id' => $checklist->sucursal_id
            ]);

            DB::commit();

            return $checklist;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al guardar checklist', [
                'folio' => $folio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Limpiar datos antes de guardar
     */
    private function cleanData(array $data): array
    {
        $fieldsToRemove = ['_token', '_method'];
        
        foreach ($fieldsToRemove as $field) {
            unset($data[$field]);
        }

        foreach ($data as $key => $value) {
            if ($value === '' && $key !== 'sucursal_id') {
                $data[$key] = null;
            }
        }

        if (isset($data['sucursal_id']) && $data['sucursal_id'] === '') {
            $data['sucursal_id'] = null;
        }

        return $data;
    }

    /**
     * Verificar si el checklist está completo
     */
    public function isComplete(Checklist $checklist): bool
    {
        return $checklist->getCompletionPercentage() === 100;
    }

    /**
     * Obtener estadísticas del checklist
     */
    public function getStats(Checklist $checklist): array
    {
        return [
            'completion' => $checklist->getCompletionPercentage(),
            'has_errors' => $checklist->hasAnyErrors(),
            'error_count' => $checklist->countErrors(),
            'sucursal_nombre' => $checklist->sucursal_nombre, 
            'completed_at' => $checklist->fecha_completado?->format('d/m/Y H:i'),
        ];
    }
}