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
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // CRUD Cuentas de Cobro
    Route::get('/cuentas-cobro', [CuentaCobroController::class, 'index'])->name('cuenta.cobro.index');
    Route::get('/cuentas-cobro/pendientes', [CuentaCobroController::class, 'pendientes'])->name('cuenta.cobro.pendientes');

    Route::get('/cuenta-cobro/create', [CuentaCobroController::class, 'create'])
        ->name('cuenta.cobro.create')
        ->middleware('role:alcalde,contratista');

    Route::post('/cuenta-cobro/guardar', [CuentaCobroController::class, 'store'])
        ->name('cuenta.cobro.guardar')
        ->middleware('role:alcalde,contratista');

    Route::get('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'show'])->name('cuenta.cobro.show');
    Route::get('/cuenta-cobro/{cuenta}/edit', [CuentaCobroController::class, 'edit'])->name('cuenta.cobro.edit');
    Route::put('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'update'])->name('cuenta.cobro.update');
    Route::delete('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'destroy'])->name('cuenta.cobro.destroy');

    // Cambio de estado protegido por rol
    Route::post('/cuenta-cobro/{cuenta}/cambiar-estado', [CuentaCobroController::class, 'cambiarEstado'])
        ->name('cuenta.cobro.cambiar.estado')
        ->middleware('role:alcalde,contratista');

    // Gestión de roles (vista índice y detalle visibles para autenticados)
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/{role}', [RolController::class, 'show'])->name('roles.show');

    // Rutas admin (solo alcalde) con prefijo y nombre admin.
    Route::middleware('role:alcalde')->prefix('admin')->name('admin.')->group(function () {
        // Usuarios pendientes y acciones
        Route::get('/usuarios/pendientes', [RolController::class, 'usuariosPendientes'])->name('usuarios.pendientes');
        Route::post('/usuarios/{usuario}/aprobar', [RolController::class, 'aprobarUsuario'])->name('usuarios.aprobar');
        Route::post('/usuarios/{usuario}/asignar-rol', [RolController::class, 'asignarRol'])->name('usuarios.asignar-rol');

        // CRUD de roles (crear/guardar/editar/actualizar/eliminar)
        Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RolController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RolController::class, 'destroy'])->name('roles.destroy');
    });
});

// Reportes para 'alcalde' y 'ordenador_gasto'
Route::middleware(['auth', 'role:alcalde,ordenador_gasto'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/reports', fn () => view('admin.reports'))->name('reports');
    });
