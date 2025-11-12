{{-- resources/views/checklist/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Checklist de Instalación')
@section('page-title', 'Checklist de Instalación')
@section('page-subtitle', 'NV-' . str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 sm:p-6 lg:p-8">
    
    <div class="max-w-7xl mx-auto">

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
                    <x-si-no-item 
                        label="Rectificación Medidas" 
                        name="rectificacion_medidas" 
                        :value="old('rectificacion_medidas', $checklist?->rectificacion_medidas)"
                    />
                    <x-si-no-item 
                        label="Planos Actualizados" 
                        name="planos_actualizados" 
                        :value="old('planos_actualizados', $checklist?->planos_actualizados)"
                    />
                    <x-si-no-item 
                        label="Planos Muebles Especiales" 
                        name="planos_muebles_especiales" 
                        :value="old('planos_muebles_especiales', $checklist?->planos_muebles_especiales)"
                    />
                    <x-si-no-item 
                        label="Modificaciones Realizadas" 
                        name="modificaciones_realizadas" 
                        :value="old('modificaciones_realizadas', $checklist?->modificaciones_realizadas)"
                    />
                    <div class="md:col-span-2">
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
                    </div>
                    <x-si-no-item 
                        label="Despacho Integral" 
                        name="despacho_integral" 
                        :value="old('despacho_integral', $checklist?->despacho_integral)"
                    />
                    <div>
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
                    </div>
                </div>
            </x-accordion-item>

            {{-- SECCIÓN 2: ERRORES PROYECTO --}}
            <x-accordion-item title="⚠️ ERRORES PROYECTO" id="errores"
                :badge="($checklist)?->hasAnyErrors() ? 'error' : null" 
                :badge-count="($checklist)?->countErrors() ?? 0">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-si-no-item label="Ventas" name="errores_ventas" 
                            :value="old('errores_ventas', $checklist?->errores_ventas)" />
                        <x-si-no-item label="Diseño" name="errores_diseno" 
                            :value="old('errores_diseno', $checklist?->errores_diseno)" />
                        <x-si-no-item label="Rectificación" name="errores_rectificacion" 
                            :value="old('errores_rectificacion', $checklist?->errores_rectificacion)" />
                        <x-si-no-item label="Producción" name="errores_produccion" 
                            :value="old('errores_produccion', $checklist?->errores_produccion)" />
                        <x-si-no-item label="Proveedor" name="errores_proveedor" 
                            :value="old('errores_proveedor', $checklist?->errores_proveedor)" />
                        <x-si-no-item label="Despacho" name="errores_despacho" 
                            :value="old('errores_despacho', $checklist?->errores_despacho)" />
                        <x-si-no-item label="Instalación" name="errores_instalacion" 
                            :value="old('errores_instalacion', $checklist?->errores_instalacion)" />
                        <x-si-no-item label="Otro" name="errores_otro" 
                            :value="old('errores_otro', $checklist?->errores_otro)" />
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
                    <x-si-no-item label="Instalación de Cielo" name="instalacion_cielo" 
                        :value="old('instalacion_cielo', $checklist?->instalacion_cielo)" />
                    <x-si-no-item label="Instalación de Piso" name="instalacion_piso" 
                        :value="old('instalacion_piso', $checklist?->instalacion_piso)" />
                    <x-si-no-item label="Remate Muros" name="remate_muros" 
                        :value="old('remate_muros', $checklist?->remate_muros)" />
                    <x-si-no-item label="Nivelación Piso" name="nivelacion_piso" 
                        :value="old('nivelacion_piso', $checklist?->nivelacion_piso)" />
                    <x-si-no-item label="Muros a Plomo" name="muros_plomo" 
                        :value="old('muros_plomo', $checklist?->muros_plomo)" />
                    <x-si-no-item label="Instalación Eléctrica" name="instalacion_electrica" 
                        :value="old('instalacion_electrica', $checklist?->instalacion_electrica)" />
                    <x-si-no-item label="Instalación Voz y Dato" name="instalacion_voz_dato" 
                        :value="old('instalacion_voz_dato', $checklist?->instalacion_voz_dato)" />
                </div>
            </x-accordion-item>

            {{-- SECCIÓN 4: INSPECCIÓN FINAL --}}
            <x-accordion-item title="✅ INSPECCIÓN FINAL" id="inspeccion-final">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-si-no-item label="Paneles Alineados" name="paneles_alineados" 
                        :value="old('paneles_alineados', $checklist?->paneles_alineados)" />
                    <x-si-no-item label="Nivelación Cubiertas" name="nivelacion_cubiertas" 
                        :value="old('nivelacion_cubiertas', $checklist?->nivelacion_cubiertas)" />
                    <x-si-no-item label="Pasacables Instalados" name="pasacables_instalados" 
                        :value="old('pasacables_instalados', $checklist?->pasacables_instalados)" />
                    <x-si-no-item label="Limpieza Cubiertas" name="limpieza_cubiertas" 
                        :value="old('limpieza_cubiertas', $checklist?->limpieza_cubiertas)" />
                    <x-si-no-item label="Limpieza Cajones" name="limpieza_cajones" 
                        :value="old('limpieza_cajones', $checklist?->limpieza_cajones)" />
                    <x-si-no-item label="Limpieza Piso" name="limpieza_piso" 
                        :value="old('limpieza_piso', $checklist?->limpieza_piso)" />
                    <x-si-no-item label="Llaves Instaladas" name="llaves_instaladas" 
                        :value="old('llaves_instaladas', $checklist?->llaves_instaladas)" />
                    <x-si-no-item label="Funcionamiento Mueble" name="funcionamiento_mueble" 
                        :value="old('funcionamiento_mueble', $checklist?->funcionamiento_mueble)" />
                    <x-si-no-item label="Puntos Eléctricos" name="puntos_electricos" 
                        :value="old('puntos_electricos', $checklist?->puntos_electricos)" />
                    <x-si-no-item label="Sillas Ubicadas" name="sillas_ubicadas" 
                        :value="old('sillas_ubicadas', $checklist?->sillas_ubicadas)" />
                    <x-si-no-item label="Accesorios" name="accesorios" 
                        :value="old('accesorios', $checklist?->accesorios)" />
                    <x-si-no-item label="Check Herramientas" name="check_herramientas" 
                        :value="old('check_herramientas', $checklist?->check_herramientas)" />
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