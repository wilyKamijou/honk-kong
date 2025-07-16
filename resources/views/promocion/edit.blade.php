@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De La Promocion</h2>
    <form action="/promociones/{{$promocion->id_promocion}}/actualizar" method="POST">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
 <!-- Nombre -->
 <div class="mb-3">
    <label for="nombrePr" class="form-label">Nombre de la promocion:</label>
    <input type="text" id="nombre" name="nombre" class="form-control" value="{{$promocion->nombre}}" required>
</div>

 <!-- valor -->
<div class="mb-3">
    <label for="ubicacionPr" class="form-label">Valor de la promocion:</label>
    <input type="text" id="ubicacionPr" name="valor" class="form-control" value="{{$promocion->valor}}" requiered>
</div>

      <!-- fecha inicio -->
      <div class="mb-3">
        <label for="ubicacionPr" class="form-label">Fecha de incio:</label>
        <input type="text" id="ubicacionPr" name="fecha_inicio" class="form-control" value="{{$promocion->fecha_inicio}}" requiered>
    </div>

      <!-- fecha fin -->
      <div class="mb-3">
        <label for="ubicacionPr" class="form-label">fecha de finalizacion:</label>
        <input type="text" id="ubicacionPr" name="fecha_fin" class="form-control" value="{{$promocion->fecha_fin}}" requiered>
    </div>

<!-- Botones -->
<div class="mb-3">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="/promociones" class="btn btn-secondary">Cancelar</a>
</div>

    </form>
@endsection
