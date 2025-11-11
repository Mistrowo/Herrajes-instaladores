<!-- Este contenido se carga dentro del modal de detalles -->
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
                <p class="text-xs text-gray-500">Solicita</p>
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
            @if($asignacion->terminado && $asignacion->fecha_termino)
            <div>
                <p class="text-xs text-gray-500">Fecha de Término</p>
                <p class="text-sm font-medium text-gray-900">{{ $asignacion->fecha_termino->format('d-m-Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Instaladores Asignados -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Instaladores Asignados ({{ $asignacion->cantidadInstaladores() }})</h4>
        <div class="space-y-2">
            @if($asignacion->instalador1)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador1->nombre, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador1->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador1->usuario }}</p>
                </div>
                <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Instalador 1</span>
            </div>
            @endif

            @if($asignacion->instalador2)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador2->nombre, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador2->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador2->usuario }}</p>
                </div>
                <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Instalador 2</span>
            </div>
            @endif

            @if($asignacion->instalador3)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador3->nombre, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador3->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador3->usuario }}</p>
                </div>
                <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Instalador 3</span>
            </div>
            @endif

            @if($asignacion->instalador4)
            <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($asignacion->instalador4->nombre, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $asignacion->instalador4->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $asignacion->instalador4->usuario }}</p>
                </div>
                <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Instalador 4</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Observaciones -->
    @if($asignacion->observaciones)
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">Observaciones</h4>
        <p class="text-sm text-gray-600">{{ $asignacion->observaciones }}</p>
    </div>
    @endif

    <!-- Información de la Nota de Venta (si existe) -->
    @if($notaVenta)
    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
        <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Información de la Nota de Venta
        </h4>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-xs text-blue-600 font-medium">Cliente</p>
                <p class="text-gray-900">{{ $notaVenta->nv_cliente }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 font-medium">Estado</p>
                <p class="text-gray-900">{{ $notaVenta->nv_estado }}</p>
            </div>
            @if($notaVenta->nv_descripcion)
            <div class="col-span-2">
                <p class="text-xs text-blue-600 font-medium">Descripción</p>
                <p class="text-gray-900">{{ $notaVenta->nv_descripcion }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs text-blue-600 font-medium">Fecha Emisión</p>
                <p class="text-gray-900">{{ $notaVenta->fecha_emision_formateada }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 font-medium">Fecha Entrega</p>
                <p class="text-gray-900">{{ $notaVenta->fecha_entrega_formateada }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Acciones de cambio de estado -->
    <div class="bg-gray-50 rounded-lg p-4 border-t-2 border-gray-200">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Cambiar Estado</h4>
        <div class="flex flex-wrap gap-2">
            @if($asignacion->estado !== 'pendiente')
            <form method="POST" action="{{ route('asignar.cambiar-estado', [$asignacion->id, 'pendiente']) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium hover:bg-yellow-200 transition-colors">
                    Marcar como Pendiente
                </button>
            </form>
            @endif

            @if($asignacion->estado !== 'aceptada')
            <form method="POST" action="{{ route('asignar.cambiar-estado', [$asignacion->id, 'aceptada']) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-medium hover:bg-green-200 transition-colors">
                    Marcar como Aceptada
                </button>
            </form>
            @endif

            @if($asignacion->estado !== 'en_proceso')
            <form method="POST" action="{{ route('asignar.cambiar-estado', [$asignacion->id, 'en_proceso']) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium hover:bg-blue-200 transition-colors">
                    Marcar como En Proceso
                </button>
            </form>
            @endif

            @if($asignacion->estado !== 'completada')
            <form method="POST" action="{{ route('asignar.cambiar-estado', [$asignacion->id, 'completada']) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-lg text-xs font-medium hover:bg-gray-200 transition-colors">
                    Marcar como Completada
                </button>
            </form>
            @endif

            @if($asignacion->estado !== 'rechazada')
            <form method="POST" action="{{ route('asignar.cambiar-estado', [$asignacion->id, 'rechazada']) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-800 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                    Marcar como Rechazada
                </button>
            </form>
            @endif
        </div>
    </div>
</div>