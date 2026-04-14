<?php

namespace App\Services;

use App\Models\EvidenciaFotografica;
use App\Models\NotaVtaActualiza;
use App\Models\Asigna;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EvidenciaService
{
    protected SucursalService $sucursalService;

    public function __construct(SucursalService $sucursalService)
    {
        $this->sucursalService = $sucursalService;
    }

    /**
     * Obtener todas las evidencias de un folio con sucursales disponibles
     */
    public function getEvidenciasByFolio(string $folio): array
    {
        $notaVenta = NotaVtaActualiza::where('nv_folio', $folio)->firstOrFail();

        $asignacion = Asigna::where('nota_venta', $folio)->with('sucursal')->first();

        $evidencias = EvidenciaFotografica::where('nota_venta', $folio)
            ->with('instalador')
            ->orderBy('fecha_subida', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'folio' => $folio,
            'notaVenta' => $notaVenta,
            'asignacion' => $asignacion,
            'evidencias' => $evidencias,
            'lugarDespacho' => $asignacion?->sucursal?->nombre ?? $notaVenta->nv_lugardespacho,
            'totalEvidencias' => $evidencias->count(),
        ];
    }

    /**
     * Guardar nueva evidencia (con sucursal opcional)
     */
    public function storeEvidencia(
        string $folio, 
        UploadedFile $imagen, 
        ?string $descripcion = null,
        ?int $sucursalId = null,
        ?int $asignaId = null
    ): EvidenciaFotografica {
        // Obtener instalador actual
        $instaladorId = Auth::check() ? Auth::id() : null;

        // Obtener asigna_id y sucursal_id si no se proporcionan
        if (!$asignaId) {
            $asignacion = Asigna::where('nota_venta', $folio)->first();
            $asignaId = $asignacion?->id;
            $sucursalId ??= $asignacion?->sucursal_id;
        }

        // Generar nombre único (siempre jpg tras comprimir)
        $nombreArchivo = time() . '_' . uniqid() . '.jpg';
        $carpeta = $sucursalId
            ? "evidencias/{$folio}/{$sucursalId}"
            : "evidencias/{$folio}";
        $rutaRelativa = "{$carpeta}/{$nombreArchivo}";
        $rutaAbsoluta = storage_path("app/public/{$rutaRelativa}");

        // Crear directorio si no existe
        if (!file_exists(dirname($rutaAbsoluta))) {
            mkdir(dirname($rutaAbsoluta), 0755, true);
        }

        // Comprimir y redimensionar (max 1200px ancho, calidad 75%)
        $manager = new ImageManager(new Driver());
        $manager->read($imagen->getRealPath())
            ->scaleDown(width: 1200)
            ->toJpeg(quality: 75)
            ->save($rutaAbsoluta);

        $path = $rutaRelativa;

        // Crear registro
        return EvidenciaFotografica::create([
            'asigna_id'    => $asignaId,
            'nota_venta'   => $folio,
            'sucursal_id'  => $sucursalId,
            'instalador_id'=> $instaladorId,
            'imagen_path'  => $path,
            'descripcion'  => $descripcion,
            'fecha_subida' => now(),
        ]);
    }

    /**
     * Eliminar evidencia
     */
    public function deleteEvidencia(int $id): bool
    {
        $evidencia = EvidenciaFotografica::findOrFail($id);
        return $evidencia->delete();
    }

    /**
     * ⭐ NUEVO - Cambiar sucursal de una evidencia
     */
    public function cambiarSucursal(int $evidenciaId, ?int $sucursalId): EvidenciaFotografica
    {
        $evidencia = EvidenciaFotografica::findOrFail($evidenciaId);
        
        if ($sucursalId && !$this->sucursalService->validarSucursalExiste($sucursalId)) {
            throw new \Exception('La sucursal seleccionada no existe o no está activa.');
        }
        
        $evidencia->sucursal_id = $sucursalId;
        $evidencia->save();
        
        return $evidencia->fresh('sucursal');
    }

    /**
     * Obtener estadísticas
     */
    public function getEstadisticas(string $folio): array
    {
        $total = EvidenciaFotografica::where('nota_venta', $folio)->count();
        $conSucursal = EvidenciaFotografica::where('nota_venta', $folio)
            ->whereNotNull('sucursal_id')
            ->count();
        $sinSucursal = $total - $conSucursal;

        return [
            'total' => $total,
            'con_sucursal' => $conSucursal,
            'sin_sucursal' => $sinSucursal,
        ];
    }
}