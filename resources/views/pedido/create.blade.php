@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Registrar Nuevo Pedido</h2>
    <form action="/pedidos/guardar" method="POST">

        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

       <!-- Tipo de cliente -->
       <div class="mb-3">
        <label for="id_tipoE" class="form-label">Cliente:</label>
            <select id="id_tipoE" name="user_id" class="form-select" required>
                <option value="" disabled selected>Seleccione al cliente</option>
                @foreach ($clientes as $cliente)
                   <option value={{$cliente->id}}> {{$cliente->name}} </option>
                @endforeach
            <!-- Agrega más opciones según los tipos disponibles -->
            </select>

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Fecha:</label>
            <input type="text" id="nombre" name="fecha" class="form-control" placeholder="Ingrese la fecha" required>
        </div>

         <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Total:</label>
            <input type="text" id="ubicacionPr" name="total" class="form-control" placeholder="Ingrese el total" requiered>
        </div>

           <!-- precio-->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Estado:</label>
            <input type="text" id="ubicacion" name="estado" class="form-control" placeholder="Ingrese el estado" required>
        </div>

           <!-- url-->
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Direccion de envio:</label>
            <input type="text" id="ubicacion" name="direccion_envio" class="form-control" placeholder="Ingrese la direccion de envio" required>

           <!-- Tipo de producto -->
           <div class="mb-3">
            <label for="tipo_pago" class="form-label">Seleccione la targeta:</label>
                <select id="tipo_pago" name="id_pago" class="form-select" required>
                    <option value="" disabled selected>Seleccione</option>
                    @foreach ($pagos as $pago)
                       <option value="{{ $pago->id_pago }}"> {{$pago->nombre_titular}} = {{$pago->numero_targera}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
            </select>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/pedidos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
