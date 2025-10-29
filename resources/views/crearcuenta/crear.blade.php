@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Encabezado con botón de retroceso --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Crear Cuenta de Cobro</h2>
        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </button>
    </div>

    {{-- Mostrar mensajes de éxito/error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <form action="{{ route('cuenta.cobro.guardar') }}" method="POST">
        @csrf

        {{-- Datos del cobrador --}}
        <h5 class="mb-3">Datos de quien cobra</h5>

        <div class="mb-3">
            <label for="nombre_cobrador" class="form-label">Nombre completo</label>
            <input type="text" name="nombre_cobrador" id="nombre_cobrador" class="form-control @error('nombre_cobrador') is-invalid @enderror" value="{{ old('nombre_cobrador') }}" required>
            @error('nombre_cobrador')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="documento_cobrador" class="form-label">Número de documento</label>
            <input type="text" name="documento_cobrador" id="documento_cobrador" class="form-control @error('documento_cobrador') is-invalid @enderror" value="{{ old('documento_cobrador') }}" required>
            @error('documento_cobrador')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="direccion_cobrador" class="form-label">Dirección</label>
            <input type="text" name="direccion_cobrador" id="direccion_cobrador" class="form-control @error('direccion_cobrador') is-invalid @enderror" value="{{ old('direccion_cobrador') }}" required>
            @error('direccion_cobrador')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telefono_cobrador" class="form-label">Teléfono</label>
            <input type="text" name="telefono_cobrador" id="telefono_cobrador" class="form-control @error('telefono_cobrador') is-invalid @enderror" value="{{ old('telefono_cobrador') }}" required>
            @error('telefono_cobrador')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email_cobrador" class="form-label">Correo electrónico</label>
            <input type="email" name="email_cobrador" id="email_cobrador" class="form-control @error('email_cobrador') is-invalid @enderror" value="{{ old('email_cobrador') }}" required>
            @error('email_cobrador')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        {{-- Datos del cliente --}}
        <h5 class="mb-3">Datos del cliente</h5>

        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre / Razón social</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control @error('nombre_cliente') is-invalid @enderror" value="{{ old('nombre_cliente') }}" required>
            @error('nombre_cliente')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="documento_cliente" class="form-label">NIT o número de identificación</label>
            <input type="text" name="documento_cliente" id="documento_cliente" class="form-control @error('documento_cliente') is-invalid @enderror" value="{{ old('documento_cliente') }}" required>
            @error('documento_cliente')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campos agregados para la cuenta --}}
        <hr>

        <h5 class="mb-3">Detalles de la Cuenta</h5>

        <div class="mb-3">
            <label for="monto" class="form-label">Monto a cobrar</label>
            <input type="number" step="0.01" name="monto" id="monto" class="form-control @error('monto') is-invalid @enderror" value="{{ old('monto') }}" required>
            @error('monto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="fecha_emision" class="form-label">Fecha de emisión</label>
            <input type="date" name="fecha_emision" id="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror" value="{{ old('fecha_emision') ?? now()->format('Y-m-d') }}">
            @error('fecha_emision')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Crear Cuenta de Cobro
            </button>
        </div>
    </form>
</div>
@endsection