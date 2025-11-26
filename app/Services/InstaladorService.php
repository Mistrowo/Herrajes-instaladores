<?php

namespace App\Services;

use App\Models\Instalador;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class InstaladorService
{
    /**
     * Listar instaladores con filtros y paginación
     */
    public function list(string $q = '', int $perPage = 10, bool $withTrashed = false): LengthAwarePaginator
    {
        $query = Instalador::query();

        // Si se solicita ver eliminados
        if ($withTrashed) {
            $query->withTrashed();
        }

        // Filtro de búsqueda
        if (!empty($q)) {
            $query->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('usuario', 'like', "%{$q}%")
                      ->orWhere('correo', 'like', "%{$q}%")
                      ->orWhere('rut', 'like', "%{$q}%")
                      ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        // Ordenar por nombre
        $query->orderBy('nombre', 'asc');

        // Paginar y mantener los filtros en la URL
        return $query->paginate($perPage)->appends([
            'q' => $q,
            'per_page' => $perPage,
            'withTrashed' => $withTrashed ? 1 : 0
        ]);
    }

    /**
     * Crear instalador
     */
    public function create(array $data): Instalador
    {
        $data['password'] = Hash::make($data['password']);
        return Instalador::create($data);
    }

    /**
     * Actualizar instalador
     */
    public function update(Instalador $instalador, array $data): bool
    {
        // Si se proporciona una nueva contraseña, hashearla
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Si no se proporciona, no actualizar el campo
            unset($data['password']);
        }

        return $instalador->update($data);
    }

    /**
     * Eliminar instalador (soft delete)
     */
    public function delete(Instalador $instalador): bool
    {
        return $instalador->delete();
    }

    /**
     * Cambiar estado activo/inactivo
     */
    public function toggleActivo(Instalador $instalador): bool
    {
        $instalador->activo = $instalador->activo === 'S' ? 'N' : 'S';
        return $instalador->save();
    }

    /**
     * Restaurar instalador eliminado
     */
    public function restore(int $id): bool
    {
        $instalador = Instalador::withTrashed()->findOrFail($id);
        return $instalador->restore();
    }

    /**
     * Eliminar permanentemente
     */
    public function forceDelete(int $id): bool
    {
        $instalador = Instalador::withTrashed()->findOrFail($id);
        return $instalador->forceDelete();
    }
}