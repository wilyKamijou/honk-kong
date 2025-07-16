@extends('home')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Reportes de Ventas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Reportes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Mostrar alerta si son datos de demostración -->
            @if($datosReporte['esDemo'] ?? false)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-info"></i> ¡Atención!</h5>
                        Estás viendo datos de demostración. Aplica filtros para ver tus datos reales.
                    </div>
                </div>
            </div>
            @endif

            <!-- Filtros -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filtrar Reporte</h3>
                    @if($datosReporte['esDemo'] ?? false)
                    <div class="card-tools">
                        <span class="badge badge-warning">Modo Demo</span>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reportes') }}" id="reportesForm">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" 
                                           value="{{ $datosReporte['fechaInicio'] }}">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control" 
                                           value="{{ $datosReporte['fechaFin'] }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumen General -->
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>${{ number_format($datosReporte['totalGeneral'], 2) }}</h3>
                            <p>Total Ventas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <a href="#" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $datosReporte['pedidosPorFecha']->sum('total_pedidos') }}</h3>
                            <p>Total Pedidos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="#" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ \Carbon\Carbon::parse($datosReporte['fechaInicio'])->diffInDays($datosReporte['fechaFin']) + 1 }}</h3>
                            <p>Días en el Periodo</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="#" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <!-- Gráfico de Ventas Diarias -->
                <div class="col-lg-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Ventas por Día
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="ventasDiariasChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Productos Más Vendidos -->
                <div class="col-lg-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Productos Más Vendidos (Cantidad)
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="productosVendidosChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tablas de Detalle -->
            <div class="row">
                <!-- Tabla de Ventas por Fecha -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-table mr-1"></i>
                                Detalle de Ventas por Fecha
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th class="text-center">Pedidos</th>
                                            <th class="text-right">Ventas</th>
                                            <th class="text-right">Promedio por Pedido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datosReporte['tableData'] as $item)
                                        @php
                                            $ventasNumerico = floatval(str_replace(['$', ','], '', $item['ventas']));
                                            $promedio = $item['pedidos'] > 0 ? $ventasNumerico / $item['pedidos'] : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ $item['pedidos'] }}</td>
                                            <td class="text-right">{{ $item['ventas'] }}</td>
                                            <td class="text-right">${{ number_format($promedio, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray">
                                            @php
                                                $totalPedidos = $datosReporte['pedidosPorFecha']->sum('total_pedidos');
                                                $totalVentas = $datosReporte['totalGeneral'];
                                                $promedioTotal = $totalPedidos > 0 ? $totalVentas / $totalPedidos : 0;
                                            @endphp
                                            <th>Total</th>
                                            <th class="text-center">{{ $totalPedidos }}</th>
                                            <th class="text-right">${{ number_format($totalVentas, 2) }}</th>
                                            <th class="text-right">${{ number_format($promedioTotal, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Productos Más Vendidos -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-boxes mr-1"></i>
                                Detalle de Productos Más Vendidos
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad Vendida</th>
                                            <th class="text-center">% del Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalVendido = $datosReporte['productosMasVendidos']->sum('total_vendido');
                                        @endphp
                                        @foreach($datosReporte['productosMasVendidos'] as $index => $producto)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $producto['nombre'] }}</td>
                                            <td class="text-center">{{ $producto['total_vendido'] }}</td>
                                            <td class="text-center">
                                                @if($totalVendido > 0)
                                                    {{ number_format(($producto['total_vendido'] / $totalVendido) * 100, 1) }}%
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
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function () {
    // Configuración común para gráficos
    const chartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Gráfico de Ventas Diarias
    const ventasCtx = document.getElementById('ventasDiariasChart').getContext('2d');
    const ventasChart = new Chart(ventasCtx, {
        type: 'bar',
        data: {
            labels: @json($datosReporte['pedidosPorFecha']->map(function($item) {
                return \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y');
            })),
            datasets: [{
                label: 'Ventas ($)',
                data: @json($datosReporte['pedidosPorFecha']->pluck('total_ventas')),
                backgroundColor: 'rgba(60, 141, 188, 0.9)',
                borderColor: 'rgba(60, 141, 188, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
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
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Ventas: $' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Productos Más Vendidos
    const productosCtx = document.getElementById('productosVendidosChart').getContext('2d');
    const productosChart = new Chart(productosCtx, {
        type: 'bar',
        data: {
            labels: @json($datosReporte['productosMasVendidos']->pluck('nombre')),
            datasets: [{
                label: 'Unidades Vendidas',
                data: @json($datosReporte['productosMasVendidos']->pluck('total_vendido')),
                backgroundColor: 'rgba(0, 123, 255, 0.8)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // Mostrar spinner durante la carga del filtro
    $('#reportesForm').on('submit', function() {
        $('.chart').each(function() {
            $(this).html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-2">Cargando datos...</p></div>');
        });
    });
});
</script>
@endsection