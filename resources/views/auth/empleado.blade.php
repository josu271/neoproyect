@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
<div class="container mt-5">
    {{-- Buscador y botones --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('empleados.index') }}" method="GET" class="d-flex">
            <input
                type="text"
                name="search"
                class="form-control me-2"
                placeholder="Buscar empleados..."
                value="{{ old('search', $search) }}"
            >
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </form>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Volver</a>
            <button id="btnAdd" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Añadir
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabla de empleados --}}
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Rol</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
                <tr
                    data-id="{{ $empleado->idEmpleado }}"
                    data-dni="{{ $empleado->DNI }}"
                    data-nombre="{{ $empleado->NombreEmpleado}}"
                    data-apellidop="{{ $empleado->ApellidopEmpleado }}"
                    data-apellidom="{{ $empleado->ApellidomEmpleado }}"
                    data-telefono="{{ $empleado->TelefonoEmpleado }}"
                    data-rol="{{ $empleado->RolEmpleado }}"
                    data-activo="{{ $empleado->ActivoEmpleado }}"
                >
                    <td>{{ $empleado->DNI }}</td>
                    <td>{{ $empleado->NombreEmpleado.' '.$empleado->ApellidopEmpleado.' '.$empleado->ApellidomEmpleado  }}</td>
                    <td>{{ $empleado->TelefonoEmpleado }}</td>
                    <td>{{ $empleado->RolEmpleado }}</td>
                    <td>{{ $empleado->ActivoEmpleado }}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-warning btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal"
                        >
                            Editar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('empleados.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Añadir Empleado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label for="dni" class="form-label">DNI</label>
              <input type="text" class="form-control" name="DNI" required>
          </div>
          <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" name="NombreEmpleado" required>
          </div>
          <div class="mb-3">
              <label for="apellidop" class="form-label">Apellido Paterno</label>
              <input type="text" class="form-control" name="ApellidopEmpleado" required>
          </div>
          <div class="mb-3">
              <label for="apellidom" class="form-label">Apellido Materno</label>
              <input type="text" class="form-control" name="ApellidomEmpleado">
          </div>
          <div class="mb-3">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="TelefonoEmpleado">
          </div>
          <div class="mb-3">
              <label for="rol" class="form-label">Rol</label>
              <input type="text" class="form-control" name="RolEmpleado">
          </div>
          <div class="mb-3">
              <label for="activo" class="form-label">Activo</label>
              <select class="form-control" name="ActivoEmpleado">
                  <option value="SI">Si</option>
                  <option value="NO">No</option>
              </select>
          </div>
          <div class="mb-3">
              <label for="contrasena" class="form-label">Contraseña</label>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" method="POST" action="">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Empleado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idEmpleado" id="edit-id">

          <div class="mb-3">
            <label for="edit-dni" class="form-label">DNI</label>
            <input type="text" class="form-control" name="DNI" id="edit-dni" required>
          </div>
          <div class="mb-3">
            <label for="edit-nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="NombreEmpleado" id="edit-nombre" required>
          </div>
          <div class="mb-3">
            <label for="edit-apellidop" class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" name="ApellidopEmpleado" id="edit-apellidop" required>
          </div>
          <div class="mb-3">
            <label for="edit-apellidom" class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" name="ApellidomEmpleado" id="edit-apellidom">
          </div>
          <div class="mb-3">
            <label for="edit-telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="TelefonoEmpleado" id="edit-telefono">
          </div>
          <div class="mb-3">
            <label for="edit-rol" class="form-label">Rol</label>
            <input type="text" class="form-control" name="RolEmpleado" id="edit-rol">
          </div>
          <div class="mb-3">
            <label for="edit-activo" class="form-label">Activo</label>
            <select class="form-control" name="ActivoEmpleado" id="edit-activo">
              <option value="SI">Si</option>
              <option value="NO">No</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="edit-password" class="form-label">Contraseña</label>
            <input
              type="password"
              class="form-control"
              name="ContrasenaEmpleado"
              id="edit-password"
              placeholder="Dejar en blanco para no cambiar"
            >
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

@push('scripts')
<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', function() {
    const tr        = this.closest('tr');
    const id        = tr.dataset.id;
    const dni       = tr.dataset.dni;
    const nombre    = tr.dataset.nombre;
    const apellidop = tr.dataset.apellidop;
    const apellidom = tr.dataset.apellidom;
    const telefono  = tr.dataset.telefono;
    const rol       = tr.dataset.rol;
    const activo    = tr.dataset.activo;

    // Rellenar campos
    document.getElementById('edit-id').value        = id;
    document.getElementById('edit-dni').value       = dni;
    document.getElementById('edit-nombre').value    = nombre;
    document.getElementById('edit-apellidop').value = apellidop;
    document.getElementById('edit-apellidom').value = apellidom;
    document.getElementById('edit-telefono').value  = telefono;
    document.getElementById('edit-rol').value       = rol;
    document.getElementById('edit-activo').value    = activo;
    document.getElementById('edit-password').value  = '';

    // Ajustar action para PUT
    document.getElementById('editForm').action = `/empleados/${id}`;
  });
});
</script>
@endpush
@endsection
