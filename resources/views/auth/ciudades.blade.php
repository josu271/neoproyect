{{-- resources/views/auth/ciudades.blade.php --}}
@extends('layouts.app')

@section('title', 'Ciudades')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gestión de Ciudades</h2>
        <div class="btn-group">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="bi bi-plus-circle-fill me-1"></i> Añadir Ciudad
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle-fill me-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- Búsqueda --}}
    <form action="{{ route('ciudades.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar ciudad..." value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search me-1"></i> Buscar
            </button>
        </div>
    </form>

    {{-- Tabla de Ciudades --}}
    <div class="table-responsive shadow-sm rounded-4">
        <table class="table table-hover text-center mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ciudades as $ciudad)
                <tr>
                    <td>{{ $ciudad->idCiudad }}</td>
                    <td>{{ $ciudad->NombreCiudad }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $ciudad->idCiudad }}">
                            <i class="bi bi-pencil-fill me-1"></i> Editar
                        </button>
                        <form action="{{ route('ciudades.destroy', $ciudad->idCiudad) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar ciudad?')">
                                <i class="bi bi-trash-fill me-1"></i> Eliminar
                            </button>
                        </form>

                        {{-- Modal Editar --}}
                        <div class="modal fade" id="modalEdit{{ $ciudad->idCiudad }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $ciudad->idCiudad }}" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalEditLabel{{ $ciudad->idCiudad }}">Editar Ciudad</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                              </div>
                              <form action="{{ route('ciudades.update', $ciudad->idCiudad) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label for="NombreCiudad{{ $ciudad->idCiudad }}" class="form-label">Nombre</label>
                                    <input type="text" name="NombreCiudad" id="NombreCiudad{{ $ciudad->idCiudad }}" class="form-control @error('NombreCiudad') is-invalid @enderror" value="{{ old('NombreCiudad', $ciudad->NombreCiudad) }}">
                                    @error('NombreCiudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-warning">Guardar cambios</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-muted">No hay ciudades</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $ciudades->withQueryString()->links() }}
    </div>
</div>

{{-- Modal Crear --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCreateLabel">Crear Ciudad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form action="{{ route('ciudades.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="NombreCiudadNew" class="form-label">Nombre</label>
            <input type="text" name="NombreCiudad" id="NombreCiudadNew" class="form-control @error('NombreCiudad') is-invalid @enderror" value="{{ old('NombreCiudad') }}">
            @error('NombreCiudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

{{-- Estilos opcionales --}}
<style>
    .btn-group .btn { min-width: 140px; }
</style>
