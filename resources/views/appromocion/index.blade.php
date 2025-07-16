@extends('home')

@section('contenido')
     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Aplicacion De Promociones</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/appromociones/crear" class="btn btn-primary"> Crear +</a>
    <a href="{{ route('appromociones.asignar-automaticas') }}" 
   class="btn btn-info"
   title="Asignará promociones a máximo la mitad de los productos"
   onclick="return confirm('¿Estás seguro de asignar promociones automáticamente? Se aplicarán a máximo la mitad de los productos disponibles.')">
    <i class="fas fa-tags"></i> Asignar Promociones
</a>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID aplicacion de la promocion</th>
                <th scope="col">Producto</th>
                <th scope="col">Promocion</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appromociones as $appromocion)
            <tr>
                <td>{{$appromocion->id_producto}} - {{$appromocion->id_promocion}}</td>
                <td>{{$productos->firstwhere('id_producto',$appromocion->id_producto)?->nombre}}</td>
                <td>{{$promociones->firstwhere('id_promocion',$appromocion->id_promocion)?->nombre}}</td>
                <td>
    
                    <form action="/appromociones/{{$appromocion->id_producto}}/{{$appromocion->id_promocion}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/appromociones/{{$appromocion->id_producto}}/{{$appromocion->id_promocion}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
