@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Cuentas de Cobro</h2>
                        <div>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Menú
                            </a>

                            {{-- Botón pendientes (muestra si el rol participa en revisión) --}}
                            @if(auth()->user()->hasAnyRole(['ordenador_gasto','supervisor','tesoreria','alcalde']))
                                <a href="{{ route('cuenta.cobro.pendientes') }}" class="btn btn-warning">
                                    Cuentas Pendientes
                                </a>
                            @endif

                            {{-- Crear solo si policy lo permite (contratista, alcalde) --}}
                            @can('create', App\Models\CuentaCobro::class)
                                <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                                    Crear Nueva Cuenta de Cobro
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    @if($cuentas->isEmpty())
                        <p class="text-center">No hay cuentas de cobro registradas.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha emisión</th>
                                        <th>Cobrador</th>
                                        <th>Cliente</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cuentas as $cuenta)
                                        <tr>
                                            <td>{{ $cuenta->id }}</td>
                                            <td>{{ optional($cuenta->fecha_emision)->format('Y-m-d') ?? $cuenta->fecha_emision }}</td>
                                            <td>{{ $cuenta->nombre_cobrador }}</td>
                                            <td>{{ $cuenta->nombre_cliente }}</td>
                                            <td>${{ number_format($cuenta->monto, 2) }}</td>
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
                                                <span class="badge bg-{{ $estadoClass }}">{{ ucfirst($cuenta->estado) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('view', $cuenta)
                                                        <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" class="btn btn-sm btn-info">Ver</a>
                                                    @endcan

                                                    @can('update', $cuenta)
                                                        <a href="{{ route('cuenta.cobro.edit', $cuenta->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                                    @endcan

                                                    @can('delete', $cuenta)
                                                        <form action="{{ route('cuenta.cobro.destroy', $cuenta->id) }}" method="POST" class="d-inline"
                                                              onsubmit="return confirm('¿Eliminar esta cuenta de cobro?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                        </form>
                                                    @endcan

                                                    {{-- Acciones de aprobación/rechazo visibles solo si corresponde (p. ej. alcalde) --}}
                                                    @if(auth()->user()->isAlcalde() && in_array($cuenta->estado, ['pendiente','revision','aprobada']))
                                                        <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" method="POST" class="d-inline ms-1">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                                        </form>
                                                        <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" method="POST" class="d-inline ms-1">
                                                            @csrf
                                                            <input type="text" name="observaciones" placeholder="Motivo del rechazo" required
                                                                   class="form-control d-inline" style="width:160px;">
                                                            <button type="submit" class="btn btn-danger btn-sm ms-1">Rechazar</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $cuentas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
