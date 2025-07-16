@extends('adminlte::page')

@section('title', 'Crear Método de Pago')

@section('content_header')
    <h1 class="m-0 text-dark">Agregar Método de Pago</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Información del Método de Pago</h3>
            </div>
            
            <form action="/pagos/guardar" method="POST" id="metodoPagoForm">
                @csrf
                
                <div class="card-body">
                    <!-- Tipo de método -->
                    <div class="form-group">
                        <label for="tipo">Tipo de Pago</label>
                        <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" id="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="tarjeta" {{ old('tipo') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="qr" {{ old('tipo') == 'qr' ? 'selected' : '' }}>Código QR</option>
                            <option value="efectivo" {{ old('tipo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="transferencia" {{ old('tipo') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        </select>
                        @error('tipo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <!-- Alias -->
                    <div class="form-group">
                        <label for="alias">Alias/Nombre</label>
                        <input type="text" class="form-control @error('alias') is-invalid @enderror" 
                               id="alias" name="alias" placeholder="Ej: Tarjeta Principal" 
                               value="{{ old('alias') }}">
                        @error('alias')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <!-- Campos para tarjeta -->
                    <div id="tarjetaFields" style="display: none;">
                        <div class="form-group">
                            <label for="nombre_titular">Nombre del Titular</label>
                            <input type="text" class="form-control @error('nombre_titular') is-invalid @enderror" 
                                   id="nombre_titular" name="nombre_titular" 
                                   value="{{ old('nombre_titular') }}">
                            @error('nombre_titular')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="numero_tarjeta">Número de Tarjeta</label>
                            <input type="text" class="form-control @error('numero_tarjeta') is-invalid @enderror" 
                                   id="numero_tarjeta" name="numero_tarjeta" 
                                   placeholder="1234 5678 9012 3456"
                                   value="{{ old('numero_tarjeta') }}">
                            @error('numero_tarjeta')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_expiracion">Fecha de Expiración</label>
                                    <input type="text" class="form-control @error('fecha_expiracion') is-invalid @enderror" 
                                           id="fecha_expiracion" name="fecha_expiracion" 
                                           placeholder="MM/AA"
                                           value="{{ old('fecha_expiracion') }}">
                                    @error('fecha_expiracion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cvc">Código CVC</label>
                                    <input type="text" class="form-control @error('cvc') is-invalid @enderror" 
                                           id="cvc" name="cvc" 
                                           placeholder="123"
                                           maxlength="3"
                                           value="{{ old('cvc') }}">
                                    @error('cvc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos para QR -->
                    <div id="qrFields" style="display: none;">
                        <div class="form-group">
                            <label for="codigo_qr">Código QR</label>
                            <textarea class="form-control @error('codigo_qr') is-invalid @enderror" 
                                      id="codigo_qr" name="codigo_qr" rows="3"
                                      placeholder="Ingrese el código QR o la URL">{{ old('codigo_qr') }}</textarea>
                            @error('codigo_qr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Método predeterminado -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('es_predeterminado') is-invalid @enderror" 
                               id="es_predeterminado" name="es_predeterminado" value="1"
                               {{ old('es_predeterminado') ? 'checked' : '' }}>
                        <label class="form-check-label" for="es_predeterminado">Establecer como método predeterminado</label>
                        @error('es_predeterminado')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Método
                    </button>
                    <a href="/pagos" class="btn btn-default float-right">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        .card-primary.card-outline {
            border-top: 3px solid #007bff;
        }
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
        }
        #numero_tarjeta {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20'%3E%3Cpath fill='%239C92AC' fill-opacity='0.4' d='M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar select2
            $('#tipo').select2({
                placeholder: "Seleccione un tipo",
                allowClear: true
            });
            
            // Máscaras para los campos
            $('#numero_tarjeta').inputmask('9999 9999 9999 9999');
            $('#fecha_expiracion').inputmask('99/99');
            $('#cvc').inputmask('999');
            
            // Mostrar/ocultar campos según el tipo seleccionado
            $('#tipo').change(function() {
                const tipo = $(this).val();
                
                // Ocultar todos los campos específicos primero
                $('#tarjetaFields, #qrFields').hide();
                $('#nombre_titular, #numero_tarjeta, #fecha_expiracion, #cvc, #codigo_qr').removeAttr('required');
                
                // Mostrar los campos correspondientes al tipo seleccionado
                if (tipo === 'tarjeta') {
                    $('#tarjetaFields').show();
                    $('#nombre_titular, #numero_tarjeta, #fecha_expiracion, #cvc').attr('required', 'required');
                } else if (tipo === 'qr') {
                    $('#qrFields').show();
                    $('#codigo_qr').attr('required', 'required');
                }
            });
            
            // Disparar el cambio al cargar la página si hay valores antiguos
            if ($('#tipo').val()) {
                $('#tipo').trigger('change');
            }
            
            // Generar datos de prueba para tarjeta
            $('.generate-test-data').click(function() {
                $('#nombre_titular').val('John Doe');
                $('#numero_tarjeta').val('4111 1111 1111 1111');
                $('#fecha_expiracion').val('12/25');
                $('#cvc').val('123');
            });
        });
    </script>
@stop