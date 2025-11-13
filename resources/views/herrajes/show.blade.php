@extends('layouts.dashboard')

@section('title', 'Herrajes')
@section('page-title', 'Herrajes - NV ' . str_pad($herraje->nv_folio, 6, '0', STR_PAD_LEFT))
@section('page-subtitle', 'Gestión de materiales de instalación')

@section('content')
<div class="p-4 sm:p-6 lg:p-8"
     x-data="herrajeForm({
        herrajeId: {{ $herraje->id }},
        nvFolio: {{ $herraje->nv_folio }},
        initial: {
            estado: '{{ $herraje->estado }}',
            instalador_id: '{{ $herraje->instalador_id ?? '' }}',
            observaciones: {{ Js::from($herraje->observaciones ?? '') }}
        }
     })"
     x-init="init()">

     <div class="mb-6">
        <button onclick="volverDashboard()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver
    </button>
    </div>

    <!-- Formulario para Agregar Ítem -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Agregar Nuevo Ítem</h3>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del ítem *</label>
                    <input type="text" 
                           x-model="form.descripcion" 
                           @keydown.enter="agregarItem()"
                           placeholder="Ej: Bisagra tipo piano 2 metros"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                </div>
                
                <div class="lg:col-span-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad *</label>
                    <input type="number" 
                           step="0.01" 
                           x-model.number="form.cantidad"
                           @keydown.enter="agregarItem()"
                           placeholder="0.00"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <p class="text-sm text-gray-500">* Campos obligatorios</p>
                <button @click="agregarItem()" 
                        :disabled="agregando"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                    <svg class="w-5 h-5" :class="{'animate-spin': agregando}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span x-text="agregando ? 'Agregando...' : 'Agregar Ítem'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Ítems -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-indigo-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Ítems Registrados</h3>
                        <p class="text-sm text-gray-600">Listado completo de herrajes</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-indigo-600" x-text="resumen.items_count"></div>
                    <div class="text-xs text-gray-500 uppercase">Total ítems</div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(it, index) in items" :key="it.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-500" x-text="index + 1"></td>
                            <td class="px-6 py-4">
                                <input type="text" 
                                       x-model="it.descripcion"
                                       class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" 
                                       step="0.01" 
                                       x-model.number="it.cantidad"
                                       class="w-32 px-3 py-2 border-2 border-gray-200 rounded-lg text-right focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </td>
                            <td class="px-6 py-4 text-center">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="items.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-lg font-semibold">No hay ítems registrados</p>
                                <p class="text-sm mt-1">Comienza agregando el primer ítem de herraje</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
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
        observaciones: initial.observaciones || '',
        items: [],
        resumen: { items_count: 0, total_estimado: 0 },
        form: { descripcion: '', cantidad: 1 },
        guardando: false,
        agregando: false,

        init() {
            this.cargarItems();
        },

        estadoLabel() {
            const labels = {
                'en_revision': 'En Revisión',
                'aprobado': 'Aprobado',
                'rechazado': 'Rechazado'
            };
            return labels[this.estado] || 'En Revisión';
        },

        async guardarEncabezado() {
            if (this.guardando) return;
            
            this.guardando = true;
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: this.estado,
                        instalador_id: this.instalador_id || null,
                        observaciones: this.observaciones || null
                    })
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                if (data.success) {
                    window.showAlert?.('success', data.message || 'Cambios guardados correctamente');
                } else {
                    throw new Error(data.message || 'Error al guardar');
                }
            } catch (e) {
                console.error('Error completo:', e);
                window.showAlert?.('error', 'No se pudieron guardar los cambios');
            } finally {
                this.guardando = false;
            }
        },

        async cargarItems() {
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                if (data.success) {
                    this.items = data.data.items || [];
                    this.resumen = data.data.resumen || { items_count: this.items.length };
                }
            } catch (e) {
                console.error('Error al cargar ítems:', e);
            }
        },

        async agregarItem() {
            if (!this.form.descripcion?.trim()) {
                window.showAlert?.('warning', 'El nombre del ítem es obligatorio');
                return;
            }
            
            if (!this.form.cantidad || this.form.cantidad <= 0) {
                window.showAlert?.('warning', 'La cantidad debe ser mayor a 0');
                return;
            }

            if (this.agregando) return;

            this.agregando = true;
            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                if (data.success) {
                    this.form = { descripcion: '', cantidad: 1 };
                    await this.cargarItems();
                    window.showAlert?.('success', data.message || 'Ítem agregado correctamente');
                } else {
                    throw new Error(data.message || 'Error al agregar ítem');
                }
            } catch (e) {
                console.error('Error completo:', e);
                window.showAlert?.('error', 'Error al agregar el ítem');
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        descripcion: it.descripcion,
                        cantidad: it.cantidad
                    })
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                if (data.success) {
                    await this.cargarItems();
                    window.showAlert?.('success', data.message || 'Ítem actualizado');
                } else {
                    throw new Error(data.message || 'Error al actualizar');
                }
            } catch (e) {
                console.error('Error completo:', e);
                window.showAlert?.('error', 'Error al actualizar el ítem');
            }
        },

        async eliminarItem(it) {
            if (!confirm('¿Estás seguro de eliminar este ítem?')) return;

            try {
                const res = await fetch(`/dashboard/herrajes/api/${this.herrajeId}/items/${it.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                if (data.success) {
                    await this.cargarItems();
                    window.showAlert?.('success', data.message || 'Ítem eliminado correctamente');
                } else {
                    throw new Error(data.message || 'Error al eliminar');
                }
            } catch (e) {
                console.error('Error completo:', e);
                window.showAlert?.('error', 'Error al eliminar el ítem');
            }
        }
    }
}
</script>
@endpush
@endsection