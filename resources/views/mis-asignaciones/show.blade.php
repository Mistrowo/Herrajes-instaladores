<!-- Vista de detalles para instaladores -->
<div class="space-y-4">
    <!-- Información General -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Información General</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-500">Nota de Venta</p>
                <p class="text-sm font-medium text-gray-900">{{ $asignacion->nota_venta }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Estado</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                             bg-{{ $asignacion->estado_badge['color'] }}-100 
                             text-{{ $asignacion->estado_badge['color'] }}-800">
                    {{ $asignacion->estado_badge['text'] }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Solicitado por</p>
                <p class="text-sm font-medium text-gray-900">{{ $asignacion->solicita }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Fecha de Asignación</p>
                <p class="text-sm font-medium text-gray-900">{{ $asignacion->fecha_asigna_formateada }}</p>
            </div>
            @if($asignacion->fecha_acepta)
            <div>
                <p class="text-xs text-gray-500">Fecha de Aceptación</p>
                <p class="text-sm font-medium text-gray-900">{{ $asignacion->fecha_acepta_formateada }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Equipo de Instalación -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Equipo de Instalación</h4>
        <div class="space-y-2">
            @if($asignacion->instalador1)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-{{ $asignacion->instalador1->id == auth()->id() ? 'blue' : 'gray' }}-100 
                            flex items-center justify-center text-{{ $asignacion->instalador1->id == auth()->id() ? 'blue' : 'gray' }}-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador1->nombre, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador1->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador1->usuario }}</p>
                </div>
                @if($asignacion->instalador1->id == auth()->id())
                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded font-medium">Tú</span>
                @endif
            </div>
            @endif

            @if($asignacion->instalador2)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-{{ $asignacion->instalador2->id == auth()->id() ? 'blue' : 'gray' }}-100 
                            flex items-center justify-center text-{{ $asignacion->instalador2->id == auth()->id() ? 'blue' : 'gray' }}-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador2->nombre, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador2->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador2->usuario }}</p>
                </div>
                @if($asignacion->instalador2->id == auth()->id())
                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded font-medium">Tú</span>
                @endif
            </div>
            @endif

            @if($asignacion->instalador3)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-{{ $asignacion->instalador3->id == auth()->id() ? 'blue' : 'gray' }}-100 
                            flex items-center justify-center text-{{ $asignacion->instalador3->id == auth()->id() ? 'blue' : 'gray' }}-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador3->nombre, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador3->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador3->usuario }}</p>
                </div>
                @if($asignacion->instalador3->id == auth()->id())
                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded font-medium">Tú</span>
                @endif
            </div>
            @endif

            @if($asignacion->instalador4)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-{{ $asignacion->instalador4->id == auth()->id() ? 'blue' : 'gray' }}-100 
                            flex items-center justify-center text-{{ $asignacion->instalador4->id == auth()->id() ? 'blue' : 'gray' }}-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador4->nombre, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador4->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador4->usuario }}</p>
                </div>
                @if($asignacion->instalador4->id == auth()->id())
                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded font-medium">Tú</span>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Información de la Nota de Venta -->
    @if($notaVenta)
    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
        <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Información del Cliente
        </h4>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-xs text-blue-600 font-medium">Cliente</p>
                <p class="text-gray-900">{{ $notaVenta->nv_cliente }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 font-medium">Estado NV</p>
                <p class="text-gray-900">{{ $notaVenta->nv_estado }}</p>
            </div>
            @if($notaVenta->nv_descripcion)
            <div class="col-span-2">
                <p class="text-xs text-blue-600 font-medium">Descripción</p>
                <p class="text-gray-900">{{ $notaVenta->nv_descripcion }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs text-blue-600 font-medium">Fecha Entrega</p>
                <p class="text-gray-900">{{ $notaVenta->fecha_entrega_formateada }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Observaciones -->
    @if($asignacion->observaciones)
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">Observaciones</h4>
        <p class="text-sm text-gray-600">{{ $asignacion->observaciones }}</p>
    </div>
    @endif

    <!-- Acciones rápidas -->
    <div class="bg-gray-50 rounded-lg p-4 border-t-2 border-gray-200">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Acciones Rápidas</h4>
        <div class="flex flex-wrap gap-2">
            @if($asignacion->estado === 'pendiente')
                <form method="POST" action="{{ route('mis-asignaciones.aceptar', $asignacion->id) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700 transition-colors">
                        ✓ Aceptar Asignación
                    </button>
                </form>

                <button onclick="if(confirm('¿Rechazar esta asignación?')) { document.getElementById('form-rechazar-{{ $asignacion->id }}').submit(); }" 
                        class="px-3 py-1.5 bg-red-100 text-red-800 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                    ✕ Rechazar
                </button>
                <form id="form-rechazar-{{ $asignacion->id }}" method="POST" action="{{ route('mis-asignaciones.rechazar', $asignacion->id) }}" class="hidden">
                    @csrf
                    @method('PATCH')
                </form>
            @endif

            @if($asignacion->estado === 'aceptada')
                <form method="POST" action="{{ route('mis-asignaciones.en-proceso', $asignacion->id) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors">
                        ⚡ Iniciar Trabajo
                    </button>
                </form>
            @endif

            @if($asignacion->estado === 'en_proceso')
                <button onclick="if(confirm('¿Marcar como completada?')) { document.getElementById('form-completar-{{ $asignacion->id }}').submit(); }" 
                        class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700 transition-colors">
                    ✓ Completar Trabajo
                </button>
                <form id="form-completar-{{ $asignacion->id }}" method="POST" action="{{ route('mis-asignaciones.completar', $asignacion->id) }}" class="hidden">
                    @csrf
                    @method('PATCH')
                </form>
            @endif

            @if($asignacion->estado === 'completada')
                <div class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                    ✓ Trabajo Completado
                </div>
            @endif

            @if($asignacion->estado === 'rechazada')
                <div class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs font-medium">
                    ✕ Asignación Rechazada
                </div>
            @endif
        </div>
    </div>
</div>