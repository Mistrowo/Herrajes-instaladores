<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HerrajeItem extends Model
{
    use SoftDeletes;

    protected $table = 'sh_herraje_items';

    protected $fillable = [
        'herraje_id',
        'codigo',
        'descripcion',
        'unidad',
        'cantidad',
        'precio',
        'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio'   => 'decimal:2',
    ];

    public function herraje()
    {
        return $this->belongsTo(Herraje::class, 'herraje_id');
    }
}
