<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Instalador extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    
    protected $table = 'sh_instalador';

    
    protected $fillable = [
        'usuario',
        'nombre',
        'telefono',
        'correo',
        'rut',
        'password',
        'activo',
        'rol',
    ];

    
    protected $hidden = [
        'password',
    ];

   
    protected $casts = [
        'activo' => 'string',
    ];

    
    public function setPasswordAttribute($value): void
    {
        if (blank($value)) {
            unset($this->attributes['password']);
            return;
        }

        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

    /* ===========================================================
     |  SCOPES
     =========================================================== */

    public function scopeActivo($query)
    {
        return $query->where('activo', 'S');
    }

    public function scopeInactivo($query)
    {
        return $query->where('activo', 'N');
    }

    public function scopeRol($query, $rol)
    {
        return $query->where('rol', $rol);
    }

    /* ===========================================================
     |  HELPERS
     =========================================================== */

    public function estaActivo(): bool
    {
        return $this->activo === 'S';
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esSupervisor(): bool
    {
        return $this->rol === 'supervisor';
    }

    public function esInstalador(): bool
    {
        return $this->rol === 'instalador';
    }

    /* ===========================================================
     |  RELACIONES
     =========================================================== */

    public function asignacionesComoAsignado1()
    {
        return $this->hasMany(Asigna::class, 'asignado1');
    }

    public function asignacionesComoAsignado2()
    {
        return $this->hasMany(Asigna::class, 'asignado2');
    }

    public function asignacionesComoAsignado3()
    {
        return $this->hasMany(Asigna::class, 'asignado3');
    }

    public function asignacionesComoAsignado4()
    {
        return $this->hasMany(Asigna::class, 'asignado4');
    }

    public function todasLasAsignaciones()
    {
        return Asigna::where('asignado1', $this->id)
            ->orWhere('asignado2', $this->id)
            ->orWhere('asignado3', $this->id)
            ->orWhere('asignado4', $this->id)
            ->get();
    }

    /* ===========================================================
     |  ACCESORS
     =========================================================== */

    public function getRutFormateadoAttribute(): string
    {
        $rut = str_replace(['.', '-'], '', (string) $this->rut);
        if ($rut === '') return '';
        $dv = substr($rut, -1);
        $numero = substr($rut, 0, -1);
        return number_format((int) $numero, 0, '', '.') . '-' . $dv;
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} ({$this->usuario})";
    }
}
