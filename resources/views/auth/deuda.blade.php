{{-- File: resources/views/auth/deuda.blade.php --}}
@extends('layouts.app')

@section('title', 'Clientes Deudores')

@section('content')
<div class="container mt-5">
    {{-- Volver al Dashboard --}}
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-2"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex align-items-center border-bottom">
            <i class="bi bi-file-earmark-minus-fill fs-3 text-danger me-3"></i>
            <h4 class="mb-0 fw-bold text-secondary">Listado de Clientes Deudores</h4>
            <span class="badge bg-warning text-dark ms-auto">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> ¡Revisar deuda!
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr class="text-uppercase">
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Estado Suscripción</th>
                            <th scope="col">Meses Adeudados</th>
                            <th scope="col">Ubicación</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientesDeudores as $i => $cliente)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-start">{{ $cliente->NombreCliente }} {{ $cliente->ApellidopCliente }}</td>
                            <td>
                                @if($cliente->suscripcion->Estado == 'Activa')
                                    <i class="bi bi-heart-fill text-success me-1"></i>
                                    <span class="text-success">Activa</span>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                    <span class="text-danger">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                @foreach($cliente->owedMonths as $month)
                                    <span class="badge bg-secondary me-1">{{ $month }}</span>
                                @endforeach
                            </td>
                            <td>{{ $cliente->UbicacionCliente }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCliente{{ $cliente->idClientes }}">
                                    <i class="bi bi-eye-fill"></i> Ver
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-success">
                                <i class="bi bi-check-circle-fill me-2"></i> No hay clientes deudores
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modales de detalle con mapa --}}
    @foreach($clientesDeudores as $cliente)
    <div class="modal fade" id="modalCliente{{ $cliente->idClientes }}" tabindex="-1" aria-hidden="true" data-ubic="{{ $cliente->UbicacionCliente }}">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-white border-bottom">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-lines-fill me-2 text-info"></i>
                        Detalle de {{ $cliente->NombreCliente }} {{ $cliente->ApellidopCliente }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>DNI:</strong> {{ $cliente->DNI }}</li>
                        <li class="list-group-item"><strong>Teléfono:</strong> {{ $cliente->TelefonoCliente }}</li>
                        <li class="list-group-item"><strong>Ubicación:</strong> {{ $cliente->UbicacionCliente }}</li>
                        <li class="list-group-item"><strong>Estado Cliente:</strong> {{ $cliente->ActivoCliente }}</li>
                        <li class="list-group-item"><strong>Estado Suscripción:</strong> {{ $cliente->suscripcion->Estado }}</li>
                        <li class="list-group-item">
                            <strong>Meses Adeudados:</strong>
                            @foreach($cliente->owedMonths as $month)
                                <span class="badge bg-secondary me-1">{{ $month }}</span>
                            @endforeach
                        </li>
                    </ul>
                    <div id="map{{ $cliente->idClientes }}" style="height: 300px; width: 100%;" class="rounded"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Leaflet JS/CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha512-sA+z0p5H..." crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha512-kZUN3..." crossorigin=""></script>
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
