<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MisAsignacionesController;

Route::middleware(['auth'])
    ->prefix('mis-asignaciones')
    ->as('mis-asignaciones.')
    ->group(function () {

        Route::get('/',                [MisAsignacionesController::class, 'index'])->name('index');
        Route::get('/{id}',            [MisAsignacionesController::class, 'show'])->name('show');

        Route::patch('/{id}/aceptar',   [MisAsignacionesController::class, 'aceptar'])->name('aceptar');
        Route::patch('/{id}/rechazar',  [MisAsignacionesController::class, 'rechazar'])->name('rechazar');
        Route::patch('/{id}/en-proceso',[MisAsignacionesController::class, 'enProceso'])->name('en-proceso');
        Route::patch('/{id}/completar', [MisAsignacionesController::class, 'completar'])->name('completar');
    });
