@extends('base')

@push('styles')
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('titulo', 'Perfil de Usuario')

@section('content')
<div class="perfil-container">
    <div class="perfil-card">
        <h1 style="margin-bottom: 0; font-size: 50px; text-align: center; color: #00d4ff;">PERFIL DEL USUARIO</h1>
        <div class="perfil-avatar">
            @if($usuario->foto)
                <img src="{{ asset('storage/' . $usuario->foto) }}" alt="Avatar del usuario">
            @else
            <img src="https://cdn-icons-png.flaticon.com/512/748/748137.png" alt="Usuario desconocido" width="100" height="100">
            @endif
        </div>
        <div class="perfil-info">
            <h2>{{ $usuario->name }}</h2>
            <p><strong>Correo:</strong> {{ $usuario->email }}</p>
            <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No proporcionado' }}</p>
            <p><strong>Dirección:</strong> {{ $usuario->direccion ?? 'No registrada' }}</p>
            <p><strong>Rol:</strong> {{ $usuario->rol ?? 'Cliente' }}</p>
        </div>
        <div class="perfil-actions">
            <a href="/perfil/{{$usuario->id}}/editar" class="btn-editar">Editar Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-cerrar-sesion">Cerrar sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection
