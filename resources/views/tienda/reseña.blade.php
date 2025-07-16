@extends('base')

@push('styles')
    <link rel="stylesheet" href="css/reseña.css">
@endpush
@section('content')
{{-- Reseñas --}}
<h2 style="text-align: center; margin-top: 100px; font-size: 50px; color: #ffffff">LO QUE DICEN LOS CLIENTES</h2>

<div class="reseñas-container" id="reseñasContainer">
    @forelse ($reseñas as $reseña)
        <div class="reseña-card">
            {{-- Mostrar información del producto --}}
            <div class="producto-info">
                <small>Reseña para: <strong>{{ $reseña->producto->nombre ?? 'Producto no disponible' }}</strong></small>
                <small>(ID: {{ $reseña->producto_id }})</small>
            </div>
            
            {{-- Información del usuario --}}
            <div class="user-info">
                <div class="user-icon">
                    <i class="fas fa-user-circle"></i>
                    <span class="reseña-nombre">
                        {{ $reseña->user->name ?? 'Usuario anónimo' }}
                    </span>
                </div>
            </div>
            
            {{-- Contenido de la reseña --}}
            <div class="reseña-contenido">
                <blockquote class="reseña-mensaje">"{{ $reseña->comentario }}"</blockquote>
                <p class="reseña-fecha">{{$reseña->fecha}}</p>
                
                {{-- Calificación con estrellas --}}
                <div class="rating-stars" aria-label="Calificación: {{ $reseña->calificacion }} de 5 estrellas">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= $reseña->calificacion ? 'fas' : 'far' }} fa-star"></i>
                    @endfor
                    <span class="rating-value">({{ $reseña->calificacion }}/5)</span>
                </div>
            </div>
        </div>
    @empty
        <div class="no-reseñas">
            <p>No hay reseñas disponibles todavía.</p>
        </div>
    @endforelse

</div>
@endsection