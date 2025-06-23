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
Route::post('/login',       [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout',      [LoginController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::view('/menu', 'auth.dashboard')->name('dashboard');

    // Clientes
    Route::resource('clientes', ClienteController::class)
         ->only(['index','store','update']);

    // Pagos
    Route::resource('pagos', PagoController::class)
         ->only(['index','store','update']);
    Route::post('pagos/process', [PagoController::class, 'process'])
         ->name('pagos.process');
         Route::get('pagos/boleta/{nroDoc}', [PagoController::class, 'downloadBoleta'])
     ->name('pagos.boleta');
// EMPLEADOS
Route::get('/empleados',   [EmpleadoController::class, 'index'])->name('empleados.index');
Route::post('/empleados',  [EmpleadoController::class, 'store'])->name('empleados.store');
Route::match(['put','patch'], '/empleados/{id}', [EmpleadoController::class, 'update'])
     ->name('empleados.update');
Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])
     ->name('empleados.destroy');
     Route::get('pagos/boleta/{nroDoc}', [PagoController::class, 'downloadBoleta'])
     ->name('pagos.boleta');


 // Deuda Masiva
    Route::resource('deuda', DeudaController::class)
         ->only(['index']);

    // Cobertura
    Route::resource('cobertura', CoberturaController::class)
         ->only(['index','create','store','update']);

    // Estadísticas
    Route::resource('estadisticas', EstadisticasController::class)
         ->only(['index']);

    // Ciudades
    Route::resource('ciudades', CiudadController::class);

    // Planes
    Route::resource('planes', PlanesController::class);
});
