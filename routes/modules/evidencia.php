<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvidenciaController;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        // === EVIDENCIA FOTOGRÁFICA ===
        Route::get('/evidencias/{folio}', [EvidenciaController::class, 'index'])
            ->name('evidencias.index')
            ->where('folio', '[0-9]+');

        Route::prefix('evidencias')->name('evidencias.')->group(function () {
            Route::post('/{folio}', [EvidenciaController::class, 'store'])
                ->name('store')
                ->where('folio', '[0-9]+');

            Route::delete('/{id}', [EvidenciaController::class, 'destroy'])
                ->name('destroy');

            // ⭐ AGREGAR ESTA RUTA QUE FALTA
            Route::patch('/{id}/sucursal', [EvidenciaController::class, 'cambiarSucursal'])
                ->name('cambiar-sucursal');
        });
    });