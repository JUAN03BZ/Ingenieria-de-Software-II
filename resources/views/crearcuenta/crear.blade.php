@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Crear Cuenta de Cobro</h2>
        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </button>
    </div>

    <form action="{{ route('cuenta.cobro.guardar') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre_cobrador" class="form-label">Nombre completo de quien cobra</label>
            <input type="text" name="nombre_cobrador" id="nombre_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="documento_cobrador" class="form-label">Número de documento</label>
            <input type="text" name="documento_cobrador" id="documento_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="direccion_cobrador" class="form-label">Dirección</label>
            <input type="text" name="direccion_cobrador" id="direccion_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telefono_cobrador" class="form-label">Teléfono</label>
            <input type="text" name="telefono_cobrador" id="telefono_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email_cobrador" class="form-label">Correo electrónico</label>
            <input type="email" name="email_cobrador" id="email_cobrador" class="form-control" required>
        </div>

        <hr>

        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre / Razón social a quien cobra</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="documento_cliente" class="form-label">NIT o número de identificación</label>
            <input type="text" name="documento_cliente" id="documento_cliente" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Crear Cuenta de Cobro
        </button>
    </form>
</div>
@endsection
