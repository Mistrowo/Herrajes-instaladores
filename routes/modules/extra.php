<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

Route::middleware(['auth'])
    ->prefix('dashboard')                
    ->group(function () {

    Route::get('/fft/{folio}', function ($folio) {
        try {
            $folioLimpio = preg_replace('/[^0-9]/', '', $folio);
            
            $response = Http::timeout(10)->get("https://clientes.ohffice.cl/api/descargar-fft/{$folioLimpio}");
            
            if ($response->failed() || $response->status() === 404) {
                return view('fft.index', [
                    'folio' => $folio,
                    'fileUrl' => null
                ]);
            }
            
            $fileUrl = "https://clientes.ohffice.cl/api/descargar-fft/{$folioLimpio}";
            
            return view('fft.index', compact('folio', 'fileUrl'));
            
        } catch (\Exception $e) {
            Log::error('Error al obtener FFT', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);
            
            return view('fft.index', [
                'folio' => $folio,
                'fileUrl' => null
            ]);
        }
    })->name('fft.index');

    // Vista OC
    Route::get('/oc/{folio}', function ($folio) {
        try {
            $folioLimpio = preg_replace('/[^0-9]/', '', $folio);
            
            $response = Http::timeout(10)->get("https://clientes.ohffice.cl/api/descargar-oc/{$folioLimpio}");
            
            if ($response->failed() || $response->status() === 404) {
                return view('oc.index', [
                    'folio' => $folio,
                    'fileUrl' => null
                ]);
            }
            
            $fileUrl = "https://clientes.ohffice.cl/api/descargar-oc/{$folioLimpio}";
            
            return view('oc.index', compact('folio', 'fileUrl'));
            
        } catch (\Exception $e) {
            Log::error('Error al obtener OC', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);
            
            return view('oc.index', [
                'folio' => $folio,
                'fileUrl' => null
            ]);
        }
    })->name('oc.index');
});