@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos Del Descuento</h2>
    <form action="/descuentos/{{$descuento->id_descuento}}/actualizar" method="POST">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- codigo -->
        <div class="mb-3">
            <label for="nombrePr" class="form-label">Codigo del Descuento:</label>
            <input type="text" id="nombre" name="codigo" class="form-control" value={{$descuento->codigo}} required>
        </div>

        <!-- Descripcion -->
        <div class="mb-3">
            <label for="ubicacionPr" class="form-label">Descripcion del descuento:</label>
            <input type="text" id="ubicacionPr" name="descripcion" class="form-control" value={{$descuento->descripcion}} requiered>
        </div>

        <!-- porcentaje -->
        <div class="mb-3">
        <label for="ubicacionPr" class="form-label">Porcentaje del descuento:</label>
        <input type="text" id="ubicacionPr" name="porcentaje" class="form-control" value={{$descuento->porcentaje}} requiered>
        </div>

        <!-- fechad e inicio -->
        <div class="mb-3">
        <label for="ubicacionPr" class="form-label">Fecha de inicio:</label>
        <input type="text" id="ubicacionPr" name="fecha_inicio" class="form-control" value={{$descuento->fecha_inicio}} requiered>
        </div>

        <!-- fecha de finalizacion -->
        <div class="mb-3">
        <label for="ubicacionPr" class="form-label">Fecha de finalizacion:</label>
        <input type="text" id="ubicacionPr" name="fecha_fin" class="form-control" value={{$descuento->fecha_fin}} requiered>
        </div>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/descuentos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
