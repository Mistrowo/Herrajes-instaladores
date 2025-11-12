<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Fasecomercialproyecto extends Model implements HasMedia
{
    use InteractsWithMedia;

    // Conexión a la BD de ventas
    protected $connection = 'ventas';

    // Tabla exacta en portal-clientes
    protected $table = 'fasecomercialproyectos';

    // Timestamps (ajusta si tu tabla no los tiene)
    public $timestamps = true;

    // Permite asignación masiva
    protected $guarded = [];

    /**
     * Accede al archivo de la Orden de Compra (colección: oc_proveedores)
     */
    public function oc_proveedores()
    {
        return $this->getFirstMedia('oc_proveedores');
    }
}