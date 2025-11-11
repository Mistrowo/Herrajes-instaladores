@csrf

@php
  // Normalizar "activo"
  $activoDefault = old('activo', $instalador->activo ?? 'S') === 'S' ? 'S' : 'N';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $instalador->nombre) }}"
               class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
        @error('nombre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
        <input type="text" name="usuario" value="{{ old('usuario', $instalador->usuario) }}"
               class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
        @error('usuario') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
        <input type="email" name="correo" value="{{ old('correo', $instalador->correo) }}"
               class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
        @error('correo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $instalador->telefono) }}"
               class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
        @error('telefono') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">RUT</label>
        <input type="text" name="rut" value="{{ old('rut', $instalador->rut) }}"
               class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
        @error('rut') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
        <select name="rol" class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200">
            @foreach(['admin'=>'Administrador','supervisor'=>'Supervisor','instalador'=>'Instalador'] as $k=>$v)
                <option value="{{ $k }}" @selected(old('rol', $instalador->rol)==$k)>{{ $v }}</option>
            @endforeach
        </select>
        @error('rol') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Contraseña {{ $instalador->exists ? '(dejar vacío para mantener)' : '' }}
        </label>
        <input type="password" name="password" class="w-full px-3 py-2 rounded-xl border focus:ring-2 focus:ring-blue-200" autocomplete="new-password">
        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2 flex items-center gap-3">
        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border">
            <input type="checkbox" name="activo" value="S" @checked($activoDefault==='S') class="rounded">
            <span class="text-sm">Activo</span>
        </label>
    </div>

</div>
