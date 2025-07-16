@extends('home')

@section ("contenido")
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="ruta-a-tu-archivo.css">

    <h2 style= "font-size: 5rem; font-family:'Times New Roman', Times, serif" class="text-center">Editar Datos De La Aplicion Del Descuento</h2>
    <form action="/apdescuentos/{{$apde->id_descuento}}/{{$apde->id_pedido}}/actualizar" method="POST">
        @method('PUT')
        
        <!-- CSRF Token (Laravel) -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

 <!-- Tipo de producto -->
 <div class="mb-3">
    <label for="id_tipoE" class="form-label">Descuento:</label>
        <select id="id_tipoE" name="id_descuento" class="form-select" required>
            <option value="" disabled selected>Seleccione el descuento</option>
            @foreach ($descuentos as $descuento)
               <option value={{$descuento->id_descuento}}> {{$descuento->descripcion}} del  {{number_format($descuento->porcentaje,0)}}% </option>
            @endforeach
        <!-- Agrega más opciones según los tipos disponibles -->
</select>

  <!-- Tipo de producto -->
  <div class="mb-3">
    <label for="id_tipoE" class="form-label">Pedidos:</label>
        <select id="id_tipoE" name="id_pedido" class="form-select" required>
            <option value="" disabled selected>Seleccione el pedido</option>
            @foreach ($pedidos as $pedido)
               <option value={{$pedido->id_pedido}}>ID:{{$pedido->id_pedido}} {{$pedido->name}} </option>
            @endforeach
        <!-- Agrega más opciones según los tipos disponibles -->
        </select>

        <!-- Botones -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/apdescuentos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    @if (session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif
@endsection
