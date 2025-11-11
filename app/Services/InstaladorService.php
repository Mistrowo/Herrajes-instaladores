<?php

namespace App\Services;

use App\Models\Instalador;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InstaladorService
{
    public function list(?string $q = null, int $perPage = 10, bool $withTrashed = false): LengthAwarePaginator
    {
        $query = Instalador::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        if ($q) {
            $qLike = "%{$q}%";
            $query->where(function ($sub) use ($qLike) {
                $sub->where('nombre', 'like', $qLike)
                    ->orWhere('usuario', 'like', $qLike)
                    ->orWhere('correo', 'like', $qLike)
                    ->orWhere('rut', 'like', $qLike)
                    ->orWhere('telefono', 'like', $qLike);
            });
        }

        return $query->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Instalador
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['activo'])) $data['activo'] = 'S';
            return Instalador::create($data);
        });
    }

    public function update(Instalador $instalador, array $data): Instalador
    {
        return DB::transaction(function () use ($instalador, $data) {
            if (empty($data['password'])) unset($data['password']); 
            $instalador->update($data);
            return $instalador;
        });
    }

    public function delete(Instalador $instalador): void
    {
        $instalador->delete();
    }

    public function restore(int $id): Instalador
    {
        $inst = Instalador::withTrashed()->findOrFail($id);
        $inst->restore();
        return $inst;
    }

    public function forceDelete(int $id): void
    {
        $inst = Instalador::withTrashed()->findOrFail($id);
        $inst->forceDelete();
    }

    public function toggleActivo(Instalador $instalador): Instalador
    {
        $instalador->activo = $instalador->activo === 'S' ? 'N' : 'S';
        $instalador->save();
        return $instalador;
    }
}
