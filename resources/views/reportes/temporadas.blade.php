@extends('home')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Ventas por Categoría</h1>
    
    <form method="GET" action="{{ route('reportes.ventas-categoria') }}" class="mb-4 card card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Año:</label>
                <select name="year" class="form-select">
                    @foreach(range(date('Y')-5, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Categoría:</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id_categoria }}" {{ $categoriaId == $cat->id_categoria ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filtrar
                </button>
            </div>
        </div>
    </form>

    @if(count($heatmapData) > 0)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-fire me-2"></i> Heatmap de Ventas ({{ $year }})
                </h5>
            </div>
            <div class="card-body">
                <div id="heatmap" style="height: 500px;"></div>
            </div>
        </div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-star me-2"></i> Productos Destacados por Mes
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($topProductos as $mes => $productos)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                {{ DateTime::createFromFormat('!m', $mes)->format('F') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <ol class="list-group list-group-numbered">
                                @foreach($productos->take(3) as $producto)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $producto->producto_nombre }}</div>
                                            <small>{{ $producto->categoria_nombre }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $producto->unidades_vendidas }}
                                        </span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i> No se encontraron datos para los filtros seleccionados.
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($heatmapData) > 0)
        // Configuración del heatmap
        const options = {
            series: Object.entries({!! json_encode($heatmapData) !!}).map(([categoria, meses]) => ({
                name: categoria,
                data: Object.entries(meses).map(([mes, valor]) => ({
                    x: ['Ene','Feb','Mar','Abr','May','Jun',
                        'Jul','Ago','Sep','Oct','Nov','Dic'][parseInt(mes)-1],
                    y: valor
                }))
            })),
            chart: {
                type: 'heatmap',
                height: 500,
                toolbar: { show: true }
            },
            dataLabels: { enabled: false },
            colors: ["#008FFB"],
            xaxis: { type: 'category' },
            title: { 
                text: 'Distribución de Ventas por Mes', 
                align: 'center',
                style: { fontSize: '16px' }
            },
            tooltip: {
                y: { formatter: (val) => '$' + val.toLocaleString() }
            }
        };

        // Renderizar el gráfico
        const chart = new ApexCharts(document.querySelector("#heatmap"), options);
        chart.render();
    @endif
});
</script>
@endpush
@endsection