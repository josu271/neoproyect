<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Empleado;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EmpleadoLoginTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;  // <-- añadimos WithoutMiddleware

    public function test_empleado_puede_iniciar_sesion_con_credenciales_validas()
    {
        // 1) Creo el empleado de prueba
        $plainPassword = 'pass1234';
        $empleado = Empleado::create([
            'DNI'                => 12345678,
            'NombreEmpleado'     => 'Juan',
            'ApellidopEmpleado'  => 'Pérez',
            'ApellidomEmpleado'  => 'García',
            'TelefonoEmpleado'   => '987654321',
            'RolEmpleado'        => 'admin',
            'ActivoEmpleado'     => 'S',
            'ContrasenaEmpleado' => bcrypt($plainPassword),
        ]);

        // 2) Hago POST sin preocuparme de CSRF
        $response = $this->post(route('login.attempt'), [
            'DNI'      => (string) $empleado->DNI,
            'password' => $plainPassword,
        ]);

        // 3) Verifico redirect y auth
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($empleado);
    }

    public function test_empleado_no_puede_iniciar_sesion_con_password_incorrecta()
    {
        // 1) Creo otro empleado
        $empleado = Empleado::create([
            'DNI'                => 87654321,
            'NombreEmpleado'     => 'María',
            'ApellidopEmpleado'  => 'López',
            'ApellidomEmpleado'  => 'Ruiz',
            'TelefonoEmpleado'   => '912345678',
            'RolEmpleado'        => 'user',
            'ActivoEmpleado'     => 'S',
            'ContrasenaEmpleado' => bcrypt('correcta123'),
        ]);

        // 2) Intento login con pass equivocada
        $response = $this->post(route('login.attempt'), [
            'DNI'      => (string) $empleado->DNI,
            'password' => 'otraClave',
        ]);

        // 3) Debería redirigir de nuevo con errores en session
        $response->assertSessionHasErrors(); 
        $this->assertGuest();
    }
}
