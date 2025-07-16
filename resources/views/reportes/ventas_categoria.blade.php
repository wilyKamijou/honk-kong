@extends('home')

@section('content')
<div class="container">
    <h1>Ventas por Categoría</h1>
    
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Fecha Fin:</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="row">
        <!-- Gráfico - Ocupa 7 columnas en pantallas grandes -->
        <div class="col-lg-7 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Top 10 Productos Más Vendidos</h5>
                </div>
<div style="height: 300px;"> <!-- Altura fija -->
    <canvas id="productosChart"></canvas>
</div>
            </div>
        </div>

        <!-- Tabla - Ocupa 5 columnas en pantallas grandes -->
        <div class="col-lg-5 col-md-12">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Resumen por Categoría</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Unidades</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalVentas = $ventas->sum('total_ventas'); @endphp
                                @foreach($ventas as $categoria)
                                <tr>
                                    <td>{{ $categoria->nombre }}</td>
                                    <td>{{ $categoria->total_unidades }}</td>
                                    <td>${{ number_format($categoria->total_ventas, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-primary">
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ $ventas->sum('total_unidades') }}</strong></td>
                                    <td><strong>${{ number_format($totalVentas, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productosData = @json($productosMasVendidos);
        
        const labels = productosData.map(item => 
            `${item.producto_nombre.substring(0, 12)}${item.producto_nombre.length > 12 ? '...' : ''}`
        );
        const categoriaLabels = productosData.map(item => item.categoria_nombre);
        const data = productosData.map(item => item.total_vendido);
        
        // Colores por categoría
        const coloresUnicos = {};
        const backgroundColors = categoriaLabels.map(categoria => {
            if (!coloresUnicos[categoria]) {
                coloresUnicos[categoria] = `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`;
            }
            return coloresUnicos[categoria];
        });

        const ctx = document.getElementById('productosChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => color.replace('60%)', '40%)')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return [
                                    `Producto: ${productosData[context.dataIndex].producto_nombre}`,
                                    `Categoría: ${categoriaLabels[context.dataIndex]}`,
                                    `Vendidos: ${context.raw}`
                                ];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '5%',
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 8,
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Unidades Vendidas',
                            padding: {top: 10, bottom: 10}
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Productos',
                            padding: {top: 10}
                        },
                        barPercentage: 0.7,
                        categoryPercentage: 0.9,
                        grid: {
                            display: false
                        }
                    }
                },
                layout: {
                    padding: {
                        left: 15,
                        right: 15,
                        top: 15,
                        bottom: 15
                    }
                }
            }
        });
    });
</script>
<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
    }
    .card-header {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    @media (max-width: 992px) {
        .col-lg-7, .col-lg-5 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endsection