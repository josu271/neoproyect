<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idClientes';
    public $timestamps = false;

    protected $fillable = [
        'DNI', 'NombreCliente', 'ApellidopCliente', 'ApellidomCliente',
        'TelefonoCliente', 'UbicacionCliente', 'idCiudad', 'ActivoCliente'
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'idCiudad');
    }

    public function suscripcion()
    {
        return $this->hasOne(Suscripcion::class, 'idCliente');
    }
    public function pagos()
{
    return $this->hasMany(Pago::class, 'idClientes');
}

public function hasPagadoMes($mes)
{
    return $this->pagos()
        ->where('PeriodoMes', $mes)
        ->where('ActivoPago', 'Pago')
        ->exists();
}
}
