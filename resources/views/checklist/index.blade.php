@extends('layouts.dashboard')

@section('title', 'Checklist de Instalación')
@section('page-title', 'Checklist')
@section('page-subtitle', 'NV-' . str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="p-4 sm:p-6 lg:p-8" x-data="checklistAccordion()">


    @push('scripts')
<script>
function checklistAccordion() {
    return {
        init() {
            @if($checklist?->hasAnyErrors())
                this.$store.accordion.open = 'errores';
            @elseif(old('errores_ventas') || old('errores_diseno'))
                this.$store.accordion.open = 'errores';
            @else
                this.$store.accordion.open = 'proyecto-pedido';
            @endif
        },
        toggle(id) {
            this.$store.accordion.open = this.$store.accordion.open === id ? null : id;
        }
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.store('accordion', {
        open: null
    });
});
</script>
@endpush
    <form action="{{ route('checklist.store', $asignacion->nota_venta) }}" method="POST" class="space-y-6">
        @csrf

        <!-- ACORDEÓN -->
        <div class="space-y-4">

            <!-- SECCIÓN 1: NÚMERO PROYECTO/PEDIDO -->
            <x-accordion-item 
                title="NÚMERO PROYECTO/PEDIDO" 
                id="proyecto-pedido"
                :open="true"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <x-check-item label="Rectificación Medidas" name="rectificacion_medidas" :value="$checklist?->rectificacion_medidas" />
                    <x-check-item label="Planos Actualizados" name="planos_actualizados" :value="$checklist?->planos_actualizados" />
                    <x-check-item label="Planos Muebles Especiales" name="planos_muebles_especiales" :value="$checklist?->planos_muebles_especiales" />
                    <x-check-item label="Modificaciones Realizadas" name="modificaciones_realizadas" :value="$checklist?->modificaciones_realizadas" />
                    <div class="md:col-span-2">
                        <label class="block font-medium text-gray-700 mb-1">Mod. Autorizadas por:</label>
                        <input type="text" name="mod_autorizadas_por" value="{{ $checklist?->mod_autorizadas_por }}" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <x-check-item label="Despacho Integral" name="despacho_integral" :value="$checklist?->despacho_integral" />
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="telefono" value="{{ $checklist?->telefono }}" class="w-full px-3 py-2 border rounded-lg" placeholder="+56 9 1234 5678">
                    </div>
                </div>
            </x-accordion-item>

            <!-- SECCIÓN 2: ERRORES PROYECTO -->
            <x-accordion-item 
                title="ERRORES PROYECTO" 
                id="errores"
                :open="$checklist?->hasAnyErrors()"
                badge="error"
                badge-count="$checklist?->countErrors()"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <x-check-item label="Ventas" name="errores_ventas" :value="$checklist?->errores_ventas" />
                    <x-check-item label="Diseño" name="errores_diseno" :value="$checklist?->errores_diseno" />
                    <x-check-item label="Rectificación" name="errores_rectificacion" :value="$checklist?->errores_rectificacion" />
                    <x-check-item label="Producción" name="errores_produccion" :value="$checklist?->errores_produccion" />
                    <x-check-item label="Proveedor" name="errores_proveedor" :value="$checklist?->errores_proveedor" />
                    <x-check-item label="Despacho" name="errores_despacho" :value="$checklist?->errores_despacho" />
                    <x-check-item label="Instalación" name="errores_instalacion" :value="$checklist?->errores_instalacion" />
                    <x-check-item label="Otro" name="errores_otro" :value="$checklist?->errores_otro" />
                </div>
                <div class="mt-4">
                    <label class="block font-medium text-gray-700 mb-1">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ $checklist?->observaciones }}</textarea>
                </div>
            </x-accordion-item>

            <!-- SECCIÓN 3: ESTADO OBRA -->
            <x-accordion-item 
                title="ESTADO OBRA AL MOMENTO DE LA INSTALACIÓN" 
                id="estado-obra"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <x-check-item label="Instalación de Cielo" name="instalacion_cielo" :value="$checklist?->instalacion_cielo" />
                    <x-check-item label="Instalación de Piso" name="instalacion_piso" :value="$checklist?->instalacion_piso" />
                    <x-check-item label="Remate Muros" name="remate_muros" :value="$checklist?->remate_muros" />
                    <x-check-item label="Nivelación Piso" name="nivelacion_piso" :value="$checklist?->nivelacion_piso" />
                    <x-check-item label="Muros a Plomo" name="muros_plomo" :value="$checklist?->muros_plomo" />
                    <x-check-item label="Instalación Eléctrica" name="instalacion_electrica" :value="$checklist?->instalacion_electrica" />
                    <x-check-item label="Instalación Voz y Dato" name="instalacion_voz_dato" :value="$checklist?->instalacion_voz_dato" />
                </div>
            </x-accordion-item>

            <!-- SECCIÓN 4: INSPECCIÓN FINAL -->
            <x-accordion-item 
                title="INSPECCIÓN FINAL" 
                id="inspeccion-final"
                badge="success"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <x-check-item label="Paneles Alineados" name="paneles_alineados" :value="$checklist?->paneles_alineados" />
                    <x-check-item label="Nivelación Cubiertas" name="nivelacion_cubiertas" :value="$checklist?->nivelacion_cubiertas" />
                    <x-check-item label="Pasacables Instalados" name="pasacables_instalados" :value="$checklist?->pasacables_instalados" />
                    <x-check-item label="Limpieza Cubiertas" name="limpieza_cubiertas" :value="$checklist?->limpieza_cubiertas" />
                    <x-check-item label="Limpieza Cajones" name="limpieza_cajones" :value="$checklist?->limpieza_cajones" />
                    <x-check-item label="Limpieza Piso" name="limpieza_piso" :value="$checklist?->limpieza_piso" />
                    <x-check-item label="Llaves Instaladas" name="llaves_instaladas" :value="$checklist?->llaves_instaladas" />
                    <x-check-item label="Funcionamiento Mueble" name="funcionamiento_mueble" :value="$checklist?->funcionamiento_mueble" />
                    <x-check-item label="Puntos Eléctricos" name="puntos_electricos" :value="$checklist?->puntos_electricos" />
                    <x-check-item label="Sillas Ubicadas" name="sillas_ubicadas" :value="$checklist?->sillas_ubicadas" />
                    <x-check-item label="Accesorios" name="accesorios" :value="$checklist?->accesorios" />
                    <x-check-item label="Check Herramientas" name="check_herramientas" :value="$checklist?->check_herramientas" />
                </div>
            </x-accordion-item>

        </div>

        <!-- BOTÓN GUARDAR -->
        <div class="flex justify-end mt-8">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                Guardar Checklist
            </button>
        </div>
    </form>
</div>
@endsection