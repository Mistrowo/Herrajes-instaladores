<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HerrajeController;

Route::middleware(['auth'])
    ->prefix('dashboard')                
    ->group(function () {

        Route::get('/herrajes/{folio}', [HerrajeController::class, 'showByFolio'])
            ->name('herrajes.show')
            ->where('folio', '[0-9]+');

        Route::prefix('herrajes/api')->name('herrajes.api.')->group(function () {
    Route::put('/{herraje}', [HerrajeController::class, 'updateHeader'])->name('update-header');
    Route::get('/{herraje}/items', [HerrajeController::class, 'items'])->name('items');
    Route::get('/{herraje}/items-agrupados', [HerrajeController::class, 'itemsAgrupados'])->name('items-agrupados'); // ⭐ NUEVA
    Route::post('/{herraje}/items', [HerrajeController::class, 'storeItem'])->name('store-item');
    Route::put('/{herraje}/items/{item}', [HerrajeController::class, 'updateItem'])->name('update-item');
    Route::delete('/{herraje}/items/{item}', [HerrajeController::class, 'destroyItem'])->name('destroy-item');
});
});
