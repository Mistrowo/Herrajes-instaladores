{{-- resources/views/checklist/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Checklist de Instalación')
@section('page-title', 'Checklist de Instalación')
@section('page-subtitle', 'NV-' . str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 sm:p-6 lg:p-8">
    
    <div class="max-w-7xl mx-auto">

        {{-- Botón Volver al Dashboard --}}
        <div class="mb-6">
            <button onclick="volverDashboard()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </button>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 px-6 py-4 rounded-r-lg shadow-md animate-fadeIn">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-green-800">¡Éxito!</p>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 px-6 py-4 rounded-r-lg shadow-md animate-fadeIn">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-red-800">Error</p>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <form id="checklistForm" action="{{ route('checklist.store', $asignacion->nota_venta) }}" method="POST" class="space-y-5">
            @csrf

            {{-- SECCIÓN 1: NÚMERO PROYECTO/PEDIDO --}}
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
                            placeholder="Observaciones rectificación medidas...">{{ old('rectificacion_medidas_obs', $checklist?->rectificacion_medidas_obs) }}</textarea>
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
                            placeholder="Observaciones planos actualizados...">{{ old('planos_actualizados_obs', $checklist?->planos_actualizados_obs) }}</textarea>
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
                            placeholder="Observaciones planos muebles especiales...">{{ old('planos_muebles_especiales_obs', $checklist?->planos_muebles_especiales_obs) }}</textarea>
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
                            placeholder="Observaciones modificaciones realizadas...">{{ old('modificaciones_realizadas_obs', $checklist?->modificaciones_realizadas_obs) }}</textarea>
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
                            placeholder="Observaciones sobre la autorización...">{{ old('mod_autorizadas_por_obs', $checklist?->mod_autorizadas_por_obs) }}</textarea>
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
                            placeholder="Observaciones despacho integral...">{{ old('despacho_integral_obs', $checklist?->despacho_integral_obs) }}</textarea>
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
                            placeholder="Observaciones teléfono...">{{ old('telefono_obs', $checklist?->telefono_obs) }}</textarea>
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
                                placeholder="Detalle del error de ventas...">{{ old('errores_ventas_obs', $checklist?->errores_ventas_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Diseño" name="errores_diseno" 
                                :value="old('errores_diseno', $checklist?->errores_diseno)" />
                            <textarea name="errores_diseno_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error de diseño...">{{ old('errores_diseno_obs', $checklist?->errores_diseno_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Rectificación" name="errores_rectificacion" 
                                :value="old('errores_rectificacion', $checklist?->errores_rectificacion)" />
                            <textarea name="errores_rectificacion_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error de rectificación...">{{ old('errores_rectificacion_obs', $checklist?->errores_rectificacion_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Producción" name="errores_produccion" 
                                :value="old('errores_produccion', $checklist?->errores_produccion)" />
                            <textarea name="errores_produccion_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error de producción...">{{ old('errores_produccion_obs', $checklist?->errores_produccion_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Proveedor" name="errores_proveedor" 
                                :value="old('errores_proveedor', $checklist?->errores_proveedor)" />
                            <textarea name="errores_proveedor_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error de proveedor...">{{ old('errores_proveedor_obs', $checklist?->errores_proveedor_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Despacho" name="errores_despacho" 
                                :value="old('errores_despacho', $checklist?->errores_despacho)" />
                            <textarea name="errores_despacho_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error de despacho...">{{ old('errores_despacho_obs', $checklist?->errores_despacho_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Instalación" name="errores_instalacion" 
                                :value="old('errores_instalacion', $checklist?->errores_instalacion)" />
                            <textarea name="errores_instalacion_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle del error de instalación...">{{ old('errores_instalacion_obs', $checklist?->errores_instalacion_obs) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <x-si-no-item label="Otro" name="errores_otro" 
                                :value="old('errores_otro', $checklist?->errores_otro)" />
                            <textarea name="errores_otro_obs" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                                placeholder="Detalle de otros errores...">{{ old('errores_otro_obs', $checklist?->errores_otro_obs) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Observaciones
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
                            placeholder="Observaciones instalación cielo...">{{ old('instalacion_cielo_obs', $checklist?->instalacion_cielo_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Instalación de Piso" name="instalacion_piso" 
                            :value="old('instalacion_piso', $checklist?->instalacion_piso)" />
                        <textarea name="instalacion_piso_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones instalación piso...">{{ old('instalacion_piso_obs', $checklist?->instalacion_piso_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Remate Muros" name="remate_muros" 
                            :value="old('remate_muros', $checklist?->remate_muros)" />
                        <textarea name="remate_muros_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones remate muros...">{{ old('remate_muros_obs', $checklist?->remate_muros_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Nivelación Piso" name="nivelacion_piso" 
                            :value="old('nivelacion_piso', $checklist?->nivelacion_piso)" />
                        <textarea name="nivelacion_piso_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones nivelación piso...">{{ old('nivelacion_piso_obs', $checklist?->nivelacion_piso_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Muros a Plomo" name="muros_plomo" 
                            :value="old('muros_plomo', $checklist?->muros_plomo)" />
                        <textarea name="muros_plomo_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones muros a plomo...">{{ old('muros_plomo_obs', $checklist?->muros_plomo_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Instalación Eléctrica" name="instalacion_electrica" 
                            :value="old('instalacion_electrica', $checklist?->instalacion_electrica)" />
                        <textarea name="instalacion_electrica_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones instalación eléctrica...">{{ old('instalacion_electrica_obs', $checklist?->instalacion_electrica_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Instalación Voz y Dato" name="instalacion_voz_dato" 
                            :value="old('instalacion_voz_dato', $checklist?->instalacion_voz_dato)" />
                        <textarea name="instalacion_voz_dato_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones instalación voz y datos...">{{ old('instalacion_voz_dato_obs', $checklist?->instalacion_voz_dato_obs) }}</textarea>
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
                            placeholder="Observaciones paneles alineados...">{{ old('paneles_alineados_obs', $checklist?->paneles_alineados_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Nivelación Cubiertas" name="nivelacion_cubiertas" 
                            :value="old('nivelacion_cubiertas', $checklist?->nivelacion_cubiertas)" />
                        <textarea name="nivelacion_cubiertas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones nivelación cubiertas...">{{ old('nivelacion_cubiertas_obs', $checklist?->nivelacion_cubiertas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Pasacables Instalados" name="pasacables_instalados" 
                            :value="old('pasacables_instalados', $checklist?->pasacables_instalados)" />
                        <textarea name="pasacables_instalados_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones pasacables...">{{ old('pasacables_instalados_obs', $checklist?->pasacables_instalados_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Limpieza Cubiertas" name="limpieza_cubiertas" 
                            :value="old('limpieza_cubiertas', $checklist?->limpieza_cubiertas)" />
                        <textarea name="limpieza_cubiertas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones limpieza cubiertas...">{{ old('limpieza_cubiertas_obs', $checklist?->limpieza_cubiertas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Limpieza Cajones" name="limpieza_cajones" 
                            :value="old('limpieza_cajones', $checklist?->limpieza_cajones)" />
                        <textarea name="limpieza_cajones_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones limpieza cajones...">{{ old('limpieza_cajones_obs', $checklist?->limpieza_cajones_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Limpieza Piso" name="limpieza_piso" 
                            :value="old('limpieza_piso', $checklist?->limpieza_piso)" />
                        <textarea name="limpieza_piso_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus-border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones limpieza piso...">{{ old('limpieza_piso_obs', $checklist?->limpieza_piso_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Llaves Instaladas" name="llaves_instaladas" 
                            :value="old('llaves_instaladas', $checklist?->llaves_instaladas)" />
                        <textarea name="llaves_instaladas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones llaves instaladas...">{{ old('llaves_instaladas_obs', $checklist?->llaves_instaladas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Funcionamiento Mueble" name="funcionamiento_mueble" 
                            :value="old('funcionamiento_mueble', $checklist?->funcionamiento_mueble)" />
                        <textarea name="funcionamiento_mueble_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones funcionamiento mueble...">{{ old('funcionamiento_mueble_obs', $checklist?->funcionamiento_mueble_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Puntos Eléctricos" name="puntos_electricos" 
                            :value="old('puntos_electricos', $checklist?->puntos_electricos)" />
                        <textarea name="puntos_electricos_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones puntos eléctricos...">{{ old('puntos_electricos_obs', $checklist?->puntos_electricos_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Sillas Ubicadas" name="sillas_ubicadas" 
                            :value="old('sillas_ubicadas', $checklist?->sillas_ubicadas)" />
                        <textarea name="sillas_ubicadas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones sillas ubicadas...">{{ old('sillas_ubicadas_obs', $checklist?->sillas_ubicadas_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Accesorios" name="accesorios" 
                            :value="old('accesorios', $checklist?->accesorios)" />
                        <textarea name="accesorios_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones accesorios...">{{ old('accesorios_obs', $checklist?->accesorios_obs) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <x-si-no-item label="Check Herramientas" name="check_herramientas" 
                            :value="old('check_herramientas', $checklist?->check_herramientas)" />
                        <textarea name="check_herramientas_obs" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm resize-none"
                            placeholder="Observaciones check herramientas...">{{ old('check_herramientas_obs', $checklist?->check_herramientas_obs) }}</textarea>
                    </div>
                </div>
            </x-accordion-item>

            {{-- Botones de Acción --}}
            <div class="sticky bottom-0 bg-gradient-to-t from-gray-100 to-transparent pt-6 pb-4">
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
<script>
// Función para volver al dashboard con el folio guardado
function volverDashboard() {
    // Guardar el folio actual en sessionStorage antes de volver
    const folio = '{{ $asignacion->nota_venta }}';
    sessionStorage.setItem('dashboard_folio', folio);
    
    // Redireccionar al dashboard
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

    // Mostrar alertas con SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#f0fdf4',
            color: '#166534'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#fef2f2',
            color: '#991b1b'
        });
    @endif

    // Confirmación antes de guardar
    submitButton.addEventListener('click', function(e) {
        e.preventDefault();

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
            confirmButtonText: '<i class="fas fa-save"></i> Sí, guardar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all',
                cancelButton: 'px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all'
            },
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading mientras guarda
                Swal.fire({
                    title: 'Guardando cambios...',
                    html: `
                        <div class="flex flex-col items-center justify-center gap-4 py-4">
                            <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-600 font-medium">Por favor espera...</p>
                            <p class="text-sm text-gray-500">No cierres esta ventana</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
                
                // Enviar formulario
                form.submit();
            }
        });
    });
});
</script>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}
</style>
@endpush
@endsection
