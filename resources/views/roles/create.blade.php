@extends('layouts.app')

@section('title', 'Crear Nuevo Rol - CuentasCobro')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (opcional) -->
        <div class="col-md-2">
            <!-- Sidebar content -->
        </div>
        
        <!-- Main content -->
        <div class="col-md-10">
            <!-- Header con navegación -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('roles.index') }}" class="text-decoration-none">
                                    <i class="fas fa-users-cog me-1"></i>Roles
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Crear Nuevo Rol</li>
                        </ol>
                    </nav>
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Crear Nuevo Rol
                    </h2>
                </div>
                
                <div class="btn-group" role="group">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Cancelar
                    </a>
                </div>
            </div>

            <!-- Mensajes de error -->
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>¡Error!</strong> Por favor corrige los siguientes errores:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('roles.store') }}" method="POST" id="createRoleForm">
                @csrf
                
                <div class="row">
                    <!-- Información básica del rol -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información Básica
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Nombre del rol -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>
                                        Nombre del Rol <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ej: coordinador, auditor, etc."
                                           required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Use solo letras minúsculas y guiones bajos
                                    </div>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Descripción del rol -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-1"></i>
                                        Descripción <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Describe las responsabilidades y funciones de este rol..."
                                              required>{{ old('description') }}</textarea>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Máximo 500 caracteres
                                    </div>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Estadísticas de permisos seleccionados -->
                                <div class="mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="mb-1">
                                                <i class="fas fa-chart-pie me-1"></i>
                                                Resumen de Permisos
                                            </h6>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Permisos seleccionados:</small>
                                                <span class="badge bg-info" id="selectedCount">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Total disponibles:</small>
                                                <span class="badge bg-secondary">{{ count($availablePermissions) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permisos disponibles -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-key me-2"></i>
                                    Asignar Permisos
                                </h5>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-light btn-sm" onclick="selectAllPermissions()">
                                        <i class="fas fa-check-square me-1"></i>
                                        Seleccionar Todo
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" onclick="clearAllPermissions()">
                                        <i class="fas fa-square me-1"></i>
                                        Limpiar Todo
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @php
                                $permissionCategories = [
                                    'Cuentas de Cobro' => [
                                        'create_cuenta_cobro' => 'Crear cuentas de cobro',
                                        'view_cuenta_cobro' => 'Ver cuentas de cobro',
                                        'view_own_cuenta_cobro' => 'Ver propias cuentas de cobro',
                                        'view_all_cuenta_cobro' => 'Ver todas las cuentas de cobro',
                                        'edit_own_cuenta_cobro' => 'Editar propias cuentas de cobro',
                                        'review_cuenta_cobro' => 'Revisar cuentas de cobro',
                                        'approve_cuenta_cobro' => 'Aprobar cuentas de cobro',
                                        'reject_cuenta_cobro' => 'Rechazar cuentas de cobro',
                                        'final_approval' => 'Aprobación final'
                                    ],
                                    'Documentos' => [
                                        'upload_documents' => 'Subir documentos',
                                        'view_documents' => 'Ver documentos'
                                    ],
                                    'Contratos' => [
                                        'view_contract_info' => 'Ver información de contratos',
                                        'manage_contracts' => 'Gestionar contratos',
                                        'contract_validation' => 'Validación de contratos'
                                    ],
                                    'Pagos' => [
                                        'authorize_payment' => 'Autorizar pagos',
                                        'process_payment' => 'Procesar pagos',
                                        'generate_checks' => 'Generar cheques',
                                        'bank_transfers' => 'Transferencias bancarias',
                                        'payment_confirmation' => 'Confirmación de pagos',
                                        'generate_payment_orders' => 'Generar órdenes de pago'
                                    ],
                                    'Presupuesto' => [
                                        'view_budget' => 'Ver presupuesto',
                                        'manage_budget' => 'Gestionar presupuesto'
                                    ],
                                    'Reportes' => [
                                        'view_reports' => 'Ver reportes',
                                        'financial_reports' => 'Reportes financieros',
                                        'view_financial_reports' => 'Ver reportes financieros',
                                        'contract_reports' => 'Reportes de contratos'
                                    ],
                                    'Administración' => [
                                        'manage_users' => 'Gestionar usuarios',
                                        'manage_contractors' => 'Gestionar contratistas',
                                        'contractor_registration' => 'Registro de contratistas',
                                        'system_admin' => 'Administrador del sistema'
                                    ],
                                    'Otros' => [
                                        'add_comments' => 'Agregar comentarios',
                                        'request_corrections' => 'Solicitar correcciones',
                                        'override_decisions' => 'Anular decisiones'
                                    ]
                                ];
                                @endphp

                                <div class="row">
                                    @foreach($permissionCategories as $category => $categoryPermissions)
                                    <div class="col-md-6 mb-4">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-primary mb-0">
                                                    @switch($category)
                                                        @case('Cuentas de Cobro')
                                                            <i class="fas fa-file-invoice me-1"></i>
                                                            @break
                                                        @case('Documentos')
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            @break
                                                        @case('Contratos')
                                                            <i class="fas fa-handshake me-1"></i>
                                                            @break
                                                        @case('Pagos')
                                                            <i class="fas fa-money-bill me-1"></i>
                                                            @break
                                                        @case('Presupuesto')
                                                            <i class="fas fa-chart-line me-1"></i>
                                                            @break
                                                        @case('Reportes')
                                                            <i class="fas fa-chart-bar me-1"></i>
                                                            @break
                                                        @case('Administración')
                                                            <i class="fas fa-cogs me-1"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-ellipsis-h me-1"></i>
                                                    @endswitch
                                                    {{ $category }}
                                                </h6>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        onclick="toggleCategoryPermissions('{{ strtolower(str_replace(' ', '_', $category)) }}')">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                            </div>
                                            
                                            @foreach($categoryPermissions as $permission => $description)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-checkbox {{ strtolower(str_replace(' ', '_', $category)) }}-permission" 
                                                       type="checkbox" 
                                                       id="permission_{{ $permission }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permission }}"
                                                       {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}
                                                       onchange="updatePermissionCount()">
                                                <label class="form-check-label" for="permission_{{ $permission }}">
                                                    <small>{{ $description }}</small>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Los campos marcados con <span class="text-danger">*</span> son obligatorios
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Crear Rol
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }
    
    .permission-checkbox {
        cursor: pointer;
    }
    
    .form-check-label {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Actualizar contador de permisos seleccionados
    function updatePermissionCount() {
        const checkedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked');
        document.getElementById('selectedCount').textContent = checkedPermissions.length;
    }
    
    // Seleccionar todos los permisos
    function selectAllPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updatePermissionCount();
    }
    
    // Limpiar todos los permisos
    function clearAllPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updatePermissionCount();
    }
    
    // Alternar permisos de una categoría específica
    function toggleCategoryPermissions(category) {
        const categoryCheckboxes = document.querySelectorAll(`.${category}-permission`);
        const checkedCount = Array.from(categoryCheckboxes).filter(cb => cb.checked).length;
        const shouldCheck = checkedCount === 0;
        
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = shouldCheck;
        });
        updatePermissionCount();
    }
    
    // Validación del formulario
    document.getElementById('createRoleForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const description = document.getElementById('description').value.trim();
        
        if (!name || !description) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios.');
            return false;
        }
        
        // Validar formato del nombre (solo letras minúsculas y guiones bajos)
        const namePattern = /^[a-z_]+$/;
        if (!namePattern.test(name)) {
            e.preventDefault();
            alert('El nombre del rol solo puede contener letras minúsculas y guiones bajos.');
            return false;
        }
    });
    
    // Inicializar contador al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        updatePermissionCount();
    });
    
    // Formatear el nombre automáticamente
    document.getElementById('name').addEventListener('input', function(e) {
        let value = e.target.value;
        // Convertir a minúsculas y reemplazar espacios por guiones bajos
        value = value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z_]/g, '');
        e.target.value = value;
    });
</script>
@endpush
@endsection