@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Producto</h2>
    <form action="/producto/{{$producto->id_producto}}/actualizar" method="POST" enctype="multipart/form-data">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre del producto:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{$producto->nombre}}" required>
        </div>

         <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Descripcion del producto:</label>
            <input type="text" id="ubicacionPr" name="descripcion" class="form-control" value="{{$producto->descripcion}}" requiered>
        </div>

           <!-- precio-->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Precio del producto:</label>
            <input type="text" id="ubicacion" name="precio" class="form-control" value="{{$producto->precio}}" required>
        </div>

           <!-- url-->
           <div class="mb-3">
            <label for="ubicacion" class="form-label">Url:</label>
            <input type="text" id="ubicacion" name="imagen_url" class="form-control" value="{{$producto->imagen_url}}" required>
        </div>

           <!-- Tipo de producto -->
           <div class="mb-3">
            <label for="id_tipoE" class="form-label">Tipo de categoria:</label>
                <select id="id_tipoE" name="id_categoria" class="form-select" required>
                    <option value="" disabled selected>Seleccione el tipo de categoria del producto</option>
                    @foreach ($categorias as $categoria)
                       <option value={{$categoria->id_categoria}}> {{$categoria->nombre}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
                </select>
                {{--
        <!-- Tipo de promocion -->
            <div class="mb-3">
                <label for="id_tipoE" class="form-label">Tipo de promocion:</label>
                    <select id="id_tipoE" name="id_promocion" class="form-select" required>
                        <option value="" disabled selected>Seleccione el tipo de promocion del producto</option>
                        @foreach ($promociones as $promocion)
                           <option value={{$promocion->id_promocion}}> {{$promocion->nombre}} </option>
                        @endforeach
                    <!-- Agrega más opciones según los tipos disponibles -->
                    </select>
            </div>


        </div>
        --}}

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/producto" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
