@extends('layouts.login')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="w-full max-w-md">
  <div class="bg-white shadow-xl rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
    <div class="p-6 text-center">
      <img src="{{ asset('logo/logo_ilesa.png') }}" alt="Logo Ilesa"
           class="mx-auto mb-2 transition-all duration-300 hover:scale-105" style="max-width: 180px;">

      <h2 class="text-2xl font-bold mb-6 text-gray-800">HERRAJES INSTALADORES</h2>
      
      {{-- Mostrar mensaje de éxito --}}
      @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
          {{ session('success') }}
        </div>
      @endif
      
      {{-- Mostrar errores generales --}}
      @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
          <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      
      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        
        {{-- Email --}}
        <div class="mb-4">
          <label for="email" class="block text-gray-700 mb-2 text-left font-medium">Correo</label>
          <div class="flex transform transition-all duration-200 focus-within:ring-2 focus-within:ring-blue-300 focus-within:ring-opacity-50 rounded-md">
            <span class="inline-flex items-center px-3 bg-gray-100 text-blue-500 border border-r-0 border-gray-300 rounded-l-md">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
              </svg>
            </span>
            <input id="email" 
                   name="email" 
                   type="email" 
                   required 
                   autocomplete="email" 
                   autofocus 
                   placeholder="nombre@empresa.com"
                   value="{{ old('email') }}"
                   class="w-full border border-gray-300 rounded-r-md p-2 focus:outline-none focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
          </div>
          @error('email')
            <p class="text-red-500 text-sm mt-1 text-left">{{ $message }}</p>
          @enderror
        </div>
        
        {{-- Contraseña --}}
        <div class="mb-6">
          <label for="password" class="block text-gray-700 mb-2 text-left font-medium">Contraseña</label>
          <div class="flex transform transition-all duration-200 focus-within:ring-2 focus-within:ring-blue-300 focus-within:ring-opacity-50 rounded-md">
            <span class="inline-flex items-center px-3 bg-gray-100 text-blue-500 border border-r-0 border-gray-300 rounded-l-md">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </span>
            <input id="password" 
                   name="password" 
                   type="password" 
                   required 
                   placeholder="••••••••"
                   class="w-full border border-gray-300 rounded-r-md p-2 focus:outline-none focus:border-blue-500 transition-colors duration-200 @error('password') border-red-500 @enderror">
          </div>
          @error('password')
            <p class="text-red-500 text-sm mt-1 text-left">{{ $message }}</p>
          @enderror
        </div>
        
        {{-- Remember Me --}}
        <div class="mb-4 flex items-center">
          <input type="checkbox" 
                 name="remember" 
                 id="remember" 
                 class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
          <label for="remember" class="ml-2 block text-sm text-gray-700">
            Mantener sesión iniciada
          </label>
        </div>
        
        {{-- Botón Ingresar --}}
        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-md
                       transition-all duration-300 transform hover:translate-y-px hover:shadow-lg flex items-center justify-center">
          <span>Ingresar</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </form>
      
      <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-500">
        © {{ date('Y') }} Ohffice | Todos los derechos reservados
      </div>
    </div>
  </div>
</div>
@endsection