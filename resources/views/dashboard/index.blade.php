@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Aplicación Instaladores')
@section('page-subtitle', 'Gestión de instalaciones')

@section('content')
<div class="p-4 sm:p-6 lg:p-8" x-data="dashboardData()">
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex flex-wrap md:flex-nowrap justify-center gap-2 overflow-x-auto pb-2">
                
                <button @click="abrirPlano()"
                        :disabled="!notaSeleccionada.folio"
                        :class="notaSeleccionada.folio ? 'bg-white hover:bg-gray-50 hover:border-blue-500 hover:text-blue-700 cursor-pointer' : 'bg-gray-100 cursor-not-allowed opacity-50'"
                        class="px-3 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-md transition-all text-sm flex-shrink-0">
                    📋 DOCUMENTOS ASOCIADOS
                </button>
                
                <button @click="abrirOC()"
                        :disabled="!notaSeleccionada.folio"
                        :class="notaSeleccionada.folio ? 'bg-white hover:bg-gray-50 hover:border-blue-500 hover:text-blue-700 cursor-pointer' : 'bg-gray-100 cursor-not-allowed opacity-50'"
                        class="px-3 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-md transition-all text-sm flex-shrink-0">
                    📄 OC
                </button>
                
                <button @click="abrirHerraje()"
                        :disabled="!notaSeleccionada.folio"
                        :class="notaSeleccionada.folio ? 'bg-white hover:bg-gray-50 hover:border-blue-500 hover:text-blue-700 cursor-pointer' : 'bg-gray-100 cursor-not-allowed opacity-50'"
                        class="px-3 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-md transition-all text-sm flex-shrink-0">
                    🔧 HERRAJE
                </button>
                
                <button @click="abrirChecklist()"
                        :disabled="!notaSeleccionada.folio"
                        :class="notaSeleccionada.folio ? 'bg-white hover:bg-gray-50 hover:border-blue-500 hover:text-blue-700 cursor-pointer' : 'bg-gray-100 cursor-not-allowed opacity-50'"
                        class="px-3 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-md transition-all text-sm flex-shrink-0">
                    ✅ CHECKLIST
                </button>
                
                <!-- EVIDENCIA FOTOGRÁFICA -->
                <button @click="abrirEvidencia()"
                        :disabled="!notaSeleccionada.folio"
                        :class="notaSeleccionada.folio ? 'bg-white hover:bg-gray-50 hover:border-blue-500 hover:text-blue-700 cursor-pointer' : 'bg-gray-100 cursor-not-allowed opacity-50'"
                        class="px-3 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-md transition-all text-sm flex-shrink-0">
                    📸 EVIDENCIA FOTOGRÁFICA
                </button>
                
            </div>
            
            <!-- Mensaje cuando no hay nota seleccionada -->
            <div x-show="!notaSeleccionada.folio" 
                 class="text-center text-sm text-gray-500 mt-3 bg-yellow-50 border border-yellow-200 rounded-lg py-2">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Selecciona una Nota de Venta para habilitar las opciones
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-green-50">
            <h3 class="text-base font-bold text-gray-900">INFORMACIÓN DEL PROYECTO</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">NOTA DE VENTA</label>
                    <div class="relative">
                        <input type="text" 
                               x-model="notaSeleccionada.folio_formateado"
                               readonly
                               placeholder="Seleccionar..."
                               class="w-full px-3 py-2 pr-10 bg-green-100 border-2 border-green-300 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer"
                               @click="abrirModal()">
                        <button @click="abrirModal()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 hover:bg-green-200 rounded transition">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">CLIENTE</label>
                    <input type="text" 
                           x-model="notaSeleccionada.cliente"
                           readonly
                           placeholder="Sin datos"
                           class="w-full px-3 py-2 bg-green-100 border-2 border-green-300 rounded-lg text-gray-900 font-medium">
                </div>
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">NOMBRE PROYECTO</label>
                    <input type="text" 
                           x-model="notaSeleccionada.descripcion"
                           readonly
                           placeholder="Sin datos"
                           class="w-full px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-gray-900">
                </div>
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">ESTADO</label>
                    <input type="text" 
                           x-model="notaSeleccionada.estado"
                           readonly
                           placeholder="Sin datos"
                           class="w-full px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-gray-900">
                </div>
                
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 mt-4">
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">EJECUTIVO</label>
                    <input type="text" 
                           x-model="notaSeleccionada.vendedor"
                           readonly
                           placeholder="Sin datos"
                           class="w-full px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-gray-900">
                </div>
                
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">FECHA EMISIÓN</label>
                    <input type="text" 
                           x-model="notaSeleccionada.fecha_emision"
                           readonly
                           placeholder="Sin datos"
                           class="w-full px-3 py-2 bg-green-100 border-2 border-green-300 rounded-lg text-gray-900 font-medium">
                </div>
                
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">FECHA ENTREGA</label>
                    <input type="text" 
                           x-model="notaSeleccionada.fecha_entrega"
                           readonly
                           placeholder="Sin datos"
                           class="w-full px-3 py-2 bg-green-100 border-2 border-green-300 rounded-lg text-gray-900 font-medium">
                </div>
                
                <div class="lg:col-span-2" x-show="asignacion.fecha_asigna">
                    <label class="block text-sm font-bold text-gray-700 mb-2">FECHA ASIGNACIÓN</label>
                    <input type="text" 
                           x-model="asignacion.fecha_asigna"
                           readonly
                           class="w-full px-3 py-2 bg-green-100 border-2 border-green-300 rounded-lg text-gray-900 font-medium">
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6" x-show="asignacion.observaciones">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900">INSTRUCCIONES / OBSERVACIONES</h3>
        </div>
        <div class="p-6">
            <textarea rows="4" 
                      x-model="asignacion.observaciones"
                      readonly
                      class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 resize-none focus:outline-none"
                      placeholder="Sin observaciones..."></textarea>
        </div>
    </div>

    <div x-show="showModal" 
         x-cloak
         @keydown.escape.window="cerrarModal()"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
             @click="cerrarModal()"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click.away="cerrarModal()"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative">
                
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Buscar Nota de Venta</h3>
                        <button @click="cerrarModal()" 
                                class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6 border-b border-gray-200">
                    <div class="relative">
                        <input type="text" 
                               x-model="busqueda"
                               @input.debounce.500ms="buscarNotas()"
                               placeholder="Buscar por folio o cliente..."
                               class="w-full px-4 py-3 pl-12 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="w-6 h-6 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div x-show="cargando" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="text-gray-500 mt-2">Cargando...</p>
                    </div>

                    <div x-show="!cargando" class="space-y-3">
                        <template x-for="nota in notasVenta" :key="nota.nv_id">
                            <button @click="seleccionarNota(nota)"
                                    class="w-full text-left p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all group">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-blue-700" x-text="'NV-' + String(nota.nv_folio).padStart(6, '0')"></p>
                                        <p class="text-sm text-gray-600 mt-1" x-text="'Cliente: ' + nota.nv_cliente"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="'Fecha: ' + nota.fecha_emision_formateada"></p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full"
                                          :class="{
                                              'bg-green-100 text-green-700': nota.nv_estado === 'Activo',
                                              'bg-yellow-100 text-yellow-700': nota.nv_estado === 'Pendiente',
                                              'bg-blue-100 text-blue-700': nota.nv_estado === 'En Proceso'
                                          }"
                                          x-text="nota.nv_estado"></span>
                                </div>
                            </button>
                        </template>

                        <div x-show="!cargando && notasVenta.length === 0" class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">No se encontraron notas de venta</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <div class="flex items-center justify-between">
                        <button @click="cambiarPagina(paginaActual - 1)"
                                :disabled="paginaActual <= 1"
                                :class="paginaActual <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200'"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg transition">
                            Anterior
                        </button>
                        <span class="text-sm text-gray-600">
                            Página <span x-text="paginaActual"></span> de <span x-text="totalPaginas"></span>
                        </span>
                        <button @click="cambiarPagina(paginaActual + 1)"
                                :disabled="paginaActual >= totalPaginas"
                                :class="paginaActual >= totalPaginas ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200'"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg transition">
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@push('scripts')
<script>
function dashboardData() {
    return {
        showModal: false,
        cargando: false,
        busqueda: '',
        notasVenta: [],
        paginaActual: 1,
        totalPaginas: 1,
        notaSeleccionada: {
            folio: '',
            folio_formateado: '',
            cliente: '',
            descripcion: '',
            vendedor: '',
            estado: '',
            fecha_emision: '',
            fecha_entrega: ''
        },
        asignacion: {
            fecha_asigna: '',
            observaciones: ''
        },

        init() {
            // Verificar si hay un folio guardado en sessionStorage
            const folioGuardado = sessionStorage.getItem('dashboard_folio');
            
            if (folioGuardado) {
                // Cargar automáticamente esa nota
                this.cargarNotaPorFolio(folioGuardado);
                // Limpiar el sessionStorage
                sessionStorage.removeItem('dashboard_folio');
            } else {
                // Cargar notas normalmente
                this.cargarNotas();
            }
        },

        /**
         * Cargar nota específica por folio (NUEVO MÉTODO)
         */
        async cargarNotaPorFolio(folio) {
            this.cargando = true;
            try {
                const response = await fetch(`/dashboard/detalles-nv?folio=${folio}`);
                const data = await response.json();
                
                if (data.success) {
                    // Cargar datos de la nota
                    this.notaSeleccionada = data.data.nota_venta;
                    
                    // Cargar asignación si existe
                    if (data.data.asignacion) {
                        this.asignacion = data.data.asignacion;
                    } else {
                        this.asignacion = { fecha_asigna: '', observaciones: '' };
                    }
                    
                    showAlert('success', 'Nota de venta cargada exitosamente');
                } else {
                    showAlert('warning', 'No se pudo cargar la nota de venta');
                    // Cargar lista normal si falla
                    this.cargarNotas();
                }
            } catch (error) {
                showAlert('error', 'Error al cargar la nota de venta');
                console.error(error);
                // Cargar lista normal si hay error
                this.cargarNotas();
            } finally {
                this.cargando = false;
            }
        },

        abrirModal() {
            this.showModal = true;
            this.cargarNotas();
        },

        cerrarModal() {
            this.showModal = false;
            this.busqueda = '';
        },

        async cargarNotas(pagina = 1) {
            this.cargando = true;
            try {
                const response = await fetch(`/dashboard/buscar-notas?page=${pagina}&buscar=${this.busqueda}`);
                const data = await response.json();
                
                if (data.success) {
                    this.notasVenta = data.data.data;
                    this.paginaActual = data.data.current_page;
                    this.totalPaginas = data.data.last_page;
                }
            } catch (error) {
                showAlert('error', 'Error al cargar notas de venta');
                console.error(error);
            } finally {
                this.cargando = false;
            }
        },

        buscarNotas() {
            this.paginaActual = 1;
            this.cargarNotas(1);
        },

        cambiarPagina(pagina) {
            if (pagina >= 1 && pagina <= this.totalPaginas) {
                this.cargarNotas(pagina);
            }
        },

        async seleccionarNota(nota) {
            this.cargando = true;
            try {
                const response = await fetch(`/dashboard/detalles-nv?folio=${nota.nv_folio}`);
                const data = await response.json();
                
                if (data.success) {
                    // Cargar datos de la nota
                    this.notaSeleccionada = data.data.nota_venta;
                    
                    // Cargar asignación si existe
                    if (data.data.asignacion) {
                        this.asignacion = data.data.asignacion;
                    } else {
                        this.asignacion = { fecha_asigna: '', observaciones: '' };
                    }
                    
                    this.cerrarModal();
                    showAlert('success', 'Nota de venta cargada exitosamente');
                } else {
                    showAlert('error', data.message || 'Error al cargar detalles');
                }
            } catch (error) {
                showAlert('error', 'Error al obtener detalles de la nota');
                console.error(error);
            } finally {
                this.cargando = false;
            }
        },

        abrirPlano() {
            if (!this.notaSeleccionada.folio) return;
            const url = `dashboard/fft/${this.notaSeleccionada.folio}`;
            window.open(url, '_blank');
            showAlert('info', `Abriendo FFT: NV-${this.notaSeleccionada.folio_formateado}`);
        },

        abrirOC() {
            if (!this.notaSeleccionada.folio) return;
            const url = `dashboard/oc/${this.notaSeleccionada.folio}`;
            window.open(url, '_blank');
            showAlert('success', `Abriendo OC: NV-${this.notaSeleccionada.folio_formateado}`);
        },

        abrirHerraje() {
            if (!this.notaSeleccionada.folio) return;
            window.location.href = `dashboard/herrajes/${this.notaSeleccionada.folio}`; 
        },

        abrirChecklist() {
            if (!this.notaSeleccionada.folio) return;
            window.location.href = `/dashboard/checklist/${this.notaSeleccionada.folio}`;
        },
        
        abrirEvidencia() {
            if (!this.notaSeleccionada.folio) return;
            window.location.href = `/dashboard/evidencias/${this.notaSeleccionada.folio}`;
        }
    }
}
</script>
@endpush
@endsection
