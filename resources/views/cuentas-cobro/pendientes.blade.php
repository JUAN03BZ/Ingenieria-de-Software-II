@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cuentas de cobro pendientes y en revisión</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($cuentas as $cuenta)
                <tr>
                    <td>{{ $cuenta->id }}</td>
                    <td>{{ $cuenta->nombre_cliente }}</td>
                    <td>${{ number_format($cuenta->monto, 2) }}</td>
                    <td>
                        @if($cuenta->estado === 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @elseif($cuenta->estado === 'revision')
                            <span class="badge bg-info text-dark">Revisión</span>
                        @elseif($cuenta->estado === 'aprobada')
                            <span class="badge bg-success text-white">Aprobada</span>
                        @else
                            <span class="badge bg-secondary text-white">{{ ucfirst($cuenta->estado) }}</span>
                        @endif
                    </td>
                    <td>
                        @if(auth()->user()->hasRole('alcalde') || auth()->user()->hasRole('contratista'))
                        <form action="{{ route('cuenta.cobro.cambiar.estado', $cuenta) }}" method="POST" class="d-inline">
                            @csrf
                            <select name="estado" class="form-select form-select-sm d-inline w-auto">
                                <option value="pendiente" @if($cuenta->estado==='pendiente') selected @endif>Pendiente</option>
                                <option value="revision" @if($cuenta->estado==='revision') selected @endif>Revisión</option>
                                <option value="aprobada" @if($cuenta->estado==='aprobada') selected @endif>Aprobada</option>
                            </select>
                            <button class="btn btn-primary btn-sm" type="submit">Actualizar</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay cuentas pendientes ni en revisión.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        @if($cuentas instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $cuentas->links() }}
        @endif
    </div>
</div>
@endsection
