@extends('home')

@section('contenido')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-credit-card text-primary me-2"></i>Todos los Métodos de Pago
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
            @if($metodos->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No hay métodos de pago registrados.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">Método</th>
                                <th style="width: 25%">Detalles</th>
                                <th style="width: 15%">Usuario</th>
                                <th style="width: 15%">Pedido</th>
                                <th style="width: 10%">Tipo</th>
                                <th style="width: 10%">Estado</th>
                                <th style="width: 5%" class="text-end">Acciones</th>
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
    @if($metodo->users)
    <div class="d-flex align-items-center">
        <div class="avatar-sm me-2">
            <span class="avatar-title rounded-circle bg-primary text-white">
                {{ substr($metodo->users->name, 0, 1) }}
            </span>
        </div>
        <div>
            <div class="fw-semibold">{{ $metodo->users->name }}</div>
            <small class="text-muted">{{ $metodo->users->email }}</small>
        </div>
    </div>
    @else
        <span class="text-muted">Usuario no encontrado</span>
    @endif
</td>
                                <td>
    @if($metodo->pedidos)
        <div class="d-flex flex-column">
            <span class="badge bg-light text-dark mb-1">
                <i class="fas fa-shopping-bag me-1"></i>
                Pedido #{{ $metodo->pedidos->id_pedido }}
            </span>
            <div class="text-muted small">
                @if($metodo->pedidos->fecha instanceof \Carbon\Carbon)
                    {{ $metodo->pedidos->fecha->format('d/m/Y') }}
                @else
                    {{ date('d/m/Y', strtotime($metodo->pedidos->fecha)) }}
                @endif
            </div>
            <div class="small text-primary">
                {{ number_format($metodo->pedidos->total, 2) }} €
            </div>
        </div>
    @else
        <span class="text-muted">Sin pedido asociado</span>
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
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $metodos->links() }}
                </div>
                
                <div class="card-footer bg-white text-center py-3">
                    <div class="text-muted">
                        Total de métodos registrados: {{ $metodos->total() }}
                        <span class="badge bg-success-light text-success ms-2">
                            Predeterminados: {{ $metodos->where('es_predeterminado', true)->count() }}
                        </span>
                    </div>
                </div>
            @endif
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