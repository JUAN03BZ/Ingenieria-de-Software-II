@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="main-content p-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Cuentas de Cobro</h2>
                    <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Cuenta
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if($cuentas->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay cuentas de cobro registradas.</p>
                        <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Crear Primera Cuenta
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Cobrador</th>
                                    <th>Nombre Cliente</th>
                                    <th>Monto</th>
                                    <th>Fecha Emisión</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cuentas as $cuenta)
                                    <tr>
                                        <td>{{ $cuenta->id }}</td>
                                        <td>{{ $cuenta->nombre_cobrador }}</td>
                                        <td>{{ $cuenta->nombre_cliente }}</td>
                                        <td>${{ number_format($cuenta->monto, 2) }}</td>
                                        <td>{{ $cuenta->fecha_emision ? date('d/m/Y', strtotime($cuenta->fecha_emision)) : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $cuenta->estado === 'pendiente' ? 'warning' : ($cuenta->estado === 'pagada' ? 'success' : 'secondary') }}">
                                                {{ ucfirst($cuenta->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-sm btn-info" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-sm btn-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cuenta.cobro.create') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('¿Está seguro de eliminar esta cuenta?')"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection