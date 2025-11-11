<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsignarController;


Route::middleware(['auth'])->group(function () {

    Route::prefix('asignar')->as('asignar.')->group(function () {
        Route::get('/', [AsignarController::class, 'index'])->name('index');
        
        Route::get('/crear', [AsignarController::class, 'create'])->name('create');
        Route::post('/', [AsignarController::class, 'store'])->name('store');
        
        Route::get('/{id}', [AsignarController::class, 'show'])->name('show');
        
        Route::get('/{id}/editar', [AsignarController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AsignarController::class, 'update'])->name('update');
        
        Route::delete('/{id}', [AsignarController::class, 'destroy'])->name('destroy');
        
        Route::patch('/{id}/estado/{estado}', [AsignarController::class, 'cambiarEstado'])->name('cambiar-estado');
        
        Route::post('/buscar-notas-venta', [AsignarController::class, 'buscarNotasVenta'])->name('buscar-notas-venta');
        
        Route::post('/verificar-asignacion', [AsignarController::class, 'verificarAsignacion'])->name('verificar-asignacion');
    });

    
  
});