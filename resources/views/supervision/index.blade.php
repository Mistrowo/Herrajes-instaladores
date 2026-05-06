@extends('layouts.dashboard')

@section('page-title', 'Supervisión')
@section('page-subtitle', 'Seguimiento de herrajes, evidencias y checklists por nota de venta')

@section('content')
<div class="p-6 space-y-6">

    {{-- Filtros --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('supervision.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Buscar nota de venta</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Ej: 123456"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Estado</label>
                <select name="estado" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="pendiente"   {{ request('estado') == 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                    <option value="aceptada"    {{ request('estado') == 'aceptada'    ? 'selected' : '' }}>Aceptada</option>
                    <option value="en_proceso"  {{ request('estado') == 'en_proceso'  ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada"  {{ request('estado') == 'completada'  ? 'selected' : '' }}>Completada</option>
                    <option value="rechazada"   {{ request('estado') == 'rechazada'   ? 'selected' : '' }}>Rechazada</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Filtrar
                </button>
                @if(request('buscar') || request('estado'))
                    <a href="{{ route('supervision.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabla de asignaciones --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Notas de Venta Asignadas</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $asignaciones->total() }} asignaciones en total</p>
        </div>

        @if($asignaciones->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">No hay asignaciones que mostrar</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Nota de Venta</th>
                            <th class="px-6 py-3 text-left">Instalador(es)</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-center">Herrajes</th>
                            <th class="px-6 py-3 text-center">Evidencias</th>
                            <th class="px-6 py-3 text-center">Checklist</th>
                            <th class="px-6 py-3 text-left">Fecha Asignación</th>
                            <th class="px-6 py-3 text-center">Ver</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($asignaciones as $asigna)
                            @php
                                $herraje      = $herrajesPorAsigna[$asigna->id] ?? null;
                                $evidencia    = $evidenciasPorNota[$asigna->nota_venta] ?? null;
                                $clists       = $checklistsPorNota[$asigna->nota_venta] ?? collect();
                                $badge        = $asigna->estadoBadge;
                                $totalInst    = $asigna->cantidadInstaladores();
                                $respondieron = $clists->count();
                                $avgPct       = $respondieron ? round($clists->avg(fn($c) => $c->getCompletionPercentage())) : 0;
                                $tieneError   = $clists->contains(fn($c) => $c->hasAnyErrors());
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Nota de Venta --}}
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $asigna->nota_venta }}</span>
                                    @if($asigna->sucursal)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $asigna->sucursal->nombre }}</p>
                                    @endif
                                </td>

                                {{-- Instaladores --}}
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        @foreach($asigna->instaladores as $inst)
                                            <p class="text-gray-700">{{ $inst->nombre }}</p>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4">
                                    @php
                                        $colorMap = [
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'green'  => 'bg-green-100 text-green-800',
                                            'blue'   => 'bg-blue-100 text-blue-800',
                                            'gray'   => 'bg-gray-100 text-gray-700',
                                            'red'    => 'bg-red-100 text-red-800',
                                        ];
                                        $colorClass = $colorMap[$badge['color']] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $badge['text'] }}
                                    </span>
                                </td>

                                {{-- Herrajes --}}
                                <td class="px-6 py-4 text-center">
                                    @if($herraje)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mx-auto">
                                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            <span class="text-xs text-gray-500 mt-1">{{ $herraje->items ?? 0 }} ítem(s)</span>
                                        </div>
                                    @else
                                        <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mx-auto">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                    @endif
                                </td>

                                {{-- Evidencias --}}
                                <td class="px-6 py-4 text-center">
                                    @if($evidencia && $evidencia->total > 0)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mx-auto">
                                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </span>
                                            <span class="text-xs text-gray-500 mt-1">{{ $evidencia->total }} foto(s)</span>
                                        </div>
                                    @else
                                        <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mx-auto">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                    @endif
                                </td>

                                {{-- Checklist --}}
                                <td class="px-6 py-4 text-center">
                                    @if($respondieron > 0)
                                        @php
                                            $barColor = $tieneError ? 'bg-red-500' : ($avgPct == 100 ? 'bg-green-500' : 'bg-yellow-400');
                                            $txtColor = $tieneError ? 'text-red-600' : ($avgPct == 100 ? 'text-green-600' : 'text-yellow-600');
                                        @endphp
                                        <div class="inline-flex flex-col items-center gap-1 w-full max-w-[90px] mx-auto">
                                            <span class="text-xs font-bold {{ $txtColor }}">{{ $avgPct }}% respondido</span>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full {{ $barColor }}" style="width: {{ $avgPct }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-400">
                                                {{ $respondieron }}/{{ $totalInst }}
                                                {{ $totalInst == 1 ? 'instalador' : 'instaladores' }}
                                            </span>
                                            @if($tieneError)
                                                <span class="text-xs text-red-500 font-medium">Con errores</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mx-auto">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                    @endif
                                </td>

                                {{-- Fecha --}}
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $asigna->fechaAsignaFormateada }}
                                </td>

                                {{-- Acción --}}
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('supervision.show', $asigna->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($asignaciones->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $asignaciones->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
