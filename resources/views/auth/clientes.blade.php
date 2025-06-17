@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="container py-5">
    <!-- ======= Encabezado & acciones ======= -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Clientes</h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Retroceder
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddCliente">
                <i class="bi bi-plus-circle"></i> Añadir Cliente
            </button>
        </div>
    </div>

    <!-- ======= Buscador ======= -->
    <div class="input-group mb-4 shadow-sm">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" class="form-control border-start-0" placeholder="Buscar cliente..." id="searchInput">
    </div>

    <!-- ======= Tabla ======= -->
    <div class="card shadow-sm mb-5">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Teléfono</th>
                            <th>Ubicación</th>
                            <th>Ciudad</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr data-bs-toggle="modal" data-bs-target="#modalViewCliente{{ $cliente->idClientes }}" style="cursor: pointer;">
                            <td>{{ $cliente->DNI }}</td>
                            <td>{{ $cliente->NombreCliente }} {{ $cliente->ApellidopCliente }} {{ $cliente->ApellidomCliente }}</td>
                            <td>{{ $cliente->TelefonoCliente }}</td>
                            <td>{{ $cliente->UbicacionCliente }}</td>
                            <td>{{ $cliente->ciudad->NombreCiudad ?? '-' }}</td>
                            <td>{{ $cliente->suscripcion->plan->nombrePlan ?? '-' }}</td>
                            <td>
                                @php $estado = $cliente->suscripcion->Estado ?? null; @endphp
                                @if ($estado === 'Activa')
                                    <span class="badge bg-success">Activa</span>
                                @elseif ($estado === 'Suspendida')
                                    <span class="badge bg-warning text-dark">Suspendida</span>
                                @elseif ($estado === 'Cancelada')
                                    <span class="badge bg-danger">Cancelada</span>
                                @else
                                    <span class="badge bg-secondary">Desconocido</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditCliente{{ $cliente->idClientes }}"
                                        onclick="event.stopPropagation();">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- ======= Modal VER ======= -->
                        <div class="modal fade" id="modalViewCliente{{ $cliente->idClientes }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Ver Cliente</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">DNI</label>
                                                <input type="text" class="form-control" value="{{ $cliente->DNI }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" value="{{ $cliente->NombreCliente }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" class="form-control" value="{{ $cliente->TelefonoCliente }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Apellido Paterno</label>
                                                <input type="text" class="form-control" value="{{ $cliente->ApellidopCliente }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Apellido Materno</label>
                                                <input type="text" class="form-control" value="{{ $cliente->ApellidomCliente }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Ciudad</label>
                                                <input type="text" class="form-control" value="{{ $cliente->ciudad->NombreCiudad ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Plan</label>
                                                <input type="text" class="form-control" value="{{ $cliente->suscripcion->plan->nombrePlan ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Estado Suscripción</label>
                                                <input type="text" class="form-control" value="{{ $cliente->suscripcion->Estado ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Ubicación</label>
                                                <input type="text" class="form-control mb-2" value="{{ $cliente->UbicacionCliente }}" readonly>
                                                <div id="mapView{{ $cliente->idClientes }}" class="rounded" style="height: 300px; width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ======= Modal EDITAR ======= -->
                        <div class="modal fade" id="modalEditCliente{{ $cliente->idClientes }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Cliente</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('clientes.update', $cliente->idClientes) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">DNI</label>
                                                    <input type="text" class="form-control" value="{{ $cliente->DNI }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" name="NombreCliente" class="form-control" value="{{ $cliente->NombreCliente }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Teléfono</label>
                                                    <input type="text" name="TelefonoCliente" class="form-control" value="{{ $cliente->TelefonoCliente }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Apellido Paterno</label>
                                                    <input type="text" name="ApellidopCliente" class="form-control" value="{{ $cliente->ApellidopCliente }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Apellido Materno</label>
                                                    <input type="text" name="ApellidomCliente" class="form-control" value="{{ $cliente->ApellidomCliente }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Ciudad</label>
                                                    <select name="idCiudad" class="form-select" required>
                                                        @foreach($ciudades as $ci)
                                                            <option value="{{ $ci->idCiudad }}" {{ $ci->idCiudad == $cliente->idCiudad ? 'selected' : '' }}>
                                                                {{ $ci->NombreCiudad }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Plan</label>
                                                    <select name="idPlan" class="form-select" required>
                                                        @foreach($planes as $pl)
                                                            <option value="{{ $pl->idPlan }}" {{ $pl->idPlan == ($cliente->suscripcion->idPlan ?? '') ? 'selected' : '' }}>
                                                                {{ $pl->nombrePlan }} ({{ $pl->precio }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @php $sus = $cliente->suscripcion; @endphp
                                                <div class="col-md-4">
                                                    <label class="form-label">Estado Suscripción</label>
                                                    <input type="hidden" name="idSuscripcion" value="{{ $sus->idSuscripcion }}">
                                                    <select name="EstadoSuscripcion" class="form-select" required>
                                                        <option value="Activa"     {{ $sus->Estado=='Activa' ? 'selected' : '' }}>Activa</option>
                                                        <option value="Suspendida" {{ $sus->Estado=='Suspendida' ? 'selected' : '' }}>Suspendida</option>
                                                        <option value="Cancelada"  {{ $sus->Estado=='Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Ubicación</label>
                                                    <input type="text" id="ubicacionEdit{{ $cliente->idClientes }}" name="UbicacionCliente" class="form-control" value="{{ $cliente->UbicacionCliente }}" required>
                                                    <small class="text-muted">Da clic en el mapa para actualizar</small>
                                                    <div id="mapEdit{{ $cliente->idClientes }}" class="rounded mt-2" style="height: 300px; width: 100%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======= Modal AÑADIR ======= -->
<div class="modal fade" id="modalAddCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-plus-square me-2"></i>Añadir Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('clientes.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <input type="text" name="DNI" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="NombreCliente" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="TelefonoCliente" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" name="ApellidopCliente" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" name="ApellidomCliente" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ciudad</label>
                            <select name="idCiudad" class="form-select" required>
                                <option value="" disabled selected>Selecciona una ciudad</option>
                                @foreach($ciudades as $ci)
                                    <option value="{{ $ci->idCiudad }}">{{ $ci->NombreCiudad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Plan</label>
                            <select name="idPlan" class="form-select" required>
                                <option value="" disabled selected>Selecciona un plan</option>
                                @foreach($planes as $pl)
                                    <option value="{{ $pl->idPlan }}">{{ $pl->nombrePlan }} ({{ $pl->precio }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ubicación</label>
                            <input type="text" id="ubicacionAdd" name="UbicacionCliente" class="form-control" placeholder="Ej: -16.4,-71.5" required>
                            <small class="text-muted">El mapa intentará usar tu ubicación actual</small>
                            <div id="mapAdd" class="rounded mt-2" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======= Scripts ======= -->
<script>
    // --- Buscador ---------------------------------------------------------
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    // --- Leaflet helpers --------------------------------------------------
   function initBasicMap(targetId, inputId, lat = -9.19, lng = -75.0152, zoom = 5, editable = true, useGeolocation = false, markerDraggable = true) {
    const map = L.map(targetId, { zoomControl: editable }).setView([lat, lng], zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let marker = L.marker([lat, lng], { draggable: markerDraggable }).addTo(map);

    if (inputId) {
        document.getElementById(inputId).value = lat.toFixed(6) + ',' + lng.toFixed(6);
    }

    if (editable && markerDraggable) {
        function setMarker(latlng) {
            marker.setLatLng(latlng);
            if (inputId) {
                document.getElementById(inputId).value = latlng.lat.toFixed(6) + ',' + latlng.lng.toFixed(6);
            }
        }

        marker.on('dragend', e => setMarker(e.target.getLatLng()));
        map.on('click', e => setMarker(e.latlng));
    }

    if (!editable) {
        map.dragging.disable();
        map.touchZoom.disable();
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();
        map.boxZoom.disable();
        map.keyboard.disable();
    }

    if (editable && useGeolocation && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const userLatLng = [position.coords.latitude, position.coords.longitude];
                map.setView(userLatLng, 14);
                if (markerDraggable) {
                    marker.setLatLng(userLatLng);
                }
            }
        );
    }

    return map;
}


    // Mapa para modal ADD
    const mapAdd = initBasicMap('mapAdd', 'ubicacionAdd', -9.19, -75.0152, 14, true, true);
    document.getElementById('modalAddCliente')
        .addEventListener('shown.bs.modal', function () {
            mapAdd.invalidateSize();
        });

    // Mapas para cada cliente (VER y EDITAR)
    @foreach($clientes as $cliente)
        const coords{{ $cliente->idClientes }} = @if($cliente->UbicacionCliente)
            [{{ explode(',', $cliente->UbicacionCliente)[0] }}, {{ explode(',', $cliente->UbicacionCliente)[1] }}]
        @else
            [-9.19, -75.0152]
        @endif;

        // --- VER (no editable) ---
        const mapView{{ $cliente->idClientes }} = initBasicMap(
    'mapView{{ $cliente->idClientes }}',
    null,
    coords{{ $cliente->idClientes }}[0],
    coords{{ $cliente->idClientes }}[1],
    14,
    true, // editable = true (para que el mapa sea interactivo)
    false, // useGeolocation
    false // markerDraggable = false (marcador estático)
);
        document.getElementById('modalViewCliente{{ $cliente->idClientes }}')
            .addEventListener('shown.bs.modal', function () {
                mapView{{ $cliente->idClientes }}.invalidateSize();
            });

        // --- EDITAR (editable) ---
        const mapEdit{{ $cliente->idClientes }} = initBasicMap(
            'mapEdit{{ $cliente->idClientes }}',
            'ubicacionEdit{{ $cliente->idClientes }}',
            coords{{ $cliente->idClientes }}[0],
            coords{{ $cliente->idClientes }}[1],
            14,
            true
        );
        document.getElementById('modalEditCliente{{ $cliente->idClientes }}')
            .addEventListener('shown.bs.modal', function () {
                mapEdit{{ $cliente->idClientes }}.invalidateSize();
            });
    @endforeach
</script>
@endsection
