@extends('base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contactanos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barraVertical.css') }}">
@endpush

@section('content')

  <div>
    <div class="contacto-box container my-5">
      <h1 class="text-center titulo-contacto">Contáctanos</h1>
    
      <div class="row">
        <div class="col-md-6">
          <form class="formulario-contacto" action="{{ route('inicio') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input type="text" name="nombre" id="nombre" required>
            </div>
            <div class="form-group">
              <label for="email">Correo electrónico</label>
              <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
              <label for="mensaje">Mensaje</label>
              <textarea name="mensaje" id="mensaje" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn-enviar">Enviar mensaje</button>
          </form>
        </div>
    
        <div class="wrapper">
          <div class="col-md-6 info-contacto">
            <h3>O llámanos</h3>
            <p>📞 +591 70899084</p>
            <p>✉️ info@hongkongrapida.com</p>
            <h3>Visítanos</h3>
            <p>Calle Falsa 123, La Paz, Bolivia</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
