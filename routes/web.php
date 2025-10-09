<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentaCobroController;

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación (públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para crear usuarios (públicas)
Route::get('/register', [CrearUsuario::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CrearUsuario::class, 'register']);

// Rutas para clientes (AGREGADAS: ahora sí existen)
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes/guardar', [ClienteController::class, 'guardar'])->name('clientes.guardar');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Rutas para Cuentas de Cobro
    Route::get('/cuenta-cobro/create', [CuentaCobroController::class, 'create'])->name('cuenta.cobro.create');
    Route::post('/cuenta-cobro/guardar', [CuentaCobroController::class, 'store'])->name('cuenta.cobro.guardar');
    Route::get('/cuentas-cobro', [CuentaCobroController::class, 'index'])->name('cuenta.cobro.index');
});