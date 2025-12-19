@extends('layouts.dashboard')

@section('title', 'Herrajes - NV ' . str_pad($herraje->nv_folio, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8"
     x-data="herrajeForm({
        herrajeId: {{ $herraje->id }},
        nvFolio: {{ $herraje->nv_folio }},
        sucursales: {{ Js::from($sucursales) }},
        initial: {
            estado: '{{ $herraje->estado }}',
            instalador_id: '{{ $herraje->instalador_id ?? '' }}',
            sucursal_id: '{{ $herraje->sucursal_id ?? '' }}',
            observaciones: {{ Js::from($herraje->observaciones ?? '') }}
        }
     })"
     x-init="init()">

    <div class="max-w-7xl mx-auto">
        <!-- Header Simple -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <button onclick="volverDashboard({{ $herraje->nv_folio }})"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-blue-200 hover:border-blue-500 text-gray-700 hover:text-blue-600 font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver
                </button>

                <div class="flex flex-col">
                    <h1 class="text-2xl font-bold text-gray-900">Herrajes</h1>
                    <p class="text-sm text-gray-500">NV-{{ str_pad($herraje->nv_folio, 6, '0', STR_PAD_LEFT) }}
                        @if($nota) - {{ $nota->nv_cliente }}@endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
                <span>/</span>
                <span class="text-gray-700 font-medium">Herrajes</span>
            </div>

            
        </div>

        <!-- Formulario para Agregar Ítem -->
        <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b-2 border-green-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Agregar Nuevo Ítem
                </h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <!-- Sucursal -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            📍 Sucursal <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.sucursal_id"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                required>
                            <option value="">Sin sucursal</option>
                            <template x-for="suc in sucursales" :key="suc.id">
                                <option :value="suc.id" x-text="`${suc.nombre} - ${suc.comuna}`"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Herraje -->
                    <div class="lg:col-span-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            🔧 Herraje <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.descripcion"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                required>
                            <option value="">Seleccionar...</option>
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
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            📦 Cantidad <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0.01"
                               x-model.number="form.cantidad"
                               placeholder="1.00"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-green-500 focus:border-green-500 transition font-mono">
                    </div>

                    <!-- Botón Agregar -->
                    <div class="lg:col-span-1 flex items-end">
                        <button @click="agregarItem()"
                                :disabled="agregando || !form.descripcion || !form.cantidad"
                                class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" :class="{'animate-spin': agregando}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Agrupada por Sucursal -->
        <div class="space-y-4">
            <template x-for="grupo in itemsAgrupados" :key="grupo.sucursal_id || 0">
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 overflow-hidden">
                    <!-- Header del Grupo -->
                    <div class="px-6 py-4 border-b-2 border-gray-200"
                         :class="{
                             'bg-gradient-to-r from-blue-50 to-indigo-50': grupo.sucursal_id,
                             'bg-gradient-to-r from-gray-50 to-gray-100': !grupo.sucursal_id
                         }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     :class="{
                                         'bg-blue-600': grupo.sucursal_id,
                                         'bg-gray-500': !grupo.sucursal_id
                                     }">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900" x-text="grupo.sucursal ? grupo.sucursal.nombre : 'Sin Sucursal Asignada'"></h3>
                                    <p class="text-sm text-gray-600" x-show="grupo.sucursal" x-text="grupo.sucursal ? grupo.sucursal.comuna : ''"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold"
                                     :class="{
                                         'text-blue-600': grupo.sucursal_id,
                                         'text-gray-600': !grupo.sucursal_id
                                     }"
                                     x-text="grupo.total_items"></div>
                                <div class="text-xs text-gray-500 uppercase">Ítems</div>
                            </div>
                        </div>
                    </div>

                    <!-- Items del Grupo -->
                    <div class="divide-y divide-gray-200">
                        <template x-for="(item, idx) in grupo.items" :key="item.id">
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <!-- Número -->
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-sm font-bold text-gray-600"
                                              x-text="idx + 1"></span>
                                    </div>

                                    <!-- Descripción -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                            </svg>
                                            <span class="font-medium text-gray-900 truncate" x-text="item.descripcion"></span>
                                        </div>
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="flex-shrink-0 w-32">
                                        <input type="number"
                                               step="0.01"
                                               min="0.01"
                                               :value="item.cantidad"
                                               @change="actualizarCantidad(item, $event.target.value)"
                                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-right focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono font-semibold">
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex-shrink-0 flex items-center gap-2">
                                        <!-- Cambiar Sucursal -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Cambiar sucursal">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            </button>
                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 x-cloak
                                                 class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border-2 border-gray-200 py-2 z-10">
                                                <button @click="cambiarSucursalItem(item, null); open = false"
                                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
                                                        :class="{'bg-gray-100 font-semibold': !item.sucursal_id}">
                                                    Sin sucursal
                                                </button>
                                                <template x-for="suc in sucursales" :key="suc.id">
                                                    <button @click="cambiarSucursalItem(item, suc.id); open = false"
                                                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 transition-colors"
                                                            :class="{'bg-blue-100 font-semibold': item.sucursal_id == suc.id}"
                                                            x-text="`📍 ${suc.nombre} - ${suc.comuna}`"></button>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Eliminar -->
                                        <button @click="eliminarItem(item)"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="itemsAgrupados.length === 0" class="bg-white rounded-xl shadow-md border-2 border-gray-200 p-12">
                <div class="flex flex-col items-center justify-center text-gray-400">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-600 mb-1">No hay herrajes registrados</p>
                    <p class="text-sm text-gray-500">Comienza agregando el primer ítem usando el formulario de arriba</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function volverDashboard(folio) {
    sessionStorage.setItem('dashboard_folio', folio);
    window.location.href = '/dashboard';
}

function herrajeForm({ herrajeId, nvFolio, sucursales, initial }) {
    return {
        herrajeId,
        nvFolio,
        sucursales,
        estado: initial.estado || 'en_revision',
        instalador_id: initial.instalador_id || '',
        sucursal_id: initial.sucursal_id || '',
        observaciones: initial.observaciones || '',
        itemsAgrupados: [],
        resumen: { items_count: 0 },
        form: { sucursal_id: '', descripcion: '', cantidad: 1 },
        guardando: false,
        agregando: false,

        init() {
            this.cargarItemsAgrupados();
        },

        async cargarItemsAgrupados() {
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items-agrupados`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Error al cargar ítems');
                const data = await res.json();
                if (data.success) {
                    this.itemsAgrupados = data.data.agrupados || [];
                    this.resumen = data.data.resumen || { items_count: 0 };
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
                    this.form = { sucursal_id: '', descripcion: '', cantidad: 1 };
                    await this.cargarItemsAgrupados();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Agregado!',
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
                    text: 'No se pudo agregar el ítem'
                });
            } finally {
                this.agregando = false;
            }
        },

        async actualizarCantidad(item, nuevaCantidad) {
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items/${item.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        sucursal_id: item.sucursal_id,
                        descripcion: item.descripcion,
                        cantidad: parseFloat(nuevaCantidad)
                    })
                });

                const data = await res.json();

                if (data.success) {
                    await this.cargarItemsAgrupados();
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        timer: 800,
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
                    text: 'No se pudo actualizar'
                });
            }
        },

        async cambiarSucursalItem(item, nuevaSucursalId) {
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items/${item.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        sucursal_id: nuevaSucursalId,
                        descripcion: item.descripcion,
                        cantidad: item.cantidad
                    })
                });

                const data = await res.json();

                if (data.success) {
                    await this.cargarItemsAgrupados();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucursal actualizada',
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
                    text: 'No se pudo cambiar la sucursal'
                });
            }
        },

        async eliminarItem(item) {
            const result = await Swal.fire({
                title: '¿Eliminar ítem?',
                text: item.descripcion,
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
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items/${item.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (data.success) {
                    await this.cargarItemsAgrupados();
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
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
                    text: 'No se pudo eliminar'
                });
            }
        }
    }
}

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

<style>
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection