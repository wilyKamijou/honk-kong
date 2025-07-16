@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De La Aplicion De Promociones</h2>
    <form action="/appromociones/{{$appromocion->id_producto}}/{{$appromocion->id_promocion}}/actualizar" method="POST">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
    <label for="id_tipoE" class="form-label">Promociones:</label>
        <select id="id_tipoE" name="id_promocion" class="form-select" required>
            <option value="" disabled selected>Seleccione la promocion</option>
            @foreach ($promociones as $promocion)
               <option value={{$promocion->id_promocion}}> {{$promocion->nombre}} </option>
            @endforeach
        <!-- Agrega más opciones según los tipos disponibles -->
</select>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/appromociones" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
@endsection
