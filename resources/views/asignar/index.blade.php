@extends('layouts.dashboard')

@section('title', 'Asignar Instaladores')

@section('page-title', 'Asignar Instaladores')
@section('page-subtitle', 'Selecciona una nota de venta para asignar instaladores')

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

    <!-- Tabs de navegación -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button onclick="cambiarTab('notas-venta')" id="tab-notas-venta"
                    class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                Notas de Venta Disponibles
            </button>
            <button onclick="cambiarTab('asignaciones')" id="tab-asignaciones"
                    class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Ver Asignaciones
            </button>
        </nav>
    </div>

    <!-- Contenido Tab: Notas de Venta -->
    <div id="content-notas-venta" class="tab-content">
        <!-- Filtros compactos -->
        <div class="mb-4 bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <form method="GET" action="{{ route('asignar.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Buscar Folio/Cliente</label>
                    <input type="text" name="buscar" value="{{ $filtrosNV['buscar'] ?? '' }}" 
                           placeholder="Folio o nombre de cliente" 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="w-40">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado_nv" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="Pendiente" {{ ($filtrosNV['estado_nv'] ?? '') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="En Proceso" {{ ($filtrosNV['estado_nv'] ?? '') == 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="Completado" {{ ($filtrosNV['estado_nv'] ?? '') == 'Completado' ? 'selected' : '' }}>Completado</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-colors">
                        Filtrar
                    </button>
                    <a href="{{ route('asignar.index') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg font-medium transition-colors">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de Notas de Venta -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Folio
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Entrega
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Asignación
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($notasVenta as $nv)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $nv->folio_formateado }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $nv->nv_cliente }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 max-w-xs truncate">
                                    {{ $nv->nv_descripcion ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $nv->nv_estado }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $nv->fecha_entrega_formateada }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $asignacion = $asignaciones->firstWhere('nota_venta', $nv->nv_folio);
                                @endphp
                                
                                @if($asignacion)
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                     bg-{{ $asignacion->estado_badge['color'] }}-100 
                                                     text-{{ $asignacion->estado_badge['color'] }}-800">
                                            {{ $asignacion->estado_badge['text'] }}
                                        </span>
                                        <div class="flex gap-1">
                                            <button onclick="verAsignacion({{ $asignacion->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 text-xs underline">
                                                Ver
                                            </button>
                                            <span class="text-gray-400">|</span>
                                            <button onclick='editarAsignacion(@json($asignacion))' 
                                                    class="text-green-600 hover:text-green-800 text-xs underline">
                                                Editar
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <button onclick="asignarInstaladores('{{ $nv->nv_folio }}', '{{ $nv->nv_cliente }}')" 
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Asignar
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">No hay notas de venta disponibles</p>
                                    <p class="text-gray-400 text-sm mt-1">Intenta cambiar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación con filtros -->
            @if($notasVenta->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $notasVenta->appends($filtrosNV)->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Contenido Tab: Asignaciones -->
    <div id="content-asignaciones" class="tab-content hidden">
        <!-- Filtros compactos -->
        <div class="mb-4 bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <form method="GET" action="{{ route('asignar.index') }}" class="flex flex-wrap gap-3 items-end">
                <input type="hidden" name="tab" value="asignaciones">
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Buscar Nota de Venta</label>
                    <input type="text" name="nota_venta" value="{{ $filtros['nota_venta'] ?? '' }}" 
                           placeholder="Buscar NV..." 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="w-40">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ ($filtros['estado'] ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aceptada" {{ ($filtros['estado'] ?? '') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                        <option value="en_proceso" {{ ($filtros['estado'] ?? '') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="completada" {{ ($filtros['estado'] ?? '') == 'completada' ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-colors">
                        Filtrar
                    </button>
                    <a href="{{ route('asignar.index') }}?tab=asignaciones" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg font-medium transition-colors">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de Asignaciones -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nota de Venta
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Solicita
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Instaladores
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
                        @forelse($asignacionesPaginadas as $asig)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $asig->nota_venta }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $asig->solicita }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if($asig->instalador1)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $asig->instalador1->nombre }}
                                        </span>
                                    @endif
                                    @if($asig->instalador2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $asig->instalador2->nombre }}
                                        </span>
                                    @endif
                                    @if($asig->instalador3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $asig->instalador3->nombre }}
                                        </span>
                                    @endif
                                    @if($asig->instalador4)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $asig->instalador4->nombre }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $asig->fecha_asigna_formateada }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                             bg-{{ $asig->estado_badge['color'] }}-100 
                                             text-{{ $asig->estado_badge['color'] }}-800">
                                    {{ $asig->estado_badge['text'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="verAsignacion({{ $asig->id }})" 
                                            class="text-blue-600 hover:text-blue-800 transition-colors" title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button onclick='editarAsignacion(@json($asig))' 
                                            class="text-green-600 hover:text-green-800 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="eliminarAsignacion({{ $asig->id }})" 
                                            class="text-red-600 hover:text-red-800 transition-colors" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">No hay asignaciones registradas</p>
                                    <p class="text-gray-400 text-sm mt-1">Intenta cambiar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación con filtros -->
            @if($asignacionesPaginadas->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $asignacionesPaginadas->appends(array_merge($filtros, ['tab' => 'asignaciones']))->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Asignar Instaladores -->
<div id="modalAsignar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Asignar Instaladores</h3>
                <p class="text-sm text-gray-500 mt-1" id="modal-subtitle"></p>
            </div>
            <button onclick="cerrarModalAsignar()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('asignar.store') }}" class="p-6">
            @csrf
            
            <input type="hidden" name="nota_venta" id="input_nota_venta">
            
            <div class="space-y-4">
                <!-- Fecha Asignación -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Asignación *</label>
                    <input type="date" name="fecha_asigna" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Instaladores en Grid 2x2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 1 (Instalador a cargo)</label>
                        <select name="asignado1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 2</label>
                        <select name="asignado2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 3</label>
                        <select name="asignado3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 4</label>
                        <select name="asignado4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalAsignar()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Guardar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-900">Editar Asignación</h3>
            <button onclick="cerrarModalEditar()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="formEditar" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nota de Venta *</label>
                    <input type="text" id="edit_nota_venta" name="nota_venta" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Asignación *</label>
                    <input type="date" id="edit_fecha_asigna" name="fecha_asigna" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 1</label>
                        <select id="edit_asignado1" name="asignado1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 2</label>
                        <select id="edit_asignado2" name="asignado2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 3</label>
                        <select id="edit_asignado3" name="asignado3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instalador 4</label>
                        <select id="edit_asignado4" name="asignado4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            @foreach($instaladores as $instalador)
                                <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea id="edit_observaciones" name="observaciones" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalEditar()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Actualizar
                </button>
            </div>
        </form>
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
            <div class="text-center py-8">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-500">Cargando...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tabs
    function cambiarTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(el => {
            el.classList.remove('border-blue-500', 'text-blue-600');
            el.classList.add('border-transparent', 'text-gray-500');
        });
        
        document.getElementById('content-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-' + tab).classList.add('border-blue-500', 'text-blue-600');
    }

    // Verificar si hay tab en URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'asignaciones') {
        cambiarTab('asignaciones');
    }

    // Modal Asignar
    function asignarInstaladores(folio, cliente) {
        document.getElementById('modalAsignar').classList.remove('hidden');
        document.getElementById('input_nota_venta').value = folio;
        document.getElementById('modal-subtitle').textContent = `NV: ${folio} - Cliente: ${cliente}`;
    }

    function cerrarModalAsignar() {
        document.getElementById('modalAsignar').classList.add('hidden');
    }

    // Modal Editar
    function editarAsignacion(asignacion) {
        document.getElementById('modalEditar').classList.remove('hidden');
        document.getElementById('formEditar').action = `/asignar/${asignacion.id}`;
        document.getElementById('edit_nota_venta').value = asignacion.nota_venta;
        document.getElementById('edit_fecha_asigna').value = asignacion.fecha_asigna;
        document.getElementById('edit_asignado1').value = asignacion.asignado1 || '';
        document.getElementById('edit_asignado2').value = asignacion.asignado2 || '';
        document.getElementById('edit_asignado3').value = asignacion.asignado3 || '';
        document.getElementById('edit_asignado4').value = asignacion.asignado4 || '';
        document.getElementById('edit_observaciones').value = asignacion.observaciones || '';
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
    }

    // Modal Ver
    function verAsignacion(id) {
        document.getElementById('modalDetalles').classList.remove('hidden');
        document.getElementById('contenidoDetalles').innerHTML = `
            <div class="text-center py-8">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-500">Cargando...</p>
            </div>
        `;
        
        fetch(`/asignar/${id}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('contenidoDetalles').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('contenidoDetalles').innerHTML = `
                    <div class="text-center text-red-600 py-8">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="font-medium">Error al cargar los detalles</p>
                    </div>
                `;
            });
    }

    function cerrarModalDetalles() {
        document.getElementById('modalDetalles').classList.add('hidden');
    }

    // Eliminar
    function eliminarAsignacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la asignación",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/asignar/${id}`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Cerrar modales con ESC y click fuera
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModalAsignar();
            cerrarModalEditar();
            cerrarModalDetalles();
        }
    });

    document.addEventListener('click', (e) => {
        if (e.target.id === 'modalAsignar') cerrarModalAsignar();
        if (e.target.id === 'modalEditar') cerrarModalEditar();
        if (e.target.id === 'modalDetalles') cerrarModalDetalles();
    });
</script>
@endpush
@endsection