@extends('home')

@section('contenido')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-tags me-2"></i>Gestión de Descuentos
                </h2>
                <div>
                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="/descuentos/crear" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Descuento
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8%">ID</th>
                            <th style="width: 15%">Código</th>
                            <th style="width: 22%">Descripción</th>
                            <th style="width: 12%">Descuento</th>
                            <th style="width: 18%">Inicio</th>
                            <th style="width: 18%">Fin</th>
                            <th style="width: 7%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($descuentos as $descuento)
                        <tr class="{{ $descuento->fecha_fin < now() ? 'bg-light-warning' : ($descuento->fecha_inicio > now() ? 'bg-light-info' : '') }}">
                            <td class="fw-bold text-muted">#{{ $descuento->id_descuento }}</td>
                            <td>
                                <span class="badge bg-primary-light text-primary font-monospace">
                                    {{ $descuento->codigo }}
                                </span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;" title="{{ $descuento->descripcion }}">
                                    {{ $descuento->descripcion }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-danger-light text-danger">
                                    {{ $descuento->porcentaje }}% OFF
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $descuento->fecha_inicio > now() ? 'bg-info-light text-info' : 'bg-success-light text-success' }}">
                                    {{ \Carbon\Carbon::parse($descuento->fecha_inicio)->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $descuento->fecha_fin < now() ? 'bg-secondary-light text-secondary' : 'bg-success-light text-success' }}">
                                    {{ \Carbon\Carbon::parse($descuento->fecha_fin)->format('d M Y') }}
                                    @if($descuento->fecha_fin < now())
                                    <i class="fas fa-clock ms-1" title="Descuento expirado"></i>
                                    @elseif($descuento->fecha_inicio > now())
                                    <i class="fas fa-calendar-check ms-1" title="Descuento programado"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/descuentos/{{ $descuento->id_descuento }}/editar" 
                                       class="btn btn-outline-primary rounded-start"
                                       data-bs-toggle="tooltip"
                                       title="Editar descuento">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <button class="btn btn-outline-danger rounded-end"
                                            onclick="confirmDelete({{ $descuento->id_descuento }})"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar descuento">
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
                    Total de descuentos: {{ count($descuentos) }}
                    <span class="badge bg-success-light text-success ms-2">
                        Activos: {{ $descuentos->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now())->count() }}
                    </span>
                    <span class="badge bg-info-light text-info ms-2">
                        Programados: {{ $descuentos->where('fecha_inicio', '>', now())->count() }}
                    </span>
                    <span class="badge bg-secondary-light text-secondary ms-2">
                        Expirados: {{ $descuentos->where('fecha_fin', '<', now())->count() }}
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
    
    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.05);
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.05);
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
    
    .font-monospace {
        font-family: monospace;
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

function confirmDelete(descuentoId) {
    const modalElement = document.getElementById('confirmModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Configurar el contenido del modal
    document.getElementById('modalTitle').textContent = 'Eliminar descuento';
    document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar el descuento? Esta acción no se puede deshacer.';
    
    // Limpiar eventos previos del botón Confirmar
    const confirmBtn = document.getElementById('confirmAction');
    confirmBtn.onclick = null;
    
    // Configurar nuevo evento para el botón Confirmar
    confirmBtn.onclick = function() {
        const form = document.getElementById('deleteForm');
        form.action = `/descuento/${descuentoId}/eliminar`;
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