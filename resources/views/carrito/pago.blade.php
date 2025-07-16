@extends('base')

@push('styles')
    <link rel="stylesheet" href="/css/pago.css">
    <style>
        /* Estilos para las pestañas de métodos de pago */
        .payment-methods-container {
            width: 100%;
            margin-bottom: 25px;
        }
        .payment-methods {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .payment-method {
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .payment-method.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .payment-method i {
            margin-right: 8px;
        }
        
        /* Ocultar pestañas no activas */
        .payment-tab {
            display: none;
        }
        .payment-tab.active {
            display: block;
        }
        
        /* Estilo unificado para el textarea */
        .payment-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
            min-height: 100px;
            resize: vertical;
            background-color: transparent;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .payment-form textarea:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        /* Estilos para el contenedor del QR */
.qr-image-container {
    width: 100%;
    max-width: 400px; /* Ajusta según necesites */
    margin: 15px auto;
    padding: 10px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

/* Estilos para la imagen QR */
.qr-image {
    width: 100%;
    height: auto;
    max-width: 300px; /* Esto hará que no se vea pixelada */
    display: block;
    margin: 0 auto;
}

/* Asegúrate que los inputs tengan el mismo ancho que el QR */
.payment-form input[type="text"],
.payment-form textarea {
    width: 100%;
    max-width: 400px; /* Debe coincidir con qr-image-container */
    margin-left: auto;
    margin-right: auto;
    display: block;
}
    </style>

@endpush

@section('content')

<div class="form-page">
    <div class="payment-methods-container">
        <div class="payment-methods">
            <div class="payment-method active" data-tab="card">
                <i class="fas fa-credit-card"></i> Tarjeta
            </div>
            <div class="payment-method" data-tab="qr">
                <i class="fas fa-qrcode"></i> QR
            </div>
            <div class="payment-method" data-tab="cash">
                <i class="fas fa-money-bill-wave"></i> Efectivo
            </div>
        </div>
    </div>

    <!-- Pestaña Tarjeta -->
    <form action="/carrito/procesar" method="POST" class="payment-form payment-tab active" id="card-tab">
        @csrf
        <input type="hidden" name="metodo_pago" value="tarjeta">
        <h2>Pago con tarjeta</h2>
        <input type="text" name="nombre_titular" placeholder="Nombre del titular" required>
        <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="Número de tarjeta" maxlength="19" required>
        <div class="row-inputs">
            <input type="text" name="fecha_expiracion" placeholder="MM/AA" required maxlength="5" oninput="formatearFecha(this)">
            <input type="number" name="cvc" placeholder="CVC" required maxlength="3" oninput="this.value = this.value.slice(0, 3)">
        </div>
        <input type="text" name="direccion_envio" placeholder="Dirección de envío" required>
        <input type="text" id="indicacione" name="indicaciones" placeholder="Indicaciones adicionales para la entrega" maxlength="19" required>
       
        <div class="form-buttons">
            <button type="submit" class="btn-confirmar">Confirmar pago</button>
            <a href="/" class="btn-cancelar">Cancelar</a>
        </div>
    </form>

    <!-- Pestaña QR -->
<form action="/carrito/procesar" method="POST" class="payment-form payment-tab" id="qr-tab">
    @csrf
    <input type="hidden" name="metodo_pago" value="qr">
    <h2>Pago con QR</h2>
    <div class="qr-instructions">
        <p>Escanea el siguiente código QR con tu aplicación de pagos:</p>
        <div class="qr-image-container">
            <img src="https://i.ibb.co/dwrZ2ZG9/Copilot-20250714-192123.png" alt="Código QR para pago" class="qr-image">
        </div>
    </div>
    <input type="text" name="direccion_envio" placeholder="Dirección de envío" required>
    <textarea name="indicaciones" placeholder="Indicaciones adicionales para la entrega"></textarea>
    <div class="form-buttons">
        <button type="submit" class="btn-confirmar">Confirmar pago</button>
        <a href="/" class="btn-cancelar">Cancelar</a>
    </div>
</form>

    <!-- Pestaña Efectivo -->
    <form action="/carrito/procesar" method="POST" class="payment-form payment-tab" id="cash-tab">
        @csrf
        <input type="hidden" name="metodo_pago" value="efectivo">
        <h2>Pago en efectivo</h2>
        <p class="cash-message">El pago se realizará al momento de la entrega</p>
        <input type="text" name="direccion_envio" placeholder="Dirección de envío" required>
        <textarea name="indicaciones" placeholder="Indicaciones adicionales para la entrega"></textarea>
        <div class="form-buttons">
            <button type="submit" class="btn-confirmar">Confirmar pedido</button>
            <a href="/" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Cambio entre métodos de pago
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            // Remover clase active de todos
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            document.querySelectorAll('.payment-tab').forEach(t => t.classList.remove('active'));
            
            // Agregar clase active al seleccionado
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab') + '-tab';
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Formatear número de tarjeta
    document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.substring(0, 16);
        e.target.value = value.replace(/(.{4})/g, '$1 ').trim();
    });

    // Formatear fecha de expiración
    function formatearFecha(input) {
        let valor = input.value.replace(/\D/g, '');
    
        if (valor.length >= 3) {
            input.value = valor.slice(0, 2) + '/' + valor.slice(2, 4);
        } else {
            input.value = valor;
        }
    }
</script>
    
@endsection