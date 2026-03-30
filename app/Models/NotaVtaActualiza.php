<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaVtaActualiza extends Model
{
    protected $connection = 'sqlsrv_soft';

    protected $table = 'NotaVta_Actualiza';

    protected $primaryKey = 'nv_id';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nv_id',
        'nv_folio',
        'nv_descripcion',
        'nv_cliente',
        'nv_vend',
        'nv_estado',
        'nv_femision',
        'nv_fentrega',
        'nv_lugardespacho',
    ];

    protected $casts = [
        'nv_femision' => 'date',
        'nv_fentrega' => 'date',
    ];

    /**
     * Scope para buscar por folio o cliente
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            if (is_numeric($termino)) {
                $q->where('nv_folio', $termino);
            } else {
                $q->where('nv_cliente', 'like', '%' . $termino . '%')
                  ->orWhere('nv_descripcion', 'like', '%' . $termino . '%');
            }
        });
    }

    /**
     * Scope para filtrar por folio
     */
    public function scopeFolio($query, $folio)
    {
        return $query->where('nv_folio', $folio);
    }

    /**
     * Scope para filtrar por cliente
     */
    public function scopeCliente($query, $cliente)
    {
        return $query->where('nv_cliente', 'like', '%' . $cliente . '%');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('nv_estado', $estado);
    }

    /**
     * Obtener folio formateado
     */
    public function getFolioFormateadoAttribute(): string
    {
        return 'NV-' . str_pad($this->nv_folio, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener fecha de emisión formateada
     */
    public function getFechaEmisionFormateadaAttribute(): string
    {
        return $this->nv_femision ? $this->nv_femision->format('d-m-Y') : '-';
    }

    /**
     * Obtener fecha de entrega formateada
     */
    public function getFechaEntregaFormateadaAttribute(): string
    {
        return $this->nv_fentrega ? $this->nv_fentrega->format('d-m-Y') : '-';
    }
}