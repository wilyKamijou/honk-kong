@extends('home')

@section('contenido')
     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Lista De Envios</h2>
    <a href="/home" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Volver</a>
    <a href="/envios/crear" class="btn btn-primary"> Crear +</a>

        <a href="#" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('igualar-form').submit();">
            <i class="fas fa-sync-alt"></i> Igualar Envíos a Pedidos
        </a>
        
        <form id="igualar-form" action="{{ route('envios.igualar') }}" method="POST" style="display: none;">
            @csrf
        </form>
  
    <table class="table table-dark table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Direccion del envio</th>
                <th scope="col">Fecha enviada</th>
                <th scope="col">Fecha de llegeda</th>
                <th scope="col">Metodo de envio</th>
                <th scope="col">Estado</th>
                <th scope="col">ID pedido</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($envios as $envio)
            <tr>
                <td>{{$envio->id_envio}}</td>
                <td>{{$envio->direccion_envio}}</td>
                <td>{{$envio->fecha_envio}}</td>
                <td>{{$envio->fecha_estimada_llegada}}</td>
                <td>{{$envio->metodo_envio}}</td>
                <td>{{$envio->estado_envio}}</td>
                <td>{{$envio->id_pedido}}</td>
                <td>
    
                    <form action="/envios/{{$envio->id_envio}}/eliminar" method="POST">
                        @CSRF
                        @method('delete')
                        <a href="/envios/{{$envio->id_envio}}/editar" class="btn btn-info">Editar</a>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
            
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
