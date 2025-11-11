<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HerrajeController;

Route::get('/herrajes/{folio}', [HerrajeController::class, 'showByFolio']);

    Route::prefix('api/herrajes')->group(function () {
        Route::put('/{herraje}', [HerrajeController::class, 'updateHeader']);
        Route::get('/{herraje}/items', [HerrajeController::class, 'items']);
        Route::post('/{herraje}/items', [HerrajeController::class, 'storeItem']);
        Route::put('/{herraje}/items/{item}', [HerrajeController::class, 'updateItem']);
        Route::delete('/{herraje}/items/{item}', [HerrajeController::class, 'destroyItem']);
    });
