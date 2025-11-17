@extends('layouts.app')

@section('title', 'Cuentas de cobro pendientes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
<div class="container">
    {{-- Encabezado --}}
    <h2>
        <i class="fas fa-file-invoice-dollar"></i>
        Cuentas de cobro pendientes y en revisión
    </h2>

    {{-- Mensajes de éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Mensajes de error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabla de cuentas --}}
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col"><i class="fas fa-hashtag me-2"></i>ID</th>
                    <th scope="col"><i class="fas fa-user me-2"></i>Cliente</th>
                    <th scope="col"><i class="fas fa-dollar-sign me-2"></i>Monto</th>
                    <th scope="col"><i class="fas fa-info-circle me-2"></i>Estado</th>
                    <th scope="col"><i class="fas fa-cogs me-2"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cuentas as $cuenta)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">
                                <i class="fas fa-hashtag me-1"></i>#{{ $cuenta->id }}
                            </span>
                        </td>
                        <td>
                            <i class="fas fa-user-circle text-info me-2"></i>
                            <strong>{{ $cuenta->nombre_cliente }}</strong>
                        </td>
                        <td>
                            <strong>${{ number_format($cuenta->monto, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            @php
                                $estadoConfig = [
                                    'pendiente' => ['class' => 'warning', 'icon' => 'clock'],
                                    'revision'  => ['class' => 'info', 'icon' => 'search'],
                                    'aprobada'  => ['class' => 'success', 'icon' => 'check-circle'],
                                    'rechazada' => ['class' => 'danger', 'icon' => 'times-circle'],
                                ];
                                
                                $config = $estadoConfig[$cuenta->estado] ?? ['class' => 'secondary', 'icon' => 'question'];
                            @endphp
                            
                            <span class="badge bg-{{ $config['class'] }}">
                                <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                {{ ucfirst($cuenta->estado) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                {{-- Botón Ver --}}
                                <a href="{{ route('cuenta.cobro.show', $cuenta) }}" 
                                   class="btn btn-sm btn-outline-info"
                                   title="Ver detalles">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>

                                {{-- Cambio de estado SOLO para alcalde --}}
                                @if(auth()->user()->isAlcalde())
                                    <form action="{{ route('cuenta.cobro.cambiar.estado', $cuenta) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de cambiar el estado de esta cuenta?')">
                                        @csrf
                                        <div class="d-flex align-items-center gap-2">
                                            <select name="estado" 
                                                    class="form-select form-select-sm" 
                                                    required
                                                    title="Seleccionar nuevo estado">
                                                <option value="pendiente" @selected($cuenta->estado === 'pendiente')>
                                                    Pendiente
                                                </option>
                                                <option value="revision" @selected($cuenta->estado === 'revision')>
                                                    Revisión
                                                </option>
                                                <option value="aprobada" @selected($cuenta->estado === 'aprobada')>
                                                    Aprobada
                                                </option>
                                                <option value="rechazada" @selected($cuenta->estado === 'rechazada')>
                                                    Rechazada
                                                </option>
                                            </select>
                                            <button class="btn btn-primary btn-sm" 
                                                    type="submit"
                                                    title="Actualizar estado">
                                                <i class="fas fa-sync-alt me-1"></i>Actualizar
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No hay cuentas pendientes ni en revisión.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if($cuentas instanceof \Illuminate\Pagination\LengthAwarePaginator && $cuentas->hasPages())
            <div class="mt-3">
                {{ $cuentas->links() }}
            </div>
        @endif
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

    // Confirmación visual al cambiar estado
    const forms = document.querySelectorAll('form[action*="cambiar.estado"]');
    forms.forEach(form => {
        const select = form.querySelector('select[name="estado"]');
        if (select) {
            select.addEventListener('change', function() {
                this.style.borderColor = '#3b82f6';
                this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
            });
        }
    });
});
</script>
@endpush