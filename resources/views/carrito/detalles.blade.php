@extends('base')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .order-confirmation {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .confirmation-header h1 {
            color: #e74c3c;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .confirmation-badge {
            display: inline-block;
            background: #2ecc71;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .info-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            flex: 1;
            min-width: 300px;
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .info-card h3 {
            color: #e74c3c;
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            color: #666;
        }
        
        .total-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: right;
            font-size: 1.2rem;
        }
        
        .total-summary strong {
            color: #e74c3c;
            font-size: 1.4rem;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn-primary {
            background: #e74c3c;
            border: none;
            padding: 10px 25px;
            font-size: 1rem;
        }
        
        .btn-outline {
            background: white;
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 10px 25px;
            font-size: 1rem;
        }
        
        .delivery-estimate {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }
        .btn-custom {
    background: #e74c3c;
    border: 2px solid #e74c3c;
    color: white !important;
    padding: 10px 25px;
    font-size: 1rem;
    font-weight: bold;
    text-decoration: none !important;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    background: #c0392b;
    border-color: #c0392b;
    color: white;
    text-decoration: none;
}
    </style>
@endpush

@section('content')
<div class="order-confirmation">
    <div class="confirmation-header">
        <h1>¡Pedido Confirmado!</h1>
        <p>Gracias por tu compra, {{ $pedido->users->name ?? 'Cliente' }}</p>
        <div class="confirmation-badge">
            <i class="fas fa-check-circle"></i> N° {{ $pedido->id_pedido }}
        </div>
    </div>
    
    <div class="info-section">
        <div class="info-card">
            <h3><i class="fas fa-info-circle"></i> Información del Pedido</h3>
           <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</p>
            <p><strong>Estado:</strong> <span style="color: #2ecc71; font-weight: bold">{{ ucfirst($pedido->estado) }}</span></p>
            <p><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-truck"></i> Dirección de Envío</h3>
            <p>{{ $pedido->direccion_envio }}</p>
            @if($pedido->indicaciones)
                <p><strong>Indicaciones:</strong> {{ $pedido->indicaciones }}</p>
            @endif
            
            <div class="delivery-estimate">
                <i class="fas fa-clock"></i> 
                <strong>Tiempo estimado de entrega:</strong> 
                30-45 minutos
            </div>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-credit-card"></i> Método de Pago</h3>
            @if($metodo_pago->tipo === 'tarjeta')
                <p><i class="fas fa-credit-card"></i> Tarjeta terminada en {{ $metodo_pago->ultimos_digitos }}</p>
            @elseif($metodo_pago->tipo === 'qr')
                <p><i class="fas fa-qrcode"></i> Pago con QR</p>
            @else
                <p><i class="fas fa-money-bill-wave"></i> Pago en efectivo</p>
            @endif
            <p><strong>Estado del pago:</strong> <span style="color: #2ecc71">Completado</span></p>
        </div>
    </div>
    
    <div class="info-card">
        <h3><i class="fas fa-pizza-slice"></i> Tus Productos</h3>
        <div class="products-grid">
            @foreach($detalles as $detalle)
            <div class="product-card">
                @if($detalle->productos->imagen_url)
                <img src="{{ $detalle->productos->imagen_url }}" alt="{{ $detalle->productos->nombre }}" class="product-image">
                @else
                <div class="product-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center">
                    <i class="fas fa-pizza-slice fa-3x" style="color: #ccc"></i>
                </div>
                @endif
                <div class="product-info">
                    <div class="product-name">{{ $detalle->productos->nombre }}</div>
                    <div class="product-meta">
                        <span>Cantidad: {{ $detalle->cantidad }}</span>
                        <span>${{ number_format($detalle->precio, 2) }}</span>
                    </div>
                    <div class="product-meta" style="margin-top: 5px; color: #e74c3c; font-weight: bold">
                        <span>Subtotal:</span>
                        <span>${{ number_format($detalle->precio * $detalle->cantidad, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="total-summary">
        <p>Total del pedido: <strong>${{ number_format($pedido->total, 2) }}</strong></p>
    </div>
<div class="action-buttons">
    <a href="/" class="btn-custom">Volver al inicio</a>
</div>
</div>
@endsection