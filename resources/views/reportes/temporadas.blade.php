@extends('home')

@section('content')
<div class="container">
    <h1>Patrones Estacionales por Categoría</h1>
    
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Año:</label>
                <select name="year" class="form-control">
                    @foreach(range(date('Y')-2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Categoría:</label>
                <select name="categoria_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id_categoria }}" {{ $categoriaId == $cat->id_categoria ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Analizar</button>
            </div>
        </div>
    </form>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Heatmap de Ventas</h5>
        </div>
        <div class="card-body">
            <div id="heatmap-container" style="height: 500px;"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Top Productos por Temporada</h5>
        </div>
        <div class="card-body">
            <canvas id="seasonalProductsChart" height="150"></canvas>
        </div>
    </div>
</div>

<!-- Librerías para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Heatmap con ApexCharts
    const categories = {!! json_encode($heatmapData->keys()) !!};
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    let series = [];
    categories.forEach(cat => {
        let data = Array(12).fill(0);
        {!! json_encode($heatmapData) !!}[cat].forEach(item => {
            data[item.mes - 1] = item.venta_total;
        });
        series.push({
            name: cat,
            data: data
        });
    });

    const heatmapOptions = {
        chart: { type: 'heatmap', height: 350 },
        dataLabels: { enabled: false },
        colors: ["#008FFB"],
        xaxis: { categories: months },
        title: { text: 'Ventas por Mes y Categoría' },
        tooltip: {
            y: {
                formatter: function(value) {
                    return '$' + value.toLocaleString();
                }
            }
        }
    };

    const heatmap = new ApexCharts(
        document.querySelector("#heatmap-container"),
        { series, ...heatmapOptions }
    );
    heatmap.render();
});
</script>
@endsection