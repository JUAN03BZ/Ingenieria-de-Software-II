@extends('layouts.app')

@section('title', 'Dashboard - CuentasCobro')

@section('content')
<style>
    /* Sidebar con animación de plegado */
    .sidebar {
        background-color: #212529;
        height: 100vh;
        width: 220px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .sidebar.collapsed {
        width: 70px !important;
    }

    .sidebar h6,
    .sidebar .nav-link span {
        transition: opacity 0.3s ease;
    }

    .sidebar.collapsed h6,
    .sidebar.collapsed .nav-link span {
        opacity: 0;
        pointer-events: none;
    }

    .sidebar .nav-link {
        color: #adb5bd;
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
        white-space: nowrap;
    }

    .sidebar .nav-link i {
        min-width: 25px;
        text-align: center;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }

    /* Botón para plegar */
    .toggle-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.3rem;
        margin-right: 10px;
        cursor: pointer;
        transition: 0.3s;
    }

    .toggle-btn:hover {
        color: #0d6efd;
    }

    /* Contenedor principal */
    .main-wrapper {
        margin-left: 220px;
        transition: all 0.3s ease;
    }

    .main-wrapper.collapsed {
        margin-left: 70px;
    }

    /*Ajuste navbar*/

    .navbar {
        z-index: 1050;
        position: sticky;
        top: 0;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Botón para plegar el sidebar -->
        <button class="toggle-btn" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-file-invoice-dollar me-2"></i>CuentasCobro
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-1"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-1"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" 
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

<div class="sidebar" id="sidebar">
    <div class="p-3">
        <h6 class="text-white-50 text-uppercase">Menú Principal</h6>
    </div>
    <nav class="nav flex-column px-3">
        <a class="nav-link active" href="{{ route('dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i><span>Dashboard</span>
        </a>
        <a class="nav-link" href="{{ route('cuenta.cobro.index') }}">
            <i class="fas fa-file-invoice me-2"></i><span>Cuentas de Cobro</span>
        </a>
        <a class="nav-link" href="{{ route('cuenta.cobro.create') }}">
            <i class="fas fa-plus-circle me-2"></i><span>Nueva Cuenta</span>
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-chart-bar me-2"></i><span>Reportes</span>
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-cog me-2"></i><span>Configuración</span>
        </a>
    </nav>
</div>


<div class="main-wrapper" id="mainWrapper">
    <div class="container-fluid">
        <div class="main-content p-4">
  
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3 text-dark">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                    <p class="text-muted">Gestiona tus cuentas de cobro de manera eficiente</p>
                </div>
            </div>

            {{-- Mostrar mensaje de éxito si existe --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            
            <!-- Panel de métricas -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 text-center py-4 px-3" style="min-height: 180px;">
                                <div class="d-flex justify-content-center align-items-center mb-3" style="height:56px;">
                                    <span class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Total Cuentas</h5>
                                <h3 class="text-primary">{{ $totalCuentas ?? 0 }}</h3>
                                <small class="text-muted">Cuentas registradas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 text-center py-4 px-3" style="min-height: 180px;">
                                <div class="d-flex justify-content-center align-items-center mb-3" style="height:56px;">
                                    <span class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Pagadas</h5>
                                <h3 class="text-success">{{ $pagadas ?? 0 }}</h3>
                                <small class="text-muted">Cuentas pagadas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 text-center py-4 px-3" style="min-height: 180px;">
                                <div class="d-flex justify-content-center align-items-center mb-3" style="height:56px;">
                                    <span class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Pendientes</h5>
                                <h3 class="text-warning">{{ $pendientes ?? 0 }}</h3>
                                <small class="text-muted">Por cobrar</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 text-center py-4 px-3" style="min-height: 180px;">
                                <div class="d-flex justify-content-center align-items-center mb-3" style="height:56px;">
                                    <span class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                        <i class="fas fa-dollar-sign fa-2x text-info"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Total Facturado</h5>
                                <h3 class="text-info">${{ number_format($totalFacturado ?? 0, 2) }}</h3>
                                <small class="text-muted">Este mes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 👇 Separador agregado para evitar que choquen los bloques -->
            <div style="margin-bottom: 40px;"></div>

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="fas fa-rocket me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('cuenta.cobro.create') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-plus-circle me-2"></i>Nueva Cuenta de Cobro
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-outline-primary btn-lg w-100">
                                <i class="fas fa-chart-line me-2"></i>Ver Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Actividad Reciente</h5>
                </div>
                <div class="card-body text-center py-4">
                    @if($totalCuentas ?? 0 > 0)
                        <p class="text-muted">Tienes {{ $totalCuentas ?? 0 }} cuenta(s) registrada(s). Revisa la sección de <a href="{{ route('cuenta.cobro.index') }}">Cuentas de Cobro</a> para más detalles.</p>
                    @else
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay actividad reciente para mostrar.</p>
                        <p class="text-muted">¡Comienza creando tu primera cuenta de cobro!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

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
