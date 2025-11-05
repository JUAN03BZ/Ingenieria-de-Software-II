@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cuentas de cobro pendientes</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($cuentas as $cuenta)
                <tr>
                    <td>{{ $cuenta->id }}</td>
                    <td>{{ $cuenta->nombre_cliente }}</td>
                    <td>${{ number_format($cuenta->monto, 2) }}</td>
                    <td>
                        <span class="badge bg-warning">{{ ucfirst($cuenta->estado) }}</span>
                    </td>
                    <td>
                        {{-- Acciones según permisos, si aplica --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay cuentas pendientes.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $cuentas->links() }}
    </div>
</div>
@endsection
