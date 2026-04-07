<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LugarDespacho extends Model
{
    protected $connection = 'sqlsrv_soft';

    protected $table = 'CW_TABLDESPACHO';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'cw_codaux',
        'cw_codldespacho',
        'cw_nomldespacho',
        'cw_llave',
    ];

    /**
     * Obtener los lugares de despacho de un cliente por codaux (RUT)
     */
    public static function porCodAux(string $codAux): \Illuminate\Support\Collection
    {
        return self::select('cw_codldespacho', 'cw_nomldespacho')
            ->whereRaw("RTRIM(cw_codaux) = ?", [trim($codAux)])
            ->orderBy('cw_codldespacho')
            ->get();
    }

    /**
     * Fallback: obtener por nombre de cliente (cuando nv_codaux aún es null)
     */
    public static function porNombreCliente(string $nombreCliente): \Illuminate\Support\Collection
    {
        return self::select('cw_codldespacho', 'cw_nomldespacho')
            ->whereIn('cw_codaux', function ($query) use ($nombreCliente) {
                $query->select('codaux')
                    ->from('NotaVta_cliente')
                    ->whereRaw("RTRIM(nomaux) = ?", [trim($nombreCliente)]);
            })
            ->orderBy('cw_codldespacho')
            ->get();
    }
}
