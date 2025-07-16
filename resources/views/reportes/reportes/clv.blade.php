@extends('home')

@section('content')
<div class="container">
    <h1>Customer Lifetime Value (CLV)</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Valor por Cohorte</h5>
                </div>
                <div class="card-body">
                    <canvas id="clvChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Retención de Clientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cohorte</th>
                                    <th>Clientes</th>
                                    @for($i = 1; $i <= 6; $i++)
                                        <th>Mes {{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retention as $row)
                                <tr>
                                    <td>{{ $row['cohorte'] }}</td>
                                    <td>{{ $row['clientes'] }}</td>
                                    @for($i = 1; $i <= 6; $i++)
                                        <td class="{{ $row['m_' . $i] == '✔' ? 'text-success' : 'text-danger' }}">
                                            {{ $row['m_' . $i] }}
                                        </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Detalle por Cohorte</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cohorte</th>
                            <th>Clientes</th>
                            <th>Ventas Totales</th>
                            <th>Pedidos</th>
                            <th>Ticket Promedio</th>
                            <th>CLV</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cohortes as $cohorte)
                        <tr>
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m', $cohorte->cohorte)->format('M Y') }}</td>
                            <td>{{ $cohorte->total_clientes }}</td>
                            <td>${{ number_format($cohorte->ventas_totales, 2) }}</td>
                            <td>{{ $cohorte->total_pedidos }}</td>
                            <td>${{ number_format($cohorte->ticket_promedio, 2) }}</td>
                            <td>${{ number_format($cohorte->ventas_totales / max(1, $cohorte->total_clientes), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('clvChart').getContext('2d');
    const cohortes = {!! json_encode($cohortes->pluck('cohorte')) !!};
    const clvData = {!! json_encode($cohortes->map(function($c) { 
        return $c->total_clientes > 0 ? $c->ventas_totales / $c->total_clientes : 0; 
    })) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: cohortes.map(c => new Date(c + '-01').toLocaleDateString('es', {month:'short', year:'numeric'})),
            datasets: [{
                label: 'CLV (Valor por cliente)',
                data: clvData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Valor en USD'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'CLV: $' + context.raw.toFixed(2);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection