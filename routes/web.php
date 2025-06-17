<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmpleadoController;
use App\Http\Controllers\Auth\ClienteController;
use App\Http\Controllers\Auth\PagoController;
use App\Http\Controllers\Auth\DeudaController;
use App\Http\Controllers\Auth\CoberturaController;
use App\Http\Controllers\Auth\EstadisticasController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('auth.dashboard'))->name('dashboard');

    // CLIENTES
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');

    // PAGOS
    Route::get('/pagos', [PagoController::class,'index'])->name('pagos.index');
    Route::post('/pagos', [PagoController::class,'store'])->name('pagos.store');
    Route::put('/pagos/{id}', [PagoController::class,'update'])->name('pagos.update');
    Route::post('/pagos/process', [PagoController::class,'process'])->name('pagos.process');

    // EMPLEADOS
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');

    // Deuda Masiva
    Route::get('/deuda', [DeudaController::class, 'index'])->name('deuda.index');

    // Cobertura
    Route::get('/cobertura', [CoberturaController::class, 'index'])->name('cobertura.index');
    Route::post('/cobertura', [CoberturaController::class, 'store'])->name('cobertura.store');
    Route::get('cobertura/create', [CoberturaController::class, 'create'])
     ->name('cobertura.create');
     Route::put('/cobertura/{id}', [CoberturaController::class, 'update'])->name('cobertura.update');


    // Estadísticas
      Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
});
