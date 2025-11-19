<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CuentasCobro')</title>
    
    <!-- Favicon personalizado -->
    <link rel="icon" href="{{ asset('img/InseCode_Logo.png') }}" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')

    <!-- Preload table styles to speed up rendering, then apply stylesheet -->
    <link rel="preload" href="{{ asset('css/tables.css') }}" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/tables.css') }}"></noscript>
    <!-- Google Fonts: Inter (preconnect + stylesheet) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Global fonts (override other font rules) -->
    <link rel="preload" href="{{ asset('css/fonts.css') }}" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/fonts.css') }}"></noscript>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-light fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-file-invoice-dollar me-2"></i>CuentasCobro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark text-light dropdown-custom">
                            <li>
                                <a class="dropdown-item text-light" href="#">
                                    <i class="fas fa-user-cog me-2"></i>Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-light" href="#">
                                    <i class="fas fa-cog me-2"></i>Configuración
                                </a>
                            </li>
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
            <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="link-text">Dashboard</span>
            </a>
            <a class="nav-link {{ Request::routeIs('cuenta.cobro.index') ? 'active' : '' }}" href="{{ route('cuenta.cobro.index') }}">
                <i class="fas fa-file-invoice"></i>
                <span class="link-text">Cuentas de Cobro</span>
            </a>
            @if(!auth()->user()->hasRole('contratista'))
            <a class="nav-link {{ Request::routeIs('cuenta.cobro.pendientes') ? 'active' : '' }}" href="{{ route('cuenta.cobro.pendientes') }}">
                <i class="fas fa-hourglass-half"></i>
                <span class="link-text">Cuentas Pendientes</span>
            </a>
            @endif
            @if(!auth()->user()->hasRole('ordenador_gasto') && !auth()->user()->hasRole('supervisor'))
            <a class="nav-link {{ Request::routeIs('cuenta.cobro.create') ? 'active' : '' }}" href="{{ route('cuenta.cobro.create') }}">
                <i class="fas fa-plus-circle"></i>
                <span class="link-text">Nueva Cuenta</span>
            </a>
            @endif

            @if(Auth::user()->role_id && Auth::user()->role->name === 'alcalde')
            <div class="mt-3">
                <h6 class="text-uppercase text-muted px-2">Administración</h6>
                <a class="nav-link {{ Request::routeIs('admin.usuarios.pendientes') ? 'active' : '' }}" href="{{ route('admin.usuarios.pendientes') }}">
                    <i class="fas fa-user-clock"></i>
                    <span class="link-text">Usuarios Pendientes</span>
                </a>
                <a class="nav-link {{ Request::routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <i class="fas fa-users-cog"></i>
                    <span class="link-text">Gestión de Roles</span>
                </a>
            </div>
            @endif

            @if(!auth()->user()->hasRole('contratista'))
            <a class="nav-link" href="#">
                <i class="fas fa-chart-line"></i>
                <span class="link-text">Reportes</span>
            </a>
            @endif
            <a class="nav-link" href="#">
                <i class="fas fa-cog"></i>
                <span class="link-text">Configuración</span>
            </a>
        </nav>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-wrapper" id="mainWrapper">
        <div class="content-surface container-fluid py-4">
            @yield('content')
        </div>
    </div>

    <!-- Formulario de Logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script del Sidebar -->
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
    </script>

    @stack('scripts')
</body>
</html>
