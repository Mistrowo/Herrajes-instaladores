@extends('layouts.dashboard')

@section('title', 'Asignar Instaladores')

@section('page-title', 'Asignar Instaladores')
@section('page-subtitle', 'Selecciona una nota de venta para asignar instaladores y sucursal')

@section('content')
<div class="p-6">

    <!-- Tabs -->
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

    <!-- Tab: Notas de Venta -->
    <div id="content-notas-venta" class="tab-content">
        <!-- Filtros -->
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
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-colors">
                        Filtrar
                    </button>
                    <a href="{{ route('asignar.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg font-medium transition-colors">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla Notas de Venta -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Entrega</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Asignación</th>
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
                                        @if($asignacion->sucursal)
                                            <span class="text-xs text-gray-500">
                                                📍 {{ $asignacion->sucursal->nombre }}
                                            </span>
                                        @endif
                                        <div class="flex gap-1">
                                            <button onclick="verAsignacion({{ $asignacion->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 text-xs underline">Ver</button>
                                            <span class="text-gray-400">|</span>
                                            <button 
                                                class="text-green-600 hover:text-green-800 text-xs underline btn-editar-inline"
                                                data-id="{{ $asignacion->id }}"
                                                data-nota-venta="{{ $asignacion->nota_venta }}"
                                                data-fecha="{{ $asignacion->fecha_asigna->format('Y-m-d') }}"
                                                data-asignado1="{{ $asignacion->asignado1 ?? '' }}"
                                                data-asignado2="{{ $asignacion->asignado2 ?? '' }}"
                                                data-asignado3="{{ $asignacion->asignado3 ?? '' }}"
                                                data-asignado4="{{ $asignacion->asignado4 ?? '' }}"
                                                data-sucursal-id="{{ $asignacion->sucursal_id ?? '' }}"
                                                data-observaciones="{{ $asignacion->observaciones ?? '' }}"
                                                data-cliente="{{ $nv->nv_cliente }}">
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
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($notasVenta->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $notasVenta->appends($filtrosNV)->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Tab: Asignaciones -->
    <div id="content-asignaciones" class="tab-content hidden">
        
        <!-- Panel de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            @php
                $totalAsignaciones = \App\Models\Asigna::count();
                $pendientes = \App\Models\Asigna::where('estado', 'pendiente')->count();
                $aceptadas = \App\Models\Asigna::where('estado', 'aceptada')->count();
                $enProceso = \App\Models\Asigna::where('estado', 'en_proceso')->count();
                $completadas = \App\Models\Asigna::where('estado', 'completada')->count();
                $rechazadas = \App\Models\Asigna::where('estado', 'rechazada')->count();
            @endphp

            <!-- Total -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAsignaciones }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-yellow-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-yellow-600 uppercase">Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $pendientes }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-yellow-600">
                        {{ $totalAsignaciones > 0 ? round(($pendientes / $totalAsignaciones) * 100, 1) : 0 }}% del total
                    </span>
                </div>
            </div>

            <!-- Aceptadas -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-green-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-green-600 uppercase">Aceptadas</p>
                        <p class="text-2xl font-bold text-green-700 mt-1">{{ $aceptadas }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-green-600">
                        {{ $totalAsignaciones > 0 ? round(($aceptadas / $totalAsignaciones) * 100, 1) : 0 }}% del total
                    </span>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-blue-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-600 uppercase">En Proceso</p>
                        <p class="text-2xl font-bold text-blue-700 mt-1">{{ $enProceso }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-blue-600">
                        {{ $totalAsignaciones > 0 ? round(($enProceso / $totalAsignaciones) * 100, 1) : 0 }}% del total
                    </span>
                </div>
            </div>

            <!-- Completadas -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-300 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase">Completadas</p>
                        <p class="text-2xl font-bold text-gray-700 mt-1">{{ $completadas }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-gray-600">
                        {{ $totalAsignaciones > 0 ? round(($completadas / $totalAsignaciones) * 100, 1) : 0 }}% del total
                    </span>
                </div>
            </div>

            <!-- Rechazadas -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-red-600 uppercase">Rechazadas</p>
                        <p class="text-2xl font-bold text-red-700 mt-1">{{ $rechazadas }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xs text-red-600">
                        {{ $totalAsignaciones > 0 ? round(($rechazadas / $totalAsignaciones) * 100, 1) : 0 }}% del total
                    </span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="mb-4 bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <form method="GET" action="{{ route('asignar.index') }}" class="flex flex-wrap gap-3 items-end">
                <input type="hidden" name="tab" value="asignaciones">
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Buscar Nota de Venta</label>
                    <input type="text" name="nota_venta" value="{{ $filtros['nota_venta'] ?? '' }}" 
                           placeholder="Buscar NV..." 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="w-40">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ ($filtros['estado'] ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aceptada" {{ ($filtros['estado'] ?? '') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                        <option value="en_proceso" {{ ($filtros['estado'] ?? '') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="completada" {{ ($filtros['estado'] ?? '') == 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="rechazada" {{ ($filtros['estado'] ?? '') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">Filtrar</button>
                    <a href="{{ route('asignar.index') }}?tab=asignaciones" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Tabla Asignaciones -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NV</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sucursal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instaladores</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($asignacionesPaginadas as $asig)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3"><span class="text-sm font-medium text-gray-900">{{ $asig->nota_venta }}</span></td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">
                                    {{ $asig->notaVenta ? $asig->notaVenta->nv_cliente : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($asig->sucursal)
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $asig->sucursal->nombre }}</div>
                                        <div class="text-gray-500 text-xs">{{ $asig->sucursal->comuna }}</div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Sin sucursal</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if($asig->instalador1)
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $asig->instalador1->nombre }}</span>
                                    @endif
                                    @if($asig->instalador2)
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $asig->instalador2->nombre }}</span>
                                    @endif
                                    @if($asig->instalador3)
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $asig->instalador3->nombre }}</span>
                                    @endif
                                    @if($asig->instalador4)
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $asig->instalador4->nombre }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $asig->fecha_asigna_formateada }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $asig->estado_badge['color'] }}-100 text-{{ $asig->estado_badge['color'] }}-800">
                                    {{ $asig->estado_badge['text'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="verAsignacion({{ $asig->id }})" 
                                            class="text-blue-600 hover:text-blue-800" 
                                            title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button 
                                        class="text-green-600 hover:text-green-800 btn-editar" 
                                        title="Editar asignación"
                                        data-id="{{ $asig->id }}"
                                        data-nota-venta="{{ $asig->nota_venta }}"
                                        data-fecha="{{ $asig->fecha_asigna->format('Y-m-d') }}"
                                        data-asignado1="{{ $asig->asignado1 ?? '' }}"
                                        data-asignado2="{{ $asig->asignado2 ?? '' }}"
                                        data-asignado3="{{ $asig->asignado3 ?? '' }}"
                                        data-asignado4="{{ $asig->asignado4 ?? '' }}"
                                        data-sucursal-id="{{ $asig->sucursal_id ?? '' }}"
                                        data-observaciones="{{ $asig->observaciones ?? '' }}"
                                        data-cliente="{{ $asig->notaVenta ? $asig->notaVenta->nv_cliente : '' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmarEliminar({{ $asig->id }})" 
                                            class="text-red-600 hover:text-red-800" 
                                            title="Eliminar asignación">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">No hay asignaciones registradas</p>
                                    <p class="text-gray-400 text-sm mt-1">Las asignaciones aparecerán aquí una vez que sean creadas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($asignacionesPaginadas->hasPages())
            <div class="bg-white px-4 py-3 border-t">
                {{ $asignacionesPaginadas->appends(array_merge($filtros, ['tab' => 'asignaciones']))->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Asignar - MEJORADO -->
<div id="modalAsignar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 sticky top-0 z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Asignar Instaladores
                    </h3>
                    <p class="text-sm text-blue-100 mt-1" id="modal-subtitle"></p>
                </div>
                <button onclick="cerrarModalAsignar()" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form method="POST" action="{{ route('asignar.store') }}" class="p-6">
            @csrf
            
            <input type="hidden" name="nota_venta" id="input_nota_venta">
            <input type="hidden" id="cliente_nombre" value="">
            
            <div class="space-y-6">
                <!-- Sucursal -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Sucursal del Cliente
                        <span id="loading-sucursales" class="hidden ml-2 text-xs text-blue-600">
                            <svg class="animate-spin inline h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Cargando...
                        </span>
                    </label>
                    <select name="sucursal_id" id="sucursal_select" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione primero la nota de venta...</option>
                    </select>
                    <div id="sucursal-info" class="mt-3 hidden">
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-blue-900">Información de la Sucursal</p>
                                    <p class="text-xs text-blue-700 mt-1"><strong>Dirección:</strong> <span id="sucursal-direccion"></span></p>
                                    <p class="text-xs text-blue-700"><strong>Comuna:</strong> <span id="sucursal-comuna"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fecha de Asignación
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="fecha_asigna" value="{{ date('Y-m-d') }}" required 
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Instaladores -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Seleccionar Instaladores
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 1 <span class="text-red-500">*</span></label>
                            <select name="asignado1" required class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $instalador)
                                    <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 2</label>
                            <select name="asignado2" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $instalador)
                                    <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 3</label>
                            <select name="asignado3" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $instalador)
                                    <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 4</label>
                            <select name="asignado4" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $instalador)
                                    <option value="{{ $instalador->id }}">{{ $instalador->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Observaciones
                    </label>
                    <textarea name="observaciones" rows="3" placeholder="Ingrese observaciones adicionales (opcional)..."
                              class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 flex gap-3 justify-end pt-4 border-t border-gray-200">
                <button type="button" onclick="cerrarModalAsignar()" 
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar - MEJORADO -->
<div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 sticky top-0 z-10">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Asignación
                </h3>
                <button onclick="cerrarModalEditar()" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="formEditar" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Nota de Venta -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nota de Venta <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_nota_venta" name="nota_venta" required readonly 
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-gray-100">
                </div>

                <!-- Sucursal Edit -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Sucursal
                    </label>
                    <select name="sucursal_id" id="edit_sucursal_id" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sin sucursal</option>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fecha de Asignación <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="edit_fecha_asigna" name="fecha_asigna" required 
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Instaladores -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Instaladores
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 1 <span class="text-red-500">*</span></label>
                            <select id="edit_asignado1" name="asignado1" required class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 2</label>
                            <select id="edit_asignado2" name="asignado2" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 3</label>
                            <select id="edit_asignado3" name="asignado3" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Instalador 4</label>
                            <select id="edit_asignado4" name="asignado4" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Seleccionar...</option>
                                @foreach($instaladores as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Observaciones
                    </label>
                    <textarea id="edit_observaciones" name="observaciones" rows="3" placeholder="Ingrese observaciones adicionales..."
                              class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 flex gap-3 justify-end pt-4 border-t border-gray-200">
                <button type="button" onclick="cerrarModalEditar()" 
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Actualizar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detalles -->
<div id="modalDetalles" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 bg-white z-10">
            <h3 class="text-xl font-bold">Detalles de Asignación</h3>
            <button onclick="cerrarModalDetalles()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="contenidoDetalles" class="p-6"></div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Mostrar alertas de sesión con SweetAlert2
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3B82F6',
        timer: 3000,
        timerProgressBar: true
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonColor: '#EF4444'
    });
@endif

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

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('tab') === 'asignaciones') {
    cambiarTab('asignaciones');
}

// Modal Asignar con carga de sucursales
function asignarInstaladores(folio, cliente) {
    document.getElementById('modalAsignar').classList.remove('hidden');
    document.getElementById('input_nota_venta').value = folio;
    document.getElementById('cliente_nombre').value = cliente;
    document.getElementById('modal-subtitle').textContent = `NV: ${folio} - Cliente: ${cliente}`;
    
    // Cargar sucursales basado en el nombre del cliente
    cargarSucursales(cliente);
}

function cargarSucursales(nombreCliente) {
    const selectSucursal = document.getElementById('sucursal_select');
    const loadingIndicator = document.getElementById('loading-sucursales');
    
    if (!nombreCliente) {
        selectSucursal.innerHTML = '<option value="">No se pudo obtener el nombre del cliente</option>';
        return;
    }
    
    selectSucursal.innerHTML = '<option value="">Cargando sucursales...</option>';
    selectSucursal.disabled = true;
    loadingIndicator.classList.remove('hidden');
    
    fetch(`/asignar/sucursales?nombre_cliente=${encodeURIComponent(nombreCliente)}`)
        .then(response => response.json())
        .then(data => {
            loadingIndicator.classList.add('hidden');
            selectSucursal.disabled = false;
            
            if (data.success && data.sucursales.length > 0) {
                selectSucursal.innerHTML = '<option value="">Seleccionar sucursal...</option>';
                data.sucursales.forEach(sucursal => {
                    const option = document.createElement('option');
                    option.value = sucursal.id;
                    option.textContent = `${sucursal.nombre} - ${sucursal.comuna}`;
                    option.dataset.direccion = sucursal.direccion_completa;
                    option.dataset.comuna = sucursal.comuna;
                    selectSucursal.appendChild(option);
                });
            } else {
                selectSucursal.innerHTML = '<option value="">No se encontraron sucursales para este cliente</option>';
            }
        })
        .catch(error => {
            console.error('Error al cargar sucursales:', error);
            loadingIndicator.classList.add('hidden');
            selectSucursal.disabled = false;
            selectSucursal.innerHTML = '<option value="">Error al cargar sucursales</option>';
        });
}

// Mostrar info de sucursal al seleccionar
document.getElementById('sucursal_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('sucursal-info');
    
    if (this.value) {
        document.getElementById('sucursal-direccion').textContent = selectedOption.dataset.direccion || '-';
        document.getElementById('sucursal-comuna').textContent = selectedOption.dataset.comuna || '-';
        infoDiv.classList.remove('hidden');
    } else {
        infoDiv.classList.add('hidden');
    }
});

function cerrarModalAsignar() {
    document.getElementById('modalAsignar').classList.add('hidden');
}

// Modal Editar con data attributes
function editarAsignacionConDatos(button) {
    const data = button.dataset;
    
    console.log('Editando asignación:', data);
    
    document.getElementById('modalEditar').classList.remove('hidden');
    document.getElementById('formEditar').action = `/asignar/${data.id}`;
    document.getElementById('edit_nota_venta').value = data.notaVenta;
    document.getElementById('edit_fecha_asigna').value = data.fecha;
    document.getElementById('edit_asignado1').value = data.asignado1 || '';
    document.getElementById('edit_asignado2').value = data.asignado2 || '';
    document.getElementById('edit_asignado3').value = data.asignado3 || '';
    document.getElementById('edit_asignado4').value = data.asignado4 || '';
    document.getElementById('edit_observaciones').value = data.observaciones || '';
    
    // Cargar sucursales para edición si tenemos el nombre del cliente
    if (data.cliente) {
        cargarSucursalesParaEdicion(data.cliente, data.sucursalId);
    } else {
        const select = document.getElementById('edit_sucursal_id');
        select.innerHTML = '<option value="">Sin sucursal</option>';
        if (data.sucursalId) {
            select.innerHTML += `<option value="${data.sucursalId}" selected>Sucursal ID: ${data.sucursalId}</option>`;
        }
    }
}

// Event listeners para botones de editar
document.addEventListener('DOMContentLoaded', function() {
    // Botones en tabla de asignaciones
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function() {
            editarAsignacionConDatos(this);
        });
    });
    
    // Botones inline en tabla de notas de venta
    document.querySelectorAll('.btn-editar-inline').forEach(button => {
        button.addEventListener('click', function() {
            editarAsignacionConDatos(this);
        });
    });
});

function cargarSucursalesParaEdicion(nombreCliente, sucursalActual) {
    const select = document.getElementById('edit_sucursal_id');
    select.innerHTML = '<option value="">Cargando...</option>';
    
    fetch(`/asignar/sucursales?nombre_cliente=${encodeURIComponent(nombreCliente)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.sucursales.length > 0) {
                select.innerHTML = '<option value="">Sin sucursal</option>';
                data.sucursales.forEach(sucursal => {
                    const option = document.createElement('option');
                    option.value = sucursal.id;
                    option.textContent = `${sucursal.nombre} - ${sucursal.comuna}`;
                    if (sucursal.id == sucursalActual) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">Sin sucursal</option>';
            }
        })
        .catch(error => {
            console.error('Error al cargar sucursales para edición:', error);
            select.innerHTML = '<option value="">Sin sucursal</option>';
        });
}

function cerrarModalEditar() {
    document.getElementById('modalEditar').classList.add('hidden');
}

// Modal Ver
function verAsignacion(id) {
    console.log('Viendo asignación ID:', id);
    
    document.getElementById('modalDetalles').classList.remove('hidden');
    document.getElementById('contenidoDetalles').innerHTML = '<div class="flex items-center justify-center py-8"><svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-3 text-gray-600">Cargando...</span></div>';
    
    fetch(`/asignar/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los detalles');
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('contenidoDetalles').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('contenidoDetalles').innerHTML = '<div class="text-center py-8"><p class="text-red-600">Error al cargar los detalles</p><button onclick="cerrarModalDetalles()" class="mt-4 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Cerrar</button></div>';
        });
}

function cerrarModalDetalles() {
    document.getElementById('modalDetalles').classList.add('hidden');
}

// Eliminar con SweetAlert2
function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarAsignacion(id);
        }
    });
}

function eliminarAsignacion(id) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/asignar/${id}`;
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    
    form.appendChild(csrf);
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
}

// Cerrar modales con ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        cerrarModalAsignar();
        cerrarModalEditar();
        cerrarModalDetalles();
    }
});
</script>
@endpush
@endsection