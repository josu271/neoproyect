<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'idPagos';
    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'idClientes',
        'idTipoComprobante',
        'idMetodoPago',
        'ActivoPago',
        'FechaPago',
        'PeriodoMes',
        'descripcion',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idClientes');
    }

    public function tipoComprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'idTipoComprobante');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'idMetodoPago');
    }
}