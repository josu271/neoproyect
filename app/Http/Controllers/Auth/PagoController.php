<?php

namespace App\Http\Controllers\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Suscripcion;
use App\Models\TipoComprobante;
use App\Models\MetodoPago;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['empleado', 'cliente', 'tipoComprobante', 'metodoPago'])
                     ->where('ActivoPago', 'Pago')
                     ->orderBy('PeriodoMes')
                     ->get();

        $pagosPendientes = Pago::with('cliente')
                                 ->where('ActivoPago', 'No Pagado')
                                 ->orderBy('PeriodoMes')
                                 ->get()
                                 ->map(function ($pago) {
                                     $cliente = Cliente::with('suscripcion.plan')->find($pago->idClientes);
                                     $pago->monto_defecto = $cliente->suscripcion->plan->precio ?? 0.00;
                                     return $pago;
                                 });

        $clientes = Cliente::with(['suscripcion.plan'])->get();
        $tiposComprobante = ['Boleta', 'Factura'];
        $metodosPago = MetodoPago::all();

        return view('auth.pagos', compact('pagos', 'pagosPendientes', 'clientes', 'tiposComprobante', 'metodosPago'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idClientes' => 'required|exists:clientes,idClientes',
            'idTipoComprobante' => 'required|exists:tipo_comprobante,idTipoComprobante',
            'idMetodoPago' => 'required|exists:metodo_pago,idMetodoPago',
            'FechaPago' => 'required|date',
            'PeriodoMes' => 'required|date_format:Y-m',
            'ActivoPago' => 'required|in:Pago'
        ]);

        $cliente = Cliente::with('suscripcion.plan')->findOrFail($request->idClientes);
        $monto = $cliente->suscripcion->plan->precio ?? 0.00;

        $tipoDoc = TipoComprobante::findOrFail($request->idTipoComprobante)->tipoDoc;

        $ultimoComprobante = TipoComprobante::where('tipoDoc', $tipoDoc)
            ->orderByDesc('idTipoComprobante')
            ->first();

        $nroSecuencia = 1;
        if ($ultimoComprobante && preg_match('/\d+$/', $ultimoComprobante->nroDoc, $matches)) {
            $nroSecuencia = (int)$matches[0] + 1;
        }

        $nuevoNroDoc = str_pad($nroSecuencia, 5, '0', STR_PAD_LEFT);

        $tipoComprobante = TipoComprobante::create([
            'tipoDoc' => $tipoDoc,
            'nroDoc' => $nuevoNroDoc,
            'monto' => $monto,
            'fecha' => now()
        ]);

        $pago = Pago::create([
            'idEmpleado' => Auth::user()->idEmpleado,
            'idClientes' => $request->idClientes,
            'idTipoComprobante' => $tipoComprobante->idTipoComprobante,
            'idMetodoPago' => $request->idMetodoPago,
            'FechaPago' => $request->FechaPago,
            'PeriodoMes' => $request->PeriodoMes,
            'ActivoPago' => 'Pago'
        ]);

        $nextMonth = Carbon::createFromFormat('Y-m', $pago->PeriodoMes)->addMonth()->format('Y-m');

        if (!Pago::where('idClientes', $pago->idClientes)->where('PeriodoMes', $nextMonth)->exists()) {
            Pago::create([
                'idEmpleado' => Auth::user()->idEmpleado,
                'idClientes' => $pago->idClientes,
                'ActivoPago' => 'No Pagado',
                'FechaPago' => Carbon::createFromFormat('Y-m', $nextMonth)->startOfMonth(),
                'PeriodoMes' => $nextMonth,
            ]);
        }

        return redirect()->route('pagos.boleta', $tipoComprobante->nroDoc);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'ActivoPago' => 'required|in:Pago,No Pagado,Cancelado'
        ]);

        $pago->ActivoPago = $request->ActivoPago;
        $pago->save();

        if ($pago->ActivoPago === 'Pago') {
            $nextMonth = Carbon::createFromFormat('Y-m', $pago->PeriodoMes)->addMonth()->format('Y-m');
            if (!Pago::where('idClientes', $pago->idClientes)->where('PeriodoMes', $nextMonth)->exists()) {
                Pago::create([
                    'idEmpleado' => Auth::user()->idEmpleado,
                    'idClientes' => $pago->idClientes,
                    'ActivoPago' => 'No Pagado',
                    'FechaPago' => Carbon::createFromFormat('Y-m', $nextMonth)->startOfMonth(),
                    'PeriodoMes' => $nextMonth,
                ]);
            }
        }

        return redirect()->route('pagos.index')->with('success', 'Estado de pago actualizado.');
    }

     public function process(Request $request)
    {
        $request->validate([
            'pagos' => 'required|array',
            'pagos.*' => 'required', // puede ser id de pago existente o id temporal (negativo)
            'idTipoComprobante' => 'required|string|in:Boleta,Factura',
            'idMetodoPago' => 'required|in:1,2,3',
            'FechaPago' => 'required|date',
            'montos' => 'required|array',
            'descripciones' => 'required|array'
        ]);

        $tipoDoc = $request->idTipoComprobante;

        $ultimo = TipoComprobante::where('tipoDoc', $tipoDoc)->orderByDesc('idTipoComprobante')->first();
        $nro = 1;
        if ($ultimo && preg_match('/\d+$/', $ultimo->nroDoc, $matches)) {
            $nro = (int)$matches[0] + 1;
        }
        $nroDocBase = $nro;

        foreach ($request->pagos as $idPago) {
            $monto = $request->montos[$idPago] ?? 0;
            $descripcion = $request->descripciones[$idPago] ?? 'Pago mensual';

            $tipoComprobante = TipoComprobante::create([
                'tipoDoc' => $tipoDoc,
                'nroDoc' => str_pad($nroDocBase++, 5, '0', STR_PAD_LEFT),
                'fecha' => now(),
                'monto' => $monto,
            ]);

            $metodoPago = MetodoPago::create([
                'tipoPago' => match($request->idMetodoPago) {
                    '1' => 'Yape',
                    '2' => 'Deposito',
                    '3' => 'Efectivo',
                },
                'Monto' => $monto,
                'fecha' => now(),
                'descripcion' => $descripcion
            ]);

            if ((int)$idPago > 0) {
                // Es un pago existente
                $pago = Pago::findOrFail($idPago);
                $pago->update([
                    'ActivoPago' => 'Pago',
                    'idTipoComprobante' => $tipoComprobante->idTipoComprobante,
                    'idMetodoPago' => $metodoPago->idMetodoPago,
                    'FechaPago' => $request->FechaPago,
                    'idEmpleado' => Auth::user()->idEmpleado
                ]);

                $nextMonth = Carbon::createFromFormat('Y-m', $pago->PeriodoMes)->addMonth()->format('Y-m');
                Pago::firstOrCreate(
                    ['idClientes' => $pago->idClientes, 'PeriodoMes' => $nextMonth],
                    [
                        'idEmpleado' => Auth::user()->idEmpleado,
                        'ActivoPago' => 'No Pagado',
                        'FechaPago' => Carbon::createFromFormat('Y-m', $nextMonth)->startOfMonth(),
                    ]
                );
            } else {
                // Es un nuevo pago (fila agregada manualmente)
                $idCliente = $request->idClientes;

                Pago::create([
                    'idEmpleado' => Auth::user()->idEmpleado,
                    'idClientes' => $idCliente,
                    'idTipoComprobante' => $tipoComprobante->idTipoComprobante,
                    'idMetodoPago' => $metodoPago->idMetodoPago,
                    'FechaPago' => $request->FechaPago,
                    'PeriodoMes' => now()->format('Y-m'),
                    'ActivoPago' => 'Pago'
                ]);
            }
        }

        return redirect()->route('pagos.boleta', $tipoComprobante->nroDoc);

    }
     public function downloadBoleta($nroDoc)
    {
        // Obtén el comprobante
        $comprobante = TipoComprobante::where('nroDoc', $nroDoc)->firstOrFail();

        // Todos los pagos que referencian este comprobante
        $pagos = Pago::with(['cliente'])
                     ->where('idTipoComprobante', $comprobante->idTipoComprobante)
                     ->get();

        // Cliente único (asumo que todos comparten el mismo cliente)
        $cliente = $pagos->first()->cliente;

        $fecha = now()->format('d/m/Y H:i');

        $data = compact('pagos', 'cliente', 'nroDoc', 'fecha');
        $alto = 200 + ($pagos->count() * 20);
$pdf = PDF::loadView('auth.boleta', $data)
          ->setPaper([0, 0, 226.77, $alto], 'portrait');
        return $pdf->download("boleta_{$nroDoc}.pdf");
    }
    public function pdf(){
        
    }
}
