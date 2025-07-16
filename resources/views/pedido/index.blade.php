@extends('home')

@section('contenido')
     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De pedidos</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/pedidos/crear" class="btn btn-primary"> Crear +</a>
 
    <a href="{{ route('pedidos.generate-fake') }}" class="btn btn-info" onclick="return confirm('¿Estás seguro de generar 50 pedidos de prueba?')">
        <i class="fas fa-magic me-1"></i> Generar 50 Pedidos de Prueba
    </a>


    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">ID cliente</th>
                <th scope="col">Fecha</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
                <th scope="col">Direccion de envio</th>
                <th scope="col">ID targeta</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr>
                <td>{{$pedido->id_pedido}}</td>
                <td>{{$pedido->user_id}}</td>
                <td>{{$pedido->fecha}}</td>
                <td>{{$pedido->total}}</td>
                <td>{{$pedido->estado}}</td>
                <td>{{$pedido->direccion_envio}}</td>
                <td>{{$pedido->id_pago}}</td>
                <td>
    
                    <form action="/pedidos/{{$pedido->id_pedido}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/pedidos/{{$pedido->id_pedido}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
