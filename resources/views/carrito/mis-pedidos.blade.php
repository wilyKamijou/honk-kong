@extends('base')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .pedidos-container {
            max-width: 1200px;
            margin: 90px auto 30px;
            padding: 20px;
        }
        
        .pedido-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .pedido-header {
            background: #e74c3c;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .pedido-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .pedido-badge {
            background: white;
            color: #e74c3c;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .pedido-body {
            padding: 20px;
        }
        
        .pedido-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            flex: 1;
            min-width: 200px;
        }
        
        .info-item h3 {
            color: #e74c3c;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .productos-list {
            margin-top: 20px;
        }
        
        .producto-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .producto-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        
        .producto-imagen {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        
        .producto-texto {
            flex: 1;
        }
        
        .producto-nombre {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .producto-cantidad {
            color: #666;
            font-size: 0.9rem;
        }
        
        .producto-precio {
            font-weight: bold;
            color: #e74c3c;
            min-width: 100px;
            text-align: right;
        }
        
        .pedido-footer {
            background: #f8f9fa;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .pedido-total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #e74c3c;
        }
        
        .action-buttons {
            text-align: right;
        }
        
        .btn-pedido {
            background: #c0392b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-pedido:hover {
            background: #a5281b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 50px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
    </style>
@endpush

@section('content')
<div class="pedidos-container">
    <!-- Header modificado con más margen inferior -->
    <div class="pedido-header" style="margin: 0 0 30px 0; border-radius: 10px;">
        <h2><i class="fas fa-clipboard-list"></i> Pedidos de {{ auth()->user()->name }}</h2>
    </div>
    
    @if($pedidos->isEmpty())
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h2>No tienes pedidos realizados</h2>
            <p>Aún no has realizado ningún pedido en nuestra tienda.</p>
            <a href="/" class="btn-pedido">Ver productos</a>
        </div>
    @else
        @foreach($pedidos as $id_pedido => $pedidoGrupo)
            @php $pedido = $pedidoGrupo->first(); @endphp
            <div class="pedido-card">
                <div class="pedido-header">
                    <h2>Pedido #{{ $id_pedido }}</h2>
                    <span class="pedido-badge">{{ ucfirst($pedido->estado) }}</span>
                </div>
                
                <div class="pedido-body">
                    <div class="pedido-info">
                        <div class="info-item">
                            <h3><i class="fas fa-calendar-alt"></i> Fecha</h3>
                            <p>{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div class="info-item">
                            <h3><i class="fas fa-truck"></i> Dirección</h3>
                            <p>{{ $pedido->direccion_envio }}</p>
                        </div>
                        
                        <div class="info-item">
                            <h3><i class="fas fa-credit-card"></i> Pago</h3>
                            <p>
                                @if($pedido->metodos_pagos->tipo === 'tarjeta')
                                    Tarjeta (****{{ $pedido->metodos_pagos->ultimos_digitos }})
                                @else
                                    {{ ucfirst($pedido->metodos_pagos->tipo) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <h3><i class="fas fa-pizza-slice"></i> Productos</h3>
                    <div class="productos-list">
                        @foreach($pedido->detalle_pedido as $detalle)
                            <div class="producto-item">
                                <div class="producto-info">
                                    @if($detalle->productos->imagen_url)
                                        <img src="{{ $detalle->productos->imagen_url }}" alt="{{ $detalle->productos->nombre }}" class="producto-imagen">
                                    @else
                                        <div class="producto-imagen" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center">
                                            <i class="fas fa-pizza-slice" style="color: #ccc"></i>
                                        </div>
                                    @endif
                                    <div class="producto-texto">
                                        <div class="producto-nombre">{{ $detalle->productos->nombre }}</div>
                                        <div class="producto-cantidad">Cantidad: {{ $detalle->cantidad }}</div>
                                    </div>
                                </div>
                                <div class="producto-precio">
                                    ${{ number_format($detalle->precio * $detalle->cantidad, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="pedido-footer">
                    <div class="pedido-total">
                        Total: ${{ number_format($pedido->total, 2) }}
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('pedido.detalles', $id_pedido) }}" class="btn-pedido">
                            Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    
    <div class="btn-container">
        <a href="/" class="btn-pedido">Volver al inicio</a>
    </div>
</div>
@endsection