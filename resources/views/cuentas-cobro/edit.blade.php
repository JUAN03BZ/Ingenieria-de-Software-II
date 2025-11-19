@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Cuenta de Cobro #{{ $cuenta->id }}</h2>
    <form action="{{ route('cuenta.cobro.update', $cuenta) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre_cobrador" class="form-label">Cobrador</label>
            <input type="text" name="nombre_cobrador" class="form-control" value="{{ old('nombre_cobrador', $cuenta->nombre_cobrador) }}" required>
        </div>

        <div class="mb-3">
            <label for="documento_cobrador" class="form-label">Documento cobrador</label>
            <input type="text" name="documento_cobrador" class="form-control" value="{{ old('documento_cobrador', $cuenta->documento_cobrador) }}" required>
        </div>

        <div class="mb-3">
            <label for="direccion_cobrador" class="form-label">Dirección cobrador</label>
            <input type="text" name="direccion_cobrador" class="form-control" value="{{ old('direccion_cobrador', $cuenta->direccion_cobrador) }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono_cobrador" class="form-label">Teléfono cobrador</label>
            <input type="text" name="telefono_cobrador" class="form-control" value="{{ old('telefono_cobrador', $cuenta->telefono_cobrador) }}" required>
        </div>

        <div class="mb-3">
            <label for="email_cobrador" class="form-label">Email cobrador</label>
            <input type="email" name="email_cobrador" class="form-control" value="{{ old('email_cobrador', $cuenta->email_cobrador) }}" required>
        </div>

        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Cliente</label>
            <input type="text" name="nombre_cliente" class="form-control" value="{{ old('nombre_cliente', $cuenta->nombre_cliente) }}" required>
        </div>

        <div class="mb-3">
            <label for="documento_cliente" class="form-label">Documento cliente</label>
            <input type="text" name="documento_cliente" class="form-control" value="{{ old('documento_cliente', $cuenta->documento_cliente) }}" required>
        </div>

        <div class="mb-3">
            <label for="monto" class="form-label">Monto</label>
            <input type="number" step="0.01" name="monto" class="form-control" value="{{ old('monto', $cuenta->monto) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion', $cuenta->descripcion) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_emision" class="form-label">Fecha de emisión</label>
            <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision', optional($cuenta->fecha_emision)->format('Y-m-d')) }}">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('cuenta.cobro.show', $cuenta) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
