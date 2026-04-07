<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocumentoExternoService
{
    private const BASE_URL = 'https://clientes.ohffice.cl/api';
    private const TIMEOUT = 10;

    /**
     * Obtener TODOS los FFT de un folio
     */
    public function obtenerFft(string $folio, ?string $lugarDespacho = null): array
    {
        try {
            $folioLimpio = preg_replace('/[^0-9]/', '', $folio);

            $params = $lugarDespacho ? ['lugar_despacho' => $lugarDespacho] : [];
            $response = Http::timeout(self::TIMEOUT)->get(self::BASE_URL . "/fft/{$folioLimpio}", $params);

            if ($response->successful()) {
                $data = $response->json();
                return $data['archivos'] ?? [];
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Error al obtener FFT', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Obtener TODAS las OC de un folio
     */
    public function obtenerOc(string $folio, ?string $lugarDespacho = null): array
    {
        try {
            $folioLimpio = preg_replace('/[^0-9]/', '', $folio);

            $params = $lugarDespacho ? ['lugar_despacho' => $lugarDespacho] : [];
            $response = Http::timeout(self::TIMEOUT)->get(self::BASE_URL . "/oc/{$folioLimpio}", $params);

            if ($response->successful()) {
                $data = $response->json();
                return $data['archivos'] ?? [];
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Error al obtener OC', [
                'folio' => $folio,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}