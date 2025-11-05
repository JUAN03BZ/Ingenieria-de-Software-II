@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Encabezado con botón de retroceso --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>
                <i class="fas fa-file-invoice-dollar me-2" style="color: #3b82f6;"></i>
                Crear Cuenta de Cobro
            </h2>
            <p  style="font-size: 0.95rem;color: #9d9b9b;">
                Complete todos los campos requeridos para generar la cuenta
            </p>
        </div>
        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </button>
    </div>

    {{-- Mostrar mensajes de éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error de validación:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario --}}
    <form action="{{ route('cuenta.cobro.guardar') }}" method="POST" id="formCrearCuenta">
        @csrf

        {{-- Sección: Datos del cobrador --}}
        <div class="form-section">
            <h5>
                <i class="fas fa-user-tie"></i>
                Datos de quien cobra
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre_cobrador" class="form-label required">
                        <i class="fas fa-id-card"></i>Nombre completo
                    </label>
                    <input type="text"
                           name="nombre_cobrador"
                           id="nombre_cobrador"
                           class="form-control @error('nombre_cobrador') is-invalid @enderror"
                           value="{{ old('nombre_cobrador') }}"
                           placeholder="Ej: Juan Pérez García"
                           required>
                    @error('nombre_cobrador')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="documento_cobrador" class="form-label required">
                        <i class="fas fa-fingerprint"></i>Número de documento
                    </label>
                    <input type="text"
                           name="documento_cobrador"
                           id="documento_cobrador"
                           class="form-control @error('documento_cobrador') is-invalid @enderror"
                           value="{{ old('documento_cobrador') }}"
                           placeholder="Ej: 1234567890"
                           required>
                    @error('documento_cobrador')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="direccion_cobrador" class="form-label required">
                        <i class="fas fa-map-marker-alt"></i>Dirección
                    </label>
                    <input type="text"
                           name="direccion_cobrador"
                           id="direccion_cobrador"
                           class="form-control @error('direccion_cobrador') is-invalid @enderror"
                           value="{{ old('direccion_cobrador') }}"
                           placeholder="Ej: Calle 123 #45-67"
                           required>
                    @error('direccion_cobrador')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="telefono_cobrador" class="form-label required">
                        <i class="fas fa-phone"></i>Teléfono
                    </label>
                    <input type="text"
                           name="telefono_cobrador"
                           id="telefono_cobrador"
                           class="form-control @error('telefono_cobrador') is-invalid @enderror"
                           value="{{ old('telefono_cobrador') }}"
                           placeholder="Ej: 3001234567"
                           required>
                    @error('telefono_cobrador')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="email_cobrador" class="form-label required">
                        <i class="fas fa-envelope"></i>Correo electrónico
                    </label>
                    <input type="email"
                           name="email_cobrador"
                           id="email_cobrador"
                           class="form-control @error('email_cobrador') is-invalid @enderror"
                           value="{{ old('email_cobrador') }}"
                           placeholder="ejemplo@correo.com"
                           required>
                    @error('email_cobrador')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <hr>

        {{-- Sección: Datos del cliente --}}
        <div class="form-section">
            <h5>
                <i class="fas fa-building"></i>
                Datos del cliente
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre_cliente" class="form-label required">
                        <i class="fas fa-user"></i>Nombre / Razón social
                    </label>
                    <input type="text"
                           name="nombre_cliente"
                           id="nombre_cliente"
                           class="form-control @error('nombre_cliente') is-invalid @enderror"
                           value="{{ old('nombre_cliente') }}"
                           placeholder="Ej: Empresa ABC S.A.S."
                           required>
                    @error('nombre_cliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="documento_cliente" class="form-label required">
                        <i class="fas fa-id-badge"></i>NIT o número de identificación
                    </label>
                    <input type="text"
                           name="documento_cliente"
                           id="documento_cliente"
                           class="form-control @error('documento_cliente') is-invalid @enderror"
                           value="{{ old('documento_cliente') }}"
                           placeholder="Ej: 900123456-7"
                           required>
                    @error('documento_cliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <hr>

        {{-- Sección: Detalles de la cuenta --}}
        <div class="form-section">
            <h5>
                <i class="fas fa-file-invoice"></i>
                Detalles de la Cuenta
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="monto" class="form-label required">
                        <i class="fas fa-dollar-sign"></i>Monto a cobrar
                    </label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="monto"
                           id="monto"
                           class="form-control @error('monto') is-invalid @enderror"
                           value="{{ old('monto') }}"
                           placeholder="Ej: 1000000.00"
                           required>
                    @error('monto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="fecha_emision" class="form-label required">
                        <i class="fas fa-calendar-alt"></i>Fecha de emisión
                    </label>
                    <input type="date"
                           name="fecha_emision"
                           id="fecha_emision"
                           class="form-control @error('fecha_emision') is-invalid @enderror"
                           value="{{ old('fecha_emision', now()->format('Y-m-d')) }}"
                           required>
                    @error('fecha_emision')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left"></i>Descripción
                    </label>
                    <textarea name="descripcion"
                              id="descripcion"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              rows="4"
                              placeholder="Describa los servicios o productos facturados...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="d-flex justify-content-end gap-3 mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                <i class="fas fa-times me-2"></i>Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Crear Cuenta de Cobro
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/crear.css') }}">
@endpush

@push('scripts')
<script>
    // Validación en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formCrearCuenta');

        // Formatear campo de teléfono (solo números)
        const telefonoInput = document.getElementById('telefono_cobrador');
        if(telefonoInput) {
            telefonoInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Formatear campo de documento (solo números)
        const documentoCobradorInput = document.getElementById('documento_cobrador');
        if(documentoCobradorInput) {
            documentoCobradorInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Formatear monto
        const montoInput = document.getElementById('monto');
        if(montoInput) {
            montoInput.addEventListener('input', function(e) {
                if(this.value < 1) this.value = 1;
            });
        }

        // Remover clase invalid al escribir
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    });
</script>
@endpush
