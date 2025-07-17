@extends('home')

@section('contenido')
    <div class="container-fluid">
        <!-- Título alineado a la izquierda con tamaño más pequeño -->
        <h2 class="mt-4 mb-4" style="font-size: 1.8rem; font-weight: 600; color: #2c3e50; text-align: left; padding-left: 15px;">Panel</h2>

        <div class="row text-center justify-content-center g-4">
        <!-- Clientes -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card-stat card-hover">
                <div class="icon-wrapper bg-gradient-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="content">
                    <h5>Clientes</h5>
                    <h2>{{ $totalClientes }}</h2>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-primary" style="width: {{ ($totalClientes/50)*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card-stat card-hover">
                <div class="icon-wrapper bg-gradient-success">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="content">
                    <h5>Productos</h5>
                    <h2>{{ $totalProductos }}</h2>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-success" style="width: {{ ($totalProductos/100)*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card-stat card-hover">
                <div class="icon-wrapper bg-gradient-warning">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="content">
                    <h5>Pedidos</h5>
                    <h2>{{ $totalPedidos }}</h2>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-warning" style="width: {{ ($totalPedidos/20)*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card-stat card-hover">
                <div class="icon-wrapper bg-gradient-info">
                    <i class="bi bi-tags"></i>
                </div>
                <div class="content">
                    <h5>Categorías</h5>
                    <h2>{{ $totalCategorias }}</h2>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-info" style="width: {{ ($totalCategorias/10)*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ganancia Total -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card-stat card-hover">
                <div class="icon-wrapper bg-gradient-purple">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="content">
                    <h5>Ganado Total</h5>
                    <h2>Bs {{ number_format($totalGanado, 2, '.', ',') }}</h2>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-purple" style="width: {{ ($totalGanado/1000)*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nueva Ficha para Reportes -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <a href="{{ route('admin.reportes') }}" style="text-decoration: none;">
                <div class="card-stat card-hover">
                    <div class="icon-wrapper bg-gradient-orange">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <div class="content">
                        <h5>Generar Reportes</h5>
                        <h2><i class="bi bi-arrow-right"></i></h2>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-orange" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Gráfico de Ventas por Año/Mes -->
        <div class="card mt-5 p-4 shadow rounded">
            <h4 class="text-center mb-4">Ventas por Mes y Año</h4>
            
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="selectYear" class="form-label">Filtrar por año:</label>
                    <select id="selectYear" class="form-select">
                        <option value="all">Todos los años</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="chartType" class="form-label">Tipo de gráfico:</label>
                    <select id="chartType" class="form-select">
                        <option value="bar">Barras</option>
                        <option value="line">Líneas</option>
                    </select>
                </div>
            </div>

            <!-- Canvas del Gráfico -->
            <div class="chart-container" style="height: 400px; width: 100%;">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>
    </div>

    <style>
        .card-stat {
            background: rgb(255, 255, 255);
            border-radius: 12px;
            padding: 25px 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.12);
        }
        
        .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 24px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .bg-gradient-purple {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
        }
        
        .bg-gradient-orange {
            background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
        }
        
        .card-stat h5 {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .card-stat h2 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .progress {
            height: 6px;
            border-radius: 3px;
            background: #f1f1f1;
        }
        
        .bg-purple {
            background-color: #9c27b0;
        }
        
        .bg-orange {
            background-color: #ff7e5f;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos desde PHP
        const allData = {
            labels: @json($meses),
            datasets: @json($datasets)
        };

        // Configuración inicial del gráfico
        const ctx = document.getElementById('ventasChart').getContext('2d');
        const ventasChart = new Chart(ctx, {
            type: 'bar',
            data: allData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                return ` ${context.dataset.label}: Bs. ${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total en Bs.'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    }
                }
            }
        });

        // Filtrar por año
        document.getElementById('selectYear').addEventListener('change', function() {
            const year = this.value;
            
            if (year === 'all') {
                ventasChart.data.datasets = allData.datasets;
            } else {
                ventasChart.data.datasets = allData.datasets.filter(ds => ds.label === year);
            }
            
            ventasChart.update();
        });

        // Cambiar tipo de gráfico
        document.getElementById('chartType').addEventListener('change', function() {
            ventasChart.config.type = this.value;
            ventasChart.update();
        });
    </script>
@endsection