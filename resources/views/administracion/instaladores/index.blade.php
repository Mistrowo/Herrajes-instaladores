@extends('layouts.dashboard')

@section('page-title', 'Administrar Instaladores')
@section('page-subtitle', 'Alta, edición, búsqueda y estado')

@section('content')
<div class="p-6 space-y-6">

    {{-- Toolbar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <form method="GET" action="{{ route('administracion.instaladores.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 w-full md:max-w-3xl">
                <div class="md:col-span-2">
                    <label class="block text-xs uppercase font-semibold text-gray-500 mb-1">Buscar</label>
                    <input type="text" name="q" value="{{ $q }}"
                        placeholder="Nombre, usuario, correo, RUT, teléfono"
                        class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-xs uppercase font-semibold text-gray-500 mb-1">Por página</label>
                    <select name="per_page" class="w-full px-3 py-2 rounded-xl border">
                        @foreach([10,20,50,100] as $n)
                            <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border bg-gray-50">
                        <input type="checkbox" name="withTrashed" value="1" @checked($withTrashed) class="rounded">
                        <span class="text-sm">Ver eliminados</span>
                    </label>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-medium transition-colors">
                            Filtrar
                        </button>
                        <a href="{{ route('administracion.instaladores.index') }}" 
                           class="px-4 py-2 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium transition-colors">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <a href="{{ route('administracion.instaladores.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nuevo Instalador
            </a>
        </div>
    </div>

    {{-- Info de resultados --}}
    @if($q || $withTrashed)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="flex-1">
            <p class="text-sm text-blue-800">
                <strong>Filtros activos:</strong>
                @if($q)
                    Búsqueda: "{{ $q }}"
                @endif
                @if($withTrashed)
                    {{ $q ? ' | ' : '' }}Mostrando eliminados
                @endif
            </p>
            <p class="text-xs text-blue-600 mt-1">
                Se encontraron <strong>{{ $instaladores->total() }}</strong> resultado(s)
            </p>
        </div>
        <a href="{{ route('administracion.instaladores.index') }}" 
           class="text-sm text-blue-600 hover:text-blue-800 font-medium underline">
            Limpiar filtros
        </a>
    </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50/80 backdrop-blur">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-gray-600 font-semibold">Instalador</th>
                        <th class="px-4 py-3 text-gray-600 font-semibold">Usuario / Correo</th>
                        <th class="px-4 py-3 text-gray-600 font-semibold">RUT</th>
                        <th class="px-4 py-3 text-gray-600 font-semibold">Rol</th>
                        <th class="px-4 py-3 text-gray-600 font-semibold">Activo</th>
                        <th class="px-4 py-3 text-right text-gray-600 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($instaladores as $i)
                        <tr class="hover:bg-gray-50 {{ $i->deleted_at ? 'opacity-60' : '' }}">
                            {{-- Instalador + avatar inicial --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($i->nombre,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $i->nombre }}</div>
                                        <div class="text-xs text-gray-500">ID #{{ $i->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Usuario / Correo --}}
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ $i->usuario }}</div>
                                <div class="text-xs text-gray-500">{{ $i->correo }}</div>
                            </td>

                            {{-- RUT --}}
                            <td class="px-4 py-3">{{ $i->rut_formateado ?? $i->rut }}</td>

                            {{-- Rol chip --}}
                            <td class="px-4 py-3">
                                @php
                                  $roles = ['admin'=>'bg-purple-100 text-purple-700','supervisor'=>'bg-amber-100 text-amber-700','instalador'=>'bg-slate-100 text-slate-700'];
                                  $roleClass = $roles[$i->rol] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleClass }}">
                                    {{ ucfirst($i->rol) }}
                                </span>
                            </td>

                            {{-- Activo con confirmación --}}
                            <td class="px-4 py-3">
                                @if(!$i->deleted_at)
                                <form action="{{ route('administracion.instaladores.toggle-activo', $i) }}" method="POST">
                                    @csrf @method('PATCH')

                                    <button
                                        type="submit"
                                        class="relative inline-flex items-center h-6 w-11 rounded-full transition focus:outline-none js-confirm"
                                        title="Cambiar estado"
                                        data-title="Cambiar estado"
                                        data-text="¿Deseas {{ $i->activo==='S' ? 'marcar como Inactivo' : 'marcar como Activo' }} a este instalador? (Este cambio es de estado operativo, no afecta el acceso)"
                                        data-icon="question"
                                        data-confirm="Sí, cambiar"
                                        data-cancel="Cancelar"
                                        >
                                        <span class="sr-only">Toggle</span>
                                        <span class="absolute inset-0 rounded-full {{ $i->activo==='S' ? 'bg-green-500/90' : 'bg-gray-300' }}"></span>
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition {{ $i->activo==='S' ? 'translate-x-6' : 'translate-x-0.5' }}"></span>
                                    </button>
                                </form>
                                @else
                                    <span class="text-xs text-gray-500">—</span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 justify-end">
                                    @if(!$i->deleted_at)
                                        <a href="{{ route('administracion.instaladores.edit', $i) }}"
                                           class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 transition-colors">Editar</a>

                                        <form action="{{ route('administracion.instaladores.destroy', $i) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button
                                                class="px-3 py-1.5 rounded-lg border text-red-600 hover:bg-red-50 js-confirm transition-colors"
                                                data-title="Enviar a papelera"
                                                data-text="Podrás restaurarlo luego."
                                                data-icon="warning"
                                                data-confirm="Sí, eliminar"
                                                data-cancel="Cancelar">
                                                Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('administracion.instaladores.restore', $i->id) }}" method="POST">
                                            @csrf
                                            <button
                                                class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 js-confirm transition-colors"
                                                data-title="Restaurar instalador"
                                                data-text="Se reactivará el registro."
                                                data-icon="question"
                                                data-confirm="Restaurar">
                                                Restaurar
                                            </button>
                                        </form>

                                        <form action="{{ route('administracion.instaladores.force-delete', $i->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button
                                                class="px-3 py-1.5 rounded-lg border text-red-600 hover:bg-red-50 js-confirm transition-colors"
                                                data-title="Eliminar definitivamente"
                                                data-text="Esta acción no se puede deshacer."
                                                data-icon="error"
                                                data-confirm="Eliminar">
                                                Borrar definitivo
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center">
                                <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </div>
                                <p class="text-gray-600 font-medium">Sin resultados</p>
                                <p class="text-gray-500 text-sm">Prueba cambiando el filtro o crea un nuevo instalador.</p>
                                <a href="{{ route('administracion.instaladores.create') }}"
                                   class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                                    Crear instalador
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación con filtros --}}
        @if($instaladores->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $instaladores->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
});

// Mostrar flash success/error como toast
@if(session('success'))
  Toast.fire({ icon: 'success', title: @json(session('success')) });
@endif
@if($errors->any())
  Toast.fire({ icon: 'error', title: 'Revisa los datos del formulario' });
@endif

// Confirmación genérica para forms con .js-confirm
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.js-confirm');
  if (!btn) return;

  e.preventDefault();
  const form = btn.closest('form');
  const title = btn.dataset.title || '¿Estás seguro?';
  const text = btn.dataset.text || 'Esta acción no se puede deshacer.';
  const icon = btn.dataset.icon || 'warning';
  const confirmButtonText = btn.dataset.confirm || 'Sí, continuar';
  const cancelButtonText = btn.dataset.cancel || 'Cancelar';

  const res = await Swal.fire({
    title, text, icon,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText,
    reverseButtons: true,
    focusCancel: true,
  });

  if (res.isConfirmed) form.submit();
});

// Confirmar envío de formularios de creación/edición (.js-confirm-submit)
document.addEventListener('submit', async (e) => {
  const form = e.target;
  if (!form.classList.contains('js-confirm-submit')) return;

  e.preventDefault();

  const title = form.dataset.title || 'Confirmar guardado';
  const text = form.dataset.text || 'Se guardarán los cambios.';
  const icon = form.dataset.icon || 'question';
  const confirmButtonText = form.dataset.confirm || 'Guardar';
  const cancelButtonText = form.dataset.cancel || 'Cancelar';

  const res = await Swal.fire({
    title, text, icon,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText,
    reverseButtons: true,
    focusCancel: true,
  });

  if (res.isConfirmed) form.submit();
});
</script>
@endpush
