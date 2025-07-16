@extends('home')

@section('content')
<div class="content-wrapper">
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Panel de Administración</h1>
    
    <!-- Tarjetas de Resumen -->
    <div class="row">
        <!-- Pedidos hoy -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pedidos Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPedidosHoy }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas hoy -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventas Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($ventasHoy, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clientes nuevos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Clientes Nuevos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clientesNuevos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Productos en Inventario</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProductos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de ventas mensuales -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ventas Mensuales</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="ventasMensualesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos recientes -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pedidos Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidosRecientes as $pedido)
                                <tr>
                                    <td>#{{ $pedido['pedido']->id }}</td>
                                    <td>
                                        @if($pedido['producto'])
                                            {{ $pedido['producto']->nombre }}
                                        @else
                                            Sin productos
                                        @endif
                                    </td>
                                    <td>${{ number_format($pedido['pedido']->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos -->
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de ventas mensuales
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ventasMensualesChart').getContext('2d');
        const ventasMensualesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Este Año',
                    data: @json($ventasMensuales),
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)'
                }, {
                    label: 'Año Anterior',
                    data: @json($ventasMensualesAnterior),
                    backgroundColor: 'rgba(200, 200, 200, 0.05)',
                    borderColor: 'rgba(200, 200, 200, 1)',
                    pointBackgroundColor: 'rgba(200, 200, 200, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(200, 200, 200, 1)',
                    borderDash: [5, 5]
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return data.datasets[tooltipItem.datasetIndex].label + ': $' + tooltipItem.yLabel.toLocaleString();
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
