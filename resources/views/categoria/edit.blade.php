@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De La Categoria</h2>
    <form action="/categorias/{{$categoria->id_categoria}}/actualizar" method="POST">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Nombre de la categoria:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{$categoria->nombre}}" required>
        </div>

         <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Descripcion de la categoria:</label>
            <input type="text" id="ubicacionPr" name="descripcion" class="form-control" value="{{$categoria->descripcion}}" requiered>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/categorias" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
