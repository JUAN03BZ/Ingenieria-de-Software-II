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
                                <i class="fas fa-arrow-left me-1"></i>
                                Menú
                            </a>
                            @if(!auth()->user()->hasRole('ordenador_gasto'))
                            <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                                Crear Nueva Cuenta de Cobro
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
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
                                            <td>{{ $cuenta->fecha_emision }}</td>
                                            <td>{{ $cuenta->nombre_cobrador }}</td>
                                            <td>{{ $cuenta->nombre_cliente }}</td>
                                            <td>${{ number_format($cuenta->monto, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $cuenta->estado === 'pendiente' ? 'warning' : ($cuenta->estado === 'pagada' ? 'success' : ($cuenta->estado === 'pendiente_ordenador' ? 'info' : 'secondary')) }}">
                                                    {{ ucfirst($cuenta->estado) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->hasRole('ordenador_gasto'))
                                                        <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" class="btn btn-sm btn-info">Ver</a>
                                                        @if($cuenta->estado === 'pendiente_ordenador')
                                                            <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                                            </form>
                                                            <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="text" name="observaciones" placeholder="Motivo del rechazo" required class="form-control d-inline" style="width:120px;">
                                                                <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" class="btn btn-sm btn-info">Ver</a>
                                                        <!-- Puedes agregar Editar/Eliminar para otros roles si lo necesitas -->
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Paginación --}}
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
