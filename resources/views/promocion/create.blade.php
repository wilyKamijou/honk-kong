@extends('home')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-percentage me-2"></i>Nueva Promoción
                </h2>
                <div>
                    <a href="/promociones" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-chevron-left me-1"></i> Volver a promociones
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <form action="/promociones/guardar" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">
                                <i class="fas fa-tag me-1 text-muted"></i>Nombre de la promoción
                            </label>
                            <input type="text" id="nombre" name="nombre" class="form-control" 
                                   placeholder="Ej: Verano 20% OFF" required>
                            <div class="invalid-feedback">
                                Por favor ingresa el nombre de la promoción.
                            </div>
                        </div>

                        <!-- Valor -->
                        <div class="mb-3">
                            <label for="valor" class="form-label fw-semibold">
                                <i class="fas fa-dollar-sign me-1 text-muted"></i>Valor de descuento
                            </label>
                            <div class="input-group">
                                <input type="number" id="valor" name="valor" class="form-control" 
                                       placeholder="Ej: 20" min="1" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Ingresa un valor entre 1 y 100</small>
                            <div class="invalid-feedback">
                                El valor debe estar entre 1% y 100%.
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <!-- Fecha de inicio -->
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-1 text-muted"></i>Fecha de inicio
                            </label>
                            <div class="input-group date" id="datepickerInicio">
                                <input type="text" id="fecha_inicio" name="fecha_inicio" 
                                       class="form-control" placeholder="Seleccione fecha" required>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback">
                                Por favor selecciona una fecha de inicio válida.
                            </div>
                        </div>

                        <!-- Fecha de fin -->
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label fw-semibold">
                                <i class="fas fa-calendar-times me-1 text-muted"></i>Fecha de finalización
                            </label>
                            <div class="input-group date" id="datepickerFin">
                                <input type="text" id="fecha_fin" name="fecha_fin" 
                                       class="form-control" placeholder="Seleccione fecha" required>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback">
                                Por favor selecciona una fecha de finalización válida.
                            </div>
                        </div>

                        <!-- Estado (opcional) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-1 text-muted"></i>Estado inicial
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="estado" id="estado_activo" value="activo" checked>
                                <label class="btn btn-outline-success" for="estado_activo">
                                    <i class="fas fa-check-circle me-1"></i> Activar
                                </label>
                                
                                <input type="radio" class="btn-check" name="estado" id="estado_inactivo" value="inactivo">
                                <label class="btn btn-outline-secondary" for="estado_inactivo">
                                    <i class="fas fa-times-circle me-1"></i> Inactivo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Promoción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
    }
    
    .was-validated .form-control:invalid, 
    .was-validated .form-select:invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .was-validated .form-control:valid, 
    .was-validated .form-select:valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(0,0,0,.1);
        border-radius: 50%;
        border-top-color: #0d6efd;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Estilo para el datepicker */
    .datepicker {
        z-index: 10000 !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>

<script>
// Validación del formulario
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})();

// Datepicker para fechas
// Datepicker para fechas
// Datepicker para fechas
$(document).ready(function(){
    $('#datepickerInicio').datepicker({
        format: 'yyyy-mm-dd',  // Formato que coincide con el requerido
        language: 'es',
        autoclose: true,
        todayHighlight: true,
        startDate: new Date()
    });
    
    $('#datepickerFin').datepicker({
        format: 'yyyy-mm-dd',  // Formato que coincide con el requerido
        language: 'es',
        autoclose: true,
        todayHighlight: true,
        startDate: new Date()
    });
    
    // Validar que fecha fin sea mayor que fecha inicio
    $('#datepickerInicio').on('changeDate', function(e) {
        $('#datepickerFin').datepicker('setStartDate', e.date);
    });
});
// Mostrar notificación
function showToast(type, title, message) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.style.zIndex = '11';
    
    let headerClass = type === 'success' ? 'bg-success text-white' : 
                     type === 'error' ? 'bg-danger text-white' : 'bg-primary text-white';
    
    toast.innerHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${headerClass}">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// La validación se simplifica ya que no hay conversión de formatos
// La validación se simplifica ya que no hay conversión de formatos
document.querySelector('form').addEventListener('submit', function(e) {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    
    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        if (inicio > fin) {
            e.preventDefault();
            showToast('error', 'Error', 'La fecha de fin debe ser posterior a la fecha de inicio');
            document.getElementById('fecha_fin').focus();
        }
    }
});

</script>
@endpush