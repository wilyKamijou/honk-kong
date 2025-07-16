<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hong Kong Comida Rápida') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="https://i.imgur.com/3vt7l0G.png" type="image/png">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="hold-transition login-page" 
      style="background: url('https://images.unsplash.com/photo-1563245372-f21724e3856d?q=80&w=1924') no-repeat center center fixed; background-size: cover;">
    
    {{ $slot }}

    @livewireScripts
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>