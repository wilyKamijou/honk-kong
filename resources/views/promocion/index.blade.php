@extends('home')

@section('contenido')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-percentage me-2"></i>Gestión de Promociones
                </h2>
                <div>
                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="/promociones/crear" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nueva Promoción
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
                            <th style="width: 25%">Promoción</th>
                            <th style="width: 15%">Descuento</th>
                            <th style="width: 20%">Fecha Inicio</th>
                            <th style="width: 20%">Fecha Fin</th>
                            <th style="width: 10%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promociones as $promocion)
                        <tr class="{{ $promocion->fecha_fin < now() ? 'bg-light-warning' : '' }}">
                            <td class="fw-bold text-muted">#{{ $promocion->id_promocion }}</td>
                            <td>
                                <div class="fw-semibold">{{ $promocion->nombre }}</div>
                                <small class="text-muted">ID: {{ $promocion->id_promocion }}</small>
                            </td>
                            <td>
                                <span class="badge bg-danger-light text-danger">
                                    {{ $promocion->valor }}% OFF
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary-light text-primary">
                                    {{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $promocion->fecha_fin < now() ? 'bg-secondary-light text-secondary' : 'bg-success-light text-success' }}">
                                    {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d M Y') }}
                                    @if($promocion->fecha_fin < now())
                                    <i class="fas fa-clock ms-1" title="Promoción expirada"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/promociones/{{ $promocion->id_promocion }}/editar" 
                                       class="btn btn-outline-primary rounded-start"
                                       data-bs-toggle="tooltip"
                                       title="Editar promoción">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <button class="btn btn-outline-danger rounded-end"
                                            onclick="confirmDelete({{ $promocion->id_promocion }})"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar promoción">
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
                    Total de promociones: {{ count($promociones) }}
                    <span class="badge bg-success-light text-success ms-2">
                        Activas: {{ $promociones->where('fecha_fin', '>=', now())->count() }}
                    </span>
                    <span class="badge bg-secondary-light text-secondary ms-2">
                        Expiradas: {{ $promociones->where('fecha_fin', '<', now())->count() }}
                    </span>
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
    
    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.05);
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

    function confirmDelete(promocionId) {
        const modalElement = document.getElementById('confirmModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        
        // Configurar el contenido del modal
        document.getElementById('modalTitle').textContent = 'Eliminar promoción';
        document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar esta promoción? Esta acción no se puede deshacer.';
        
        // Limpiar eventos previos del botón Confirmar
        const confirmBtn = document.getElementById('confirmAction');
        confirmBtn.onclick = null;
        
        // Configurar nuevo evento para el botón Confirmar
        confirmBtn.onclick = function() {
            const form = document.getElementById('deleteForm');
            form.action = `/promociones/${promocionId}/eliminar`;
            form.submit();
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