@extends('layouts.dashboard')

@section('title', 'Evidencia Fotográfica')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8" x-data="evidenciaData()">
    
    <!-- Header Principal -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex items-center justify-between mb-6">
            <button onclick="volverDashboard()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-blue-200 hover:border-blue-500 text-gray-700 hover:text-blue-600 font-medium rounded-lg shadow-sm hover:shadow-md transition-all">

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
                <span class="text-gray-700 font-medium">Evidencias</span>
            </div>
        </div>

       

        <!-- Filtro de Sucursales -->
        @if($sucursales->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-gray-700 font-medium">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrar por sucursal:
                </div>
                <select id="filtroSucursal"
                        onchange="filtrarPorSucursal(this.value)"
                        class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">📍 Todas las sucursales</option>
                    <option value="0" {{ $sucursalActual === 0 ? 'selected' : '' }}>❌ Sin sucursal</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}" {{ $sucursalActual == $sucursal->id ? 'selected' : '' }}>
                            📍 {{ $sucursal->nombre }} - {{ $sucursal->comuna }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Formulario de Subida Mejorado -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Subir Nueva Evidencia</h3>
                        <p class="text-sm text-gray-500">Agrega fotografías de la instalación</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('evidencias.store', $folio) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Columna Izquierda: Imagen -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">
                            📸 Imagen <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative">
                            <input id="imagen" name="imagen" type="file" class="hidden" accept="image/*" required />
                            <label for="imagen" 
                                   class="group flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gradient-to-br from-gray-50 to-gray-100 hover:from-blue-50 hover:to-indigo-50 hover:border-blue-400 transition-all duration-300">
                                <div id="preview-container" class="flex flex-col items-center justify-center py-6">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 mb-1">Haz clic o arrastra una imagen</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP hasta 5MB</p>
                                </div>
                            </label>
                        </div>

                        <div id="preview" class="hidden">
                            <img id="preview-img" class="w-full h-64 object-cover rounded-xl shadow-md" />
                            <button type="button" onclick="limpiarImagen()" 
                                    class="mt-2 text-sm text-red-600 hover:text-red-700 font-medium">
                                ✕ Cambiar imagen
                            </button>
                        </div>
                    </div>

                    <!-- Columna Derecha: Detalles -->
                    <div class="space-y-4">
                        <!-- Sucursal -->
                        @if($sucursales->count() > 0)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                📍 Sucursal <span class="text-xs text-gray-500 font-normal">(opcional)</span>
                            </label>
                            <select name="sucursal_id"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Sin sucursal específica</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}"
                                        {{ $asignacion && $asignacion->sucursal_id == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->nombre }} - {{ $sucursal->comuna }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2 flex items-start gap-1">
                                <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Si no seleccionas, la evidencia no estará asociada a ninguna sucursal
                            </p>
                        </div>
                        @endif

                        <!-- Descripción -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                💬 Descripción <span class="text-xs text-gray-500 font-normal">(opcional)</span>
                            </label>
                            <textarea name="descripcion" rows="5"
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                      placeholder="Ej: Instalación completa en sala de estar, vista frontal..."></textarea>
                        </div>

                        <!-- Botón Submit -->
                        <button type="submit"
                                class="w-full px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Subir Evidencia
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Galería de Evidencias -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Galería de Evidencias</h3>
                            <p class="text-sm text-gray-500">{{ $evidencias->count() }} fotografías subidas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if($evidencias->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay evidencias fotográficas</h3>
                        <p class="text-gray-500 text-sm">Comienza subiendo tu primera fotografía usando el formulario de arriba</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($evidencias as $evidencia)
                            <div class="group relative bg-white rounded-xl overflow-hidden border-2 border-gray-200 hover:border-blue-400 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                
                                <!-- Imagen Principal -->
                                <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 75%;">
                                    <button type="button"
                                            @click="verImagen('{{ $evidencia->url }}', @js($evidencia->descripcion ?? ''))"
                                            class="absolute inset-0 w-full h-full">
                                        <img src="{{ $evidencia->url }}"
                                             alt="Evidencia"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        
                                        <!-- Overlay Hover -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                                            <span class="text-white text-sm font-medium flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                                Ver imagen
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Badge Sucursal -->
                                    <div class="absolute top-3 left-3 z-10">
                                        @if($evidencia->sucursal)
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-full shadow-lg">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ Str::limit($evidencia->sucursal->nombre, 20) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Sin sucursal
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Botón Eliminar -->
                                    <button type="button"
                                            onclick="confirmarEliminacion({{ $evidencia->id }})"
                                            class="absolute top-3 right-3 z-10 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center shadow-lg transform hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Contenido de la Card -->
                                <div class="p-4 space-y-3">
                                    <!-- Metadata -->
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $evidencia->fecha_subida->format('d/m/Y H:i') }}
                                    </div>

                                    @if($evidencia->instalador)
                                    <div class="flex items-center gap-2 text-xs text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="font-medium">{{ $evidencia->instalador->nombre }}</span>
                                    </div>
                                    @endif

                                    <!-- Descripción -->
                                    @if($evidencia->descripcion)
                                    <p class="text-sm text-gray-600 line-clamp-2 italic">
                                        "{{ $evidencia->descripcion }}"
                                    </p>
                                    @else
                                    <p class="text-sm text-gray-400 italic">Sin descripción</p>
                                    @endif

                                    <!-- Cambiar Sucursal -->
                                    <div class="pt-3 border-t border-gray-200">
                                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                                            Cambiar sucursal:
                                        </label>
                                        <select onchange="cambiarSucursalEvidencia({{ $evidencia->id }}, this.value)"
                                                class="w-full px-3 py-2 text-xs border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                            <option value="">❌ Sin sucursal</option>
                                            @foreach($sucursales as $suc)
                                                <option value="{{ $suc->id }}" {{ $evidencia->sucursal_id == $suc->id ? 'selected' : '' }}>
                                                    📍 {{ $suc->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <form id="delete-form-{{ $evidencia->id }}"
                                      action="{{ route('evidencias.destroy', $evidencia->id) }}"
                                      method="POST"
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Ver Imagen Grande -->
    <div x-show="showImageModal"
         x-cloak
         @click="cerrarImagenModal()"
         @keydown.escape.window="cerrarImagenModal()"
         class="fixed inset-0 bg-black/95 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div @click.stop class="max-w-6xl w-full">
            <div class="flex justify-end mb-4">
                <button @click="cerrarImagenModal()"
                        class="group w-12 h-12 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transition-all">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <img :src="imagenActual"
                 alt="Evidencia"
                 class="w-full h-auto max-h-[80vh] object-contain rounded-2xl shadow-2xl">

            <div x-show="descripcionActual" class="mt-6 bg-white/10 backdrop-blur-md rounded-xl p-6">
                <p x-text="descripcionActual" class="text-white text-center text-lg font-medium"></p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function evidenciaData() {
    return {
        showImageModal: false,
        imagenActual: '',
        descripcionActual: '',

        verImagen(url, descripcion) {
            this.imagenActual = url;
            this.descripcionActual = descripcion || '';
            this.showImageModal = true;
        },

        cerrarImagenModal() {
            this.showImageModal = false;
        }
    }
}

// Preview imagen mejorado
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('imagen');
    const previewContainer = document.getElementById('preview-container');
    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('preview-img');
    
    if (!input) return;

    input.addEventListener('change', function(e) {
        const file = e.target.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            previewContainer.classList.add('hidden');
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    });
});

function limpiarImagen() {
    const input = document.getElementById('imagen');
    const previewContainer = document.getElementById('preview-container');
    const preview = document.getElementById('preview');
    
    input.value = '';
    previewContainer.classList.remove('hidden');
    preview.classList.add('hidden');
}

function filtrarPorSucursal(sucursalId) {
    const url = new URL(window.location.href);
    if (sucursalId !== '') {
        url.searchParams.set('sucursal_id', sucursalId);
    } else {
        url.searchParams.delete('sucursal_id');
    }
    window.location.href = url.toString();
}

async function cambiarSucursalEvidencia(evidenciaId, sucursalId) {
    try {
        const response = await fetch(`/dashboard/evidencias/${evidenciaId}/sucursal`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ sucursal_id: sucursalId || null })
        });

        const data = await response.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: 'Sucursal actualizada correctamente',
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => window.location.reload(), 1200);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo actualizar'
            });
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al actualizar la sucursal'
        });
    }
}

function confirmarEliminacion(evidenciaId) {
    Swal.fire({
        title: '¿Eliminar evidencia?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + evidenciaId).submit();
        }
    });
}

function volverDashboard() {
    sessionStorage.setItem('dashboard_folio', '{{ $folio }}');
    window.location.href = '{{ route("dashboard") }}';
}

// Mostrar alertas de sesión
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

<style>
[x-cloak] { display: none !important; }

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection