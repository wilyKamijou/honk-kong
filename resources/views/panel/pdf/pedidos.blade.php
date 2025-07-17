<table>
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total (Bs)</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $pedido)
        <tr>
            <td>{{ $pedido->id_pedido }}</td>
            <td>{{ $pedido->user->name ?? 'N/A' }}</td>
            <td>{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}</td>
            <td>{{ number_format($pedido->total, 2) }}</td>
            <td>{{ ucfirst($pedido->estado) }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align: right;">Total General:</td>
            <td colspan="2">{{ number_format($datos->sum('total'), 2) }} Bs</td>
        </tr>
    </tbody>
</table>