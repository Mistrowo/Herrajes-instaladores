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
    public function getEvidenciasByFolio(string $folio, ?int $sucursalId = null): array
    {
        $notaVenta = NotaVtaActualiza::where('nv_folio', $folio)->firstOrFail();
        
        // Obtener asignación
        $asignacion = Asigna::where('nota_venta', $folio)
            ->with('sucursal')
            ->first();
        
        // Obtener sucursales disponibles para este cliente
        $sucursales = collect();
        if ($notaVenta->nv_cliente) {
            $sucursales = $this->sucursalService->buscarSucursalesPorNombreCliente($notaVenta->nv_cliente);
        }

        // Obtener evidencias (filtrar por sucursal si se especifica)
        $query = EvidenciaFotografica::where('nota_venta', $folio)
            ->with(['sucursal', 'instalador'])
            ->orderBy('fecha_subida', 'desc')
            ->orderBy('created_at', 'desc');
        
        if ($sucursalId !== null) {
            if ($sucursalId == 0) {
                // Filtrar solo las que NO tienen sucursal
                $query->whereNull('sucursal_id');
            } else {
                // Filtrar por sucursal específica
                $query->where('sucursal_id', $sucursalId);
            }
        }
        
        $evidencias = $query->get();

        // Agrupar evidencias por sucursal
        $evidenciasPorSucursal = $evidencias->groupBy(function($evidencia) {
            return $evidencia->sucursal_id ?? 0;
        });

        // Estadísticas
        $totalEvidencias = $evidencias->count();
        $conSucursal = $evidencias->filter(fn($e) => $e->sucursal_id !== null)->count();
        $sinSucursal = $totalEvidencias - $conSucursal;

        return [
            'folio' => $folio,
            'notaVenta' => $notaVenta,
            'asignacion' => $asignacion,
            'evidencias' => $evidencias,
            'evidenciasPorSucursal' => $evidenciasPorSucursal,
            'sucursales' => $sucursales,
            'sucursalActual' => $sucursalId,
            'totalEvidencias' => $totalEvidencias,
            'conSucursal' => $conSucursal,
            'sinSucursal' => $sinSucursal,
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
        // Validar que la sucursal existe si se proporciona
        if ($sucursalId) {
            if (!$this->sucursalService->validarSucursalExiste($sucursalId)) {
                throw new \Exception('La sucursal seleccionada no existe o no está activa.');
            }
        }

        // Generar nombre único (siempre jpg tras comprimir)
        $nombreArchivo = time() . '_' . uniqid() . '.jpg';
        $rutaRelativa = "evidencias/{$folio}/{$nombreArchivo}";
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

        // Obtener instalador actual
        $instaladorId = Auth::check() ? Auth::id() : null;

        // Obtener asigna_id si no se proporciona
        if (!$asignaId) {
            $asignacion = Asigna::where('nota_venta', $folio)->first();
            $asignaId = $asignacion ? $asignacion->id : null;
        }

        // Crear registro
        return EvidenciaFotografica::create([
            'asigna_id' => $asignaId,
            'nota_venta' => $folio,
            'sucursal_id' => $sucursalId,
            'instalador_id' => $instaladorId,
            'imagen_path' => $path,
            'descripcion' => $descripcion,
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