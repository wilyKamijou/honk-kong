<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hong kong') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
     <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    

    <style>
        /* Estilos base */  
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --dark-bg: rgba(19, 32, 75, 0.9);
            --text-light: rgb(18, 15, 15);
            --text-dark: rgb(104, 102, 102);
        }
        
        /* Fondo personalizado con overlay */
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://cdn.pixabay.com/photo/2018/02/28/22/50/tree-3189339_1280.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            min-height: 100vh;
            color: var(--text-dark);
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: -1;
        }
        
        /* Barra de navegación personalizada */
        .custom-navbar {
            background-color: var(--dark-bg) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 30;
        }
        
        /* Header de página personalizado */
        .page-header {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            margin-bottom: 1.5rem;
        }
        
        /* Contenido principal */
        main {
            position: relative;
            z-index: 10;
            padding-top: 2rem; /* Espacio después de la navbar */
        }
        
        /* Efectos de transición */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Botones */
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        
        /* Tarjetas/Contenedores */
        .card {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Formularios */
        .form-input {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
        }
        
        .form-input:focus {
            border-color: var(--primary-color);
            ring-color: var(--primary-color);
        }
        
        /* Utilidades */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }
        
        .backdrop-blur-md {
            backdrop-filter: blur(8px);
        }
        
        .bg-opacity-80 {
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <x-banner />

    <!-- Barra de navegación personalizada -->


    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    @stack('modals')
    @livewireScripts
    
    <!-- Script para los botones de comentarios rápidos -->
    @stack('scripts')
</body>
</html>