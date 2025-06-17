@extends('layouts.app')

@section('title', 'Menú Principal')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Bienvenido, {{ Auth::user()->NombreEmpleado }}</h2>
        <p class="text-muted fs-5">Selecciona una opción para comenzar a trabajar.</p>
    </div>

    <div class="row g-4 justify-content-center">
        {{-- Clientes --}}
        <div class="col-md-4">
            <a href="{{ route('clientes.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-scale">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-people-fill fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Clientes</h5>
                        <p class="card-text text-muted">Gestión completa de clientes registrados.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Pagos --}}
        <div class="col-md-4">
            <a href="{{ route('pagos.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-scale">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-cash-coin fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Pagos</h5>
                        <p class="card-text text-muted">Control y seguimiento de pagos realizados.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Empleados --}}
        <div class="col-md-4">
            <a href="{{ route('empleados.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-scale">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-person-badge-fill fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold">Empleados</h5>
                        <p class="card-text text-muted">Administración eficiente del personal.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Deuda Masiva --}}
        <div class="col-md-4">
            <a href="{{ route('deuda.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-scale">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-octagon-fill fs-1 text-dark"></i>
                        </div>
                        <h5 class="card-title fw-bold">Deuda Masiva</h5>
                        <p class="card-text text-muted">Visualización de clientes con pagos pendientes masivos.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Cobertura --}}
        <div class="col-md-4">
            <a href="{{ route('cobertura.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-scale">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-map-fill fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Cobertura</h5>
                        <p class="card-text text-muted">Gestíon de zonas y cobertura de servicio.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Estadísticas --}}
        <div class="col-md-4">
            <a href="{{ route('estadisticas.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-scale">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-graph-up-arrow fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Estadísticas</h5>
                        <p class="card-text text-muted">Informes y análisis del sistema.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Estilos adicionales --}}
<style>
    .hover-scale {
        transition: transform 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.03);
    }
</style>
@endsection
