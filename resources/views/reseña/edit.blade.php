@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De La Reseña</h2>
    <form action="/reseñas/{{$reseña->id_reseña}}/actualizar" method="POST">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- comentario -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Comentario:</label>
            <input type="text" id="nombre" name="comentario" class="form-control" value={{$reseña->comentario}} required>
        </div>

         <!-- calificacion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Calificacion:</label>
            <input type="text" id="ubicacionPr" name="calificacion" class="form-control" value={{$reseña->calificacion}} requiered>
        </div>

      <!-- fercha -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Fecha:</label>
            <input type="text" id="ubicacionPr" name="fecha" class="form-control" value={{$reseña->fecha}} requiered>
        </div>

         <!-- Tipo de producto -->
        <div class="mb-3">
            <label for="id_tipoE" class="form-label">Usuarios:</label>
                <select id="id_tipoE" name="user_id" class="form-select" required>
                    <option value="" disabled selected>Seleccione al usuario</option>
                    @foreach ($users as $user)
                       <option value={{$user->id}}> {{$user->name}} </option>
                    @endforeach
                <!-- Agrega más opciones según los tipos disponibles -->
        </select>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/reseñas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
