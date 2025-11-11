<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Herraje extends Model
{
    use SoftDeletes;

    protected $table = 'sh_herraje';

    protected $fillable = [
        'nv_folio',
        'asigna_id',
        'instalador_id',
        'estado',
        'items_count',
        'total_estimado',
        'observaciones',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_estimado' => 'decimal:2',
        'items_count'    => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(HerrajeItem::class, 'herraje_id');
    }

    public function asigna()
    {
        return $this->belongsTo(Asigna::class, 'asigna_id');
    }

    public function instalador()
    {
        return $this->belongsTo(Instalador::class, 'instalador_id');
    }

    public function recalcularTotales(): void
    {
        $total = $this->items()->whereNull('deleted_at')->get()
            ->sum(function ($i) {
                $precio = $i->precio ?? 0;
                return $precio * (float)$i->cantidad;
            });

        $this->items_count   = $this->items()->whereNull('deleted_at')->count();
        $this->total_estimado = $total ?: null;
        $this->save();
    }
}
