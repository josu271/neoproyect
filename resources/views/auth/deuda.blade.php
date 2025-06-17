```blade
{{-- File: resources/views/auth/deuda.blade.php --}}
@extends('layouts.app')

@section('title', '📋 Clientes Deudores')

@section('content')
<div class="container mt-5">
    {{-- Botón para volver al Dashboard con emoji --}}
    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-light border">
            🔙 ¡Volver al Dashboard!
        </a>
    </div>

    <div class="card border-info shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📊 Lista de Clientes con Deuda</h4>
            <span class="badge bg-warning text-dark">
                ¡Revisa aquí! ⚠️
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>#️⃣</th>
                            <th>👤 Nombre</th>
                            <th>🔔 Estado Suscripción</th>
                            <th>🗓️ Mes(es) Adeudado(s)</th>
                            <th>📍 Ubicación</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientesDeudores as $i => $cliente)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $cliente->NombreCliente }} {{ $cliente->ApellidopCliente }}</td>
                            <td>
                                @if($cliente->suscripcion->Estado == 'Activa') ❤️
                                @else ❌ @endif
                                {{ $cliente->suscripcion->Estado }}
                            </td>
                            <td>🕒 {{ implode(', ', $cliente->owedMonths) }}</td>
                            <td>📌 {{ $cliente->UbicacionCliente }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCliente{{ $cliente->idClientes }}">
                                    👀 Ver Detalles
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">✅ ¡No hay clientes deudores! 🎉</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modals con detalles y mapa --}}
    @foreach($clientesDeudores as $cliente)
    <div class="modal fade" id="modalCliente{{ $cliente->idClientes }}" tabindex="-1" aria-hidden="true" data-ubic="{{ $cliente->UbicacionCliente }}">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">👤 Detalle de {{ $cliente->NombreCliente }} {{ $cliente->ApellidopCliente }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group mb-3">
                        <li class="list-group-item">🔢 <strong>DNI:</strong> {{ $cliente->DNI }}</li>
                        <li class="list-group-item">📞 <strong>Teléfono:</strong> {{ $cliente->TelefonoCliente }}</li>
                        <li class="list-group-item">🏠 <strong>Ubicación:</strong> {{ $cliente->UbicacionCliente }}</li>
                        <li class="list-group-item">🟢 <strong>Estado Cliente:</strong> {{ $cliente->ActivoCliente }}</li>
                        <li class="list-group-item">💳 <strong>Estado Suscripción:</strong> {{ $cliente->suscripcion->Estado }}</li>
                        <li class="list-group-item">⏳ <strong>Mes(es) Adeudado(s):</strong> {{ implode(', ', $cliente->owedMonths) }}</li>
                    </ul>
                    <div id="map{{ $cliente->idClientes }}" style="height:300px; width:100%;" class="rounded"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Leaflet JS/CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', () => {
            if (!modal.dataset.mapLoaded) {
                const id = modal.id.replace('modalCliente','');
                const ubic = modal.dataset.ubic;
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(ubic)}`)
                    .then(r => r.json())
                    .then(data => {
                        if(data.length){
                            const {lat, lon} = data[0];
                            const map = L.map(`map${id}`).setView([lat,lon],14);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ attribution:'&copy; OpenStreetMap' }).addTo(map);
                            L.marker([lat,lon]).addTo(map);
                            modal.dataset.mapLoaded = true;
                        }
                    });
            }
        });
    });
</script>
```
