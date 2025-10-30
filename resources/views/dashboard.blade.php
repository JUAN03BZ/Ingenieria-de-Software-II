@extends('layouts.app')

@section('title', 'Dashboard - CuentasCobro')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Botón para colapsar sidebar -->

        <a class="navbar-brand text-light fw-bold" href="{{ route('dashboard') }}">
            <i class="fas fa-file-invoice-dollar me-2"></i>CuentasCobro
        </a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end bg-dark text-light">
                        <li><a class="dropdown-item text-light" href="#"><i class="fas fa-user-cog me-1"></i>Perfil</a></li>
                        <li><a class="dropdown-item text-light" href="#"><i class="fas fa-cog me-1"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider bg-secondary"></li>
                        <li>
                            <a class="dropdown-item text-light" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
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
        <a class="nav-link" href="{{ route('cuenta.cobro.create') }}">
            <i class="fas fa-plus-circle"></i>
            <span class="link-text">Nueva Cuenta</span>
        </a>

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
            <i class="fas fa-chart-bar"></i>
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
                <h2 class="fw-bold text-light mb-1">¡Bienvenido, {{ Auth::user()->name }}!</h2>
                <p class="text-secondary m-0">Gestiona tus cuentas de cobro de manera eficiente</p>
            </div>
        </div>

        <!-- INFO DE USUARIO -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-dark border-0 shadow-sm mt-2 mb-4 user-info-card" style="max-width: 500px;">
                    <div class="card-body py-3">
                        <h5 class="mb-2"><i class="fas fa-user me-1"></i>Información de tu cuenta</h5>
                        <ul class="list-unstyled mb-0 small">
                            <li><h6 class="d-inline m-0"><strong>Nombre:</strong>  {{ Auth::user()->name }}</h6></li>
                            <li><h6 class="d-inline m-0"><strong>Email:</strong> {{ Auth::user()->email }}</h6></li>
                            <li><h6 class="d-inline m-0"><strong>Rol:  </strong> {{ Auth::user()->role->name ?? 'Sin rol' }}</h6></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTA DE ÉXITO -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- MÉTRICAS -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="metric-card text-center">
                    <div class="metric-icon">
                        <i class="fas fa-file-invoice text-info"></i>
                    </div>
                    <h5>Total Cuentas</h5>
                    <h2 class="text-info count-up" data-target="{{ $totalCuentas ?? 0 }}">0</h2>
                    <small class="text-muted">Cuentas registradas</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card text-center">
                    <div class="metric-icon">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <h5>Pagadas</h5>
                    <h2 class="text-success count-up" data-target="{{ $pagadas ?? 0 }}">0</h2>
                    <small class="text-muted">Cuentas pagadas</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card text-center">
                    <div class="metric-icon">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <h5>Pendientes</h5>
                    <h2 class="text-warning count-up" data-target="{{ $pendientes ?? 0 }}">0</h2>
                    <small class="text-muted">Por cobrar</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card text-center">
                    <div class="metric-icon">
                        <i class="fas fa-dollar-sign text-primary"></i>
                    </div>
                    <h5>Total Facturado</h5>
                    <h2 class="text-primary">$<span class="count-up" data-target="{{ number_format($totalFacturado ?? 0, 2) }}">0</span></h2>
                    <small class="text-muted">Este mes</small>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Cuenta de Cobro
                        </a>
                    </div>
                    <div class="col-12 col-md-6">
                        <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-chart-line me-2"></i>Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTIVIDAD RECIENTE -->
        <div class="card shadow-sm bg-dark border-0 text-light mb-5">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Actividad Reciente</h5>
            </div>
            <div class="card-body text-center py-4">
                @if($actividadesRecientes->count() > 0)
                    <ul class="list-group list-group-flush bg-transparent">
                        @foreach($actividadesRecientes as $cuenta)
                            <li class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center mb-2" style="border-radius:8px;">
                                <div>
                                    <strong>#{{ $cuenta->id }}</strong> - {{ $cuenta->descripcion ?? 'Sin descripción' }}<br>
                                    <small class="text-info">{{ $cuenta->fecha_emision ? date('d/m/Y', strtotime($cuenta->fecha_emision)) : 'Sin fecha' }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">${{ number_format($cuenta->monto, 2) }}</span>
                                    <span class="badge {{ $cuenta->estado == 'pagada' ? 'bg-success' : ($cuenta->estado == 'pendiente' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($cuenta->estado) }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p>No hay actividad reciente. ¡Crea tu primera cuenta de cobro!</p>
                @endif
            </div>
        </div>

    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

<!-- SCRIPT PARA SIDEBAR -->
<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('collapsed');
    });
</script>
@endsection