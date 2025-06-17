<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suscripcion;
use App\Models\Pago;
use Illuminate\Support\Carbon;

class GenerarPagosMensuales extends Command
{
    protected $signature = 'pagos:generar-mensuales';
    protected $description = 'Genera registros “No Pagado” para cada suscripción activa que aún no tenga pago en el mes actual.';

    public function handle()
    {
        $mesActual = Carbon::now()->format('Y-m');
        $hoyInicio = Carbon::now()->startOfMonth();

        Suscripcion::where('Estado', 'Activa')->get()->each(function($sus) use ($mesActual, $hoyInicio) {
            $clienteId = $sus->idCliente;

            // Si ya existe un pago para este cliente y mes, se salta
            if (Pago::where('idClientes', $clienteId)
                    ->where('PeriodoMes', $mesActual)
                    ->exists()) {
                return;
            }

            // Crear pago pendiente “No Pagado”
            Pago::create([
                'idEmpleado'   => 1,                 // si no estás en contexto Auth, pon un empleado genérico
                'idClientes'   => $clienteId,
                'ActivoPago'   => 'No Pagado',
                'FechaPago'    => $hoyInicio,
                'PeriodoMes'   => $mesActual,
            ]);

            $this->info("Creado No Pagado para cliente {$clienteId} – periodo {$mesActual}");
        });

        $this->info('Pagos mensuales pendientes generados correctamente.');
    }
}
