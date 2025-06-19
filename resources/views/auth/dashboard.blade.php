@extends('layouts.app')

@section('title', 'Menú Principal')

@section('content')
<!-- Botón para abrir/cerrar el sidebar -->
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
    <i class="bi bi-list"></i> Menú
</button>

<!-- Sidebar Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarLabel">Menú</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="bg-light border-end h-100">
            <ul class="nav flex-column p-3">
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door-fill me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('clientes.index') ? 'active fw-bold' : '' }}" href="{{ route('clientes.index') }}">
                        <i class="bi bi-people-fill me-2"></i>Clientes
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('pagos.index') ? 'active fw-bold' : '' }}" href="{{ route('pagos.index') }}">
                        <i class="bi bi-cash-coin me-2"></i>Pagos
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('empleados.index') ? 'active fw-bold' : '' }}" href="{{ route('empleados.index') }}">
                        <i class="bi bi-person-badge-fill me-2"></i>Empleados
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('deuda.index') ? 'active fw-bold' : '' }}" href="{{ route('deuda.index') }}">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>Deuda Masiva
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('cobertura.index') ? 'active fw-bold' : '' }}" href="{{ route('cobertura.index') }}">
                        <i class="bi bi-map-fill me-2"></i>Cobertura
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('estadisticas.index') ? 'active fw-bold' : '' }}" href="{{ route('estadisticas.index') }}">
                        <i class="bi bi-graph-up-arrow me-2"></i>Estadísticas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('ciudades.index') ? 'active fw-bold' : '' }}" href="{{ route('ciudades.index') }}">
                        <i class="bi bi-building me-2"></i>Ciudades
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('planes.index') ? 'active fw-bold' : '' }}" href="{{ route('planes.index') }}">
                        <i class="bi bi-gear-fill me-2"></i>Planes
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Contenido principal -->
<div class="p-4">
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
                        <p class="card-text text-muted">Gestión de zonas y cobertura de servicio.</p>
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
    .nav-link.active {
        background-color: #e9ecef;
        border-radius: 4px;
    }
</style>
@endsection
