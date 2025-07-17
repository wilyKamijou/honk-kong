<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descuento</th>
            <th>Productos</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $promo)
        <tr>
            <td>{{ $promo->id_promocion }}</td>
            <td>{{ $promo->nombre }}</td>
            <td>{{ $promo->descuento }}%</td>
            <td>{{ $promo->productos_count ?? '0' }}</td>
            <td>{{ \Carbon\Carbon::parse($promo->fecha_inicio)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}</td>
            <td>
                @if($promo->fecha_fin > now())
                    <span style="color: green;">Activa</span>
                @else
                    <span style="color: red;">Expirada</span>
                @endif
            </td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="6">Total Promociones:</td>
            <td>{{ $datos->count() }}</td>
        </tr>
    </tbody>
</table>