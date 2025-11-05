@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="main-content p-4">
        <!-- ENCABEZADO -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-file-invoice me-2" style="color: #3b82f6;"></i>Cuentas de Cobro
                        </h2>
                        <p class="text-muted mb-0" style="font-size: 0.95rem;">
                            <i class="fas fa-list-ul me-1"></i>Gestiona y visualiza todas tus cuentas
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                        </a>
                        @if(!auth()->user()->hasRole('ordenador_gasto'))
                        <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Cuenta
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTA DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- CARD PRINCIPAL -->
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2" style="color: #3b82f6;"></i>Listado de Cuentas
                    </h5>
                    <span class="badge" style="background: linear-gradient(135deg, #3b82f6, #1e3a8a); font-size: 0.9rem;">
                        {{ $cuentas->count() }} {{ $cuentas->count() === 1 ? 'cuenta' : 'cuentas' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                @if($cuentas->isEmpty())
                    <!-- ESTADO VACÍO -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-inbox fa-4x" style="color: #64748b; opacity: 0.5;"></i>
                        </div>
                        <h4 style="color: #94a3b8; font-weight: 600;">No hay cuentas de cobro</h4>
                        <p class="text-muted mb-4">Comienza creando tu primera cuenta de cobro</p>
                        @if(!auth()->user()->hasRole('ordenador_gasto'))
                        <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Crear Primera Cuenta
                        </a>
                        @endif
                    </div>
                @else
                    <!-- TABLA DE CUENTAS -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="color: #94a3b8;width: 60px;">
                                        <i class="fas fa-hashtag me-1"></i>ID
                                    </th>
                                    <th style="color: #94a3b8;">
                                        <i class="fas fa-user-tie me-1"></i>Cobrador
                                    </th>
                                    <th style="color: #94a3b8;">
                                        <i class="fas fa-user me-1"></i>Cliente
                                    </th>
                                    <th style="color: #94a3b8;width: 130px;">
                                        <i class="fas fa-dollar-sign me-1"></i>Monto
                                    </th>
                                    <th style="color: #94a3b8;width: 130px;">
                                        <i class="fas fa-calendar me-1"></i>Fecha
                                    </th>
                                    <th style="color: #94a3b8;width: 120px;" class="text-center">
                                        <i class="fas fa-info-circle me-1"></i>Estado
                                    </th>
                                    <th style="color: #94a3b8;width: 160px;" class="text-center">
                                        <i class="fas fa-cog me-1"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cuentas as $cuenta)
                                    <tr>
                                        <td>
                                            <span class="badge" style="background: #334155; font-size: 0.85rem;">
                                                #{{ $cuenta->id }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: #e2e8f0;">{{ $cuenta->nombre_cobrador }}</strong>
                                        </td>
                                        <td>
                                            <span style="color: #cbd5e1;">{{ $cuenta->nombre_cliente }}</span>
                                        </td>
                                        <td>
                                            <strong style="color: #3b82f6; font-size: 1.05rem;">
                                                ${{ number_format($cuenta->monto, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span style="color: #94a3b8;">
                                                <i class="fas fa-calendar-day me-1" style="font-size: 0.85rem;"></i>
                                                {{ $cuenta->fecha_emision ? date('d/m/Y', strtotime($cuenta->fecha_emision)) : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $cuenta->estado === 'pendiente' ? 'warning' : ($cuenta->estado === 'pagada' ? 'success' : ($cuenta->estado === 'pendiente_ordenador' ? 'info' : 'secondary')) }}">
                                                <i class="fas {{ $cuenta->estado === 'pagada' ? 'fa-check-circle' : ($cuenta->estado === 'pendiente' ? 'fa-clock' : ($cuenta->estado === 'pendiente_ordenador' ? 'fa-user-check' : 'fa-times-circle')) }} me-1"></i>
                                                {{ ucfirst($cuenta->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if(auth()->user()->hasRole('ordenador_gasto'))
                                                    <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" 
                                                        class="btn btn-sm btn-info" 
                                                        title="Ver detalles"
                                                        data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($cuenta->estado === 'pendiente_ordenador')
                                                        <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Aprobar">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="text" name="observaciones" placeholder="Motivo del rechazo" required class="form-control d-inline" style="width:120px;">
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Rechazar">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" 
                                                        class="btn btn-sm btn-info" 
                                                        title="Ver detalles"
                                                        data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    {{-- Aquí podrías agregar Editar/Eliminar para otros roles si lo necesitas --}}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGINACIÓN -->
                    @if(method_exists($cuentas, 'links'))
                        <div class="mt-4">
                            {{ $cuentas->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/listado.css') }}">
@endpush

@push('scripts')
<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
