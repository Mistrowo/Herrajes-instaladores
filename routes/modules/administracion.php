<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministracionController;

Route::middleware(['auth'])->group(function () {

   
    Route::prefix('administracion/instaladores')->as('administracion.instaladores.')->group(function () {
        Route::get('/', [AdministracionController::class, 'instaladoresIndex'])->name('index');
        Route::get('/crear', [AdministracionController::class, 'instaladoresCreate'])->name('create');
        Route::post('/', [AdministracionController::class, 'instaladoresStore'])->name('store');
        Route::get('/{instalador}/editar', [AdministracionController::class, 'instaladoresEdit'])->name('edit');
        Route::put('/{instalador}', [AdministracionController::class, 'instaladoresUpdate'])->name('update');
        Route::delete('/{instalador}', [AdministracionController::class, 'instaladoresDestroy'])->name('destroy');

        // Extra
        Route::patch('/{instalador}/toggle-activo', [AdministracionController::class, 'instaladoresToggleActivo'])
            ->name('toggle-activo');
        Route::post('/{id}/restore', [AdministracionController::class, 'instaladoresRestore'])->name('restore');
        Route::delete('/{id}/force-delete', [AdministracionController::class, 'instaladoresForceDelete'])->name('force-delete');
    });
});