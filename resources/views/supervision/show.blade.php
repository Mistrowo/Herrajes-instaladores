@extends('layouts.dashboard')

@section('page-title', 'Detalle NV ' . $asigna->nota_venta)
@section('page-subtitle', 'Revisión completa de la nota de venta')

@section('content')
<div class="p-6 space-y-6">

    {{-- Encabezado / info de la asignación --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('supervision.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h2 class="text-xl font-bold text-gray-900">Nota de Venta: {{ $asigna->nota_venta }}</h2>
                    @php
                        $colorMap = ['yellow'=>'bg-yellow-100 text-yellow-800','green'=>'bg-green-100 text-green-800','blue'=>'bg-blue-100 text-blue-800','gray'=>'bg-gray-100 text-gray-700','red'=>'bg-red-100 text-red-800'];
                        $badge    = $asigna->estadoBadge;
                        $colorCls = $colorMap[$badge['color']] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorCls }}">
                        {{ $badge['text'] }}
                    </span>
                </div>
                @if($asigna->sucursal)
                    <p class="text-sm text-gray-500 mt-1 ml-8">{{ $asigna->sucursal->nombre }}</p>
                @endif
            </div>
            <div class="text-right text-sm text-gray-500 space-y-1">
                <p>Asignado: <span class="font-medium text-gray-700">{{ $asigna->fechaAsignaFormateada }}</span></p>
                @if($asigna->fechaAceptaFormateada)
                    <p>Aceptado: <span class="font-medium text-gray-700">{{ $asigna->fechaAceptaFormateada }}</span></p>
                @endif
                @if($asigna->fechaTerminoFormateada)
                    <p>Terminado: <span class="font-medium text-gray-700">{{ $asigna->fechaTerminoFormateada }}</span></p>
                @endif
            </div>
        </div>

        {{-- Instaladores --}}
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Instaladores asignados</p>
            <div class="flex flex-wrap gap-2">
                @foreach($asigna->instaladores as $inst)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-800 text-sm rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $inst->nombre }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===================== HERRAJES ===================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Herrajes</h3>
                <p class="text-xs text-gray-500">Materiales y repuestos reportados</p>
            </div>
        </div>

        @if($herrajes->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <p class="text-sm">El instalador aún no ha registrado herrajes.</p>
            </div>
        @else
            @foreach($herrajes as $herraje)
                <div class="px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm">
                            <span class="font-medium text-gray-700">Registrado por:</span>
                            <span class="text-gray-600">{{ $herraje->instaladorNombre }}</span>
                            @if($herraje->sucursal)
                                <span class="ml-2 text-gray-400">— {{ $herraje->sucursal->nombre }}</span>
                            @endif
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            {{ $herraje->items_count ?? 0 }} ítem(s)
                        </div>
                    </div>

                    @if($herraje->items->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Código</th>
                                        <th class="px-4 py-2 text-left">Descripción</th>
                                        <th class="px-4 py-2 text-center">Unidad</th>
                                        <th class="px-4 py-2 text-right">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($herraje->items as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-600 font-mono text-xs">{{ $item->codigo ?? '—' }}</td>
                                            <td class="px-4 py-2 text-gray-800">{{ $item->descripcion }}</td>
                                            <td class="px-4 py-2 text-center text-gray-500">{{ $item->unidad }}</td>
                                            <td class="px-4 py-2 text-right text-gray-700">{{ $item->cantidad }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    {{-- ===================== EVIDENCIAS ===================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Evidencia Fotográfica</h3>
                <p class="text-xs text-gray-500">{{ $evidencias->count() }} foto(s) subida(s)</p>
            </div>
        </div>

        @if($evidencias->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <p class="text-sm">El instalador aún no ha subido evidencias fotográficas.</p>
            </div>
        @else
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($evidencias as $ev)
                        <div class="group relative">
                            <a href="{{ $ev->url }}" target="_blank" class="block">
                                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200 group-hover:border-blue-300 transition">
                                    <img src="{{ $ev->url }}"
                                         alt="{{ $ev->descripcion ?? 'Evidencia' }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                         onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-400\'><svg class=\'w-8 h-8\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
                                </div>
                            </a>
                            <div class="mt-1.5 space-y-0.5">
                                @if($ev->descripcion)
                                    <p class="text-xs text-gray-700 truncate" title="{{ $ev->descripcion }}">{{ $ev->descripcion }}</p>
                                @endif
                                <p class="text-xs text-gray-400">{{ $ev->instalador?->nombre ?? '' }}</p>
                                @if($ev->sucursal)
                                    <p class="text-xs text-gray-400 truncate">{{ $ev->sucursal->nombre }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- ===================== CHECKLIST ===================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Checklist de Instalación</h3>
                <p class="text-xs text-gray-500">
                    {{ $checklists->count() > 0 ? $checklists->count() . ' checklist(s) registrado(s)' : 'Aún no completado' }}
                </p>
            </div>
        </div>

        @if($checklists->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <p class="text-sm">El instalador aún no ha llenado el checklist.</p>
            </div>
        @else
            @foreach($checklists as $checklist)
                @php
                    $pct      = $checklist->getCompletionPercentage();
                    $seccPct  = $checklist->getCompletionBySection();
                    $errores  = $checklist->getErrorNames();
                    $hasErr   = $checklist->hasAnyErrors();
                    $barColor = $hasErr ? 'bg-red-500' : ($pct == 100 ? 'bg-green-500' : 'bg-yellow-400');
                    $pctColor = $hasErr ? 'text-red-600' : ($pct == 100 ? 'text-green-600' : 'text-yellow-600');
                @endphp

                <div class="{{ !$loop->last ? 'border-b border-gray-100' : '' }} p-6 space-y-5" x-data="{ detalle: false }">

                    {{-- Encabezado del checklist --}}
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $checklist->instalador?->nombre ?? 'Instalador desconocido' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $checklist->fechaCompletadoFormateada }}
                                @if($checklist->sucursal)
                                    — {{ $checklist->sucursal->nombre }}
                                @endif
                            </p>
                        </div>

                        {{-- % global --}}
                        <div class="flex items-center gap-3">
                            @if($hasErr)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ count($errores) }} error(es)
                                </span>
                            @endif
                            <div class="flex items-center gap-2">
                                <div class="w-28 bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $barColor }} transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-sm font-bold {{ $pctColor }}">{{ $pct }}%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjetas por sección --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($seccPct as $sec)
                            @php
                                $sPct   = $sec['percentage'];
                                $sColor = $sPct == 100 ? 'bg-green-500' : ($sPct >= 50 ? 'bg-yellow-400' : 'bg-gray-300');
                                $sTxt   = $sPct == 100 ? 'text-green-700' : ($sPct >= 50 ? 'text-yellow-700' : 'text-gray-500');
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-xs font-semibold text-gray-600 mb-2">{{ $sec['label'] }}</p>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $sColor }}" style="width: {{ $sPct }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold {{ $sTxt }} w-8 text-right">{{ $sPct }}%</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $sec['completed'] }}/{{ $sec['total'] }} respondidos</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Errores detectados --}}
                    @if($hasErr)
                        <div class="bg-red-50 border border-red-100 rounded-lg p-3">
                            <p class="text-xs font-semibold text-red-700 mb-2">Errores reportados por el instalador:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($errores as $campo => $nombre)
                                    @php $obsKey = $campo . '_obs'; @endphp
                                    <div>
                                        <span class="inline-block px-2.5 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                            {{ $nombre }}
                                        </span>
                                        @if($checklist->$obsKey)
                                            <p class="text-xs text-red-500 mt-0.5 ml-1">{{ $checklist->$obsKey }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Observaciones generales --}}
                    @if($checklist->observaciones)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Observaciones generales</p>
                            <p class="text-sm text-gray-700">{{ $checklist->observaciones }}</p>
                        </div>
                    @endif

                    {{-- Toggle detalle completo --}}
                    <div>
                        <button @click="detalle = !detalle"
                                class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 transition-transform" :class="detalle ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            <span x-text="detalle ? 'Ocultar detalle completo' : 'Ver detalle completo'"></span>
                        </button>

                        <div x-show="detalle" x-transition class="mt-4 space-y-4" style="display:none">
                            @php
                                $secciones = [
                                    'Proyecto / Pedido' => [
                                        ['campo'=>'rectificacion_medidas',     'obs'=>'rectificacion_medidas_obs',     'label'=>'Rectificación de medidas'],
                                        ['campo'=>'planos_actualizados',       'obs'=>'planos_actualizados_obs',       'label'=>'Planos actualizados'],
                                        ['campo'=>'planos_muebles_especiales', 'obs'=>'planos_muebles_especiales_obs', 'label'=>'Planos muebles especiales'],
                                        ['campo'=>'modificaciones_realizadas', 'obs'=>'modificaciones_realizadas_obs', 'label'=>'Modificaciones realizadas'],
                                        ['campo'=>'despacho_integral',         'obs'=>'despacho_integral_obs',         'label'=>'Despacho integral'],
                                        ['campo'=>'telefono',                  'obs'=>'telefono_obs',                  'label'=>'Teléfono'],
                                    ],
                                    'Errores Detectados' => [
                                        ['campo'=>'errores_ventas',       'obs'=>'errores_ventas_obs',       'label'=>'Errores de ventas',       'es_error'=>true],
                                        ['campo'=>'errores_diseno',       'obs'=>'errores_diseno_obs',       'label'=>'Errores de diseño',       'es_error'=>true],
                                        ['campo'=>'errores_rectificacion','obs'=>'errores_rectificacion_obs','label'=>'Errores de rectificación','es_error'=>true],
                                        ['campo'=>'errores_produccion',   'obs'=>'errores_produccion_obs',   'label'=>'Errores de producción',   'es_error'=>true],
                                        ['campo'=>'errores_proveedor',    'obs'=>'errores_proveedor_obs',    'label'=>'Errores de proveedor',    'es_error'=>true],
                                        ['campo'=>'errores_despacho',     'obs'=>'errores_despacho_obs',     'label'=>'Errores de despacho',     'es_error'=>true],
                                        ['campo'=>'errores_instalacion',  'obs'=>'errores_instalacion_obs',  'label'=>'Errores de instalación',  'es_error'=>true],
                                        ['campo'=>'errores_otro',         'obs'=>'errores_otro_obs',         'label'=>'Otro error',              'es_error'=>true],
                                    ],
                                    'Estado de Obra' => [
                                        ['campo'=>'instalacion_cielo',    'obs'=>'instalacion_cielo_obs',    'label'=>'Instalación de cielo'],
                                        ['campo'=>'instalacion_piso',     'obs'=>'instalacion_piso_obs',     'label'=>'Instalación de piso'],
                                        ['campo'=>'remate_muros',         'obs'=>'remate_muros_obs',         'label'=>'Remate de muros'],
                                        ['campo'=>'nivelacion_piso',      'obs'=>'nivelacion_piso_obs',      'label'=>'Nivelación de piso'],
                                        ['campo'=>'muros_plomo',          'obs'=>'muros_plomo_obs',          'label'=>'Muros a plomo'],
                                        ['campo'=>'instalacion_electrica','obs'=>'instalacion_electrica_obs','label'=>'Instalación eléctrica'],
                                        ['campo'=>'instalacion_voz_dato', 'obs'=>'instalacion_voz_dato_obs', 'label'=>'Instalación voz y dato'],
                                    ],
                                    'Inspección Final' => [
                                        ['campo'=>'paneles_alineados',    'obs'=>'paneles_alineados_obs',    'label'=>'Paneles alineados'],
                                        ['campo'=>'nivelacion_cubiertas', 'obs'=>'nivelacion_cubiertas_obs', 'label'=>'Nivelación de cubiertas'],
                                        ['campo'=>'pasacables_instalados','obs'=>'pasacables_instalados_obs','label'=>'Pasacables instalados'],
                                        ['campo'=>'limpieza_cubiertas',   'obs'=>'limpieza_cubiertas_obs',   'label'=>'Limpieza cubiertas'],
                                        ['campo'=>'limpieza_cajones',     'obs'=>'limpieza_cajones_obs',     'label'=>'Limpieza cajones'],
                                        ['campo'=>'limpieza_piso',        'obs'=>'limpieza_piso_obs',        'label'=>'Limpieza piso'],
                                        ['campo'=>'llaves_instaladas',    'obs'=>'llaves_instaladas_obs',    'label'=>'Llaves instaladas'],
                                        ['campo'=>'funcionamiento_mueble','obs'=>'funcionamiento_mueble_obs','label'=>'Funcionamiento del mueble'],
                                        ['campo'=>'puntos_electricos',    'obs'=>'puntos_electricos_obs',    'label'=>'Puntos eléctricos'],
                                        ['campo'=>'sillas_ubicadas',      'obs'=>'sillas_ubicadas_obs',      'label'=>'Sillas ubicadas'],
                                        ['campo'=>'accesorios',           'obs'=>'accesorios_obs',           'label'=>'Accesorios'],
                                        ['campo'=>'check_herramientas',   'obs'=>'check_herramientas_obs',   'label'=>'Herramientas retiradas'],
                                    ],
                                ];
                            @endphp

                            @foreach($secciones as $titulo => $items)
                                <div>
                                    <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $titulo }}</h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1">
                                        @foreach($items as $item)
                                            @php
                                                $valor   = $checklist->{$item['campo']};
                                                $obs     = $checklist->{$item['obs']} ?? null;
                                                $esError = $item['es_error'] ?? false;
                                                if ($valor === 'SI') {
                                                    $chip = $esError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                                                } elseif ($valor === 'NO') {
                                                    $chip = $esError ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';
                                                } else {
                                                    $chip = 'bg-gray-100 text-gray-400';
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between px-2 py-1.5 rounded hover:bg-gray-50 gap-2">
                                                <span class="text-xs text-gray-700 truncate">{{ $item['label'] }}</span>
                                                <span class="flex-shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $chip }}">
                                                    {{ $valor ?? '—' }}
                                                </span>
                                            </div>
                                            @if($obs)
                                                <p class="text-xs text-gray-400 px-2 -mt-1 mb-1">{{ $obs }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection
