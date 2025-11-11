<?php

namespace App\Services;

use App\Models\Instalador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     *
     * @param array $credentials
     * @return Instalador
     * @throws ValidationException
     */
    public function login(array $credentials): Instalador
    {
        $instalador = Instalador::where('correo', $credentials['email'])->first();

        if (!$instalador) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros.'],
            ]);
        }

        if ($instalador->activo !== 'S') {
            throw ValidationException::withMessages([
                'email' => ['Su cuenta está inactiva. Contacte al administrador.'],
            ]);
        }

        if (!Hash::check($credentials['password'], $instalador->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        Auth::login($instalador, $credentials['remember'] ?? false);

        request()->session()->regenerate();

        return $instalador;
    }

    /**
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     *
     * @param array $credentials
     * @return bool
     */
    public function verificarCredenciales(array $credentials): bool
    {
        $instalador = Instalador::where('correo', $credentials['email'])
            ->where('activo', 'S')
            ->first();

        if (!$instalador) {
            return false;
        }

        return Hash::check($credentials['password'], $instalador->password);
    }

    /**
     *
     * @return Instalador|null
     */
    public function obtenerUsuarioAutenticado(): ?Instalador
    {
        return Auth::user();
    }

    /**
     *
     * @return bool
     */
    public function estaAutenticado(): bool
    {
        return Auth::check();
    }

    /**
     *
     * @param Instalador $instalador
     * @return void
     */
    public function registrarUltimoAcceso(Instalador $instalador): void
    {
        $instalador->update([
            'ultimo_acceso' => now(),
        ]);
    }
}