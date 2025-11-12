@extends('layouts.dashboard')

@section('title', 'FFT - Ficha Técnica')

@section('page-title', 'Ficha Técnica (FFT)')
@section('page-subtitle', 'Visualización de documento técnico')

@section('content')
<div class="p-6">
    
    @if(!isset($fileUrl) || empty($fileUrl))
        <!-- Mensaje cuando NO hay archivo -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200 p-12">
            <div class="text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">FFT No Disponible</h3>
                <p class="text-gray-600 mb-6">
                    No se encontró la Ficha Técnica (FFT) para la Nota de Venta 
                    <strong class="text-blue-600">{{ $folio }}</strong>
                </p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Volver al Dashboard
                    </a>
                    <button onclick="window.close()" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Header con acciones -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-blue-100">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Folio</p>
                        <p class="text-lg font-bold text-gray-900">{{ $folio }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ $fileUrl }}" 
                       target="_blank"
                       download
                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar
                    </a>
                    
                    <button onclick="window.close()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Visor de Documento -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="relative w-full" style="height: calc(100vh - 280px); min-height: 500px;">
                <!-- Loading Overlay -->
                <div id="loading" class="absolute inset-0 bg-white flex items-center justify-center z-10">
                    <div class="text-center">
                        <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Cargando documento...</p>
                    </div>
                </div>
                
                <!-- Iframe Visor -->
                <iframe 
                    id="documentViewer"
                    src="{{ $fileUrl }}" 
                    class="w-full h-full border-0"
                    frameborder="0"
                    onload="hideLoading()"
                    onerror="handleError()"
                ></iframe>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-blue-800">
                        Ficha Técnica (FFT) asociada a la Nota de Venta <strong>{{ $folio }}</strong>
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function hideLoading() {
        const loading = document.getElementById('loading');
        if (loading) {
            loading.style.display = 'none';
        }
    }
    
    function handleError() {
        hideLoading();
        const viewer = document.getElementById('documentViewer');
        if (viewer) {
            viewer.style.display = 'none';
        }
        
        showAlert('error', 'No se pudo cargar el documento. Intenta descargarlo.');
        
        // Mostrar mensaje de error en el visor
        const container = viewer?.parentElement;
        if (container) {
            container.innerHTML = `
                <div class="flex items-center justify-center h-full p-12">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Error al cargar el documento</h3>
                        <p class="text-gray-500 mb-4">No se pudo visualizar el archivo. Intenta descargarlo.</p>
                        <a href="{{ $fileUrl ?? '#' }}" 
                           target="_blank"
                           download
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar archivo
                        </a>
                    </div>
                </div>
            `;
        }
    }
    
    // Auto-cerrar el loading después de 15 segundos
    setTimeout(() => {
        hideLoading();
    }, 15000);
    
    @if(!isset($fileUrl) || empty($fileUrl))
    // Mostrar alerta cuando no hay archivo
    window.addEventListener('load', function() {
        showAlert('warning', 'No se encontró el FFT para este folio');
    });
    @endif
</script>
@endpush
@endsection