@extends('layouts.app')

@section('title', 'Gestión de Planes')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Planes</h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left-circle"></i> menu
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                <i class="bi bi-plus-circle"></i> Agregar Plan
            </button>
        </div>
    </div>

    <form method="GET" action="{{ route('planes.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar plan..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio (S/.)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($planes as $plan)
                <tr>
                    <td>{{ $plan->idPlan }}</td>
                    <td>{{ $plan->nombrePlan }}</td>
                    <td>{{ number_format($plan->precio, 2) }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#editPlanModal"
                            data-id="{{ $plan->idPlan }}"
                            data-nombre="{{ $plan->nombrePlan }}"
                            data-precio="{{ $plan->precio }}"
                            data-route="{{ route('planes.update', $plan->idPlan) }}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-muted">No se encontraron planes.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-end">
        {{ $planes->withQueryString()->links() }}
    </div>
</div>

{{-- Modal: Crear Plan --}}
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('planes.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPlanLabel">Agregar Nuevo Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombrePlan" class="form-label">Nombre del Plan</label>
                        <input type="text" name="nombrePlan" class="form-control" id="nombrePlan" required>
                    </div>
                    <div class="mb-3">
                        <label for="precioPlan" class="form-label">Precio (S/.)</label>
                        <input type="number" name="precio" class="form-control" id="precioPlan" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Editar Plan --}}
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editPlanForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlanLabel">Editar Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNombrePlan" class="form-label">Nombre del Plan</label>
                        <input type="text" name="nombrePlan" class="form-control" id="editNombrePlan" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrecioPlan" class="form-label">Precio (S/.)</label>
                        <input type="number" name="precio" class="form-control" id="editPrecioPlan" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script de edición inyectado en el mismo archivo --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById('editPlanModal');
        editModal.addEventListener('show.bs.modal', event => {
            const btn = event.relatedTarget;
            const action = btn.getAttribute('data-route');
            const nombre = btn.getAttribute('data-nombre');
            const precio = btn.getAttribute('data-precio');

            const form = document.getElementById('editPlanForm');
            form.action = action;
            form.querySelector('#editNombrePlan').value = nombre;
            form.querySelector('#editPrecioPlan').value = precio;
        });
    });
</script>

@endsection
