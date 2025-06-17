<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodo_pago';
    protected $primaryKey = 'idMetodoPago';
    public $timestamps = false;

    protected $fillable = [
        'tipoPago',
        'Monto',
        'fecha',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idMetodoPago');
    }
}

