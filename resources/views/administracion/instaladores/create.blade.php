@extends('layouts.dashboard')

@section('page-title', 'Nuevo Instalador')
@section('page-subtitle', 'Crear registro')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-3xl">
        <form action="{{ route('administracion.instaladores.store') }}" method="POST" class="space-y-4">
            @include('administracion.instaladores._form', ['instalador' => $instalador])
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Guardar</button>
                <a href="{{ route('administracion.instaladores.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
