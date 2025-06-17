<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Empleado extends Authenticatable
{
    protected $table = 'empleado';
    protected $primaryKey = 'idEmpleado';
    public $timestamps = false;

    protected $fillable = [
        'DNI',
        'NombreEmpleado',
        'ApellidopEmpleado',
        'ApellidomEmpleado',
        'TelefonoEmpleado',
        'RolEmpleado',
        'ActivoEmpleado',
        'ContrasenaEmpleado',
    ];

    protected $hidden = ['ContrasenaEmpleado'];

    public function getAuthPassword()
    {
        return $this->ContrasenaEmpleado;
    }

   
}
