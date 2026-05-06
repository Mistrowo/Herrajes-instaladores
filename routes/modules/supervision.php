<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupervisionController;

Route::middleware(['auth', 'role:admin,supervisor'])->group(function () {

    Route::prefix('supervision')->as('supervision.')->group(function () {
        Route::get('/', [SupervisionController::class, 'index'])->name('index');
        Route::get('/{id}', [SupervisionController::class, 'show'])->name('show');
    });

});
