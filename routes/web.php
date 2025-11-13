<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentaCobroController;
use App\Http\Controllers\RolController;

// Raíz
Route::get('/', fn () => redirect('/login'));

// Auth (públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro (público)
Route::get('/register', [CrearUsuario::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CrearUsuario::class, 'register']);

// Clientes (públicas)
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes/guardar', [ClienteController::class, 'guardar'])->name('clientes.guardar');

// Protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard por rol/fase como principal
    Route::get('/dashboard', [CuentaCobroController::class, 'dashboardFases'])->name('dashboard');

    // Alias opcional al mismo dashboard
    Route::get('/dashboard-cuentas', [CuentaCobroController::class, 'dashboardFases'])
        ->name('cuenta.cobro.dashboard');

    // Cuentas de Cobro
    Route::get('/cuentas-cobro', [CuentaCobroController::class, 'index'])->name('cuenta.cobro.index');
    Route::get('/cuentas-cobro/pendientes', [CuentaCobroController::class, 'pendientes'])->name('cuenta.cobro.pendientes');

    Route::get('/cuenta-cobro/create', [CuentaCobroController::class, 'create'])->name('cuenta.cobro.create');
    Route::post('/cuenta-cobro/guardar', [CuentaCobroController::class, 'store'])->name('cuenta.cobro.guardar');

    Route::get('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'show'])->name('cuenta.cobro.show');
    Route::get('/cuenta-cobro/{cuenta}/edit', [CuentaCobroController::class, 'edit'])->name('cuenta.cobro.edit');
    Route::put('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'update'])->name('cuenta.cobro.update');
    Route::delete('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'destroy'])->name('cuenta.cobro.destroy');

    // Ruta para enviar a supervisor (solo contratista o alcalde)
    Route::post('/cuenta-cobro/{cuenta}/enviar-supervisor', [CuentaCobroController::class, 'enviarASupervision'])
        ->name('cuenta.cobro.enviar.supervisor')->middleware('role:contratista,alcalde');

    // Supervisor: aprobar/rechazar vía supervisorDecision (¡corregido!)
    Route::post('/cuenta-cobro/{cuenta}/supervisor', [CuentaCobroController::class, 'supervisorDecision'])
        ->name('cuenta.cobro.supervisor')->middleware('role:supervisor');

    // Contratación
    Route::post('/cuenta-cobro/{cuenta}/contratacion', [CuentaCobroController::class, 'contratacionDecision'])
        ->name('cuenta.cobro.contratacion')->middleware('role:contratacion');

    // Tesorería
    Route::post('/cuenta-cobro/{cuenta}/tesoreria', [CuentaCobroController::class, 'tesoreriaDecision'])
        ->name('cuenta.cobro.tesoreria')->middleware('role:tesoreria');

    // Ordenador de gasto
    Route::post('/cuenta-cobro/{cuenta}/ordenador', [CuentaCobroController::class, 'ordenadorDecision'])
        ->name('cuenta.cobro.ordenador')->middleware('role:ordenador_gasto');

    // (Opcional solo para alcaldía, si tienes métodos directos de aprobar/rechazar globales)
    Route::post('/cuenta-cobro/{cuenta}/aprobar', [CuentaCobroController::class, 'aprobar'])
        ->name('cuenta.cobro.aprobar')
        ->middleware('role:alcalde');

    Route::post('/cuenta-cobro/{cuenta}/rechazar', [CuentaCobroController::class, 'rechazar'])
        ->name('cuenta.cobro.rechazar')
        ->middleware('role:alcalde');

    // Gestión de roles (índice y detalle para autenticados)
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/{role}', [RolController::class, 'show'])->name('roles.show');

    // Rutas admin (solo alcalde)
    Route::middleware('role:alcalde')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/usuarios/pendientes', [RolController::class, 'usuariosPendientes'])->name('usuarios.pendientes');
        Route::post('/usuarios/{usuario}/aprobar', [RolController::class, 'aprobarUsuario'])->name('usuarios.aprobar');
        Route::post('/usuarios/{usuario}/asignar-rol', [RolController::class, 'asignarRol'])->name('usuarios.asignar-rol');

        // CRUD de roles
        Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RolController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RolController::class, 'destroy'])->name('roles.destroy');

        // Reportes para 'alcalde' y 'ordenador_gasto'
        Route::middleware('role:alcalde,ordenador_gasto')->group(function () {
            Route::get('/reports', fn () => view('admin.reports'))->name('reports');
        });
    });
});
