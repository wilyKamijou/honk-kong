@extends('home')

@section('contenido')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Aplicacion De Descuentos</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/apdescuentos/crear" class="btn btn-primary"> Crear +</a>
    <a href="{{ route('apdescuentos.asignar-automaticos') }}" class="btn btn-primary" onclick="return confirm('¿Estás seguro de asignar descuentos automáticamente?')">
    <i class="fas fa-magic"></i> Asignar Descuentos
</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID aplicacion del descuento</th>
                <th scope="col">Descuento</th>
                <th scope="col">Pedido</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apdes as $apde)
            <tr>
                <td>{{$apde->id_descuento}} - {{$apde->id_pedido}}</td>
                <td>{{$apde->descripcion}} del {{number_format($apde->porcentaje,0)}}%</td>
                <td>{{$apde->name}}</td>
                <td>
    
                    <form action="/apdescuentos/{{$apde->id_descuento}}/{{$apde->id_pedido}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/apdescuentos/{{$apde->id_descuento}}/{{$apde->id_pedido}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
