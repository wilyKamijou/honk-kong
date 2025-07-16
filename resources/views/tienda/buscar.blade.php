@extends('base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/producto.css') }}?v=1.1">
    <link rel="stylesheet" href="{{ asset('css/actions.css') }}?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
      
    <div class="banner-content">
        <h1>¡Tu pizza favorita, recién horneada!</h1>
        <p>Disfruta de sabores irresistibles con calidad garantizada</p>
        <a href="#menu" class="order-now-btn">Ordenar ahora</a>
    </div>
 <!-- Productos -->
 <div>
    <h1 style="text-align: center; font-size: 50px; color: #ffffff">NUESTROS PRODUCTOS</h1>
    <div class="products-container">
        @foreach ($productos as $producto)
            <div class="product-card">
                <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                <h3>{{ $producto->nombre }}</h3>
                <h4>{{ $producto->descripcion }}</h4>
                <p class="price">Bs {{ number_format($producto->precio, 2) }}</p>
                
                <div class="product-actions-container">
                    <div class="product-actions">
                        @php
                            $enCarrito = isset($carrito[$producto->id_producto]);
                            $yaComento = \App\Models\Resena::where('user_id', auth()->id())
                                                  ->where('producto_id', $producto->id_producto)
                                                  ->exists();
                        @endphp
                        
                        @if (!$enCarrito)
                            <a class="add-to-cart" href="/carrito/agregar/{{$producto->id_producto}}">
                                Agregar
                            </a>
                        @else
                            <div class="add-to-cart in-cart-disabled">  
                                En carrito
                            </div>
                        @endif
                        
                        <div class="comment-wrapper">
                            <a href="{{ route('reseñas.createByUser', ['producto' => $producto->id_producto]) }}" 
                               class="btn-review">
                                <i class="far fa-comment"></i>
                            </a>
                            <div class="comment-indicator {{ $yaComento ? 'reviewed' : 'not-reviewed' }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection