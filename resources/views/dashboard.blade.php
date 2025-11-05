@extends('layouts.app')

@section('title', 'Dashboard - CuentasCobro')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-light fw-bold" href="{{ route('dashboard') }}">
            <i class="fas fa-file-invoice-dollar me-2"></i>CuentasCobro
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end bg-dark text-light dropdown-custom">
                        <li><a class="dropdown-item text-light" href="#"><i class="fas fa-user-cog me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item text-light" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider bg-secondary"></li>
                        <li>
                            <a class="dropdown-item text-light" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="p-3 sidebar-header">
        <h6 class="text-uppercase text-muted">Menú</h6>
    </div>
    <nav class="nav flex-column px-2">
        <a class="nav-link active" href="{{ route('dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="link-text">Dashboard</span>
        </a>
        <a class="nav-link" href="{{ route('cuenta.cobro.index') }}">
            <i class="fas fa-file-invoice"></i>
            <span class="link-text">Cuentas de Cobro</span>
        </a>
        <a class="nav-link" href="{{ route('cuenta.cobro.pendientes_ordenador') }}">
            <i class="fas fa-hourglass-half"></i>
            <span class="link-text">Cuentas Pendientes</span>
        </a>
        @if(!auth()->user()->hasRole('ordenador_gasto'))
        <a class="nav-link" href="{{ route('cuenta.cobro.create') }}">
            <i class="fas fa-plus-circle"></i>
            <span class="link-text">Nueva Cuenta</span>
        </a>
        @endif

        @if(Auth::user()->role_id && Auth::user()->role->name === 'alcalde')
        <div class="mt-3">
            <h6 class="text-uppercase text-muted px-2">Administración</h6>
            <a class="nav-link" href="{{ route('admin.usuarios.pendientes') }}">
                <i class="fas fa-user-clock"></i>
                <span class="link-text">Usuarios Pendientes</span>
            </a>
            <a class="nav-link" href="{{ route('roles.index') }}">
                <i class="fas fa-users-cog"></i>
                <span class="link-text">Gestión de Roles</span>
            </a>
        </div>
        @endif

        <a class="nav-link" href="#">
            <i class="fas fa-chart-line"></i>
            <span class="link-text">Reportes</span>
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-cog"></i>
            <span class="link-text">Configuración</span>
        </a>
    </nav>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="main-wrapper" id="mainWrapper">
    <div class="content-surface container-fluid py-4">

        <!-- ENCABEZADO -->
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-light mb-1">
                    <i class="fas fa-wave-square me-2"></i>¡Bienvenido, {{ Auth::user()->name }}!
                </h2>
                <p class="text-secondary m-0">
                    <i class="fas fa-chart-pie me-2"></i>Gestiona tus cuentas de cobro de manera eficiente
                </p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- INFO DE USUARIO -->
            <div class="col-lg-4">
                <div class="card bg-dark border-0 shadow-sm user-info-card h-100">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="fas fa-user-circle me-2"></i>Información de tu cuenta
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-center">
                                <i class="fas fa-id-badge me-3 text-info"></i>
                                <div>
                                    <small class="text-muted d-block">Nombre</small>
                                    <strong>{{ Auth::user()->name }}</strong>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="fas fa-envelope me-3 text-warning"></i>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <strong>{{ Auth::user()->email }}</strong>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-shield-alt me-3 text-success"></i>
                                <div>
                                    <small class="text-muted d-block">Rol</small>
                                    <strong>{{ Auth::user()->role->name ?? 'Sin rol' }}</strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ACCIONES RÁPIDAS -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm card-glass">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="fas fa-rocket me-2 text-warning"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if(!auth()->user()->hasRole('ordenador_gasto'))
                            <div class="col-12 col-md-4">
                                <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center py-3">
                                    <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                    <span>Nueva Cuenta</span>
                                </a>
                            </div>
                            @endif
                            <div class="col-12 col-md-4">
                                <a href="{{ route('cuenta.cobro.pendientes_ordenador') }}" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3">
                                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                                    <span>Cuentas Pendientes</span>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-outline-light w-100 d-flex flex-column align-items-center py-3">
                                    <i class="fas fa-list fa-2x mb-2"></i>
                                    <span>Ver Todas</span>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="#" class="btn btn-outline-light w-100 d-flex flex-column align-items-center py-3">
                                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                                    <span>Reportes</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTA DE ÉXITO -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- MÉTRICAS -->
        <!-- el bloque de métricas sigue igual -->

        @isset($actividadesRecientes)
        <!-- ACTIVIDAD RECIENTE -->
        <div class="card shadow-sm bg-dark border-0 text-light mb-5 activity-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2 text-info"></i>Actividad Reciente
                </h5>
                <span class="badge bg-info">{{ $actividadesRecientes->count() }} cuentas</span>
            </div>
            <div class="card-body py-3">
                @if($actividadesRecientes->count() > 0)
                    <ul class="list-group list-group-flush bg-transparent">
                        @foreach($actividadesRecientes as $cuenta)
                            <li class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center mb-2 activity-item">
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-secondary me-2">#{{ $cuenta->id }}</span>
                                        <strong>{{ $cuenta->descripcion ?? 'Sin descripción' }}</strong>
                                    </div>
                                    <small class="text-info">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $cuenta->fecha_emision ? date('d/m/Y', strtotime($cuenta->fecha_emision)) : 'Sin fecha' }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            ${{ number_format($cuenta->monto, 2) }}
                                        </span>
                                    </div>
                                    <span class="badge {{ $cuenta->estado == 'pagada' ? 'bg-success' : ($cuenta->estado == 'pendiente' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        <i class="fas {{ $cuenta->estado == 'pagada' ? 'fa-check' : 'fa-clock' }} me-1"></i>
                                        {{ ucfirst($cuenta->estado) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3 muted-icon"></i>
                        <p class="text-muted mb-3">No hay actividad reciente</p>
                        @if(!auth()->user()->hasRole('ordenador_gasto'))
                        <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Crear primera cuenta
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @endisset

    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

<script>
    // Sidebar toggle
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('collapsed');
        });
    }
    // Contadores
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.count-up');
        counters.forEach(counter => {
            const target = parseFloat(counter.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = Math.floor(target);
                }
            };
            updateCounter();
        });
    });
</script>
@endsection
