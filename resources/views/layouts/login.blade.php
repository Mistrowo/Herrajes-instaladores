<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token">
    <title>@yield('title', 'Auth')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo/logo_ilesa.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('logo/logo_ilesa.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/logo_ilesa.png') }}">

    

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    @yield('content')
</body>
</html>
