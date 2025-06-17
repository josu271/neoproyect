@extends('layouts.app')

@section('title', 'Cobertura de Servicio')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0">Cobertura de Servicio</h2>
        <div>
            <!-- Botón que abre el modal de agregar -->
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalAgregarCobertura">
                <i class="bi bi-plus-lg"></i> Agregar
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div id="map" style="height: 500px;"></div>
</div>

<!-- Modal para agregar cobertura -->
<div class="modal fade" id="modalAgregarCobertura" tabindex="-1" aria-labelledby="modalAgregarCoberturaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarCoberturaLabel">Nueva Cobertura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form action="{{ route('cobertura.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3" id="mapAgregar" style="height: 400px;"></div>
          <div class="mb-3">
            <label for="ubicacionnap" class="form-label">Ubicación (lat,lng)</label>
            <input type="text" name="ubicacionnap" id="ubicacionnap" class="form-control" readonly required>
          </div>
          <div class="row mt-3">
            <div class="col-md-4">
              <label for="maxCli" class="form-label">Max Clientes</label>
              <input type="number" name="maxCli" id="maxCli" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label for="ActiCliente" class="form-label">Clientes Activos</label>
              <input type="number" name="ActiCliente" id="ActiCliente" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label for="Estado" class="form-label">Estado</label>
              <select name="Estado" id="Estado" class="form-select" required>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para editar cobertura -->
<div class="modal fade" id="modalEditarCobertura" tabindex="-1" aria-labelledby="modalEditarCoberturaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarCoberturaLabel">Editar Cobertura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formEditar" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3" id="mapEditar" style="height: 400px;"></div>
          <div class="mb-3">
            <label for="ubicacionnap_edit" class="form-label">Ubicación (lat,lng)</label>
            <input type="text" name="ubicacionnap" id="ubicacionnap_edit" class="form-control" readonly required>
          </div>
          <div class="row mt-3">
            <div class="col-md-4">
              <label for="maxCli_edit" class="form-label">Max Clientes</label>
              <input type="number" name="maxCli" id="maxCli_edit" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label for="ActiCliente_edit" class="form-label">Clientes Activos</label>
              <input type="number" name="ActiCliente" id="ActiCliente_edit" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label for="Estado_edit" class="form-label">Estado</label>
              <select name="Estado" id="Estado_edit" class="form-select" required>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicialización del mapa principal
        var map = L.map('map').setView([-12.0464, -77.0428], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Carga marcadores y popups con botón Editar
        var coberturas = @json($coberturas);
        var markers = [];
        coberturas.forEach(function(c) {
            var parts = c.ubicacionnap.split(',');
            if(parts.length === 2) {
                var lat = parseFloat(parts[0]), lng = parseFloat(parts[1]);
                var marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(
                    `<strong>ID:</strong> ${c.idCobertura}<br>` +
                    `<strong>Max:</strong> ${c.maxCli}<br>` +
                    `<strong>Activos:</strong> ${c.ActiCliente}<br>` +
                    `<strong>Estado:</strong> ${c.Estado}<br>` +
                    `<button class="btn btn-sm btn-primary mt-2" onclick="openEdit(${c.idCobertura})">Editar</button>`
                );
                markers.push(marker);
            }
        });
        if(markers.length) {
            var group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.5));
        }

        // Función para abrir modal de agregar
        var modalAgregar = document.getElementById('modalAgregarCobertura');
        modalAgregar.addEventListener('shown.bs.modal', function() {
            if(window.mapAdd) return;
            window.mapAdd = L.map('mapAgregar').setView([0,0],2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(window.mapAdd);
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    var lat = pos.coords.latitude, lng = pos.coords.longitude;
                    window.mapAdd.setView([lat, lng], 15);
                    var markerAdd = L.marker([lat, lng], {draggable:true}).addTo(window.mapAdd);
                    document.getElementById('ubicacionnap').value = lat + ',' + lng;
                    markerAdd.on('dragend', function(e) {
                        var p = e.target.getLatLng();
                        document.getElementById('ubicacionnap').value = p.lat + ',' + p.lng;
                    });
                }, function() { alert('No se pudo obtener ubicación'); });
            } else {
                alert('Geolocalización no soportada');
            }
        });

        // Función para abrir y cargar modal de editar
        window.openEdit = function(id) {
    var c = coberturas.find(x => x.idCobertura === id);
    // Rellenar campos edición
    document.getElementById('ubicacionnap_edit').value = c.ubicacionnap;
    document.getElementById('maxCli_edit').value = c.maxCli;
    document.getElementById('ActiCliente_edit').value = c.ActiCliente;
    document.getElementById('Estado_edit').value = c.Estado;
    var coords = c.ubicacionnap.split(',').map(Number);
    // Inicializar o recrear mapa de edición
    var mapContainer = document.getElementById('mapEditar');
    if(window.mapEdit) {
        window.mapEdit.remove();
    }
    window.mapEdit = L.map('mapEditar', { center: coords, zoom: 15 });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(window.mapEdit);
    var markerEdit = L.marker(coords, {draggable:true}).addTo(window.mapEdit);
    markerEdit.on('dragend', function(e) {
        var p = e.target.getLatLng();
        document.getElementById('ubicacionnap_edit').value = p.lat + ',' + p.lng;
    });
    // Ajustar acción del formulario
    document.getElementById('formEditar').action = `/cobertura/${id}`;
    // Mostrar modal y ajustar tamaño del mapa cuando sea visible
    var modalEl = document.getElementById('modalEditarCobertura');
    var bsModal = new bootstrap.Modal(modalEl);
    modalEl.addEventListener('shown.bs.modal', function() {
        window.mapEdit.invalidateSize();
    }, { once: true });
    bsModal.show();
};
    });
</script>
@endpush
