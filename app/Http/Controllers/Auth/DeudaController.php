<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Carbon\Carbon;

class DeudaController extends Controller
{
    public function index()
    {
        $ultimoMes = Carbon::now()->copy()->startOfMonth();

        $clientes = Cliente::where('ActivoCliente', 'Activo')
            ->whereHas('suscripcion', fn($q) => $q->where('Estado', 'Activa'))
            ->with(['suscripcion', 'pagos'])
            ->get();

        $clientesDeudores = $clientes->map(function ($cliente) use ($ultimoMes) {
            $suscripcion = $cliente->suscripcion;
            $fechaInicio = $suscripcion->FechaInicio ?? $suscripcion->created_at;
            $inicio = Carbon::parse($fechaInicio)->startOfMonth();

            if ($inicio->gt($ultimoMes)) {
                return null;
            }

            $mes = $inicio->copy();
            $owed = [];

            while ($mes->lte($ultimoMes)) {
                $periodo = $mes->format('Y-m');
                $haPagado = $cliente->pagos->contains(fn($p) =>
                    $p->PeriodoMes === $periodo && $p->ActivoPago === 'Pago'
                );

                if (! $haPagado) {
                    $owed[] = $periodo;
                }
                $mes->addMonth();
            }

            if (!empty($owed)) {
                sort($owed);
                $cliente->owedMonths = $owed;
                return $cliente;
            }

            return null;
        })
        ->filter()
        ->values();

        return view('auth.deuda', compact('clientesDeudores'));
    }
}