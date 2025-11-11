<?php

namespace App\Services;

use App\Models\Asigna;
use App\Models\EvidenciaFotografica;
use App\Models\NotaVtaActualiza;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EvidenciaService
{
    public function getEvidenciasByFolio(int $folio)
    {
        $nota = NotaVtaActualiza::where('nv_folio', $folio)->firstOrFail();
        $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();

        $evidencias = EvidenciaFotografica::where('asigna_id', $asignacion->id)
            ->with('instalador')
            ->orderByDesc('fecha_subida')
            ->get();

        return compact('nota', 'asignacion', 'evidencias');
    }

    public function storeEvidencia(int $folio, UploadedFile $imagen, ?string $descripcion = null): EvidenciaFotografica
    {
        $asignacion = Asigna::where('nota_venta', $folio)->firstOrFail();
        $instalador = Auth::user(); // auth normal

        $path = $imagen->store("evidencias/{$folio}", 'public');

        return EvidenciaFotografica::create([
            'asigna_id' => $asignacion->id,
            'nota_venta' => $folio,
            'instalador_id' => $instalador->id,
            'imagen_path' => $path,
            'descripcion' => $descripcion,
        ]);
    }

    public function deleteEvidencia(int $id): bool
    {
        $evidencia = EvidenciaFotografica::findOrFail($id);

        if (Storage::disk('public')->exists($evidencia->imagen_path)) {
            Storage::disk('public')->delete($evidencia->imagen_path);
        }

        return $evidencia->delete();
    }
}