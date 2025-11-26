@extends('layouts.dashboard')

@section('title', 'Evidencia Fotográfica')
@section('page-title', 'Evidencia Fotográfica')
@section('page-subtitle', 'NV-' . str_pad($nota->nv_folio, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
  <div class="mb-6">
    

    <button onclick="volverDashboard()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver
    </button>
        </div>
  
    <!-- Formulario de Subida -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Subir Nueva Evidencia</h3>

        <form action="{{ route('evidencias.store', $nota->nv_folio) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen *</label>
                <div class="flex items-center justify-center w-full">
                    <label for="imagen" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="preview-container">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm text-gray-500">Haz clic para subir o arrastra una imagen</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP hasta 5MB</p>
                        </div>
                        <input id="imagen" name="imagen" type="file" class="hidden" accept="image/*" required />
                    </label>
                </div>
                <div id="preview" class="mt-3 hidden">
                    <img id="preview-img" class="max-h-48 rounded-lg mx-auto" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción de la imagen (opcional)</label>
                <textarea name="descripcion" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ej: Foto de instalación final en living..."></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Subir Evidencia
                </button>
            </div>
        </form>
    </div>

    <!-- Galería de Evidencias -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Evidencias Subidas ({{ $evidencias->count() }})</h3>

        @if($evidencias->isEmpty())
            <p class="text-center text-gray-500 py-8">Aún no hay evidencias fotográficas.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($evidencias as $evidencia)
                    <div class="group relative bg-gray-50 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition">
                        <a href="{{ $evidencia->url }}" target="_blank" class="block">
                            <img src="{{ $evidencia->url }}" alt="Evidencia" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4">
                            <p class="text-xs text-gray-500">
                                Subido por <span class="font-medium text-gray-700">{{ $evidencia->instalador->nombre }}</span>
                                <br>
                                {{ $evidencia->fecha_subida->format('d/m/Y H:i') }}
                            </p>
                            @if($evidencia->descripcion)
                                <p class="text-sm text-gray-700 mt-2 italic">“{{ $evidencia->descripcion }}”</p>
                            @endif
                        </div>

                        <form id="delete-form-{{ $evidencia->id }}" 
      action="{{ route('evidencias.destroy', $evidencia->id) }}" 
      method="POST" 
      class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
    @csrf
    @method('DELETE')
    
    <button type="button" 
            onclick="confirmarEliminacion({{ $evidencia->id }})"
            class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
    </button>
</form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>

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
    const folio = '{{ $asignacion->nota_venta }}';
    sessionStorage.setItem('dashboard_folio', folio);
    
    window.location.href = '{{ route("dashboard") }}';
}
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                const img = document.getElementById('preview-img');
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection