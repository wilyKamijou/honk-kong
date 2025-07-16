@extends('adminlte::page')

@section('title', 'Honk Kong')

@section('content_header')
<!-- En la sección content_header -->
@push('header')
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
        </a>
    </li>
@endpush
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>@yield('page_title', 'Panel de Control')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
                    <li class="breadcrumb-item active">@yield('page_title')</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @yield('contenido')
    </div>
@stop

@section('css')
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Estilos adicionales -->
    <style>
        .sidebar-collapse .main-sidebar {
            transform: translate(0, 0);
        }
        .navbar-nav .dropdown-menu {
            position: absolute;
        }
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Forzar la recarga del sidebar si está colapsado
            if (localStorage.getItem('adminlte.sidebar.collapse') {
                document.body.classList.add('sidebar-collapse');
            }
            
            // Botón de hamburguesa personalizado
            const hamburger = document.createElement('li');
            hamburger.className = 'nav-item';
            hamburger.innerHTML = `
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            `;
            document.querySelector('.main-header .navbar-nav').prepend(hamburger);
        });
    </script>
@stop