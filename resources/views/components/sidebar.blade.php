<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
    
    <!-- Overlay para móvil -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-600 to-indigo-700 
                  transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl">
        
        <!-- Header del Sidebar -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-blue-500/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">Ohffice</h1>
                    <p class="text-blue-200 text-xs">Sistema Herrajes</p>
                </div>
            </div>
            
            <!-- Botón cerrar (móvil) -->
            <button @click="sidebarOpen = false" 
                    class="lg:hidden text-white/80 hover:text-white p-2 rounded-lg hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- User Info - Mostrar usuario de sesión -->
        <div class="px-6 py-4 border-b border-blue-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-semibold text-sm truncate">{{ auth()->user()->nombre }}</p>
                    <p class="text-blue-200 text-xs truncate capitalize">
                        @if(auth()->user()->esAdmin())
                            👑 Administrador
                        @elseif(auth()->user()->esSupervisor())
                            👨‍💼 Supervisor
                        @else
                            🔧 Instalador
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
            
            <!-- Dashboard / Inicio -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all
                      {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Inicio</span>
            </a>

            {{-- Mis Asignaciones - Solo para instaladores --}}
            @if(auth()->user()->esInstalador() || auth()->user()->esSupervisor())
                <a href="{{ route('mis-asignaciones.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all
                          {{ request()->routeIs('mis-asignaciones.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Mis Asignaciones</span>
                </a>
            @endif
            
            {{-- SOLO visible para administradores --}}
            @can('admin-only')
                <!-- Divider -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Administración</p>
                </div>

                <!-- Asignar Instaladores -->
                <a href="{{ route('asignar.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all
                          {{ request()->routeIs('asignar.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Asignar Instaladores</span>
                </a>

                <!-- CRUD INSTALADORES -->
                <a href="{{ route('administracion.instaladores.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all
                          {{ request()->routeIs('administracion.instaladores.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Administrar Instaladores</span>
                </a>
            @endcan
            
        </nav>
        
        <!-- Footer del Sidebar -->
        <div class="border-t border-blue-500/30 p-4">
            <!-- Salir / Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-blue-100 hover:bg-red-500/20 hover:text-white transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Salir</span>
                </button>
            </form>
            
            <!-- Copyright -->
            <div class="mt-3 text-center text-xs text-blue-200">
                © {{ date('Y') }} Ilesa
            </div>
        </div>
        
    </aside>
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
        <!-- Header / Topbar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                
                <!-- Mobile Menu Button + Title -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" 
                            class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-500 hidden sm:block">@yield('page-subtitle', 'Panel de control')</p>
                    </div>
                </div>
                
                <!-- Notificaciones -->
                <div class="flex items-center gap-3">
                    <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>
        </header>
        
        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>
    </div>
    
</div>