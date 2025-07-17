<table>
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Cantidad</th>
            <th>Precio Unitario (Bs)</th>
            <th>Subtotal (Bs)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $detalle)
        <tr>
            <td>{{ $detalle->id_pedido }}</td>
            <td>{{ $detalle->productos->nombre ?? 'N/A' }}</td>
            <td>{{ $detalle->productos->categorias->nombre ?? 'N/A' }}</td>
            <td>{{ $detalle->cantidad }}</td>
            <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
            <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="5" style="text-align: right;">Total:</td>
            <td>{{ number_format($datos->sum(function($item) { 
                return $item->cantidad * $item->precio_unitario; 
            }), 2) }} Bs</td>
        </tr>
    </tbody>
</table>