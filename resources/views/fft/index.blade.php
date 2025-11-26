@extends('layouts.dashboard')

@section('title', 'Proyectos - Documentos Asociados')
@section('page-title', 'Documentos Asociados')
@section('page-subtitle', 'Visualización de documentos técnicos')

@section('content')
<div class="p-6">
    
    @if(!$tieneDocumentos)
        <!-- Mensaje cuando NO hay archivo -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200 p-12">
            <div class="text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Documentos No Disponibles</h3>
                <p class="text-gray-600 mb-6">
                    No se encontró documentos asociados para la Nota de Venta 
                    <strong class="text-blue-600">{{ $folio }}</strong>
                </p>
                <div class="flex items-center justify-center gap-3">
                  
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
        <!-- Header con información del folio -->
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
                        <p class="text-xs text-gray-500">{{ count($archivos) }} documento(s) disponible(s)</p>
                    </div>
                </div>
                
                <button onclick="window.close()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cerrar Ventana
                </button>
            </div>
        </div>

        <!-- Tabla de Archivos Disponibles -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Archivos  Disponibles
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre del Archivo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tamaño
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($archivos as $index => $archivo)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $archivo['nombre'] }}</p>
                                        <p class="text-xs text-gray-500">Documentos Asociados</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">
                                    {{ number_format($archivo['size'] / 1024, 2) }} KB
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper(pathinfo($archivo['nombre'], PATHINFO_EXTENSION)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="verVistaPrevia('{{ $archivo['url'] }}', '{{ $archivo['nombre'] }}')" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-all shadow-sm hover:shadow-md"
                                            title="Ver vista previa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </button>
                                    
                                    <a href="{{ $archivo['url'] }}" 
                                       target="_blank"
                                       download
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg font-medium transition-all shadow-sm hover:shadow-md"
                                       title="Descargar archivo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Descargar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-blue-800">
                        <strong>Documentos asociados</strong> a la Nota de Venta <strong>{{ $folio }}</strong>. 
                        Haz clic en "Ver" para visualizar el documento en pantalla completa.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- Modal Vista Previa -->
<div id="modalVistaPrevia" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
    <div class="relative w-full h-full max-w-7xl max-h-[95vh] flex flex-col">
        <!-- Header del Modal -->
        <div class="bg-white rounded-t-xl px-6 py-4 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Vista Previa</h3>
                    <p class="text-sm text-gray-600" id="nombreArchivoPreview">-</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a id="descargarDesdePreview" href="#" 
                   target="_blank"
                   download
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </a>
                
                <button onclick="cerrarVistaPrevia()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cerrar
                </button>
            </div>
        </div>
        
        <!-- Visor del Documento -->
        <div class="flex-1 bg-white rounded-b-xl shadow-2xl overflow-hidden relative">
            <!-- Loading Overlay -->
            <div id="loadingPreview" class="absolute inset-0 bg-white flex items-center justify-center z-10">
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
                id="iframePreview"
                src=""
                class="w-full h-full border-0"
                frameborder="0"
            ></iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verVistaPrevia(url, nombre) {
        // Mostrar modal
        document.getElementById('modalVistaPrevia').classList.remove('hidden');
        
        // Actualizar información
        document.getElementById('nombreArchivoPreview').textContent = nombre;
        document.getElementById('descargarDesdePreview').href = url;
        
        // Mostrar loading
        document.getElementById('loadingPreview').style.display = 'flex';
        
        // Cargar documento
        const iframe = document.getElementById('iframePreview');
        iframe.src = url;
        
        // Ocultar loading cuando cargue
        iframe.onload = function() {
            document.getElementById('loadingPreview').style.display = 'none';
        };
        
        // Auto-cerrar loading después de 15 segundos
        setTimeout(() => {
            document.getElementById('loadingPreview').style.display = 'none';
        }, 15000);
    }
    
    function cerrarVistaPrevia() {
        document.getElementById('modalVistaPrevia').classList.add('hidden');
        
        // Limpiar iframe
        const iframe = document.getElementById('iframePreview');
        iframe.src = '';
    }
    
    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarVistaPrevia();
        }
    });
    
    // Cerrar con click en fondo oscuro
    document.getElementById('modalVistaPrevia').addEventListener('click', (e) => {
        if (e.target.id === 'modalVistaPrevia') {
            cerrarVistaPrevia();
        }
    });
</script>
@endpush
@endsection