@extends('layouts.dashboard')

@section('title', 'Checklist - NV ' . str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-7xl mx-auto">

<!-- Header Simple -->
<div class="flex items-center justify-between mb-6">
    <!-- Lado izquierdo: Volver + Título -->
    <div class="flex items-center gap-4">
        <button onclick="volverDashboard()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 hover:border-blue-500 text-gray-700 hover:text-blue-600 font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </button>

        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-gray-900">Checklist de Instalación</h1>
            <p class="text-sm text-gray-500">
                NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}
                @if($nota) • {{ $nota->nv_cliente }}@endif
            </p>
        </div>
    </div>

    <!-- Lado derecho: Breadcrumb + Badge -->
    <div class="flex items-center gap-4">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
            <span>/</span>
            <span class="text-gray-700 font-medium">Checklist</span>
        </div>

        {{-- Badge de completitud --}}
        @if($checklist)
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm"
             style="
                @if($checklist->getCompletionPercentage() === 100)
                    background-color: #dcfce7; color: #166534;
                @elseif($checklist->getCompletionPercentage() < 100 && $checklist->getCompletionPercentage() > 0)
                    background-color: #fef3c7; color: #92400e;
                @else
                    background-color: #f3f4f6; color: #374151;
                @endif
             ">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-bold">{{ $checklist->getCompletionPercentage() }}%</span>
        </div>
        @endif
    </div>
</div>
        {{-- Formulario --}}
        <form id="checklistForm" action="{{ route('checklist.store', $asignacion->nota_venta) }}" method="POST" class="space-y-5">
            @csrf

            {{-- SELECTOR DE SUCURSAL - DESTACADO --}}
            <div class="bg-white rounded-xl shadow-md border-2 border-blue-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label class="block text-lg font-bold text-gray-900 mb-2">
                            Sucursal de Instalación <span class="text-red-500">*</span>
                        </label>
                        <p class="text-sm text-gray-600 mb-3">Selecciona la sucursal donde se realizó la instalación</p>
                        
                        @if($sucursales->count() > 0)
                            <select name="sucursal_id" 
                                    required
                                    class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium">
                                <option value="">Seleccionar sucursal...</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" 
                                            {{ old('sucursal_id', $checklist?->sucursal_id) == $sucursal->id ? 'selected' : '' }}>
                                        📍 {{ $sucursal->nombre }} - {{ $sucursal->comuna }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-lg">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">No hay sucursales disponibles para este cliente</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 1: PROYECTO/PEDIDO --}}
            <x-accordion-item title="📋 NÚMERO PROYECTO/PEDIDO" id="proyecto-pedido">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Rectificación Medidas --}}
                    <div class="space-y-2">
                        <x-si-no-item 
                            label="Rectificación Medidas" 
                            name="rectificacion_medidas" 
                            :value="old('rectificacion_medidas', $checklist?->rectificacion_medidas)"
                        />
                        <textarea name="rectificacion_medidas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('rectificacion_medidas_obs', $checklist?->rectificacion_medidas_obs) }}</textarea>
                    </div>

                    {{-- Planos Actualizados --}}
                    <div class="space-y-2">
                        <x-si-no-item 
                            label="Planos Actualizados" 
                            name="planos_actualizados" 
                            :value="old('planos_actualizados', $checklist?->planos_actualizados)"
                        />
                        <textarea name="planos_actualizados_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('planos_actualizados_obs', $checklist?->planos_actualizados_obs) }}</textarea>
                    </div>

                    {{-- Planos Muebles Especiales --}}
                    <div class="space-y-2">
                        <x-si-no-item 
                            label="Planos Muebles Especiales" 
                            name="planos_muebles_especiales" 
                            :value="old('planos_muebles_especiales', $checklist?->planos_muebles_especiales)"
                        />
                        <textarea name="planos_muebles_especiales_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('planos_muebles_especiales_obs', $checklist?->planos_muebles_especiales_obs) }}</textarea>
                    </div>

                    {{-- Modificaciones Realizadas --}}
                    <div class="space-y-2">
                        <x-si-no-item 
                            label="Modificaciones Realizadas" 
                            name="modificaciones_realizadas" 
                            :value="old('modificaciones_realizadas', $checklist?->modificaciones_realizadas)"
                        />
                        <textarea name="modificaciones_realizadas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('modificaciones_realizadas_obs', $checklist?->modificaciones_realizadas_obs) }}</textarea>
                    </div>

                    {{-- Modificaciones Autorizadas por --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="block font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Modificaciones Autorizadas por:
                        </label>
                        <input type="text" name="mod_autorizadas_por" 
                               value="{{ old('mod_autorizadas_por', $checklist?->mod_autorizadas_por) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                               placeholder="Nombre de quien autorizó">

                        <textarea name="mod_autorizadas_por_obs" rows="2"
                            class="w-full mt-2 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('mod_autorizadas_por_obs', $checklist?->mod_autorizadas_por_obs) }}</textarea>
                    </div>

                    {{-- Despacho Integral --}}
                    <div class="space-y-2">
                        <x-si-no-item 
                            label="Despacho Integral" 
                            name="despacho_integral" 
                            :value="old('despacho_integral', $checklist?->despacho_integral)"
                        />
                        <textarea name="despacho_integral_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('despacho_integral_obs', $checklist?->despacho_integral_obs) }}</textarea>
                    </div>

                    {{-- Teléfono --}}
                    <div class="space-y-2">
                        <label class="block font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Teléfono
                        </label>
                        <input type="text" name="telefono" 
                               value="{{ old('telefono', $checklist?->telefono) }}" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm" 
                               placeholder="+56 9 1234 5678">

                        <textarea name="telefono_obs" rows="2"
                            class="w-full mt-2 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('telefono_obs', $checklist?->telefono_obs) }}</textarea>
                    </div>
                </div>
            </x-accordion-item>

            {{-- SECCIÓN 2: ERRORES PROYECTO --}}
            <x-accordion-item title="⚠️ ERRORES PROYECTO" id="errores"
                :badge="($checklist)?->hasAnyErrors() ? 'error' : null" 
                :badge-count="($checklist)?->countErrors() ?? 0">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <x-si-no-item label="Ventas" name="errores_ventas" 
                                :value="old('errores_ventas', $checklist?->errores_ventas)" />
                            <textarea name="errores_ventas_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_ventas_obs', $checklist?->errores_ventas_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Diseño" name="errores_diseno" 
                                :value="old('errores_diseno', $checklist?->errores_diseno)" />
                            <textarea name="errores_diseno_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_diseno_obs', $checklist?->errores_diseno_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Rectificación" name="errores_rectificacion" 
                                :value="old('errores_rectificacion', $checklist?->errores_rectificacion)" />
                            <textarea name="errores_rectificacion_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_rectificacion_obs', $checklist?->errores_rectificacion_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Producción" name="errores_produccion" 
                                :value="old('errores_produccion', $checklist?->errores_produccion)" />
                            <textarea name="errores_produccion_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_produccion_obs', $checklist?->errores_produccion_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Proveedor" name="errores_proveedor" 
                                :value="old('errores_proveedor', $checklist?->errores_proveedor)" />
                            <textarea name="errores_proveedor_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_proveedor_obs', $checklist?->errores_proveedor_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Despacho" name="errores_despacho" 
                                :value="old('errores_despacho', $checklist?->errores_despacho)" />
                            <textarea name="errores_despacho_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_despacho_obs', $checklist?->errores_despacho_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Instalación" name="errores_instalacion" 
                                :value="old('errores_instalacion', $checklist?->errores_instalacion)" />
                            <textarea name="errores_instalacion_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_instalacion_obs', $checklist?->errores_instalacion_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Otro" name="errores_otro" 
                                :value="old('errores_otro', $checklist?->errores_otro)" />
                            <textarea name="errores_otro_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error...">{{ old('errores_otro_obs', $checklist?->errores_otro_obs) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Observaciones Generales
                        </label>
                        <textarea name="observaciones" rows="4" 
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                  placeholder="Describe los errores encontrados o cualquier observación relevante...">{{ old('observaciones', $checklist?->observaciones) }}</textarea>
                    </div>
                </div>
            </x-accordion-item>

            {{-- SECCIÓN 3: ESTADO OBRA --}}
            <x-accordion-item title="🏗️ ESTADO OBRA AL MOMENTO DE LA INSTALACIÓN" id="estado-obra">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-si-no-item label="Instalación de Cielo" name="instalacion_cielo" 
                            :value="old('instalacion_cielo', $checklist?->instalacion_cielo)" />
                        <textarea name="instalacion_cielo_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('instalacion_cielo_obs', $checklist?->instalacion_cielo_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Instalación de Piso" name="instalacion_piso" 
                            :value="old('instalacion_piso', $checklist?->instalacion_piso)" />
                        <textarea name="instalacion_piso_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('instalacion_piso_obs', $checklist?->instalacion_piso_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Remate Muros" name="remate_muros" 
                            :value="old('remate_muros', $checklist?->remate_muros)" />
                        <textarea name="remate_muros_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('remate_muros_obs', $checklist?->remate_muros_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Nivelación Piso" name="nivelacion_piso" 
                            :value="old('nivelacion_piso', $checklist?->nivelacion_piso)" />
                        <textarea name="nivelacion_piso_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('nivelacion_piso_obs', $checklist?->nivelacion_piso_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Muros a Plomo" name="muros_plomo" 
                            :value="old('muros_plomo', $checklist?->muros_plomo)" />
                        <textarea name="muros_plomo_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('muros_plomo_obs', $checklist?->muros_plomo_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Instalación Eléctrica" name="instalacion_electrica" 
                            :value="old('instalacion_electrica', $checklist?->instalacion_electrica)" />
                        <textarea name="instalacion_electrica_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('instalacion_electrica_obs', $checklist?->instalacion_electrica_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Instalación Voz y Dato" name="instalacion_voz_dato" 
                            :value="old('instalacion_voz_dato', $checklist?->instalacion_voz_dato)" />
                        <textarea name="instalacion_voz_dato_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('instalacion_voz_dato_obs', $checklist?->instalacion_voz_dato_obs) }}</textarea>
                    </div>
                </div>
            </x-accordion-item>

            {{-- SECCIÓN 4: INSPECCIÓN FINAL --}}
            <x-accordion-item title="✅ INSPECCIÓN FINAL" id="inspeccion-final">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-si-no-item label="Paneles Alineados" name="paneles_alineados" 
                            :value="old('paneles_alineados', $checklist?->paneles_alineados)" />
                        <textarea name="paneles_alineados_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('paneles_alineados_obs', $checklist?->paneles_alineados_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Nivelación Cubiertas" name="nivelacion_cubiertas" 
                            :value="old('nivelacion_cubiertas', $checklist?->nivelacion_cubiertas)" />
                        <textarea name="nivelacion_cubiertas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('nivelacion_cubiertas_obs', $checklist?->nivelacion_cubiertas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Pasacables Instalados" name="pasacables_instalados" 
                            :value="old('pasacables_instalados', $checklist?->pasacables_instalados)" />
                        <textarea name="pasacables_instalados_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('pasacables_instalados_obs', $checklist?->pasacables_instalados_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Limpieza Cubiertas" name="limpieza_cubiertas" 
                            :value="old('limpieza_cubiertas', $checklist?->limpieza_cubiertas)" />
                        <textarea name="limpieza_cubiertas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('limpieza_cubiertas_obs', $checklist?->limpieza_cubiertas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Limpieza Cajones" name="limpieza_cajones" 
                            :value="old('limpieza_cajones', $checklist?->limpieza_cajones)" />
                        <textarea name="limpieza_cajones_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('limpieza_cajones_obs', $checklist?->limpieza_cajones_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Limpieza Piso" name="limpieza_piso" 
                            :value="old('limpieza_piso', $checklist?->limpieza_piso)" />
                        <textarea name="limpieza_piso_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('limpieza_piso_obs', $checklist?->limpieza_piso_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Llaves Instaladas" name="llaves_instaladas" 
                            :value="old('llaves_instaladas', $checklist?->llaves_instaladas)" />
                        <textarea name="llaves_instaladas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('llaves_instaladas_obs', $checklist?->llaves_instaladas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Funcionamiento Mueble" name="funcionamiento_mueble" 
                            :value="old('funcionamiento_mueble', $checklist?->funcionamiento_mueble)" />
                        <textarea name="funcionamiento_mueble_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('funcionamiento_mueble_obs', $checklist?->funcionamiento_mueble_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Puntos Eléctricos" name="puntos_electricos" 
                            :value="old('puntos_electricos', $checklist?->puntos_electricos)" />
                        <textarea name="puntos_electricos_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('puntos_electricos_obs', $checklist?->puntos_electricos_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Sillas Ubicadas" name="sillas_ubicadas" 
                            :value="old('sillas_ubicadas', $checklist?->sillas_ubicadas)" />
                        <textarea name="sillas_ubicadas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('sillas_ubicadas_obs', $checklist?->sillas_ubicadas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Accesorios" name="accesorios" 
                            :value="old('accesorios', $checklist?->accesorios)" />
                        <textarea name="accesorios_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('accesorios_obs', $checklist?->accesorios_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Check Herramientas" name="check_herramientas" 
                            :value="old('check_herramientas', $checklist?->check_herramientas)" />
                        <textarea name="check_herramientas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones...">{{ old('check_herramientas_obs', $checklist?->check_herramientas_obs) }}</textarea>
                    </div>
                </div>
            </x-accordion-item>

            {{-- Botones de Acción --}}
            <div class="sticky bottom-0 bg-gradient-to-t from-white to-transparent pt-6 pb-4">
                <div class="flex justify-between items-center gap-4 flex-wrap">
                    {{-- Botón Descargar PDF --}}
                    @if($checklist)
                    <a href="{{ route('checklist.pdf', $asignacion->nota_venta) }}" 
                       class="group px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-semibold rounded-lg hover:from-red-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Descargar PDF</span>
                    </a>
                    @else
                    <div class="text-sm text-gray-500 italic">
                        💡 Guarda el checklist primero para descargarlo en PDF
                    </div>
                    @endif
                    
                    {{-- Botón Guardar --}}
                    <button type="button" id="submitButton" 
                            class="group px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        <span>Guardar Checklist</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function volverDashboard() {
    const folio = '{{ $asignacion->nota_venta }}';
    sessionStorage.setItem('dashboard_folio', folio);
    window.location.href = '{{ route("dashboard") }}';
}

document.addEventListener('alpine:init', () => {
    Alpine.store('accordion', {
        open: null
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checklistForm');
    const submitButton = document.getElementById('submitButton');

    // Alertas de sesión
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif

    // Confirmación antes de guardar
    submitButton.addEventListener('click', function(e) {
        e.preventDefault();

        // Validar sucursal
        const sucursalSelect = document.querySelector('select[name="sucursal_id"]');
        if (sucursalSelect && !sucursalSelect.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debes seleccionar una sucursal antes de guardar'
            });
            return;
        }

        Swal.fire({
            title: '¿Confirmar guardado?',
            html: `
                <div class="text-center">
                    <p class="text-gray-600 mb-2">Se guardarán todos los cambios realizados en el checklist</p>
                    <p class="text-sm text-gray-500">Nota de Venta: <strong class="text-blue-600">NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}</strong></p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Guardando...',
                    html: '<div class="flex justify-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });
                
                form.submit();
            }
        });
    });
});
</script>
@endpush
@endsection