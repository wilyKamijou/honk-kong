@extends('home')

@section('content')
<div class="container">
    <h1>Ventas por Fecha</h1>
    
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Fecha Fin:</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Agrupar por:</label>
                <select name="agrupacion" class="form-control">
                    <option value="dia" {{ $agrupacion == 'dia' ? 'selected' : '' }}>Día</option>
                    <option value="semana" {{ $agrupacion == 'semana' ? 'selected' : '' }}>Semana</option>
                    <option value="mes" {{ $agrupacion == 'mes' ? 'selected' : '' }}>Mes</option>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
            <div class="row mb-4">

</div>
        </div>
    </form>
    canvas

    <div class="row">
        <!-- Gráfico Principal -->
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Evolución de Ventas</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="changeChartType('bar')">Barras</button>
                        <button class="btn btn-outline-secondary" onclick="changeChartType('line')">Líneas</button>
                    </div>
                </div>
<div style="height: 300px;">
                    <canvas id="ventasChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Resumen Estadístico -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td>Total Período:</td>
                                <td class="text-end">${{ number_format($ventas->sum('total_ventas'), 2) }}</td>
                            </tr>
                            <tr>
                                <td>Pedidos:</td>
                                <td class="text-end">{{ $ventas->sum('total_pedidos') }}</td>
                            </tr>
                            <tr>
                                <td>Productos:</td>
                                <td class="text-end">{{ $ventas->sum('total_productos') }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td>Promedio diario:</td>
                                <td class="text-end">${{ number_format($ventas->avg('total_ventas'), 2) }}</td>
                            </tr>
                            <tr>
                                <td>Mejor día:</td>
                                <td class="text-end">
                                    {{ $ventas->sortByDesc('total_ventas')->first()->fecha_agrupada ?? 'N/A' }}<br>
                                    <small>${{ number_format($ventas->max('total_ventas') ?? 0, 2) }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Detalle por {{ $agrupacion == 'dia' ? 'Día' : ($agrupacion == 'semana' ? 'Semana' : 'Mes') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Pedidos</th>
                            <th>Productos</th>
                            <th>Total Ventas</th>
                            <th>% del Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPeriodo = $ventas->sum('total_ventas'); @endphp
                        @foreach($ventas as $venta)
                        <tr>
                            <td>{{ $venta->fecha_agrupada }}</td>
                            <td>{{ $venta->total_pedidos }}</td>
                            <td>{{ $venta->total_productos }}</td>
                            <td>${{ number_format($venta->total_ventas, 2) }}</td>
                            <td>
                                @if($totalPeriodo > 0)
                                    {{ number_format(($venta->total_ventas / $totalPeriodo) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let ventasChart;

    document.addEventListener('DOMContentLoaded', function() {
        const ventasData = @json($ventas);
        
        renderChart('bar'); // Renderizar inicialmente como gráfico de barras
    });

    function renderChart(type) {
        const ctx = document.getElementById('ventasChart').getContext('2d');
        const ventasData = @json($ventas);
        
        const labels = ventasData.map(item => item.fecha_agrupada);
        const ventas = ventasData.map(item => item.total_ventas);
        const productos = ventasData.map(item => item.total_productos);

        if (ventasChart) {
            ventasChart.destroy(); // Destruir gráfico anterior si existe
        }

        ventasChart = new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ventas ($)',
                        data: ventas,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Productos Vendidos',
                        data: productos,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        type: 'line', // Siempre mostrar como línea
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Ventas ($)'
                        },
                        grace: '5%'
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Productos'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
                        grace: '5%'
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.dataset.label.includes('$') 
                                        ? '$' + context.parsed.y.toFixed(2)
                                        : context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    function changeChartType(type) {
        renderChart(type);
    }
</script>

<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
    }
    .card-header {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>
@endsection