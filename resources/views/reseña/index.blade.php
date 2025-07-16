@extends('home')

@section('title', 'Administración de Reseñas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card elegant-card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-comments text-primary me-2"></i>Gestión de Reseñas
                        </h3>
                        <div class="card-tools">
                            <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-chevron-left me-1"></i> Volver
                            </a>
                            <a href="/reseñas/crear" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Nueva Reseña
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body px-0 pb-0">
                    @if(count($reseñas) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 8%">ID</th>
                                    <th style="width: 30%">Comentario</th>
                                    <th style="width: 15%">Calificación</th>
                                    <th style="width: 15%">Fecha</th>
                                    <th style="width: 15%">Usuario</th>
                                    <th style="width: 17%" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reseñas as $reseña)
                                <tr>
                                    <td class="fw-bold text-muted">#{{ $reseña->id_reseña }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $reseña->comentario }}">
                                            {{ $reseña->comentario }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $reseña->calificacion)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="badge bg-warning-light text-warning ms-2">
                                                {{ $reseña->calificacion }}/5
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-light text-primary">
                                            {{ \Carbon\Carbon::parse($reseña->fecha)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <span class="initials">{{ substr($reseña->user->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $reseña->user->name ?? 'Usuario' }}</div>
                                                <small class="text-muted">ID: {{ $reseña->user_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="/reseñas/{{ $reseña->id_reseña }}/editar" 
                                               class="btn btn-outline-primary rounded-start"
                                               data-bs-toggle="tooltip"
                                               title="Editar reseña">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            
                                            <button class="btn btn-outline-danger rounded-end"
                                                    onclick="confirmDelete({{ $reseña->id_reseña }})"
                                                    data-bs-toggle="tooltip"
                                                    title="Eliminar reseña">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer bg-white">
                        <div class="text-muted fs-7">
                            Total de reseñas: {{ count($reseñas) }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="empty-state-icon">
                            <i class="fas fa-comments fa-4x text-muted"></i>
                        </div>
                        <h3 class="mt-3 text-muted">No hay reseñas disponibles</h3>
                        <p class="text-muted mb-4">Cuando los usuarios dejen reseñas, aparecerán aquí.</p>
                        <a href="/reseñas/crear" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Crear primera reseña
                        </a>
                    </div>
                    @endif
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

@section('css')
<style>
    .elegant-card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #e1f0ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b7ddd;
        font-weight: bold;
    }
    
    .rating-stars {
        display: flex;
        align-items: center;
    }
    
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
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
    
    .empty-state-icon {
        opacity: 0.6;
        margin-bottom: 1rem;
    }
</style>
@endsection

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
function confirmDelete(reseñaId){
    const modalElement = document.getElementById('confirmModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Configurar el contenido del modal
    document.getElementById('modalTitle').textContent = 'Eliminar reseña';
    document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar esta reseña? Esta acción no se puede deshacer.';
    
    // Limpiar eventos previos del botón Confirmar
    const confirmBtn = document.getElementById('confirmAction');
    confirmBtn.onclick = null;
    
    // Configurar nuevo evento para el botón Confirmar
    confirmBtn.onclick = function() {
        const form = document.getElementById('deleteForm');
        form.action = `/reseñas/${reseñaId}/eliminar`;
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


