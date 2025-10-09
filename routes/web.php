<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrearUsuario;
use App\Http\Controllers\NuevaCuenta;

use App\Http\Controllers\ClienteController;

Route::post('/clientes/guardar', [ClienteController::class, 'guardar'])->name('clientes.guardar');


Route::get('/cuenta-cobro', [NuevaCuenta::class, 'crear'])->name('cuenta.cobro.crear');
Route::post('/cuenta-cobro', [NuevaCuenta::class, 'guardar'])->name('cuenta.cobro.guardar');
Route::get('/cuentas-cobro', [NuevaCuenta::class, 'index'])->name('cuenta.cobro.index');


// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//RRutas crear usuarios
Route::get('/register', [CrearUsuario::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CrearUsuario::class, 'register']);


//Ruta Crear Cuenta de Cobro
Route::get('/cuenta-cobro', [NuevaCuenta::class, 'crear'])->name('cuenta.cobro.crear');
Route::post('/cuenta-cobro', [NuevaCuenta::class, 'guardar'])->name('cuenta.cobro.guardar');


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});