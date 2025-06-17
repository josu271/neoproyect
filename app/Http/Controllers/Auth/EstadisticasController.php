<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Cobertura;
use App\Models\MetodoPago;
use App\Models\Suscripcion;
use App\Models\Plan;
use App\Models\Empleado;

class EstadisticasController extends Controller
{
    /**
     * Muestra el panel de estadísticas.
     */
    public function index()
    {
        // Total de clientes
        $totalClientes = Cliente::count();

        // Clientes activos
        $clientesActivos = Cliente::where('ActivoCliente', 'Activo')->count();

        // Ingresos del mes actual (sumando Monto de metodo_pago ligado a pagos)
        $hoy = Carbon::now();
        $ingresosMes = MetodoPago::join('pagos', 'metodo_pago.idMetodoPago', '=', 'pagos.idMetodoPago')
            ->whereYear('metodo_pago.fecha', $hoy->year)
            ->whereMonth('metodo_pago.fecha', $hoy->month)
            ->sum('metodo_pago.Monto');

        // Suscriptores por plan (solo activas)
        $suscriptores = Suscripcion::where('Estado', 'Activa')
            ->select('idPlan', DB::raw('COUNT(*) as count'))
            ->groupBy('idPlan')
            ->pluck('count', 'idPlan')
            ->toArray();
        $suscriptoresPorPlan = [];
        foreach ($suscriptores as $planId => $count) {
            $plan = Plan::find($planId);
            $suscriptoresPorPlan[$plan->nombrePlan] = $count;
        }

        // Ocupación promedio de NAPs
        $ocupacionPromedio = Cobertura::select(DB::raw('AVG(ActiCliente / GREATEST(maxCli,1) * 100) as avg'))
            ->value('avg');
        $ocupacionPromedio = round($ocupacionPromedio, 2);

        // Tasa de cancelación de suscripciones
        $totalSus = Suscripcion::count();
        $canceladas = Suscripcion::where('Estado', 'Cancelada')->count();
        $tasaCancelacion = $totalSus > 0 ? round($canceladas / $totalSus * 100, 2) : 0;

        // Empleados activos
        $empleadosActivos = Empleado::where('ActivoEmpleado', 'Activo')->count();

        // Retornar la vista según el nombre real de la plantilla
        return view('auth.Estadisticas', compact(
            'totalClientes',
            'clientesActivos',
            'ingresosMes',
            'suscriptoresPorPlan',
            'ocupacionPromedio',
            'tasaCancelacion',
            'empleadosActivos'
        ));
    }
}
