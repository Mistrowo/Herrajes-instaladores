<?php

use App\Http\Controllers\ChecklistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/checklist/{folio}', [ChecklistController::class, 'index'])
            ->name('checklist.index')
            ->where('folio', '[0-9]+');

        Route::post('/checklist/{folio}', [ChecklistController::class, 'store'])
            ->name('checklist.store')
            ->where('folio', '[0-9]+');

        Route::get('/checklist/{folio}/pdf', [ChecklistController::class, 'downloadPdf'])
            ->name('checklist.pdf')
            ->where('folio', '[0-9]+');

    });