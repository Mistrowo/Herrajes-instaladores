@extends('layouts.dashboard')

@section('title', 'Herrajes')
@section('page-title', 'Herrajes')
@section('page-subtitle', 'Detalle y captura de ítems')

@section('content')
<div class="p-4 sm:p-6 lg:p-8"
     x-data="herrajeForm({
        herrajeId: {{ $herraje->id }},
        nvFolio: {{ $herraje->nv_folio }},
        initial: {
            estado: '{{ $herraje->estado }}',
            instalador_id: '{{ $herraje->instalador_id }}',
            observaciones: @json($herraje->observaciones)
        }
     })"
     x-init="init()">

    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-100 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Herrajes NV-{{ str_pad($herraje->nv_folio, 6, '0', STR_PAD_LEFT) }}
                    </h3>
                    <p class="text-sm text-gray-500">Captura y control de materiales de instalación</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                          :class="{
                            'bg-yellow-100 text-yellow-700': estado==='borrador',
                            'bg-blue-100 text-blue-700': estado==='en_revision',
                            'bg-green-100 text-green-700': estado==='aprobado',
                            'bg-red-100 text-red-700': estado==='rechazado',
                          }"
                          x-text="estadoLabel()"></span>
                </div>
            </div>
        </div>

        <!-- Fila de info -->
        <div class="px-6 py-4 grid grid-cols-1 lg:grid-cols-12 gap-4">
          

            <!-- Asignación -->
            <div class="lg:col-span-4">
                <div class="text-sm text-gray-500 font-semibold mb-1">Asignación</div>
                <div class="p-3 border rounded-lg bg-slate-50">
                    @if($asigna)
                        <div class="text-gray-900 text-sm">Fecha asigna: {{ $asigna->fecha_asigna_formateada }}</div>
                        <div class="text-xs text-gray-600 mt-1">Estado: {{ $asigna->estado_badge['text'] ?? ucfirst($asigna->estado) }}</div>
                        <div class="text-xs text-gray-500 mt-1">Instaladores: {{ $asigna->cantidadInstaladores() }}</div>
                    @else
                        <div class="text-gray-500 text-sm">Sin asignación vinculada</div>
                    @endif
                </div>
            </div>

            <!-- Responsable + Estado -->
            <div class="lg:col-span-4">
                <div class="text-sm text-gray-500 font-semibold mb-1">Responsable de Herraje</div>
                <div class="flex gap-2">
                    <select x-model="instalador_id" @change="guardarEncabezado()"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">-- Seleccionar --</option>
                        @foreach($instaladores as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->nombre }} ({{ $inst->usuario }})</option>
                        @endforeach
                    </select>
                    <select x-model="estado" @change="guardarEncabezado()"
                            class="px-3 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="borrador">Borrador</option>
                        <option value="en_revision">En Revisión</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Observaciones</label>
            <textarea x-model="observaciones" @blur="guardarEncabezado()"
                      rows="3" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg"
                      placeholder="Notas internas, consideraciones, etc."></textarea>
        </div>
    </div>

    <!-- Form Ítem (Nombre + Cantidad) -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-slate-50">
            <h3 class="text-base font-bold text-gray-900">Agregar Ítem</h3>
        </div>
        <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-3">
            <input type="text" x-model="form.descripcion" placeholder="Nombre del ítem *"
                   class="lg:col-span-9 px-3 py-2 border-2 border-gray-300 rounded-lg">
            <input type="number" step="0.01" x-model.number="form.cantidad" placeholder="Cantidad *"
                   class="lg:col-span-3 px-3 py-2 border-2 border-gray-300 rounded-lg text-right">
            <div class="lg:col-span-12 flex items-center justify-end">
                <button @click="agregarItem()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    Agregar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla ítems -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-green-50 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900">Ítems registrados</h3>
            <div class="text-sm text-gray-700">
                <span class="font-semibold" x-text="resumen.items_count"></span> ítems
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                        <th class="px-4 py-2 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="it in items" :key="it.id">
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                <input type="text" x-model="it.descripcion" @change="updateItem(it)"
                                       class="w-full px-2 py-1 border rounded">
                            </td>
                            <td class="px-4 py-2 text-right">
                                <input type="number" step="0.01" x-model.number="it.cantidad" @change="updateItem(it)"
                                       class="w-28 px-2 py-1 border rounded text-right">
                            </td>
                            <td class="px-4 py-2 text-right">
                                <button @click="eliminarItem(it)"
                                        class="px-3 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="items.length===0">
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">Sin ítems aún</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
function herrajeForm({ herrajeId, nvFolio, initial }) {
    return {
        herrajeId,
        nvFolio,
        estado: initial.estado,
        instalador_id: initial.instalador_id || '',
        observaciones: initial.observaciones || '',
        items: [],
        resumen: { items_count: 0 },

        // Solo nombre + cantidad
        form: { descripcion:'', cantidad:1 },

        init() { this.cargarItems(); },

        estadoLabel() {
            return ({
                borrador: 'Borrador',
                en_revision: 'En Revisión',
                aprobado: 'Aprobado',
                rechazado: 'Rechazado'
            })[this.estado] || 'Borrador';
        },

        async guardarEncabezado() {
            try {
                const res = await fetch(`/dashboard/api/herrajes/${this.herrajeId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({
                        estado: this.estado,
                        instalador_id: this.instalador_id || null,
                        observaciones: this.observaciones || null
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error();
                window.showAlert?.('success','Encabezado actualizado');
            } catch (e) { window.showAlert?.('error','No se pudo actualizar encabezado'); }
        },

        async cargarItems() {
            try {
                const res = await fetch(`/dashboard/api/herrajes/${this.herrajeId}/items`);
                const data = await res.json();
                if (data.success) {
                    this.items = data.data.items;
                    this.resumen = data.data.resumen ?? { items_count: this.items.length };
                }
            } catch (e) { console.error(e); }
        },

        async agregarItem() {
            if (!this.form.descripcion || !this.form.cantidad) {
                window.showAlert?.('warning','Completa nombre y cantidad');
                return;
            }
            try {
                const res = await fetch(`/dashboard/api/herrajes/${this.herrajeId}/items`, {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify(this.form)
                });
                const data = await res.json();
                if (data.success) {
                    this.form = { descripcion:'', cantidad:1 };
                    await this.cargarItems();
                    window.showAlert?.('success','Ítem agregado');
                } else {
                    window.showAlert?.('error','No se pudo agregar');
                }
            } catch (e) { window.showAlert?.('error','Error al agregar ítem'); }
        },

        async updateItem(it) {
            try {
                const res = await fetch(`/dashboard/api/herrajes/${this.herrajeId}/items/${it.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({
                        descripcion: it.descripcion,
                        cantidad: it.cantidad
                    })
                });
                const data = await res.json();
                if (data.success) {
                    await this.cargarItems();
                    window.showAlert?.('success','Ítem actualizado');
                } else {
                    window.showAlert?.('error','No se pudo actualizar');
                }
            } catch (e) { window.showAlert?.('error','Error al actualizar ítem'); }
        },

        async eliminarItem(it) {
            if (!confirm('¿Eliminar este ítem?')) return;
            try {
                const res = await fetch(`/dashboard/api/herrajes/${this.herrajeId}/items/${it.id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                });
                const data = await res.json();
                if (data.success) {
                    await this.cargarItems();
                    window.showAlert?.('success','Ítem eliminado');
                } else {
                    window.showAlert?.('error','No se pudo eliminar');
                }
            } catch (e) { window.showAlert?.('error','Error al eliminar ítem'); }
        }
    }
}
</script>
@endpush
@endsection
