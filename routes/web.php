<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmpleadoController;
use App\Http\Controllers\Auth\ClienteController;
use App\Http\Controllers\Auth\PagoController;
use App\Http\Controllers\Auth\DeudaController;
use App\Http\Controllers\Auth\CoberturaController;
use App\Http\Controllers\Auth\EstadisticasController;
use App\Http\Controllers\Auth\CiudadController;
use App\Http\Controllers\Auth\PlanesController;

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
// EMPLEADOS
Route::get('/empleados',   [EmpleadoController::class, 'index'])->name('empleados.index');
Route::post('/empleados',  [EmpleadoController::class, 'store'])->name('empleados.store');
Route::match(['put','patch'], '/empleados/{id}', [EmpleadoController::class, 'update'])
     ->name('empleados.update');
Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])
     ->name('empleados.destroy');

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
    // Ciudades
     // Listar
Route::get('/ciudades', [CiudadController::class, 'index'])->name('ciudades.index');

// Formulario de creación
Route::get('/ciudades/create', [CiudadController::class, 'create'])
     ->name('ciudades.create');

// Guardar nueva ciudad
Route::post('/ciudades', [CiudadController::class, 'store'])->name('ciudades.store');

// Formulario de edición
Route::get('/ciudades/{id}/edit', [CiudadController::class, 'edit'])
     ->name('ciudades.edit');

// Actualizar ciudad
Route::put('/ciudades/{id}', [CiudadController::class, 'update'])
     ->name('ciudades.update');

// Eliminar ciudad
Route::delete('/ciudades/{id}', [CiudadController::class, 'destroy'])
     ->name('ciudades.destroy');


// Planes
      Route::get('/planes', [PlanesController::class, 'index'])->name('planes.index');
     Route::post('/planes', [PlanesController::class, 'store'])->name('planes.store');
     Route::put('/planes/{id}', [PlanesController::class, 'update'])->name('planes.update');
     Route::delete('/planes/{id}', [PlanesController::class, 'destroy'])->name('planes.destroy');
});
