<!-- resources/views/partials/admin-navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/home">
            <i class="bi bi-speedometer2 me-2"></i>Panel de Administración
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/reseñas">
                        <i class="bi bi-chat-square-text me-1"></i> Reseñas
                    </a>
                </li>
                <!-- Añade más items según necesites -->
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-circle me-1"></i> Mi Cuenta
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link">
                            <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>