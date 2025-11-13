<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChecklistController;
use App\Models\Ventas\Fasecomercialproyecto;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistema Herrajes Ilesa
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (solo autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Búsqueda y detalles de notas (AJAX)
    Route::get('/dashboard/buscar-notas', [DashboardController::class, 'buscarNotasVenta'])->name('dashboard.buscar-notas');
    Route::get('/dashboard/detalles-nv', [DashboardController::class, 'obtenerDetallesNV'])->name('dashboard.detalles-nv');

    // Descargar OC
    Route::get('/dashboard/descargar-oc/{folio}', function ($folio) {
        $fase = Fasecomercialproyecto::where('folio_nv', $folio)->first();

        if (!$fase || !$fase->oc_proveedores()) {
            abort(404, 'OC no encontrada');
        }

        $media = $fase->oc_proveedores();
        $url = "https://clientes.ohffice.cl/storage/{$media->id}/{$media->file_name}";

        return redirect()->away($url);
    })->name('dashboard.descargar-oc');

    // ⭐ Checklist - NUEVAS RUTAS
    Route::prefix('dashboard/checklist')->group(function () {
        Route::get('/{folio}', [ChecklistController::class, 'index'])->name('checklist.index');
        Route::post('/{folio}', [ChecklistController::class, 'store'])->name('checklist.store');
        Route::get('/{folio}/pdf', [ChecklistController::class, 'pdf'])->name('checklist.pdf');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
});