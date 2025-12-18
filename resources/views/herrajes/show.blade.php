@extends('layouts.dashboard')

@section('title', 'Herrajes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8"
     x-data="herrajeForm({
        herrajeId: {{ $herraje->id }},
        nvFolio: {{ $herraje->nv_folio }},
        initial: {
            estado: '{{ $herraje->estado }}',
            instalador_id: '{{ $herraje->instalador_id ?? '' }}',
            sucursal_id: '{{ $herraje->sucursal_id ?? '' }}',
            observaciones: {{ Js::from($herraje->observaciones ?? '') }}
        }
     })"
     x-init="init()">

    <!-- Header Principal -->
    <div class="max-w-7xl mx-auto mb-8">
        <!-- Breadcrumb y Volver -->
        <div class="flex items-center justify-between mb-6">
            <button onclick="volverDashboard({{ $herraje->nv_folio }})"
                    class="group inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-200 hover:border-blue-500 text-gray-700 hover:text-blue-600 font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </button>

            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
                <span>/</span>
                <span class="text-gray-700 font-medium">Herrajes</span>
            </div>
        </div>

       

        <!-- Selector de Sucursal -->
        @if($sucursales->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex items-center gap-2 text-gray-700 font-semibold min-w-fit">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Sucursal:
                </div>
                <select x-model="sucursal_id"
                        @change="actualizarSucursal()"
                        class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Sin sucursal específica</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}" {{ $herraje->sucursal_id == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }} - {{ $sucursal->comuna }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Formulario para Agregar Ítem -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Agregar Nuevo Ítem</h3>
                        <p class="text-sm text-gray-600">Selecciona el herraje y la cantidad necesaria</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Descripción -->
                    <div class="lg:col-span-9">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            🔧 Herraje <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.descripcion"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-gray-800"
                                required>
                            <option value="" disabled>Seleccionar herraje...</option>
                            <option>ADHESIVO DE MONTAJE 300ML</option>
                            <option>AGOREX</option>
                            <option>DILUYENTE</option>
                            <option>ESCUADRA PLASTICA BLANCA</option>
                            <option>ESCUADRA PLASTICA CAFE</option>
                            <option>ESCUADRA PLASTICA DOBLE BLANCA</option>
                            <option>ESCUADRA PLASTICA NEGRA</option>
                            <option>ESCUADRADA METALICA 1 X 1</option>
                            <option>HUAIPE</option>
                            <option>MINIFIX 15MM</option>
                            <option>PASACABLE BLANCO</option>
                            <option>PASACABLE GRIS</option>
                            <option>PASACABLE NEGRO</option>
                            <option>PERNO DE EXPANSION 5/16X2</option>
                            <option>PLETINA 50X70X3 NEGRA</option>
                            <option>SILICONA BLANCA 300 ML</option>
                            <option>SILICONA TRANSPARENTE 300 ML</option>
                            <option>TAPA SOBERBIO ADHESIVA GRIS HUMO DE 12 MM</option>
                            <option>TAPA SOBERBIO ADHESIVA NEGRA DE 12 MM</option>
                            <option>TAPA SOBERBIO ADHESIVA PERAL DE 12 MM</option>
                            <option>TAPA SOBERBIO ADHESIVO BLANCO DE 12 MM</option>
                            <option>TAPA SOBERBIO ADHESIVO GRAFITO DE 12 MM</option>
                            <option>TARUGO DE MADERA</option>
                            <option>TARUGO FISHER 8 MM</option>
                            <option>TARUGO PARA VOLCANITA 8MM</option>
                            <option>TORNILLO AGLOMERADO 3,5X15</option>
                            <option>TORNILLO AGLOMERADO 3,5X20</option>
                            <option>TORNILLO AGLOMERADO 3,5X30</option>
                            <option>TORNILLO AGLOMERADO 3,5X40</option>
                            <option>TORNILLO AGLOMERADO 4,5X20</option>
                            <option>TORNILLO AGLOMERADO 4,5X40</option>
                            <option>TORNILLO AGLOMERADO 4,5X50</option>
                            <option>TORNILLO AGLOMERADO 5X70</option>
                            <option>TORNILLO LENTEJA PUNTA DE BROCA 8X1 1/4</option>
                            <option>TORNILLO LENTEJA PUNTA DE BROCA 8X1/2</option>
                            <option>TORNILLO LENTEJA PUNTA DE BROCA 8X3/4</option>
                            <option>TORNILLO PUNTA BROCA 6X1 ZINCADO</option>
                            <option>TORNILLO ROSCALATA DE 10 X 1</option>
                            <option>TORNILLO ROSCALATA DE 10 X 3/4</option>
                            <option>TORNILLO ROSCALATA DE 6X1/2</option>
                            <option>TORNILLO SOBERBIO DE 1-1/2</option>
                            <option>TORNILLO SOBERBIO DE 2</option>
                            <option>TORNILLO VOLCANITA 1X6 NEGRO</option>
                            <option>TORNILLO VOLCANITA 1X6 ZINCADO</option>
                            <option>TORNILLO VOLCANITA PUNTA AGUJA 1 1/4x6 NEGRO</option>
                            <option>TORNILLO VOLCANITA PUNTA AGUJA 1 1/4x6 ZINCADO</option>
                        </select>
                    </div>

                    <!-- Cantidad -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            📦 Cantidad <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0.01"
                               x-model.number="form.cantidad"
                               placeholder="1.00"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="agregarItem()"
                            :disabled="agregando || !form.descripcion || !form.cantidad"
                            class="inline-flex items-center gap-3 px-8 py-3.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:transform-none">
                        <svg class="w-5 h-5" :class="{'animate-spin': agregando}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span x-text="agregando ? 'Agregando...' : 'Agregar Ítem'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Ítems -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-indigo-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Ítems Registrados</h3>
                            <p class="text-sm text-gray-600">Listado completo de herrajes solicitados</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-indigo-600" x-text="resumen.items_count || 0"></div>
                        <div class="text-xs text-gray-500 uppercase">Total</div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">#</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider w-40">Cantidad</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-48">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(it, index) in items" :key="it.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-sm font-bold text-gray-600" x-text="index + 1"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                        <input type="text" 
                                               x-model="it.descripcion"
                                               @blur="updateItem(it)"
                                               class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" 
                                           step="0.01"
                                           min="0.01"
                                           x-model.number="it.cantidad"
                                           @blur="updateItem(it)"
                                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-right focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="updateItem(it)"
                                                class="inline-flex items-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-semibold transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Guardar
                                        </button>
                                        <button @click="eliminarItem(it)"
                                                class="inline-flex items-center gap-1 px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-semibold transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="items.length === 0">
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-600 mb-1">No hay ítems registrados</p>
                                    <p class="text-sm text-gray-500">Comienza agregando el primer herraje usando el formulario de arriba</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function volverDashboard(folio) {
    sessionStorage.setItem('dashboard_folio', folio);
    window.location.href = '/dashboard';
}

function herrajeForm({ herrajeId, nvFolio, initial }) {
    return {
        herrajeId,
        nvFolio,
        estado: initial.estado || 'en_revision',
        instalador_id: initial.instalador_id || '',
        sucursal_id: initial.sucursal_id || '',
        observaciones: initial.observaciones || '',
        items: [],
        resumen: { items_count: 0 },
        form: { descripcion: '', cantidad: 1 },
        guardando: false,
        agregando: false,

        init() {
            this.cargarItems();
        },

        async cargarItems() {
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Error al cargar ítems');
                const data = await res.json();
                if (data.success) {
                    this.items = data.data.items || [];
                    this.resumen = data.data.resumen || { items_count: this.items.length };
                }
            } catch (e) {
                console.error('Error:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los ítems'
                });
            }
        },

        async actualizarSucursal() {
            if (this.guardando) return;
            this.guardando = true;

            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        sucursal_id: this.sucursal_id || null
                    })
                });

                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: 'Sucursal actualizada correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (e) {
                console.error('Error:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar la sucursal'
                });
            } finally {
                this.guardando = false;
            }
        },

        async agregarItem() {
            if (!this.form.descripcion) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Selecciona un herraje'
                });
                return;
            }
            if (!this.form.cantidad || this.form.cantidad <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'La cantidad debe ser mayor a 0'
                });
                return;
            }
            if (this.agregando) return;

            this.agregando = true;

            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await res.json();

                if (data.success) {
                    this.form = { descripcion: '', cantidad: 1 };
                    await this.cargarItems();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Agregado!',
                        text: 'Ítem agregado exitosamente',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (e) {
                console.error('Error:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo agregar el ítem'
                });
            } finally {
                this.agregando = false;
            }
        },

        async updateItem(it) {
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items/${it.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        descripcion: it.descripcion,
                        cantidad: it.cantidad
                    })
                });

                const data = await res.json();

                if (data.success) {
                    await this.cargarItems();
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (e) {
                console.error('Error:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el ítem'
                });
            }
        },

        async eliminarItem(it) {
            const result = await Swal.fire({
                title: '¿Eliminar ítem?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items/${it.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (data.success) {
                    await this.cargarItems();
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'Ítem eliminado exitosamente',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (e) {
                console.error('Error:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar el ítem'
                });
            }
        }
    }
}

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
</script>
@endpush
@endsection