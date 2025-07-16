@extends('home')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-boxes me-2"></i>Catálogo de Productos
                </h2>
                <div>
                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="/producto/crear" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 20%">Producto</th>
                            <th style="width: 20%">Descripción</th>
                            <th style="width: 10%">Precio</th>
                            <th style="width: 15%">Imagen</th>
                            <th style="width: 10%">Promoción</th>
                            <th style="width: 10%">Categoría</th>
                            <th style="width: 10%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr>
                            <td class="fw-bold text-muted">#{{ $producto->id_producto }}</td>
                            <td>
                                <div class="fw-semibold">{{ $producto->nombre }}</div>
                                <small class="text-muted">ID: {{ $producto->id_producto }}</small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;">{{ $producto->descripcion }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary-light text-primary">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($producto->imagen_url)
                                <a href="{{ $producto->imagen_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    Ver Imagen
                                </a>
                                @else
                                <span class="badge bg-secondary-light text-secondary">
                                    Sin imagen
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($producto->descuento > 0)
                                <span class="badge bg-success-light text-success">
                                    {{ $producto->descuento }}% OFF
                                </span>
                                @else
                                <span class="badge bg-secondary-light text-secondary">
                                    Sin promo
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info-light text-info">
                                    {{ $producto->id_categoria }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/producto/{{ $producto->id_producto }}/editar" 
                                       class="btn btn-outline-primary rounded-start"
                                       data-bs-toggle="tooltip"
                                       title="Editar producto">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <button class="btn btn-outline-danger rounded-end"
                                            onclick="confirmDelete({{ $producto->id_producto }})"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar producto">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white text-center py-3">
                <div class="text-muted">
                    Total de productos registrados: {{ count($productos) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Confirmar acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                ¿Estás seguro de realizar esta acción?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('css')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-secondary-light {
        background-color: rgba(108, 117, 125, 0.1);
    }
    
    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('js')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Función para mostrar el modal de confirmación
function confirmDelete(productId) {
    const modalElement = document.getElementById('confirmModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Configurar el contenido del modal
    document.getElementById('modalTitle').textContent = 'Eliminar producto';
    document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.';
    
    // Limpiar eventos previos del botón Confirmar
    const confirmBtn = document.getElementById('confirmAction');
    confirmBtn.onclick = null;
    
    // Configurar nuevo evento para el botón Confirmar
    confirmBtn.onclick = function() {
        const form = document.getElementById('deleteForm');
        form.action = `/producto/${productId}/eliminar`;
        form.submit();
        modal.hide();
    };
    
    // Configurar evento para el botón Cancelar
    const cancelBtn = modalElement.querySelector('.btn-secondary');
    cancelBtn.onclick = function() {
        modal.hide();
    };
    
    // Mostrar el modal
    modal.show();
}
</script>
@endpush