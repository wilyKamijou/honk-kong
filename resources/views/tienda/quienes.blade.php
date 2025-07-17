@extends('base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/barraVertical.css') }}">
<style>
    body {
        background: url('/images/hamburguesa-fondo.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
        background: rgba(0, 0, 0, 0.75);
        padding: 2.5rem 1rem 1.5rem;
        color: #fff;
        text-align: center;
    }

    .hero-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 6px #000;
    }

    .hero-section p {
        font-size: 1.1rem;
        color: #ffcb05;
        text-shadow: 1px 1px 3px #000;
    }

    .team-section,
    .info-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        padding: 2.5rem 1rem;
    }

    .team-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        width: 200px;
        color: #fff;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0 30px rgba(255, 203, 5, 0.5);
    }

    .team-card h3 {
        font-size: 1rem;
        margin-bottom: 5px;
        color: #ffcb05;
    }

    .team-card p {
        font-size: 0.9rem;
        color: #ddd;
    }

    .icono-usuario {
        width: 100px;
        height: 100px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #ffcb05;
        border: 3px solid #ffcb05;
        box-shadow: 0 0 10px rgba(255, 203, 5, 0.3);
        transition: background 0.3s ease;
    }

    .icono-usuario:hover {
        background: rgba(255, 203, 5, 0.2);
    }

    .info-card {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 2rem;
        max-width: 520px;
        color: #fff;
        box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.05), 0 0 20px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: scale(1.03);
        box-shadow: 0 0 25px rgba(255, 203, 5, 0.4);
    }

    .info-title {
        font-size: 1.8rem;
        margin-bottom: 1rem;
        padding-left: 1rem;
        font-weight: bold;
        color: #ffcb05;
        border-left: 5px solid #ffcb05;
    }

    .info-card p {
        font-size: 1rem;
        line-height: 1.7;
        text-align: justify;
    }

    .valores-list li {
        margin-bottom: 0.7rem;
        color: #eee;
    }

    .valores-list li::before {
        content: '';
        color: #ffcb05;
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .info-section,
        .team-section {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endpush

@section('titulo', 'Quiénes Somos')

@section('content')

<!-- Encabezado -->
<section class="hero-section">
    <div class="container">
        <h1>Quiénes Somos</h1>
        <p>Conoce la pasión y el corazón detrás de Hong Kong</p>
    </div>
</section>

<!-- Misión y Valores -->
<section class="info-section">
    <div class="info-card">
        <h2 class="info-title">Nuestra Misión</h2>
        <p>
            <strong>En Hong Kong Comida Rápida</strong>, nos comprometemos a ofrecer una experiencia gastronómica inigualable, combinando la velocidad del servicio con sabores auténticos y una atención cercana y amable. Elaboramos cada platillo con ingredientes frescos y cuidadosamente seleccionados, garantizando calidad, sabor y bienestar en cada bocado. Nuestra pasión es alimentar sonrisas, cuidando tanto el paladar como la salud de nuestros clientes.
        </p>
    </div>

    <div class="info-card">
        <h2 class="info-title">Nuestros Valores</h2>
        <ul class="valores-list">
            <li><strong>Pasión por la cocina:</strong> Cuidamos cada platillo con entusiasmo y experiencia.</li>
            <li><strong>Puntualidad:</strong> Valoramos tu tiempo, entregando con eficiencia.</li>
            <li><strong>Cercanía:</strong> Escuchamos y crecemos junto a nuestros clientes.</li>
            <li><strong>Calidad y frescura:</strong> Ingredientes seleccionados, sabor auténtico.</li>
            <li><strong>Responsabilidad:</strong> Comprometidos con prácticas sostenibles.</li>
            <li><strong>Trabajo en equipo:</strong> Fomentamos respeto, cooperación y crecimiento.</li>
        </ul>
    </div>
</section>

<!-- Integrantes -->
<section class="team-section">
    @php
        $team = [
            ['nombre' => 'Dennis Polonio', 'rol' => 'Desarrollador & Coordinador'],
            ['nombre' => 'Willian Torrico', 'rol' => 'Backend & Base de Datos'],
            ['nombre' => 'Lizeth Muñoz', 'rol' => 'Diseño & Frontend'],
            ['nombre' => 'Brian Pardo', 'rol' => 'QA & Seguridad'],
            ['nombre' => 'Gueider Céspedes', 'rol' => 'Soporte Técnico'],
        ];
    @endphp

    @foreach($team as $miembro)
    <div class="team-card">
        <div class="icono-usuario">
            <i class="fas fa-user"></i>
        </div>
        <h3>{{ $miembro['nombre'] }}</h3>
        <p>{{ $miembro['rol'] }}</p>
    </div>
    @endforeach
</section>

@endsection