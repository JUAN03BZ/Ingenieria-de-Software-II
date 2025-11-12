@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalle Cuenta de Cobro #{{ $cuenta->id }}</h2>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Cobrador:</strong> {{ $cuenta->nombre_cobrador }}</li>
        <li class="list-group-item"><strong>Documento cobrador:</strong> {{ $cuenta->documento_cobrador }}</li>
        <li class="list-group-item"><strong>Cliente:</strong> {{ $cuenta->nombre_cliente }}</li>
        <li class="list-group-item"><strong>Monto:</strong> ${{ number_format($cuenta->monto, 2) }}</li>
        <li class="list-group-item">
            <strong>Estado:</strong>
            @php
                $estadoClass = match($cuenta->estado) {
                    'pendiente' => 'warning',
                    'aprobada'  => 'primary',
                    'pagada'    => 'success',
                    'rechazada' => 'danger',
                    'revision'  => 'info',
                    default     => 'secondary'
                };
            @endphp
            <span class="badge bg-{{ $estadoClass }}">{{ ucfirst($cuenta->estado) }}</span>
        </li>
        <li class="list-group-item"><strong>Descripción:</strong> {{ $cuenta->descripcion }}</li>
        <li class="list-group-item"><strong>Fecha emisión:</strong> {{ optional($cuenta->fecha_emision)->format('Y-m-d') ?? $cuenta->fecha_emision }}</li>
        @if($cuenta->observaciones)
            <li class="list-group-item"><strong>Observaciones:</strong> {{ $cuenta->observaciones }}</li>
        @endif
    </ul>

    <div class="mb-3 d-flex align-items-center gap-2">
        <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-secondary">Volver al listado</a>

        @can('update', $cuenta)
            <a href="{{ route('cuenta.cobro.edit', $cuenta->id) }}" class="btn btn-warning">Editar</a>
        @endcan

        @can('delete', $cuenta)
            <form action="{{ route('cuenta.cobro.destroy', $cuenta->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('¿Eliminar esta cuenta de cobro?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
        @endcan>

        {{-- Aprobación/Rechazo solo para alcalde --}}
        @if(auth()->user()->isAlcalde() && in_array($cuenta->estado, ['pendiente','revision','aprobada']))
            <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Aprobar</button>
            </form>
            <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" method="POST" class="d-inline ms-2">
                @csrf
                <input type="text" name="observaciones" placeholder="Motivo del rechazo" required
                       class="form-control d-inline" style="width:180px;">
                <button type="submit" class="btn btn-danger ms-1">Rechazar</button>
            </form>
        @endif
    </div>
</div>
@endsection
