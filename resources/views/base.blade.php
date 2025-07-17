<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hong Kong
    </title>

    @stack('styles')  <!-- Esto es lo que hace que se cargue el CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
   
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    
    <!-- Íconos FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{asset('css/baner.css')}}">
    <link rel="stylesheet" href="/css/barraVertical.css">
    <style> <!-- Tipografía de Google Fonts -->
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    </style>
</head>
<body>

  {{-- Barra superior --}}
 <div class="navbar">
    <div class="logo">
      <img src="{{ asset('images/logo.png') }}" alt="" />
    </div>
    <nav class="nav-links">
        <a href="/">Home</a>
        <a href="/quienes">Quiénes somos</a>
        <a href="/mis-pedidos">Mis pedidos</a>
        <div class="nav-actions">

            @auth
            <form method="POST" action="{{ route('logout') }}" >
                @csrf
                <button type="submit" class="icon-cerrar">
                    <i class="fas fa-sign-out-alt"></i>Cerrar sesión 
                </button>
            </form>
                @else
                    <a href="{{ route('login') }}">
                        <i class="fas fa-user"></i> Iniciar sesión
                    </a>
                @endauth
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="/home" id="botonFlotante">
                        <i class="fas fa-home"></i>
                    </a>
                @endif
            
            <a href="/carrito/ver" class="icon-button cart-button">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">{{ $carritoCantidad }}</span>   <!-- Cambia este número dinámicamente -->
            </a>
            <!--boton de perfil-->
            <a href="/perfil" class="boton-usuario">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </nav>
    
  </div>
<!-- Contenedor de todas las paginas -->
<div class="page">
  <main class="container mt-4">
    @yield('content')
  </main>
</div>
<!--footer-->
<footer class="footer">
    <div class="footer-container">
      <!-- Columna izquierda: Contacto -->
      <div class="footer-column contact">
        <h3>Contáctanos</h3>
        <p><i class="fas fa-phone-alt"></i> <a href="tel:+123456789">+1 234 567 89</a></p>
        <p><i class="fas fa-envelope"></i> <a href="mailto:ventas@comidita.com">ventas@comidita.com</a></p>
        <p><i class="fas fa-map-marker-alt"></i> <a href="#">Calle Sabrosa 123, Ciudad Del Sabor</a></p>
      </div>
  
      <!-- Columna centro: Logo -->
      <div class="footer-column center-logo">
        <img src="/storage/imagen/fondo.jpg" alt="Logo Comidita" class="logo-footer">
        <p class="slogan">"El sabor que te hace volver"</p>
      </div>
  
      <!-- Columna derecha: Redes sociales -->
      <div class="footer-column social">
        <h3>Síguenos</h3>
        <div class="social-icons">
          <a href="https://facebook.com" target="_blank" class="icon facebook" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://instagram.com" target="_blank" class="icon instagram" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://wa.me/123456789" target="_blank" class="icon whatsapp" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  
    <!-- Parte inferior -->
    <div class="footer-bottom">
      <p>© 2025 Honk Kong. Todos los derechos reservados.</p>
    </div>
</footer>
  

<!-- Botón hamburguesa -->
<button id="toggleSidebar" class="btn_sidebar">

    &#9776; {{-- Este es el ícono de 3 rayas --}}
</button>

<!-- SIDEBAR IZQUIERDO -->
<div id="sidebar">
    <!-- BOTÓN CERRAR (X) -->
    <button id="closeSidebar" style="position: absolute; top: 10px; right: 10px; font-size: 50px; background: none; border: none; color: white; cursor: pointer;">
        &times;
    </button>
    <ul class="sidebar-menu">
        <li class="sidebar-title">CATEGORÍAS</li>
            @foreach($categorias as $categoria)
        <li><a href="/buscar/{{$categoria->id_categoria}}">{{ $categoria->nombre }}</a></li>
        @endforeach
        <li style="  border-bottom: 1px solid rgba(255, 255, 255, 0.2)"></li>
        <li class="sidebar-title"><a href="/reseña">RESEÑAS</a></li>
    </ul>
</div>

    {{--scrips--}}
    {{--barra diagonal--}}
    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');
    
        // Abrir el sidebar
        toggleSidebar.addEventListener('click', (e) => {
            e.stopPropagation(); // Evita que el clic se propague
            sidebar.style.left = '0px';
            document.body.classList.add('sidebar-open');
        });
    
        // Cerrar con el botón "X"
        closeSidebar.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.style.left = '-250px';
            document.body.classList.remove('sidebar-open');
        });
    
        // Cerrar si se hace clic fuera del sidebar
        document.addEventListener('click', (e) => {
            if (
                document.body.classList.contains('sidebar-open') &&
                !sidebar.contains(e.target) &&
                e.target !== toggleSidebar
            ) {
                sidebar.style.left = '-250px';
                document.body.classList.remove('sidebar-open');
            }
        });
    </script>
    
     {{--loop infinito scrip---}}
     <script>
      document.addEventListener('DOMContentLoaded', function () {
          const container = document.getElementById('reseñasContainer');
      
          container.addEventListener('wheel', function(e) {
              e.preventDefault();
              container.scrollLeft += e.deltaY*5;
          });
      
          // Loop infinito simulado
          container.addEventListener('scroll', () => {
              if (container.scrollLeft >= container.scrollWidth / 2) {
                  container.scrollLeft = 0;
              }
          });
      });
      </script>
   
        
</body>
</html>
