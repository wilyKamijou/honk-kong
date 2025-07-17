@extends('home')

@section('content')
<div class="container-fluid px-4">
    <!-- Encabezado mejorado -->
    <div class="d-flex justify-content-center align-items-center my-5">
        <h1 class="display-3 fw-bold text-center" style="font-family: 'Times New Roman', Times, serif;">PANEL DE ADMINISTRACIÓN</h1>
    </div>
    
    <!-- Tarjetas de Resumen - Ahora en 2 filas para mejor distribución -->
    <div class="row g-4 mb-4">
        <!-- Fila 1 -->
        <div class="col-xxl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="h5 font-weight-bold text-primary text-uppercase mb-1">Pedidos Hoy</div>
                            <div class="h2 mb-0 font-weight-bold">{{ $totalPedidosHoy }}</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-calendar fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="h5 font-weight-bold text-success text-uppercase mb-1">Ventas Hoy</div>
                            <div class="h2 mb-0 font-weight-bold">${{ number_format($ventasHoy, 2) }}</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-dollar-sign fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="h5 font-weight-bold text-info text-uppercase mb-1">Clientes Nuevos</div>
                            <div class="h2 mb-0 font-weight-bold">{{ $clientesNuevos }}</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-user-plus fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="h5 font-weight-bold text-warning text-uppercase mb-1">Productos</div>
                            <div class="h2 mb-0 font-weight-bold">{{ $totalProductos }}</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-boxes fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes - Ahora en cuadrícula más compacta -->
    <div class="row g-4 mb-4">
        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('reportes.ventas-categoria') }}" class="text-decoration-none">
                <div class="card shadow-lg h-100 hover-scale">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-chart-pie fa-4x mb-3 text-primary"></i>
                        <h3 class="h4 font-weight-bold">Ventas por Categoría</h3>
                        <p class="mb-0 text-muted">Análisis detallado por categoría</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('reportes.ventas-fecha') }}" class="text-decoration-none">
                <div class="card shadow-lg h-100 hover-scale">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-calendar-alt fa-4x mb-3 text-success"></i>
                        <h3 class="h4 font-weight-bold">Ventas por Fecha</h3>
                        <p class="mb-0 text-muted">Tendencias temporales</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('admin.panel') }}" class="text-decoration-none">
                <div class="card shadow-lg h-100 hover-scale">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-sun fa-4x mb-3 text-info"></i>
                        <h3 class="h4 font-weight-bold">Panel y Reportes</h3>
                        <p class="mb-0 text-muted">Patrones estacionales</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-md-6">
            <a href="{{ route('reportes.clv') }}" class="text-decoration-none">
                <div class="card shadow-lg h-100 hover-scale">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-user-tie fa-4x mb-3 text-warning"></i>
                        <h3 class="h4 font-weight-bold">CLV</h3>
                        <p class="mb-0 text-muted">Customer Lifetime Value</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Pedidos recientes - Ahora más compacto -->
    <div class="card shadow-lg mb-4">
        <div class="card-header py-3 bg-primary">
            <h2 class="h4 m-0 font-weight-bold text-white">Pedidos Recientes</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Total</th>
                            <th>Estado</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidosRecientes as $pedido)
                        <tr>
                            <td>#{{ $pedido['pedido']->id }}</td>
                            <td>{{ $pedido['producto']->nombre ?? 'N/A' }}</td>
                            <td>${{ number_format($pedido['pedido']->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $pedido['pedido']->estado == 'entregado' ? 'success' : 
                                    ($pedido['pedido']->estado == 'enviado' ? 'info' : 'warning') 
                                }}">
                                    {{ ucfirst($pedido['pedido']->estado) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale {
        transition: transform 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.03);
    }
    .card {
        border-radius: 0.5rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
    .display-3 {
        font-size: 4rem;
        letter-spacing: 1px;
    }
</style>
@endsection