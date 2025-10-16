<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentaCobroController;
use App\Http\Controllers\RolController;

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect('/login');
});

// ====================
// 🔐 Rutas de Autenticación (públicas)
// ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================
// 👤 Registro de usuarios (público)
// ====================
Route::get('/register', [CrearUsuario::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CrearUsuario::class, 'register']);

// ====================
// 👥 Rutas de Clientes (públicas o según tu elección)
// ====================
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes/guardar', [ClienteController::class, 'guardar'])->name('clientes.guardar');

// ====================
// 🔒 Rutas protegidas por autenticación
// ====================
Route::middleware(['auth'])->group(function () {
    
    // 📊 Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // ====================
    // 💰 Cuentas de Cobro
    // ====================
    Route::get('/cuenta-cobro/create', [CuentaCobroController::class, 'create'])->name('cuenta.cobro.create');
    Route::post('/cuenta-cobro/guardar', [CuentaCobroController::class, 'store'])->name('cuenta.cobro.guardar');
    Route::get('/cuentas-cobro', [CuentaCobroController::class, 'index'])->name('cuenta.cobro.index');

    // ====================
    // 🧩 Rutas de Roles
    // ====================
        Route::resource('roles', RolController::class)->except(['show'])->names([
        'index' => 'roles.index',
        'create' => 'roles.create',
        'store' => 'roles.store',
        'edit' => 'roles.edit',
        'update' => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);

    // Ruta personalizada para mostrar un rol específico
        Route::get('/roles/{role}', [RolController::class, 'show'])->name('roles.show');

    // ====================
    // ⚙️ Rutas adicionales de Roles
    // ====================
    Route::prefix('roles')->name('roles.')->group(function () {
            Route::post('/assign-role', [RolController::class, 'assignRole'])->name('assign');
            Route::post('/remove-role', [RolController::class, 'removeRole'])->name('remove');
            Route::get('/users-without-role', [RolController::class, 'getUsersWithoutRole'])->name('users.without.role');
    });

    // ====================
    // 🏛️ Rutas solo para ADMIN con rol "alcalde"
    // ====================
    Route::prefix('admin')->middleware(['check.role:alcalde'])->name('admin.')->group(function () {
        // Gestión de usuarios pendientes de aprobación
        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/pendientes', [RolController::class, 'usuariosPendientes'])->name('pendientes');
            Route::post('/{usuario}/asignar-rol', [RolController::class, 'asignarRol'])->name('asignar-rol');
        });

        // Configuración del sistema
        Route::get('/settings', function() {
            return view('admin.settings');
        })->name('settings');
    });
});

// ====================
// 🧠 Rutas por tipo de rol
// ====================
Route::middleware(['auth'])->group(function () {

    // Contratista
    Route::middleware(['check.role:contratista'])->prefix('contratista')->name('contratista.')->group(function () {
        Route::get('/dashboard', function() {
            return view('contratista.dashboard');
        })->name('dashboard');
    });

    // Supervisor
    Route::middleware(['check.role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', function() {
            return view('supervisor.dashboard');
        })->name('dashboard');
    });

    // Roles administrativos (alcalde, ordenador del gasto)
    Route::middleware(['check.role:alcalde,ordenador_gasto'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reports', function() {
            return view('admin.reports');
        })->name('reports');
    });
});
