@extends('home')

@section('contenido')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-utensils me-2"></i>Administración de Categorías
                </h2>
                <div>
                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="/categorias/crear" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nueva Categoría
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%">ID</th>
                            <th style="width: 30%">Nombre</th>
                            <th style="width: 40%">Descripción</th>
                            <th style="width: 20%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr>
                            <td class="fw-bold text-muted">#{{ $categoria->id_categoria }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if(str_contains(strtolower($categoria->nombre), 'pizza'))
                                        <i class="fas fa-pizza-slice me-2 text-danger"></i>
                                    @elseif(str_contains(strtolower($categoria->nombre), 'sushi'))
                                        <i class="fas fa-fish me-2 text-primary"></i>
                                    @else
                                        <i class="fas fa-utensils me-2 text-warning"></i>
                                    @endif
                                    <span class="fw-semibold">{{ $categoria->nombre }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;">{{ $categoria->descripcion }}</div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/categorias/{{ $categoria->id_categoria }}/editar" 
                                       class="btn btn-outline-primary rounded-start"
                                       data-bs-toggle="tooltip"
                                       title="Editar categoría">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <button class="btn btn-outline-danger rounded-end"
                                            onclick="confirmDelete({{ $categoria->id_categoria }}, 'categorias')"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar categoría">
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
                    Total de categorías registradas: {{ count($categorias) }}
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
                <button type="button" class="btn btn-danger" id="confirmAction">Confirmar</button>
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
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
    
    /* Estilos para iconos de categorías */
    .fa-pizza-slice {
        color: #dc3545;
    }
    .fa-fish {
        color: #0d6efd;
    }
    .fa-utensils {
        color: #fd7e14;
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

    // Función para eliminar (corregida)
   
function confirmDelete(itemId, itemType) {
    const modalElement = document.getElementById('confirmModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Configurar el contenido del modal
    document.getElementById('modalTitle').textContent = 'Eliminar categoría';
    document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar este categoria? Esta acción no se puede deshacer.';
    
    // Limpiar eventos previos del botón Confirmar
    const confirmBtn = document.getElementById('confirmAction');
    confirmBtn.onclick = null;
    
    // Configurar nuevo evento para el botón Confirmar
    confirmBtn.onclick = function() {
        const form = document.getElementById('deleteForm');
        form.action = `/categoria/${productId}/eliminar`;
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