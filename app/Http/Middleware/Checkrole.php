<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\Instalador $user */
        $user = auth()->user();

        foreach ($roles as $rol) {
            $hasRole = match($rol) {
                'admin' => $user->esAdmin(),
                'supervisor' => $user->esSupervisor(),
                'instalador' => $user->esInstalador(),
                default => false,
            };

            if ($hasRole) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a esta página.');
    }
}