<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureInstaladorIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si hay un usuario autenticado
        if (Auth::check()) {
            $instalador = Auth::user();
            
            // Verificar si el instalador está activo
            if ($instalador->activo !== 'S') {
                // Cerrar sesión si está inactivo
                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Su cuenta ha sido desactivada. Contacte al administrador.']);
            }
        }

        return $next($request);
    }
}