@extends('home')
    
@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Detalle De Pedido</h2>
    <form action="/dtpedidos/{{$detalle->id_pedido}}/{{$detalle->id_producto}}/actualizar" method="POST">
        @method('PUT')

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <!-- cantidad -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Cantidad:</label>
            <input type="text" id="nombre" name="cantidad" class="form-control" value="{{$detalle->cantidad}}" required>
        </div>
        
        <!-- precio -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Precio:</label>
            <input type="float" id="nombre" name="precio" class="form-control" value="{{$detalle->precio}}" required>
        </div>

           <!-- Tipo de producto -->
           <div class="mb-3">
            <label for="id_tipoE" class="form-label">Producto:</label>
                <select id="id_tipoE" name="id_producto" class="form-select" required>
                    <option value="" disabled selected>Seleccione el producto</option>
                    @foreach ($productos as $producto)
                       <option value={{$producto->id_producto}}> {{$producto->nombre}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>

          <!-- Tipo de producto -->
          <div class="mb-3">
            <label for="id_tipoE" class="form-label">Predido:</label>
                <select id="id_tipoE" name="id_pedido" class="form-select" required>
                    <option value="" disabled selected>Seleccione el pedido</option>
                    @foreach ($pedidos as $pedido)
                       <option value={{$pedido->id_pedido}}> {{$pedido->id_pedido}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/dtpedidos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    @if (session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin: 10px 0;">
        {{ session('error') }}
    </div>
@endif

@endsection
