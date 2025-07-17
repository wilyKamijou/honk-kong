@extends('home')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-clipboard-list me-2"></i>Lista de Pedidos
                </h2>
                <div>
                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="{{ route('pedidos.create') }}" class="btn btn-sm btn-primary me-2">
                        <i class="fas fa-plus me-1"></i> Crear
                    </a>
                    <a href="{{ route('pedidos.generate-fake') }}" class="btn btn-sm btn-info" 
                       onclick="return confirm('¿Estás seguro de generar 50 pedidos de prueba?')">
                        <i class="fas fa-magic me-1"></i> Generar 50
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
                            <th style="width: 10%">Cliente</th>
                            <th style="width: 10%">Fecha</th>
                            <th style="width: 10%">Total</th>
                            <th style="width: 15%">Estado</th>
                            <th style="width: 20%">Dirección</th>
                            <th style="width: 10%">Método Pago</th>
                            <th style="width: 20%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                        <tr>
                            <td class="fw-bold text-muted">#{{ $pedido->id_pedido }}</td>
                            <td>
                                <div class="fw-semibold">Cliente #{{ $pedido->user_id }}</div>
                                @if($pedido->cliente)
                                <small class="text-muted">{{ $pedido->cliente->name }}</small>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <span class="badge bg-primary-light text-primary">
                                    ${{ number_format($pedido->total, 2) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('pedidos.update', $pedido->id_pedido) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="">
                                    
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" 
                                                class="btn {{ $pedido->estado == 'pendiente' ? 'btn-warning' : 'btn-outline-warning' }}"
                                                onclick="this.form.estado.value='pendiente'; this.form.submit()">
                                            Pendiente
                                        </button>
                                        <button type="button" 
                                                class="btn {{ $pedido->estado == 'procesado' ? 'btn-info' : 'btn-outline-info' }}"
                                                onclick="this.form.estado.value='procesado'; this.form.submit()">
                                            Procesado
                                        </button>
                                        <button type="button" 
                                                class="btn {{ $pedido->estado == 'cancelado' ? 'btn-danger' : 'btn-outline-danger' }}"
                                                onclick="this.form.estado.value='cancelado'; this.form.submit()">
                                            Cancelado
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;">{{ $pedido->direccion_envio }}</div>
                            </td>
<td>
    @if($pedido->metodos_pagos)
    <span class="badge bg-info-light text-info">
        {{ ucfirst($pedido->metodos_pagos->tipo) }}
        @if($pedido->metodos_pagos->alias)
            ({{ $pedido->metodos_pagos->alias }})
        @endif
    </span>
    @else
    <span class="badge bg-secondary-light text-secondary">
        Sin método
    </span>
    @endif
</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('pedidos.edit', $pedido->id_pedido) }}" 
                                       class="btn btn-outline-primary rounded-start"
                                       data-bs-toggle="tooltip"
                                       title="Editar pedido">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <button class="btn btn-outline-danger rounded-end"
                                            onclick="confirmDelete({{ $pedido->id_pedido }})"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar pedido">
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
                    Total de pedidos registrados: {{ count($pedidos) }}
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
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .status-btn-group .btn {
        border-radius: 0;
    }
    
    .status-btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .status-btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
</style>
@endpush
@push('js')
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Función para mostrar el modal de confirmación
    function confirmDelete(pedidoId) {
        const modalElement = document.getElementById('confirmModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        
        // Configurar el contenido del modal
        document.getElementById('modalTitle').textContent = 'Eliminar pedido';
        document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar este pedido? Esta acción no se puede deshacer.';
        
        // Configurar el botón de confirmación
        const confirmBtn = document.getElementById('confirmAction');
        
        // Limpiar eventos previos
        confirmBtn.replaceWith(confirmBtn.cloneNode(true));
        const newConfirmBtn = document.getElementById('confirmAction');
        
        // Asignar nuevo evento
        newConfirmBtn.onclick = function() {
            const form = document.getElementById('deleteForm');
            form.action = `/pedidos/${pedidoId}/eliminar`;
            form.submit();
        };
        
        // Mostrar el modal
        modal.show();
    }

    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush