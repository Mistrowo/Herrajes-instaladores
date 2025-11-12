<?php

namespace App\Services;

use App\Models\Asigna;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChecklistService
{
    /**
     * Obtener asignación y checklist por folio
     */
    public function getByFolio(int $folio): array
    {
        $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();
        $checklist = Checklist::where('asigna_id', $asignacion->id)->first();

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

            // Obtener asignación
            $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();
            
            // Obtener instalador autenticado
            $instalador = Auth::user();

            // Limpiar datos: remover _token y otros campos no necesarios
            $cleanData = $this->cleanData($data);

            // Crear o actualizar checklist
            $checklist = Checklist::updateOrCreate(
                ['asigna_id' => $asignacion->id],
                array_merge($cleanData, [
                    'nota_venta' => $folio,
                    'instalador_id' => $instalador->id,
                    'fecha_completado' => now(),
                ])
            );

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
        // Remover campos que no son parte del modelo
        $fieldsToRemove = ['_token', '_method'];
        
        foreach ($fieldsToRemove as $field) {
            unset($data[$field]);
        }

        // Convertir valores vacíos a null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
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
            'completed_at' => $checklist->fecha_completado?->format('d/m/Y H:i'),
        ];
    }
}