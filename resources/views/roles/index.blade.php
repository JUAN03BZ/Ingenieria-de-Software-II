@extends('layouts.app')

@section('title', 'Gestión de Roles - CuentasCobro')

@section('content')
@php
    // Variable reutilizable: considera "alcalde" como rol con permisos de gestión en esta vista
    $canManageRoles = auth()->check() && (auth()->user()?->role?->name === 'alcalde');
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- ENCABEZADO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-2">
                        <i class="fas fa-users-cog me-2"></i>
                        Gestión de Roles
                    </h2>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">
                        <i class="fas fa-shield-alt me-1"></i>Administra los roles y permisos del sistema
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    @if($canManageRoles)
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Rol
                    </a>
                    @endif
                </div>
            </div>

            <!-- ALERTAS -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- MÉTRICAS -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1">{{ $roles->count() }}</h4>
                                    <small>Total de Roles</small>
                                </div>
                                <div>
                                    <i class="fas fa-users-cog fa-3x" style="opacity: 0.8;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1">{{ $roles->sum('users_count') }}</h4>
                                    <small>Usuarios con Rol</small>
                                </div>
                                <div>
                                    <i class="fas fa-user-check fa-3x" style="opacity: 0.8;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1">{{ $roles->where('users_count', 0)->count() }}</h4>
                                    <small>Roles Sin Usuarios</small>
                                </div>
                                <div>
                                    <i class="fas fa-user-times fa-3x" style="opacity: 0.8;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1">6</h4>
                                    <small>Roles del Sistema</small>
                                </div>
                                <div>
                                    <i class="fas fa-cogs fa-3x" style="opacity: 0.8;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE ROLES -->
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Roles
                        </h5>
                        <span class="badge bg-info">{{ $roles->count() }} roles registrados</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">
                                        <i class="fas fa-hashtag me-1"></i>ID
                                    </th>
                                    <th>
                                        <i class="fas fa-tag me-1"></i>Nombre
                                    </th>
                                    <th>
                                        <i class="fas fa-info-circle me-1"></i>Descripción
                                    </th>
                                    <th style="width: 130px;">
                                        <i class="fas fa-users me-1"></i>Usuarios
                                    </th>
                                    <th style="width: 130px;">
                                        <i class="fas fa-key me-1"></i>Permisos
                                    </th>
                                    <th style="width: 120px;">
                                        <i class="fas fa-calendar me-1"></i>Creado
                                    </th>
                                    @if($canManageRoles)
                                    <th style="width: 180px;" class="text-center">
                                        <i class="fas fa-cogs me-1"></i>Acciones
                                    </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $role->id }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-capitalize" style="color: #ffffff;">
                                            @switch($role->name)
                                                @case('contratista') <i class="fas fa-user-tie me-1"></i> @break
                                                @case('supervisor') <i class="fas fa-user-check me-1"></i> @break
                                                @case('alcalde') <i class="fas fa-crown me-1"></i> @break
                                                @case('ordenador_gasto') <i class="fas fa-money-check-alt me-1"></i> @break
                                                @case('tesoreria') <i class="fas fa-coins me-1"></i> @break
                                                @case('contratacion') <i class="fas fa-handshake me-1"></i> @break
                                                @default <i class="fas fa-user me-1"></i>
                                            @endswitch
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <small style="color: #94a3b8;">{{ $role->description ?? 'Sin descripción' }}</small>
                                    </td>
                                    <td>
                                        @if($role->users_count > 0)
                                            <span class="badge bg-success">
                                                <i class="fas fa-users me-1"></i>{{ $role->users_count }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-user-slash me-1"></i>Sin usuarios
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($role->permissions && count($role->permissions) > 0)
                                            <span class="badge bg-info">
                                                <i class="fas fa-key me-1"></i>{{ count($role->permissions) }}
                                            </span>
                                            <button class="btn btn-sm btn-outline-info" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#permissions-{{ $role->id }}"
                                                    title="Ver permisos">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-ban me-1"></i>Sin permisos
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small style="color: #94a3b8;">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            {{ $role->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>

                                    @if($canManageRoles)
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('roles.show', $role->id) }}" 
                                               class="btn btn-outline-info" 
                                               title="Ver detalles"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Editar"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @php
                                                $sistema = ['contratista', 'supervisor', 'alcalde', 'ordenador_gasto', 'tesoreria', 'contratacion'];
                                            @endphp
                                            @if(!in_array($role->name, $sistema, true) && $role->users_count == 0)
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" 
                                                  method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este rol? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="Eliminar"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                                <button class="btn btn-outline-secondary" 
                                                        disabled 
                                                        title="No se puede eliminar">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>

                                <!-- FILA COLAPSABLE CON PERMISOS -->
                                @if($role->permissions && count($role->permissions) > 0)
                                <tr class="collapse" id="permissions-{{ $role->id }}">
                                    <td colspan="{{ $canManageRoles ? '7' : '6' }}">
                                        <div class="bg-light p-3">
                                            <strong style="color: #3b82f6;">
                                                <i class="fas fa-shield-alt me-2"></i>Permisos asignados:
                                            </strong>
                                            <div class="mt-3">
                                                @foreach($role->permissions as $permission)
                                                <span class="badge bg-light me-2 mb-2">
                                                    <i class="fas fa-key me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- ESTADO VACÍO -->
                    <div class="text-center py-5">
                        <i class="fas fa-users-cog fa-4x mb-4"></i>
                        <h4>No hay roles registrados</h4>
                        <p class="mb-4">Comienza creando el primer rol del sistema.</p>
                        @if($canManageRoles)
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Crear Primer Rol
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@push('scripts')
<script>
    // Auto-cerrar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (el) {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(function(){ 
                    el.style.display = 'none'; 
                }, 600);
            });
        }, 5000);

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush