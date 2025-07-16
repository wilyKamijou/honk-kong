<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Tus estilos personalizados -->
    <link href="{{ asset('css/admin-styles.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- =============================================== -->
        <!-- Navbar de AdminLTE (mejorado) -->
        <!-- =============================================== -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/home" class="nav-link">Inicio</a>
                </li>
                @yield('navbar-left')
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                @yield('navbar-right')
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- =============================================== -->
        <!-- Sidebar de AdminLTE (mejorado) -->
        <!-- =============================================== -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/home" class="brand-link text-center">
                <span class="brand-text font-weight-light">PANEL ADMIN</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Menú principal -->
                        <li class="nav-header">MENÚ PRINCIPAL</li>
                        
                        <li class="nav-item">
                            <a href="/reseñas" class="nav-link @if(Request::is('reseñas*')) active @endif">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Reseñas</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="/productos" class="nav-link @if(Request::is('productos*')) active @endif">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Productos</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="/usuarios" class="nav-link @if(Request::is('usuarios*')) active @endif">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        
                        @yield('sidebar-items')
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- =============================================== -->
        <!-- Contenido principal (área para tus estilos) -->
        <!-- =============================================== -->
        <div class="content-wrapper">
            <!-- Encabezado de contenido -->
            @hasSection('content_header')
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('content_header')</h1>
                        </div>
                        <div class="col-sm-6">
                            @yield('content_header_actions')
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Contenido principal -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- =============================================== -->
        <!-- Footer -->
        <!-- =============================================== -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Versión</b> 1.0.0
            </div>
            <strong>Copyright &copy; {{ date('Y') }} Tu Empresa.</strong> Todos los derechos reservados.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    
    @yield('scripts')
</body>
</html>