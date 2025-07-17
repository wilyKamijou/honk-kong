@extends('base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/quienes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barraVertical.css') }}">
@endpush

@section('titulo', 'Quiénes Somos')

@section('content')

<section class="hero-section text-white text-center py-4">
  <div class="container">
    <h1 class="titulo">Quiénes Somos</h1>
    <p class="subtitulo">Conoce la pasión y el corazón detrás de Hong Kong Comida Rápida</p>
  </div>
</section>

<section class="info-section container">
  <div class="card-box">
    <div class="info-card">
      <h2 class="info-title">Nuestra Misión</h2>
      <p>
        En <strong>Hong Kong Comida Rápida</strong> En Hong Kong Comida Rápida, nuestra misión es deleitar a nuestros clientes con una experiencia culinaria excepcional, combinando la rapidez de un servicio eficiente con la calidad y el sabor auténtico de cada platillo. Nos comprometemos a preparar alimentos frescos, nutritivos y sabrosos utilizando ingredientes seleccionados cuidadosamente.
        Creemos que la comida rápida no debe sacrificar el bienestar ni el gusto, por eso trabajamos día a día para brindar un servicio ágil, amigable y confiable que respalde nuestras recetas caseras y nuestra pasión por la cocina.
        Nos esforzamos por ser más que un restaurante.
      </p>
      <ul>
        <li>✅ Integrantes del proyecto</li>
        <li>✅ Dennis Polonio Apaza Chavez</li>
        <li>✅ Willian Torrico Jimenez</li>
        <li>✅ Lizeth Muñoz Herbas</li>
        <li>✅ Brian Pardo Vargas</li>
        <li>✅ Gueider Angel Cespedes Muñoz</li>
      </ul>
    </div>

    <div class="info-card">
      <h2 class="info-title">Nuestros Valores</h2>
      <div class="valores-list">
        <ul>
          <li>❤️ Pasión por la cocina:
            Cocinamos con entusiasmo, cuidando el sabor, presentación y experiencia en cada platillo.
          </li>
          <li>🕒 Puntualidad:
            Valoramos tu tiempo, entregando pedidos rápido y sin perder calidad.
          </li>
          <li>👨‍👩‍👧‍👦 Cercanía
            Escuchamos a nuestros clientes y crecemos junto a la comunidad.
          </li>
          <li>🥗 Calidad y frescura
            Usamos ingredientes seleccionados para ofrecer sabor auténtico y saludable.
          </li>
          <li>🌱 Responsabilidad
            Aplicamos prácticas sostenibles con conciencia ambiental y social.
          <li> 🤝 Trabajo en equipo
            Fomentamos respeto, cooperación y crecimiento entre nuestro equipo.
          </li>
         
        </ul>
    </div>
  </div>
</section>

@endsection
