@extends('base')

@push('styles')
    <link rel="stylesheet" href="/css/editperfil.css">
@endpush

@section('titulo', 'Editar Perfil')

@section('content')
<div class="perfil-container">
    <h1 class="titulo">Editar Perfil</h1>

    <form method="POST" action="{{route('actualizarPerfil', $usuario->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="perfil-avatar">
            @if($usuario->foto)
                <img src="{{ asset('storage/' . $usuario->foto) }}" alt="Avatar del usuario">
            @else
                <img src="https://cdn-icons-png.flaticon.com/512/748/748137.png" alt="Usuario desconocido">
            @endif
            <label for="foto">Cambiar foto</label>
            <input type="file" name="foto" id="foto" accept="image/*">
        </div>

        <div class="perfil-info">
            <div>
              <label for="name">Nombre</label>
              <input type="text" id="name" name="name" value="{{ $usuario->name }}">
            </div>
            <div>
              <label for="email">Correo</label>
              <input type="email" id="email" name="email" value="{{ $usuario->email }}">
            </div>
            <!--
            <div>
              <label for="telefono">Teléfono</label>
              <input type="text" id="telefono" name="telefono" value="{{ $usuario->telefono ?? '' }}">
            </div>
            <div>
              <label for="direccion">Dirección</label>
              <input type="text" id="direccion" name="direccion" value="{{ $usuario->direccion ?? '' }}">
            </div>
          </div>
          -->

        <div class="perfil-actions">
            <button type="submit" class="btn-editar">Guardar cambios</button>
            <a href="{{route('mostrarPerfil')}}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>
@endsection
