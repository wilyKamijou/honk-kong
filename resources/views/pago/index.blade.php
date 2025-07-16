@extends('home')

@section('contenido')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-credit-card text-primary me-2"></i>Mis Métodos de Pago
                </h3>
                <div>
                    <a href="/home" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-chevron-left me-1"></i> Volver
                    </a>
                    <a href="/pagos/crear" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Método
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%">Método</th>
                            <th style="width: 30%">Detalles</th>
                            <th style="width: 20%">Tipo</th>
                            <th style="width: 15%">Estado</th>
                            <th style="width: 10%" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($metodos as $metodo)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="payment-icon me-3">
                                        @if($metodo->tipo === 'tarjeta')
                                            <i class="fas fa-credit-card text-primary"></i>
                                        @elseif($metodo->tipo === 'qr')
                                            <i class="fas fa-qrcode text-info"></i>
                                        @elseif($metodo->tipo === 'efectivo')
                                            <i class="fas fa-money-bill-wave text-success"></i>
                                        @else
                                            <i class="fas fa-exchange-alt text-secondary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $metodo->alias ?? ucfirst($metodo->tipo) }}</div>
                                        <small class="text-muted">Creado: {{ $metodo->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($metodo->tipo === 'tarjeta')
                                    <div>**** **** **** {{ $metodo->ultimos_digitos }}</div>
                                    <small class="text-muted">{{ $metodo->nombre_titular }} - {{ $metodo->marca }}</small>
                                @elseif($metodo->tipo === 'qr')
                                    <div class="text-truncate" style="max-width: 200px;">{{ $metodo->codigo_qr }}</div>
                                    <small class="text-muted">Código QR</small>
                                @else
                                    <div>Pago en {{ ucfirst($metodo->tipo) }}</div>
                                    <small class="text-muted">{{ $metodo->alias ?? 'Sin alias' }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($metodo->tipo === 'tarjeta') bg-primary-light text-primary
                                    @elseif($metodo->tipo === 'qr') bg-info-light text-info
                                    @elseif($metodo->tipo === 'efectivo') bg-success-light text-success
                                    @else bg-secondary-light text-secondary @endif">
                                    {{ ucfirst($metodo->tipo) }}
                                </span>
                            </td>
                            <td>
                                @if($metodo->es_predeterminado)
                                    <span class="badge bg-success-light text-success">
                                        <i class="fas fa-check-circle me-1"></i> Predeterminado
                                    </span>
                                @else
                                    <span class="badge bg-secondary-light text-secondary">
                                        <i class="fas fa-clock me-1"></i> Alternativo
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(!$metodo->es_predeterminado)
                                        <form action="{{ route('metodos-pago.set-default', $metodo->id_pago) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary rounded-start"
                                                    data-bs-toggle="tooltip"
                                                    title="Establecer como predeterminado">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button class="btn btn-outline-danger rounded-end"
                                            onclick="confirmDelete({{ $metodo->id_pago }})"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar método">
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
                    Total de métodos registrados: {{ count($metodos) }}
                    <span class="badge bg-success-light text-success ms-2">
                        Predeterminados: {{ $metodos->where('es_predeterminado', true)->count() }}
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
    
    .payment-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .bg-primary-light {
        background-color: rgba(59, 125, 221, 0.1);
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
        background-color: rgba(23, 162, 184, 0.1);
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

    function confirmDelete(pagoId) {
        const modalElement = document.getElementById('confirmModal');
        const modal = new bootstrap.Modal(modalElement);
        
        // Configurar el contenido del modal
        document.getElementById('modalTitle').textContent = 'Eliminar método de pago';
        document.getElementById('modalBody').textContent = '¿Estás seguro de eliminar este método de pago? Esta acción no se puede deshacer.';
        
        // Limpiar eventos previos del botón Confirmar
        const confirmBtn = document.getElementById('confirmAction');
        confirmBtn.onclick = null;
        
        // Configurar nuevo evento para el botón Confirmar
        confirmBtn.onclick = function() {
            const form = document.getElementById('deleteForm');
            form.action = `/metodos-pago/${pagoId}`;
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