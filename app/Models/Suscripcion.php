<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';
    protected $primaryKey = 'idSuscripcion';
    public $timestamps = false;

    protected $fillable = ['idCliente', 'idPlan', 'FechaInicio', 'Estado'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idPlan');
    }
}
