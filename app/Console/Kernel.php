<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\GenerarPagosMensuales::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Ejecutar el 1° de cada mes a las 00:05
        $schedule->command('pagos:generar-mensuales')
                 ->monthlyOn(1, '00:05');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
