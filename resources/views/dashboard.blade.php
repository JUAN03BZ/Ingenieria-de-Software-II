@extends('layouts.app')

@section('title', 'Dashboard - CuentasCobro')

@section('content')
<style>
    /* Sidebar con animación de plegado automático */
.sidebar {
    background-color: #212529;
    height: 100vh;
    width: 70px;
    transition: width 0.4s cubic-bezier(0.77, 0, 0.175, 1);
    overflow: hidden;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.3);
}

/* Cuando el cursor está sobre la barra, se expande */
.sidebar:hover {
    width: 220px;
}

/* Texto e íconos */
.sidebar h6,
.sidebar .nav-link span {
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    pointer-events: none;
}

/* Cuando está expandida por hover, muestra texto */
.sidebar:hover h6,
.sidebar:hover .nav-link span {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

/* Enlaces del sidebar */
.sidebar .nav-link {
    color: #adb5bd;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease, padding 0.3s ease;
    white-space: nowrap;
}

.sidebar .nav-link i {
    min-width: 25px;
    text-align: center;
    transition: transform 0.3s ease;
}

/* Íconos ligeramente más grandes en modo colapsado */
.sidebar:not(:hover) .nav-link i {
    transform: scale(1.2);
}

/* Hover en enlace */
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background-color: #0d6efd;
    color: #fff;
}

/* Botón (opcional) para forzar colapsar/expandir */
.toggle-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.3rem;
    margin-right: 10px;
    cursor: pointer;
    transition: color 0.3s ease, transform 0.3s ease;
}

.toggle-btn:hover {
    color: #0d6efd;
    transform: rotate(90deg);
}

/* Contenedor principal */
.main-wrapper {
    margin-left: 70px;
    transition: margin-left 0.4s cubic-bezier(0.77, 0, 0.175, 1);
}

/* Cuando sidebar se expande, el contenido se ajusta */
.sidebar:hover ~ .main-wrapper {
    margin-left: 220px;
}

/* Ajuste navbar */
.navbar {
    z-index: 1050;
    position: sticky;
    top: 0;
}


    /* 🔹 Fondo general tipo login */
    body {
        background: linear-gradient(135deg, #1f3f99ff 0%, #000000ff 100%);
        min-height: 100vh;
        font-family: 'Dosis', 'Poppins', sans-serif;
        color: #fff;
    }

    /* 🔹 Sidebar moderno translúcido */
    .sidebar {
        background: rgba(10, 20, 40, 0.95);
        backdrop-filter: blur(8px);
        height: 100vh;
        width: 240px;
        transition: all 0.3s ease;
        position: fixed;
        top: 0;
        left: 0;
        border-right: 2px solid #1f3f99ff;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
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
        color: #a0b3ff;
        display: flex;
        align-items: center;
        padding: 12px 18px;
        border-radius: 8px;
        transition: 0.3s;
        margin: 4px 10px;
        font-size: 1rem;
    }

    .sidebar .nav-link i {
        min-width: 25px;
        text-align: center;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background: linear-gradient(90deg, #1f3f99ff, #3a4fd5ff);
        color: #fff;
        box-shadow: 0 0 10px rgba(69, 243, 255, 0.3);
    }

    /* 🔹 Navbar superior */
    .navbar {
        background: rgba(10, 20, 40, 0.95) !important;
        border-bottom: 2px solid #1f3f99ff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
    }

    .toggle-btn {
        background: none;
        border: none;
        color: #45f3ff;
        font-size: 1.4rem;
        cursor: pointer;
        transition: color 0.3s;
    }

    .toggle-btn:hover {
        color: #fff;
    }

    /* 🔹 Contenedor principal */
    .main-wrapper {
        margin-left: 240px;
        transition: all 0.3s ease;
    }

    .main-wrapper.collapsed {
        margin-left: 70px;
    }

    .main-content {
        padding: 30px;
    }

    /* 🔹 Tarjetas de métricas */
    .card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(69, 243, 255, 0.1);
        border-radius: 14px;
        color: #fff;
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 20px rgba(31, 63, 153, 0.4);
    }

    .card-title {
        font-weight: 600;
        color: #45f3ff;
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* 🔹 Tipografía del saludo */
    h1.h3 {
        color: #ffffff;
        font-weight: 600;
    }

    p.text-muted {
        color: #aab6ff !important;
    }

    /* 🔹 Iconos dentro de círculos brillantes */
    .metric-icon {
        background: linear-gradient(135deg, #1f3f99ff, #000000ff);
        box-shadow: 0 0 10px rgba(69, 243, 255, 0.25);
    }

    /* 🔹 Links y texto */
    a {
        color: #45f3ff;
        text-decoration: none;
    }

    a:hover {
        color: #fff;
        text-shadow: 0 0 6px rgba(69, 243, 255, 0.8);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="toggle-btn" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>

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

<div class="sidebar" id="sidebar">
    <div class="p-3">
        <h6 class="text-light-50 text-uppercase">Menú</h6>
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
        <div class="main-content">
            
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                    <p class="text-muted">Gestiona tus cuentas de cobro de manera eficiente</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- 🔹 Panel de métricas -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card text-center py-4 px-3 h-100">
                                <div class="d-flex justify-content-center mb-3">
                                    <span class="metric-icon rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                        <i class="fas fa-file-invoice fa-2x text-info"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Total Cuentas</h5>
                                <h3 class="text-info">{{ $totalCuentas ?? 0 }}</h3>
                                <small class="text-light">Cuentas registradas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center py-4 px-3 h-100">
                                <div class="d-flex justify-content-center mb-3">
                                    <span class="metric-icon rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Pagadas</h5>
                                <h3 class="text-success">{{ $pagadas ?? 0 }}</h3>
                                <small class="text-light">Cuentas pagadas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center py-4 px-3 h-100">
                                <div class="d-flex justify-content-center mb-3">
                                    <span class="metric-icon rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Pendientes</h5>
                                <h3 class="text-warning">{{ $pendientes ?? 0 }}</h3>
                                <small class="text-light">Por cobrar</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center py-4 px-3 h-100">
                                <div class="d-flex justify-content-center mb-3">
                                    <span class="metric-icon rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                        <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                                    </span>
                                </div>
                                <h5 class="card-title">Total Facturado</h5>
                                <h3 class="text-primary">${{ number_format($totalFacturado ?? 0, 2) }}</h3>
                                <small class="text-light">Este mes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 40px;"></div>

            <!-- 🔹 Acciones rápidas -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
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

            <!-- 🔹 Actividad reciente -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Actividad Reciente</h5>
                </div>
                <div class="card-body text-center py-4">
                    @if($totalCuentas ?? 0 > 0)
                        <p class="text-light">Tienes {{ $totalCuentas ?? 0 }} cuenta(s) registrada(s). Revisa la sección de <a href="{{ route('cuenta.cobro.index') }}">Cuentas de Cobro</a>.</p>
                    @else
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-light">No hay actividad reciente para mostrar.</p>
                        <p class="text-light">¡Comienza creando tu primera cuenta de cobro!</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

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
