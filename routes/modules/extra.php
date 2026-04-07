<?php

use Illuminate\Support\Facades\Route;
use App\Services\DocumentoExternoService;

Route::middleware(['auth'])
    ->prefix('dashboard')                
    ->group(function () {

    // Vista FFT
    Route::get('/fft/{folio}', function ($folio, DocumentoExternoService $service) {
        $archivos = $service->obtenerFft($folio, request('lugar_despacho'));

        return view('fft.index', [
            'folio' => $folio,
            'archivos' => $archivos,
            'tieneDocumentos' => !empty($archivos)
        ]);
    })->name('fft.index');

    // Vista OC
    Route::get('/oc/{folio}', function ($folio, DocumentoExternoService $service) {
        $archivos = $service->obtenerOc($folio, request('lugar_despacho'));

        return view('oc.index', [
            'folio' => $folio,
            'archivos' => $archivos,
            'tieneDocumentos' => !empty($archivos)
        ]);
    })->name('oc.index');
});