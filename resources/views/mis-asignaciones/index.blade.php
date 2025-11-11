@extends('layouts.dashboard')

@section('title', 'Mis Asignaciones')

@section('page-title', 'Mis Asignaciones')
@section('page-subtitle', 'Gestiona tus asignaciones de instalación')

@section('content')
<div class="p-6">
    
    <!-- Alertas de sesión -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('success', '{{ session('success') }}');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('error', '{{ session('error') }}');
            });
        </script>
    @endif

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $estadisticas['pendientes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Aceptadas</p>
                    <p class="text-2xl font-bold text-green-900">{{ $estadisticas['aceptadas'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">En Proceso</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $estadisticas['en_proceso'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['completadas'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros compactos -->
    <div class="mb-4 bg-white rounded-lg shadow-sm border border-gray-200 p-3">
        <form method="GET" action="{{ route('mis-asignaciones.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="w-48">
                <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ ($filtros['estado'] ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aceptada" {{ ($filtros['estado'] ?? '') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                    <option value="rechazada" {{ ($filtros['estado'] ?? '') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="en_proceso" {{ ($filtros['estado'] ?? '') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada" {{ ($filtros['estado'] ?? '') == 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            
            <div class="w-44">
                <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Desde</label>
                <input type="date" name="fecha_desde" value="{{ $filtros['fecha_desde'] ?? '' }}" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div class="w-44">
                <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ $filtros['fecha_hasta'] ?? '' }}" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('mis-asignaciones.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg font-medium transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de asignaciones -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nota de Venta
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Equipo Asignado
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha Asignación
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($asignaciones as $asignacion)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $asignacion->nota_venta }}</div>
                            @if($asignacion->observaciones)
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($asignacion->observaciones, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if($asignacion->instalador1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                 {{ $asignacion->instalador1->id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $asignacion->instalador1->nombre }}
                                    </span>
                                @endif
                                @if($asignacion->instalador2)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                 {{ $asignacion->instalador2->id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $asignacion->instalador2->nombre }}
                                    </span>
                                @endif
                                @if($asignacion->instalador3)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                 {{ $asignacion->instalador3->id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $asignacion->instalador3->nombre }}
                                    </span>
                                @endif
                                @if($asignacion->instalador4)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                 {{ $asignacion->instalador4->id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $asignacion->instalador4->nombre }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $asignacion->fecha_asigna_formateada }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                         bg-{{ $asignacion->estado_badge['color'] }}-100 
                                         text-{{ $asignacion->estado_badge['color'] }}-800">
                                {{ $asignacion->estado_badge['text'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Ver detalles -->
                                <button onclick="verDetalles({{ $asignacion->id }})" 
                                        class="text-blue-600 hover:text-blue-800 transition-colors" title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                @if($asignacion->estado === 'pendiente')
                                    <!-- Aceptar -->
                                    <form method="POST" action="{{ route('mis-asignaciones.aceptar', $asignacion->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 transition-colors" title="Aceptar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Rechazar -->
                                    <button onclick="rechazarAsignacion({{ $asignacion->id }})" 
                                            class="text-red-600 hover:text-red-800 transition-colors" title="Rechazar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif

                                @if($asignacion->estado === 'aceptada')
                                    <!-- Iniciar proceso -->
                                    <form method="POST" action="{{ route('mis-asignaciones.en-proceso', $asignacion->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 transition-colors" title="Iniciar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                @if($asignacion->estado === 'en_proceso')
                                    <!-- Completar -->
                                    <button onclick="completarAsignacion({{ $asignacion->id }})" 
                                            class="text-green-600 hover:text-green-800 transition-colors" title="Completar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No tienes asignaciones</p>
                                <p class="text-gray-400 text-sm mt-1">Cuando te asignen trabajos, aparecerán aquí</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($asignaciones->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $asignaciones->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Ver Detalles -->
<div id="modalDetalles" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-900">Detalles de Asignación</h3>
            <button onclick="cerrarModalDetalles()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div id="contenidoDetalles" class="p-6">
            <!-- Cargando... -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verDetalles(id) {
        document.getElementById('modalDetalles').classList.remove('hidden');
        document.getElementById('contenidoDetalles').innerHTML = '<div class="text-center py-8"><p class="text-gray-500">Cargando...</p></div>';
        
        fetch(`/mis-asignaciones/${id}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('contenidoDetalles').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('contenidoDetalles').innerHTML = '<div class="text-center text-red-600"><p>Error al cargar</p></div>';
            });
    }

    function cerrarModalDetalles() {
        document.getElementById('modalDetalles').classList.add('hidden');
    }

    function rechazarAsignacion(id) {
        Swal.fire({
            title: '¿Rechazar esta asignación?',
            text: "Esta acción cambiará el estado a rechazada",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/mis-asignaciones/${id}/rechazar`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function completarAsignacion(id) {
        Swal.fire({
            title: '¿Completar esta asignación?',
            text: "Esto marcará la asignación como finalizada",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, completar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/mis-asignaciones/${id}/completar`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Cerrar modal con ESC y click fuera
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarModalDetalles();
    });

    document.addEventListener('click', (e) => {
        if (e.target.id === 'modalDetalles') cerrarModalDetalles();
    });
</script>
@endpush
@endsection