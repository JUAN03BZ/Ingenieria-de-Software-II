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

    /*=================================
    =      Cuentas de Cobro CRUD     =
    =================================*/
    Route::get('/cuentas-cobro', [CuentaCobroController::class, 'index'])->name('cuenta.cobro.index');
    Route::get('/cuenta-cobro/create', [CuentaCobroController::class, 'create'])->name('cuenta.cobro.create');
    Route::post('/cuenta-cobro/guardar', [CuentaCobroController::class, 'store'])->name('cuenta.cobro.guardar');
    Route::get('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'show'])->name('cuenta.cobro.show');
    Route::get('/cuenta-cobro/{cuenta}/edit', [CuentaCobroController::class, 'edit'])->name('cuenta.cobro.edit');
    Route::put('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'update'])->name('cuenta.cobro.update');
    Route::delete('/cuenta-cobro/{cuenta}', [CuentaCobroController::class, 'destroy'])->name('cuenta.cobro.destroy');

    // Sólo alcalde y ordenador pueden ver cuentas pendientes
    Route::middleware(['role:alcalde,ordenador_gasto'])->group(function () {
        // Cuentas de cobro pendientes (estado 'pendiente')
        Route::get('/cuentas-cobro/pendientes', [CuentaCobroController::class, 'pendientes'])
            ->name('cuenta.cobro.pendientes');

        // Pendientes para ordenador de gasto
        Route::get('/cuentas-cobro/pendientes-ordenador', [CuentaCobroController::class, 'pendientesOrdenador'])
            ->name('cuenta.cobro.pendientes_ordenador');
    });

    // Aprobar/rechazar cuentas (solo ordenador de gasto)
    Route::post('/cuenta-cobro/{cuenta}/aprobar', [CuentaCobroController::class, 'aprobar'])
        ->name('cuenta.cobro.aprobar')->middleware('role:ordenador_gasto');
    Route::post('/cuenta-cobro/{cuenta}/rechazar', [CuentaCobroController::class, 'rechazar'])
        ->name('cuenta.cobro.rechazar')->middleware('role:ordenador_gasto');

    /*=================================
    =      Roles y usuarios admin     =
    =================================*/
    // Crear/editar/eliminar roles (solo alcalde)
    Route::middleware('role:alcalde')->group(function () {
        Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RolController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RolController::class, 'destroy'])->name('roles.destroy');

        // Admin: usuarios pendientes y asignación de rol
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/usuarios/pendientes', [RolController::class, 'usuariosPendientes'])->name('usuarios.pendientes');
            Route::post('/usuarios/{usuario}/aprobar', [RolController::class, 'aprobarUsuario'])->name('usuarios.aprobar');
            Route::post('/usuarios/{usuario}/asignar-rol', [RolController::class, 'asignarRol'])->name('usuarios.asignar-rol');
            Route::get('/settings', fn () => view('admin.settings'))->name('settings');
        });

        // Acciones AJAX de roles (alcalde o contratación)
        Route::prefix('roles')->name('roles.')->middleware('role:alcalde,contratacion')->group(function () {
            Route::post('/assign-role', [RolController::class, 'assignRole'])->name('assign');
            Route::post('/remove-role', [RolController::class, 'removeRole'])->name('remove');
            Route::get('/users-without-role', [RolController::class, 'getUsersWithoutRole'])->name('users.without.role');
        });
    });

    // Listado de roles accesible a autenticados
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    // Mostrar detalle de rol (final)
    Route::get('/roles/{role}', [RolController::class, 'show'])->name('roles.show');
});

// Reportes para 'alcalde' y 'ordenador_gasto'
Route::middleware(['auth', 'role:alcalde,ordenador_gasto'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', fn () => view('admin.reports'))->name('reports');
});
