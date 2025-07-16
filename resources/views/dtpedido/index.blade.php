@extends('home')

@push('styles')
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
         <!-- Bootstrap Icons -->
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endpush

@section('contenido')
   
    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Detalles Del Pedido</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/dtpedidos/crear" class="btn btn-primary"> Crear +</a>
     <a href="#" class="btn btn-info" 
       onclick="event.preventDefault(); document.getElementById('generar-detalles-form').submit();">
        <i class="fas fa-plus-circle"></i> Generar Detalles Automáticos
    </a>
    
    <form id="generar-detalles-form" action="{{ route('dtpedidos.generar') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Cliente</th>
                <th scope="col">ID Pedido</th>
                <th scope="col">Producto</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Precio</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td>{{$detalle->id_pedido}} - {{$detalle->id_producto}}</td>
                <td>{{$detalle->nombre_cliente}}</td>
                <td>{{$detalle->id_pedido}}</td>
                <td>{{$detalle->nombre_producto}}</td>
                <td>{{$detalle->cantidad}}</td>
                <td>{{$detalle->precio}}</td>
                <td>
    
                    <form action="/dtpedidos/{{$detalle->id_pedido}}/{{$detalle->id_producto}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/dtpedidos/{{$detalle->id_pedido}}/{{$detalle->id_producto}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
   
@endsection
