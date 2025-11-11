<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Instalador;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para verificar si es administrador
        Gate::define('admin-only', function (Instalador $instalador) {
            return $instalador->esAdmin();
        });

        // Gate para verificar si es supervisor
        Gate::define('supervisor-only', function (Instalador $instalador) {
            return $instalador->esSupervisor();
        });

        // Gate para verificar si es instalador
        Gate::define('instalador-only', function (Instalador $instalador) {
            return $instalador->esInstalador();
        });

        // Gate para admin o supervisor
        Gate::define('admin-or-supervisor', function (Instalador $instalador) {
            return $instalador->esAdmin() || $instalador->esSupervisor();
        });
    }
}