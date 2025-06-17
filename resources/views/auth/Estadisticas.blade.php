@extends('layouts.app')

@section('title', 'Estadísticas')

@section('content')
<div class="container mt-5">
    <!-- Botón Volver -->
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">
        <i class="bi bi-arrow-left-circle me-2"></i>Volver
    </a>

    <h2 class="fw-bold mb-4 text-center">Panel de Estadísticas</h2>

    <div class="row g-4">
        <!-- Total de Clientes -->
        <div class="col-md-4">
            <div class="card shadow rounded-4 h-100 text-center py-4">
                <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                <h5 class="card-title fw-bold">Total de Clientes</h5>
                <p class="fs-3">{{ $totalClientes }}</p>
            </div>
        </div>

        <!-- Clientes Activos -->
        <div class="col-md-4">
            <div class="card shadow rounded-4 h-100 text-center py-4">
                <i class="bi bi-person-check-fill fs-1 text-success mb-3"></i>
                <h5 class="card-title fw-bold">Clientes Activos</h5>
                <p class="fs-3">{{ $clientesActivos }}</p>
            </div>
        </div>

        <!-- Ingresos Mes Actual -->
        <div class="col-md-4">
            <div class="card shadow rounded-4 h-100 text-center py-4">
                <i class="bi bi-cash-stack fs-1 text-success mb-3"></i>
                <h5 class="card-title fw-bold">Ingresos (Mes)</h5>
                <p class="fs-3">S/ {{ number_format($ingresosMes, 2) }}</p>
            </div>
        </div>

        <!-- Suscriptores por Plan -->
        <div class="col-md-6">
            <div class="card shadow rounded-4 h-100 p-4">
                <h5 class="card-title fw-bold text-center mb-3">Suscriptores por Plan</h5>
                <ul class="list-group list-group-flush">
                    @foreach($suscriptoresPorPlan as $plan => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $plan }}
                            <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Ocupación Promedio NAP -->
        <div class="col-md-6">
            <div class="card shadow rounded-4 h-100 p-4">
                <h5 class="card-title fw-bold text-center mb-3">Ocupación Promedio NAP</h5>
                <p class="fs-2 text-center">{{ $ocupacionPromedio }}%</p>
                <p class="text-muted text-center">Porcentaje promedio de ocupación de todos los NAPs</p>
            </div>
        </div>

        <!-- Tasa de Cancelación -->
        <div class="col-md-4">
            <div class="card shadow rounded-4 h-100 text-center py-4">
                <i class="bi bi-x-octagon-fill fs-1 text-danger mb-3"></i>
                <h5 class="card-title fw-bold">Tasa de Cancelación</h5>
                <p class="fs-3">{{ $tasaCancelacion }}%</p>
            </div>
        </div>

        <!-- Empleados Activos -->
        <div class="col-md-4">
            <div class="card shadow rounded-4 h-100 text-center py-4">
                <i class="bi bi-person-badge-fill fs-1 text-warning mb-3"></i>
                <h5 class="card-title fw-bold">Empleados Activos</h5>
                <p class="fs-3">{{ $empleadosActivos }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
