@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Cuentas de Cobro</h2>
                        <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                            Crear Nueva Cuenta de Cobro
                        </a>
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
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cuentas as $cuenta)
                                        <tr>
                                            <td>{{ $cuenta->id }}</td>
                                            <td>{{ $cuenta->fecha }}</td>
                                            <td>{{ $cuenta->cliente->nombre ?? 'N/A' }}</td>
                                            <td>${{ number_format($cuenta->valor, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $cuenta->estado === 'pendiente' ? 'warning' : ($cuenta->estado === 'pagada' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst($cuenta->estado) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('cuenta.cobro.show', $cuenta->id) }}" class="btn btn-sm btn-info">Ver</a>
                                                <a href="{{ route('cuenta.cobro.edit', $cuenta->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                                <form action="{{ route('cuenta.cobro.destroy', $cuenta->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cuenta de cobro?')">
                                                        Eliminar
                                                    </button>
                                                </form>
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
</div>
@endsection