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
                        @php
                            $estadoClass = match($cuenta->estado) {
                                'pendiente' => 'warning text-dark',
                                'revision'  => 'info text-dark',
                                'aprobada'  => 'success text-white',
                                'rechazada' => 'danger text-white',
                                default     => 'secondary text-white'
                            };
                            $estadoLabel = ucfirst($cuenta->estado);
                        @endphp
                        <span class="badge bg-{{ explode(' ', $estadoClass)[0] }} {{ explode(' ', $estadoClass)[1] ?? '' }}">
                            {{ $estadoLabel }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('cuenta.cobro.show', $cuenta) }}" class="btn btn-sm btn-outline-info">Ver</a>

                            {{-- Cambio de estado SOLO alcalde (contratista no cambia estado) --}}
                            @if(auth()->user()->isAlcalde())
                                <form action="{{ route('cuenta.cobro.cambiar.estado', $cuenta) }}" method="POST" class="d-inline">
                                    @csrf
                                    <select name="estado" class="form-select form-select-sm d-inline w-auto">
                                        <option value="pendiente" @selected($cuenta->estado==='pendiente')>Pendiente</option>
                                        <option value="revision" @selected($cuenta->estado==='revision')>Revisión</option>
                                        <option value="aprobada" @selected($cuenta->estado==='aprobada')>Aprobada</option>
                                        <option value="rechazada" @selected($cuenta->estado==='rechazada')>Rechazada</option>
                                    </select>
                                    <button class="btn btn-primary btn-sm" type="submit">Actualizar</button>
                                </form>
                            @endif
                        </div>
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
