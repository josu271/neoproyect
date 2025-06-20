@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
<div class="container mt-5">
    {{-- Título con icono y botones --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="bi bi-person-lines-fill me-2"></i>
            Empleados
        </h1>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left-circle me-1"></i> MENU
            </a>
            <button id="btnAdd" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i> Añadir
            </button>
        </div>
    </div>

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabla de empleados --}}
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
              <tr>
                  <th>DNI</th>
                  <th>Nombre Completo</th>
                  <th>Teléfono</th>
                  <th>Rol</th>
                  <th>Activo</th>
                  <th class="text-center">Acciones</th>
              </tr>
          </thead>
          <tbody>
              @foreach($empleados as $empleado)
                  <tr
                      data-id="{{ $empleado->idEmpleado }}"
                      data-dni="{{ $empleado->DNI }}"
                      data-nombre="{{ $empleado->NombreEmpleado }}"
                      data-apellidop="{{ $empleado->ApellidopEmpleado }}"
                      data-apellidom="{{ $empleado->ApellidomEmpleado }}"
                      data-telefono="{{ $empleado->TelefonoEmpleado }}"
                      data-rol="{{ $empleado->RolEmpleado }}"
                      data-activo="{{ $empleado->ActivoEmpleado }}"
                  >
                      <td>{{ $empleado->DNI }}</td>
                      <td>{{ "{$empleado->NombreEmpleado} {$empleado->ApellidopEmpleado} {$empleado->ApellidomEmpleado}" }}</td>
                      <td>{{ $empleado->TelefonoEmpleado ?: '-' }}</td>
                      <td>{{ $empleado->RolEmpleado ?: '-' }}</td>
                      <td>
                        @if($empleado->ActivoEmpleado == 'SI')
                          <span class="badge bg-success">Sí</span>
                        @else
                          <span class="badge bg-danger">No</span>
                        @endif
                      </td>
                      <td class="text-center">
                          <button class="btn btn-sm btn-warning btn-edit" title="Editar" data-bs-toggle="modal" data-bs-target="#editModal">
                              <i class="bi bi-pencil-square"></i>
                          </button>
                      </td>
                  </tr>
              @endforeach
          </tbody>
      </table>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('empleados.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i>
            Añadir Empleado
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">DNI</label>
            <input type="number" class="form-control" name="DNI" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Activo</label>
            <select class="form-select" name="ActivoEmpleado" required>
              <option value="SI">Sí</option>
              <option value="NO">No</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="NombreEmpleado" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" name="ApellidopEmpleado" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" name="ApellidomEmpleado">
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="tel" class="form-control" name="TelefonoEmpleado">
          </div>
          <div class="col-md-6">
            <label class="form-label">Rol</label>
            <input type="text" class="form-control" name="RolEmpleado">
          </div>
          <div class="col-12">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="ContrasenaEmpleado" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editForm" method="POST" action="{{ route('empleados.update', ['id' => '__ID__']) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>
            Editar Empleado
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <input type="hidden" id="edit-id">
          <div class="col-md-6">
            <label class="form-label">DNI</label>
            <input type="number" class="form-control" id="edit-dni" name="DNI" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Activo</label>
            <select class="form-select" id="edit-activo" name="ActivoEmpleado" required>
              <option value="SI">Sí</option>
              <option value="NO">No</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" id="edit-nombre" name="NombreEmpleado" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" id="edit-apellidop" name="ApellidopEmpleado" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" id="edit-apellidom" name="ApellidomEmpleado">
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="edit-telefono" name="TelefonoEmpleado">
          </div>
          <div class="col-md-6">
            <label class="form-label">Rol</label>
            <input type="text" class="form-control" id="edit-rol" name="RolEmpleado">
          </div>
          <div class="col-12">
            <label class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" class="form-control" id="edit-password" name="ContrasenaEmpleado">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      const tr   = this.closest('tr');
      const id   = tr.dataset.id;
      const form = document.getElementById('editForm');

      // Rellenar inputs
      document.getElementById('edit-dni').value       = tr.dataset.dni;
      document.getElementById('edit-activo').value    = tr.dataset.activo;
      document.getElementById('edit-nombre').value    = tr.dataset.nombre;
      document.getElementById('edit-apellidop').value = tr.dataset.apellidop;
      document.getElementById('edit-apellidom').value = tr.dataset.apellidom;
      document.getElementById('edit-telefono').value  = tr.dataset.telefono;
      document.getElementById('edit-rol').value       = tr.dataset.rol;
      document.getElementById('edit-password').value  = '';

      // Reemplazar marcador __ID__ en action
      form.action = form.action.replace('__ID__', id);
    });
  });
});
</script>
@endpush
