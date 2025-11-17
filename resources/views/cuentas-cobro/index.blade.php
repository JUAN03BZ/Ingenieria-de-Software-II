@extends('layouts.app')

@section('title', 'Cuentas de Cobro')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/indexcuentas.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- Encabezado --}}
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Cuentas de Cobro
                        </h2>
                        <div>
                            {{-- Botón volver al menú --}}
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i>Menú
                            </a>

                            {{-- Botón pendientes (solo si no es contratista) --}}
                            @if(auth()->user()->hasAnyRole(['ordenador_gasto','supervisor','tesoreria','alcalde']))
                                <a href="{{ route('cuenta.cobro.pendientes') }}" class="btn btn-warning me-2">
                                    <i class="fas fa-clock me-1"></i>Cuentas Pendientes
                                </a>
                            @endif

                            {{-- Crear solo si policy lo permite (contratista, alcalde) --}}
                            @can('create', App\Models\CuentaCobro::class)
                                <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i>Crear Nueva Cuenta de Cobro
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- Cuerpo de la card --}}
                <div class="card-body">
                    {{-- Mensaje de éxito --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Tabla o mensaje vacío --}}
                    @if($cuentas->isEmpty())
                        <p class="text-center">
                            <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.6;"></i><br>
                            No hay cuentas de cobro registradas.
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                        <th><i class="fas fa-calendar me-2"></i>Fecha emisión</th>
                                        <th><i class="fas fa-user-tie me-2"></i>Cobrador</th>
                                        <th><i class="fas fa-building me-2"></i>Cliente</th>
                                        <th><i class="fas fa-dollar-sign me-2"></i>Monto</th>
                                        <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                                        <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cuentas as $cuenta)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $cuenta->id }}</span>
                                            </td>
                                            <td>{{ optional($cuenta->fecha_emision)->format('d/m/Y') ?? $cuenta->fecha_emision }}</td>
                                            <td>{{ $cuenta->nombre_cobrador }}</td>
                                            <td>{{ $cuenta->nombre_cliente }}</td>
                                            <td>
                                                <strong>${{ number_format($cuenta->monto, 2, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $estadoClass = match($cuenta->estado) {
                                                        'pendiente' => 'warning',
                                                        'pagada' => 'success',
                                                        'aprobada' => 'primary',
                                                        'rechazada' => 'danger',
                                                        'revision' => 'info',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $estadoClass }}">
                                                    {{ ucfirst($cuenta->estado) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    {{-- Ver --}}
                                                    @can('view', $cuenta)
                                                        <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" 
                                                           class="btn btn-sm btn-info"
                                                           title="Ver detalles">
                                                            <i class="fas fa-eye me-1"></i>Ver
                                                        </a>
                                                    @endcan

                                                    {{-- Editar --}}
                                                    @can('update', $cuenta)
                                                        <a href="{{ route('cuenta.cobro.edit', $cuenta->id) }}" 
                                                           class="btn btn-sm btn-warning"
                                                           title="Editar cuenta">
                                                            <i class="fas fa-edit me-1"></i>Editar
                                                        </a>
                                                    @endcan

                                                    {{-- Eliminar --}}
                                                    @can('delete', $cuenta)
                                                        <form action="{{ route('cuenta.cobro.destroy', $cuenta->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('¿Está seguro de eliminar esta cuenta de cobro?');">
                                                            @csrf 
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-danger"
                                                                    title="Eliminar cuenta">
                                                                <i class="fas fa-trash me-1"></i>Eliminar
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    {{-- Acciones de supervisión --}}
                                                    @if(
                                                        auth()->user()->hasRole('supervisor') &&
                                                        $cuenta->fase === 'supervisor' &&
                                                        $cuenta->estado === 'pendiente'
                                                    )
                                                        <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" 
                                                              method="POST" 
                                                              class="d-inline ms-1"
                                                              onsubmit="return confirm('¿Aprobar esta cuenta de cobro?');">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-success btn-sm"
                                                                    title="Aprobar cuenta">
                                                                <i class="fas fa-check me-1"></i>Aprobar
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" 
                                                              method="POST" 
                                                              class="d-inline ms-1">
                                                            @csrf
                                                            <input type="text" 
                                                                   name="observaciones" 
                                                                   placeholder="Motivo del rechazo" 
                                                                   required
                                                                   class="form-control d-inline" 
                                                                   style="width:160px;">
                                                            <button type="submit" 
                                                                    class="btn btn-danger btn-sm ms-1"
                                                                    title="Rechazar cuenta">
                                                                <i class="fas fa-times me-1"></i>Rechazar
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Acciones de alcaldía --}}
                                                    @if(auth()->user()->isAlcalde() && in_array($cuenta->estado, ['pendiente','revision','aprobada']))
                                                        <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" 
                                                              method="POST" 
                                                              class="d-inline ms-1"
                                                              onsubmit="return confirm('¿Aprobar esta cuenta de cobro?');">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-success btn-sm"
                                                                    title="Aprobar cuenta">
                                                                <i class="fas fa-check-circle me-1"></i>Aprobar
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" 
                                                              method="POST" 
                                                              class="d-inline ms-1">
                                                            @csrf
                                                            <input type="text" 
                                                                   name="observaciones" 
                                                                   placeholder="Motivo del rechazo" 
                                                                   required
                                                                   class="form-control d-inline" 
                                                                   style="width:160px;">
                                                            <button type="submit" 
                                                                    class="btn btn-danger btn-sm ms-1"
                                                                    title="Rechazar cuenta">
                                                                <i class="fas fa-times-circle me-1"></i>Rechazar
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginación --}}
                        @if($cuentas->hasPages())
                            <div class="mt-3">
                                {{ $cuentas->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush