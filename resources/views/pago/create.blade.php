@extends('home')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Métodos de Pago</h1>
        <a href="{{ route('pagos.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver a métodos
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Agregar Nuevo Método de Pago</h6>
                </div>
                
                <form action="{{ route('pagos.store') }}" method="POST" id="payment-method-form">
                    @csrf
                    
                    <div class="card-body">
                        <!-- Selección de Usuario -->
                        <div class="form-group">
                            <label for="user_id" class="font-weight-bold">Usuario asociado *</label>
                            <select class="form-control select2 @error('user_id') is-invalid @enderror" 
                                    name="user_id" id="user_id" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selección de Pedido -->
                        <div class="form-group">
                            <label for="pedido_id" class="font-weight-bold">Pedido asociado (opcional)</label>
                            <select class="form-control select2 @error('pedido_id') is-invalid @enderror" 
                                    name="pedido_id" id="pedido_id">
                                <option value="">Sin pedido asociado</option>
                                @foreach($pedidos as $pedido)
                                    @php
                                        $fecha = is_string($pedido->fecha) ? date('d/m/Y', strtotime($pedido->fecha)) : $pedido->fecha->format('d/m/Y');
                                    @endphp
                                    <option value="{{ $pedido->id_pedido }}" {{ old('pedido_id') == $pedido->id_pedido ? 'selected' : '' }}>
                                        Pedido #{{ $pedido->id_pedido }} - {{ number_format($pedido->total, 2) }} € ({{ $fecha }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pedido_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo de Método -->
                        <div class="form-group">
                            <label for="tipo" class="font-weight-bold">Tipo de método *</label>
                            <select class="form-control @error('tipo') is-invalid @enderror" 
                                    name="tipo" id="tipo" required onchange="this.form.submit()">
                                <option value="">Seleccione un tipo</option>
                                @foreach($tipos as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alias -->
                        <div class="form-group">
                            <label for="alias">Alias (opcional)</label>
                            <input type="text" class="form-control @error('alias') is-invalid @enderror" 
                                   id="alias" name="alias" value="{{ old('alias') }}" 
                                   placeholder="Ej: Mi tarjeta principal" maxlength="50">
                            @error('alias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campos Condicionales - Tarjeta -->
                        @if(old('tipo') == 'tarjeta')
                        <div id="tarjeta-fields" class="payment-type-fields">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Instrucciones para tarjetas:</strong>
                                <ul class="mb-0">
                                    <li>Ingrese los 16 dígitos de su tarjeta (se formatearán automáticamente)</li>
                                    <li>El nombre debe coincidir exactamente con el de la tarjeta</li>
                                    <li>No almacenamos los datos de tu tarjeta, se procesan de forma segura</li>
                                </ul>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_titular">Nombre del titular *</label>
                                        <input type="text" class="form-control @error('nombre_titular') is-invalid @enderror" 
                                               id="nombre_titular" name="nombre_titular" 
                                               value="{{ old('nombre_titular') }}"
                                               placeholder="Como aparece en la tarjeta"
                                               required maxlength="100">
                                        @error('nombre_titular')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="marca">Marca de tarjeta *</label>
                                        <select class="form-control @error('marca') is-invalid @enderror" 
                                                id="marca" name="marca" required>
                                            <option value="">Seleccione marca</option>
                                            <option value="Visa" {{ old('marca') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                            <option value="Mastercard" {{ old('marca') == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                            <option value="American Express" {{ old('marca') == 'American Express' ? 'selected' : '' }}>American Express</option>
                                            <option value="Other" {{ old('marca') == 'Other' ? 'selected' : '' }}>Otra</option>
                                        </select>
                                        @error('marca')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero_tarjeta">Número de tarjeta *</label>
                                        <input type="text" class="form-control @error('numero_tarjeta') is-invalid @enderror" 
                                               id="numero_tarjeta" name="numero_tarjeta" 
                                               value="{{ old('numero_tarjeta') }}"
                                               placeholder="1234 5678 9012 3456"
                                               required maxlength="19"
                                               data-mask="0000 0000 0000 0000">
                                        <small class="form-text text-muted">Ingrese 16 dígitos (ej: 1234 5678 9012 3456)</small>
                                        @error('numero_tarjeta')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
<div class="col-md-6">
    <div class="form-group">
        <label for="fecha_expiracion">Fecha de expiración *</label>
        <input type="text" 
               class="form-control @error('fecha_expiracion') is-invalid @enderror" 
               id="fecha_expiracion" 
               name="fecha_expiracion" 
               value="{{ old('fecha_expiracion') }}"
               placeholder="MM/YY"
               required
               maxlength="5"
               pattern="(0[1-9]|1[0-2])\/[0-9]{2}"
               title="Formato MM/YY (ej: 12/27)">
        <small class="form-text text-muted">Ingrese mes y año (MM/YY) - Ejemplo: 12/27 para diciembre 2027</small>
        @error('fecha_expiracion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>             </div>
                        </div>
                        @endif

                        <!-- Campos Condicionales - QR -->
                        @if(old('tipo') == 'qr')
                        <div id="qr-fields" class="payment-type-fields">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Instrucciones para QR:</strong>
                                <ul class="mb-0">
                                    <li>El código QR debe tener entre 10 y 50 caracteres</li>
                                    <li>Solo se permiten letras, números, guiones y guiones bajos</li>
                                    <li>Puede escanear el código con su dispositivo móvil</li>
                                </ul>
                            </div>
                            
                            <div class="form-group">
                                <label for="codigo_qr">Código QR *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('codigo_qr') is-invalid @enderror" 
                                           id="codigo_qr" name="codigo_qr" 
                                           value="{{ old('codigo_qr') }}"
                                           placeholder="Ingrese el código QR"
                                           required
                                           minlength="10"
                                           maxlength="50"
                                           pattern="[A-Za-z0-9\-_]+"
                                           title="Solo letras, números, guiones y guiones bajos (10-50 caracteres)">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="scan-qr">
                                            <i class="fas fa-qrcode"></i> Escanear
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Mínimo 10, máximo 50 caracteres alfanuméricos</small>
                                @error('codigo_qr')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <!-- Método Predeterminado -->
                        <div class="form-group mt-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="es_predeterminado" 
                                       name="es_predeterminado" value="1" {{ old('es_predeterminado') ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="es_predeterminado">
                                    Establecer como método de pago predeterminado
                                </label>
                                <small class="form-text text-muted">Este método se usará automáticamente para futuras compras</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('pagos.create') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo mr-1"></i> Reiniciar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar Método
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Sección de Instrucciones -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-question-circle mr-2"></i>Ayuda</h6>
                </div>
                <div class="card-body">
                    <h5 class="font-weight-bold text-primary">Información importante</h5>
                    <p>Complete todos los campos obligatorios (*) para agregar un nuevo método de pago.</p>
                    
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fas fa-user mr-2"></i>Usuario asociado</h6>
                        <ul>
                            <li>Seleccione el usuario al que pertenece este método</li>
                            <li>Campo obligatorio para identificar al propietario</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fas fa-shopping-cart mr-2"></i>Pedido asociado</h6>
                        <ul>
                            <li>Opcionalmente puede vincularlo a un pedido existente</li>
                            <li>Útil para métodos de pago de un solo uso</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fas fa-credit-card mr-2"></i>Tarjetas</h6>
                        <ul>
                            <li>Debe ingresar los 16 dígitos de la tarjeta</li>
                            <li>La fecha de expiración debe ser futura</li>
                            <li>No se almacenan los CVV/CVC por seguridad</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fas fa-qrcode mr-2"></i>Códigos QR</h6>
                        <ul>
                            <li>Use la cámara de su dispositivo para escanear</li>
                            <li>Los códigos deben ser únicos y válidos</li>
                            <li>No comparta sus códigos QR públicamente</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <strong>Seguridad:</strong> Todos los datos se transmiten de forma cifrada y cumplen con los estándares PCI DSS.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 6px);
        padding: .375rem .75rem;
    }
    .payment-type-fields {
        transition: all 0.3s ease;
        overflow: hidden;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2 para usuarios y pedidos
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: function() {
            return $(this).data('placeholder');
        },
        width: '100%'
    });
    // Formatear fecha de expiración (MM/YY)
$('#fecha_expiracion').on('input', function() {
    let value = $(this).val().replace(/\D/g, ''); // Elimina todo excepto números
    
    if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    
    $(this).val(value);
});

// Validación adicional antes de enviar
$('#payment-method-form').submit(function(e) {
    // Validación de fecha de expiración
    const fecha = $('#fecha_expiracion').val();
    if (!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(fecha)) {
        Swal.fire({
            title: 'Formato incorrecto',
            text: 'La fecha de expiración debe tener el formato MM/YY (ej: 12/27)',
            icon: 'error'
        });
        $('#fecha_expiracion').focus();
        e.preventDefault();
        return false;
    }
    
    // Otras validaciones...
});

    // Formatear número de tarjeta con espacios cada 4 dígitos
    $('#numero_tarjeta').on('input', function(e) {
        // Eliminar todo excepto números
        let value = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        
        // Limitar a 16 dígitos
        if (value.length > 16) {
            value = value.substring(0, 16);
        }
        
        // Formatear con espacios cada 4 dígitos
        let formatted = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        $(this).val(formatted.trim());
        
        // Detectar marca de tarjeta basado en el primer dígito
        if (/^4/.test(value)) {
            $('#marca').val('Visa').trigger('change');
        } else if (/^5[1-5]/.test(value)) {
            $('#marca').val('Mastercard').trigger('change');
        } else if (/^3[47]/.test(value)) {
            $('#marca').val('American Express').trigger('change');
        } else if (value.length > 0) {
            $('#marca').val('Other').trigger('change');
        }
    });

    // Validar longitud exacta de tarjeta antes de enviar
    $('#payment-method-form').submit(function(e) {
        const tipo = $('#tipo').val();
        let isValid = true;
        
        // Validar usuario obligatorio
        if (!$('#user_id').val()) {
            Swal.fire({
                title: 'Usuario requerido',
                text: 'Debe seleccionar un usuario asociado',
                icon: 'error'
            });
            $('#user_id').focus();
            isValid = false;
        }
        
        if (tipo === 'tarjeta') {
            const numero = $('#numero_tarjeta').val().replace(/\s+/g, '');
            if (numero.length !== 16 || !/^\d+$/.test(numero)) {
                Swal.fire({
                    title: 'Error en tarjeta',
                    text: 'El número de tarjeta debe tener exactamente 16 dígitos numéricos',
                    icon: 'error'
                });
                $('#numero_tarjeta').focus();
                isValid = false;
            }
            
            const fecha = $('#fecha_expiracion').val();
            if (!fecha) {
                Swal.fire({
                    title: 'Error',
                    text: 'La fecha de expiración es requerida',
                    icon: 'error'
                });
                $('#fecha_expiracion').focus();
                isValid = false;
            }
        } else if (tipo === 'qr') {
            const codigo = $('#codigo_qr').val();
            if (!codigo || codigo.length < 10 || codigo.length > 50) {
                Swal.fire({
                    title: 'Error en QR',
                    text: 'El código QR debe tener entre 10 y 50 caracteres válidos',
                    icon: 'error'
                });
                $('#codigo_qr').focus();
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Simular escaneo de QR con generación de código válido
    $('#scan-qr').click(function() {
        Swal.fire({
            title: 'Escanear código QR',
            html: `
                <p>En una aplicación real, esto activaría la cámara para escanear un código QR.</p>
                <div class="text-center my-3">
                    <i class="fas fa-qrcode fa-5x text-primary"></i>
                </div>
                <p class="small">Para esta demostración, generaremos un código de ejemplo.</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Generar código',
            cancelButtonText: 'Cancelar',
            icon: 'info'
        }).then((result) => {
            if (result.isConfirmed) {
                // Generar un código QR de ejemplo válido (10-50 caracteres)
                const randomString = Math.random().toString(36).substring(2, 12) + 
                                   '-' + 
                                   Math.random().toString(36).substring(2, 6).toUpperCase();
                $('#codigo_qr').val('QR-' + randomString);
                
                Swal.fire({
                    title: 'Código generado',
                    text: 'Se ha creado un código QR de ejemplo válido',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
});
</script>
@endsection