@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Cliente</h2>
    <form action="{{ route('clientes.guardar') }}" method="POST">
        @csrf

        {{-- Datos del cliente --}}
        <h4>Información del Cliente</h4>
        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre completo / Razón social</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="documento_cliente" class="form-label">NIT o número de identificación</label>
            <input type="text" name="documento_cliente" id="documento_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="direccion_cliente" class="form-label">Dirección</label>
            <input type="text" name="direccion_cliente" id="direccion_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telefono_cliente" class="form-label">Teléfono</label>
            <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email_cliente" class="form-label">Correo electrónico</label>
            <input type="email" name="email_cliente" id="email_cliente" class="form-control" required>
        </div>

        <hr>

        {{-- Información adicional --}}
        <h4>Datos adicionales</h4>
        <div class="mb-3">
            <label for="tipo_cliente" class="form-label">Tipo de cliente</label>
            <select name="tipo_cliente" id="tipo_cliente" class="form-control" required>
                <option value="">Seleccione una opción</option>
                <option value="natural">Persona natural</option>
                <option value="juridico">Persona jurídica</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Notas u observaciones adicionales..."></textarea>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary" onclick="history.back()">← Regresar</button>
            <button type="submit" class="btn btn-success">Registrar Cliente</button>
        </div>
    </form>
</div>
@endsection
