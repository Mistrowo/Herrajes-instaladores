<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistema Ohffice</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="icon" type="image/png" href="{{ asset('logo/logo_ilesa.png') }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#64748b',
                    }
                }
            }
        }
    </script>
    
    <style>
         /* Ocultar elementos mientras Alpine.js se carga */
    [x-cloak] { 
        display: none !important; 
    }
    
    /* Opcional: Mostrar un skeleton/loader mientras carga */
    .alpine-loading {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    
    .alpine-loaded {
        opacity: 1;
    }
        /* Scroll personalizado */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Sidebar + Main Content -->
    <x-sidebar />
    
    <!-- Scripts Globales -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        window.showAlert = function(type, message) {
            Swal.fire({
                icon: type,
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>