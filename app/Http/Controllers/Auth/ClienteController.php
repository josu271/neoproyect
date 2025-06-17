<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
    
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Ciudad;
use App\Models\Suscripcion;
use App\Models\Plan;

use App\Models\Pago;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['ciudad', 'suscripcion.plan'])->get();
        $ciudades = Ciudad::all();
        $planes = Plan::all();
        return view('auth.clientes', compact('clientes', 'ciudades', 'planes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'DNI' => 'required|unique:clientes,DNI',
            'NombreCliente' => 'required',
            'ApellidopCliente' => 'required',
            'ApellidomCliente' => 'required',
            'TelefonoCliente' => 'required',
            'UbicacionCliente' => 'required',
            'idCiudad' => 'required|exists:ciudades,idCiudad',
            'idPlan' => 'required|exists:planes,idPlan',
        ]);

        // Crear cliente
        $cliente = Cliente::create($request->only([
            'DNI', 'NombreCliente', 'ApellidopCliente', 'ApellidomCliente',
            'TelefonoCliente', 'UbicacionCliente', 'idCiudad'
        ]) + ['ActivoCliente' => 'Activo']);

        // Crear suscripción
        Suscripcion::create([
            'idCliente' => $cliente->idClientes,
            'idPlan' => $request->idPlan,
            'FechaInicio' => now(),
            'Estado' => 'Activa',
        ]);
        
        //pago
        Pago::create([
    'idEmpleado'   => Auth::id(),
    'idClientes'   => $cliente->idClientes,
    'ActivoPago'   => 'No Pagado',
    'FechaPago'    => now(),
    'PeriodoMes'   => now()->format('Y-m'),
]);

// Primer “No Pagado” del mes siguiente:
$proximo = Carbon::now()->addMonth()->startOfMonth();
Pago::create([
    'idEmpleado'   => Auth::id(),
    'idClientes'   => $cliente->idClientes,
    'ActivoPago'   => 'No Pagado',
    'FechaPago'    => $proximo,
    'PeriodoMes'   => $proximo->format('Y-m'),
]);

        return redirect()->route('clientes.index')->with('success', 'Cliente añadido correctamente.');
    }

    public function update(Request $request, $id)
{
    // 1) Validación como ya tienes para Cliente:
    $request->validate([
        'NombreCliente'      => 'required',
        'ApellidopCliente'   => 'required',
        'ApellidomCliente'   => 'required',
        'TelefonoCliente'    => 'required',
        'UbicacionCliente'   => 'required',
        'idCiudad'           => 'required|exists:ciudades,idCiudad',
        'idSuscripcion'      => 'required|exists:suscripciones,idSuscripcion',
        'EstadoSuscripcion'  => 'required|in:Activa,Suspendida,Cancelada',
    ]);

    // 2) Actualizas datos del cliente
    $cliente = Cliente::findOrFail($id);
    $cliente->update($request->only([
        'NombreCliente','ApellidopCliente','ApellidomCliente',
        'TelefonoCliente','UbicacionCliente','idCiudad'
    ]));

    // 3) Actualizas el estado de la suscripción
    $sus = Suscripcion::findOrFail($request->input('idSuscripcion'));
    $sus->Estado = $request->input('EstadoSuscripcion');
    $sus->save();

    return redirect()->route('clientes.index')
                    ->with('success', 'Cliente y suscripción actualizados exitosamente.');
}

}
