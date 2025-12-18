<div class="text-center py-8">
    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <p class="text-red-600 font-medium mb-2">{{ $message ?? 'Error al cargar los detalles' }}</p>
    <button onclick="cerrarModalDetalles()" class="mt-4 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
        Cerrar
    </button>
</div>