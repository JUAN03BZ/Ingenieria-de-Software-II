@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalle Cuenta de Cobro #{{ $cuenta->id }}</h2>
    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Cobrador:</strong> {{ $cuenta->nombre_cobrador }}</li>
        <li class="list-group-item"><strong>Documento cobrador:</strong> {{ $cuenta->documento_cobrador }}</li>
        <li class="list-group-item"><strong>Cliente:</strong> {{ $cuenta->nombre_cliente }}</li>
        <li class="list-group-item"><strong>Monto:</strong> ${{ number_format($cuenta->monto, 2) }}</li>
        <li class="list-group-item"><strong>Estado:</strong> {{ ucfirst($cuenta->estado) }}</li>
        <li class="list-group-item"><strong>Descripción:</strong> {{ $cuenta->descripcion }}</li>
        <li class="list-group-item"><strong>Fecha emisión:</strong> {{ $cuenta->fecha_emision }}</li>
        @if($cuenta->observaciones)
            <li class="list-group-item"><strong>Observaciones:</strong> {{ $cuenta->observaciones }}</li>
        @endif
    </ul>
    <div class="mb-3">
        <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-secondary me-2">Volver al listado</a>
        @if(
            (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('ordenador_gasto')
            || (property_exists(auth()->user(), 'role') && (auth()->user()->role->name ?? '') == 'ordenador_gasto'))
            && strtolower($cuenta->estado) == 'pendiente_ordenador'
        )
            <form action="{{ route('cuenta.cobro.aprobar', $cuenta->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Aprobar</button>
            </form>
            <form action="{{ route('cuenta.cobro.rechazar', $cuenta->id) }}" method="POST" class="d-inline ms-2">
                @csrf
                <input type="text" name="observaciones" placeholder="Motivo del rechazo" required class="form-control d-inline" style="width:180px;">
                <button type="submit" class="btn btn-danger ms-1">Rechazar</button>
            </form>
        @endif
    </div>
</div>
@endsection
