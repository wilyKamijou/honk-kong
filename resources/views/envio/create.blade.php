@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Envio</h2>
    <form action="/envios/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Direccion del envio:</label>
            <input type="text" id="nombre" name="direccion_envio" class="form-control" placeholder="Ingrese la direccion" required>
        </div>

         <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Fecha enviada:</label>
            <input type="text" id="ubicacionPr" name="fecha_envio" class="form-control" placeholder="Ingrese la fecha" requiered>
        </div>

           <!-- precio-->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Fecha de llegada</label>
            <input type="text" id="ubicacion" name="fecha_estimada_llegada" class="form-control" placeholder="Ingrese la fecha de llegada" required>
        </div>

           <!-- url-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Metodo de envio:</label>
            <input type="text" id="ubicacion" name="metodo_envio" class="form-control" placeholder="Ingrese el metodo" required>
        </div>
        <!-- url-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Estado:</label>
            <input type="text" id="ubicacion" name="estado_envio" class="form-control" placeholder="Ingrese el estado del envio" required>
        </div>
  
           <!-- Tipo de producto -->
           <div class="mb-3">
            <label for="id_tipoE" class="form-label">Seleccione el pedido:</label>
                <select id="id_tipoE" name="id_pedido" class="form-select" required>
                    <option value="" disabled selected>Seleccione</option>
                    @foreach ($pedidos as $pedido)
                       <option value={{$pedido->id_pedido}}> {{$pedido->id_pedido}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
   
        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/envios" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
