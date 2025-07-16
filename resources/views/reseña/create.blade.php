@extends('home')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-star me-2"></i>Nueva Reseña
                </h2>
                <div>
                    <a href="/reseñas" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-chevron-left me-1"></i> Volver a reseñas
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <form action=" /reseñas/guardar " method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <!-- Comentario -->
                        <div class="mb-3">
                            <label for="comentario" class="form-label fw-semibold">
                                <i class="fas fa-comment me-1 text-muted"></i>Comentario
                            </label>
                            <textarea id="comentario" name="comentario" class="form-control" 
                                      rows="5" placeholder="Escribe tu experiencia con el producto" required></textarea>
                            <div class="invalid-feedback">
                                Por favor ingresa un comentario sobre el producto.
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <!-- Calificación -->
                        <div class="mb-3">
                            <label for="calificacion" class="form-label fw-semibold">
                                <i class="fas fa-star me-1 text-muted"></i>Calificación
                            </label>
                            <div class="rating-input">
                                <div class="d-flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star fa-2x rating-star" data-value="{{ $i }}" style="cursor: pointer; color: #ddd;"></i>
                                    @endfor
                                    <input type="hidden" id="calificacion" name="calificacion" value="0" required>
                                </div>
                                <small class="text-muted">Selecciona de 1 a 5 estrellas</small>
                                <div class="invalid-feedback">
                                    Por favor selecciona una calificación.
                                </div>
                            </div>
                        </div>

                        <!-- Usuario -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-muted"></i>Usuario
                            </label>
                            <select id="user_id" name="user_id" class="form-select" required>
                                <option value="" disabled selected>Selecciona un usuario</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona un usuario.
                            </div>
                        </div>

                        <!-- Producto -->
                        <div class="mb-3">
                            <label for="producto_id" class="form-label fw-semibold">
                                <i class="fas fa-box-open me-1 text-muted"></i>Producto
                            </label>
                            <select id="producto_id" name="producto_id" class="form-select" required>
                                <option value="" disabled selected>Selecciona un producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona un producto.
                            </div>
                        </div>

                <!-- Botones de acción -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Reseña
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
    
    .rating-star {
        margin-right: 5px;
        transition: color 0.2s;
    }
    
    .rating-star:hover,
    .rating-star.active {
        color: #ffc107 !important;
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
</style>
@endpush

@push('js')
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

// Sistema de calificación con estrellas
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const value = parseInt(this.getAttribute('data-value'));
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('calificacion');
        
        stars.forEach((s, index) => {
            if (index < value) {
                s.classList.add('active');
                s.style.color = '#ffc107';
            } else {
                s.classList.remove('active');
                s.style.color = '#ddd';
            }
        });
        
        ratingInput.value = value;
    });
});

// Mostrar toast de notificación
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

// Establecer fecha actual por defecto
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = today;
});
</script>
@endpush