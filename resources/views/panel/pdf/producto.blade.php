<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio (Bs)</th>
            <th>Stock</th>
            <th>Fecha Creación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $producto)
        <tr>
            <td>{{ $producto->id_producto }}</td>
            <td>{{ $producto->nombre }}</td>
            <td>{{ $producto->categorias->nombre ?? 'N/A' }}</td>
            <td>{{ number_format($producto->precio, 2) }}</td>
            <td>{{ $producto->stock ?? 'N/A' }}</td>
            <td>{{ \Carbon\Carbon::parse($producto->created_at)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3">Total Productos:</td>
            <td colspan="3">{{ $datos->count() }}</td>
        </tr>
    </tbody>
</table>