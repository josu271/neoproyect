@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-cash-coin me-2"></i>Pagos</h2>
        <div>
            <button class="btn btn-outline-secondary me-2" onclick="window.location.href='{{ route('dashboard') }}'">
                <i class="bi bi-arrow-left"></i> MENU
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProcessPagos">
                <i class="bi bi-plus-circle"></i> Añadir Pago
            </button>
        </div>
    </div>

    <!-- Tabla de Pagos -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Empleado</th>
                            <th>Cliente</th>
                            <th>Tipo Doc.</th>
                            <th>Método Pago</th>
                            <th>Monto</th>
                            <th>Fecha Pago</th>
                            <th>Periodo</th>
                            <th>Estado</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagos as $pago)
                            @if($pago->ActivoPago === 'Pago')
                                <tr>
                                    <td>{{ $pago->idPagos }}</td>
                                    <td>{{ optional($pago->empleado)->NombreEmpleado }} {{ optional($pago->empleado)->ApellidopEmpleado }} {{ optional($pago->empleado)->ApellidomEmpleado }}</td>
                                    <td>{{ optional($pago->cliente)->NombreCliente }} {{ optional($pago->cliente)->ApellidopCliente }} {{ optional($pago->cliente)->ApellidomCliente }}</td>
                                    <td>{{ optional($pago->tipoComprobante)->tipoDoc ?? '-' }}</td>
                                    <td>{{ optional($pago->metodoPago)->tipoPago ?? '-' }}</td>
                                    <td>{{ optional($pago->metodoPago)->Monto ? number_format($pago->metodoPago->Monto, 2) : '-' }}</td>
                                    <td>{{ $pago->FechaPago }}</td>
                                    <td>{{ $pago->PeriodoMes }}</td>
                                    <td><span class="badge bg-success">Pago</span></td>
                                    <td>
                                        <a href="{{ route('pagos.boleta', optional($pago->tipoComprobante)->nroDoc) }}"
                                           target="_blank"
                                           class="text-danger">
                                            <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Procesar Pagos Pendientes -->
    <div class="modal fade" id="modalProcessPagos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-cash-stack me-2"></i>Procesar Pagos Pendientes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Aquí añadimos ID al form -->
                <form id="formProcessPagos" method="POST" action="{{ route('pagos.process') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Buscar Cliente</label>
                            <input list="clientesList" id="clienteInput" class="form-control" placeholder="Nombre del cliente...">
                            <datalist id="clientesList">
                                @foreach($clientes as $cliente)
                                    <option data-id="{{ $cliente->idClientes }}"
                                            value="{{ $cliente->NombreCliente }} {{ $cliente->ApellidopCliente }} {{ $cliente->ApellidomCliente }}">
                                @endforeach
                            </datalist>
                            <input type="hidden" id="idClientesHidden">
                        </div>

                        <div id="pagosPendientesCliente" class="mb-4" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Pagos pendientes de <span id="nombreCliente"></span></h5>
                                <button type="button" id="btnAddRow" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus"></i> Agregar fila
                                </button>
                            </div>
                            <input type="hidden" name="idClientes" id="idClientesInput">
                            <table class="table table-sm table-bordered" id="tablaPagosPendientes">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <th style="width:110px;">Periodo</th>
                                        <th style="width:110px;">Monto (S/)</th>
                                        <th>Descripción</th>
                                        <th style="width:70px;">Sel.</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaPagosCliente"></tbody>
                            </table>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label>Tipo Comprobante</label>
                                    <select name="idTipoComprobante" class="form-select" required>
                                        @foreach($tiposComprobante as $tipo)
                                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Método Pago</label>
                                    <select name="idMetodoPago" class="form-select" required>
                                        <option value="1">Yape</option>
                                        <option value="2">Deposito</option>
                                        <option value="3">Efectivo</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago</label>
                                    <input type="date" name="FechaPago" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Empleado</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->NombreEmpleado ?? 'Empleado' }}" readonly>
                                    <input type="hidden" name="idEmpleado" value="{{ auth()->user()->idEmpleado ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!-- Al botón le añadimos data-bs-dismiss -->
                        <button id="btnProcesar" type="submit" class="btn btn-success" data-bs-dismiss="modal">
                            Procesar Pagos Seleccionados
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Datos iniciales
    const pagos           = @json($pagosPendientes);
    const tablaBody       = document.getElementById('tablaPagosCliente');
    const btnAddRow       = document.getElementById('btnAddRow');
    let   filaExtraCont   = 0;

    // Buscar cliente => mostrar pagos
    document.getElementById('clienteInput').addEventListener('input', function () {
        const val      = this.value;
        const options  = document.querySelectorAll('#clientesList option');
        let idCliente  = null;
        options.forEach(opt => { if (opt.value === val) idCliente = opt.dataset.id; });
        if (!idCliente) return;

        tablaBody.innerHTML = '';

        const pagosCliente = pagos.filter(p => p.idClientes == idCliente);
        pagosCliente.forEach(p => agregarFilaPago(p.idPagos, p.PeriodoMes, p.monto_defecto ?? '0.00', 'Pago mensual', false));

        document.getElementById('idClientesHidden').value    = idCliente;
        document.getElementById('idClientesInput').value     = idCliente;
        document.getElementById('nombreCliente').textContent = val;
        document.getElementById('pagosPendientesCliente').style.display = 'block';
    });

    // Agregar fila manual vacía
    btnAddRow?.addEventListener('click', () => {
        filaExtraCont--;
        agregarFilaPago(filaExtraCont, 'N/A', '', 'Pago mensual', true);
    });

    function agregarFilaPago(id, periodo, monto, descripcion, checked){
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="align-middle text-center">
              <input type="text" class="form-control form-control-sm text-center" value="${periodo}" readonly tabindex="-1" style="background:#f9f9f9">
            </td>
            <td>
              <input type="number" step="0.01" min="0" name="montos[${id}]" value="${monto}" class="form-control form-control-sm">
            </td>
            <td>
              <input type="text" name="descripciones[${id}]" value="${descripcion}" class="form-control form-control-sm">
            </td>
            <td class="text-center">
              <input type="checkbox" name="pagos[]" value="${id}" ${checked?'checked':''}>
            </td>
        `;
        tablaBody.appendChild(tr);
    }

    // 1) Al enviar el form, abrimos el PDF en nueva pestaña
    const form = document.getElementById('formProcessPagos');
    form.addEventListener('submit', function () {
      this.target = '_blank';
    });

    // 2) Cuando el modal termine de cerrarse, recargamos la página
    const modalEl = document.getElementById('modalProcessPagos');
    modalEl.addEventListener('hidden.bs.modal', function () {
      window.location.reload();
    });
</script>
@endsection
