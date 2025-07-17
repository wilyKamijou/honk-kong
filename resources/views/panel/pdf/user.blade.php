<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Pedidos</th>
            <th>Registro</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $usuario)
        <tr>
            <td>{{ $usuario->id }}</td>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->telefono ?? 'N/A' }}</td>
            <td>{{ $usuario->pedidos_count ?? '0' }}</td>
            <td>{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="5">Total Usuarios:</td>
            <td>{{ $datos->count() }}</td>
        </tr>
    </tbody>
</table>