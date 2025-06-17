<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $primaryKey = 'idPlan';
    public $timestamps = false;

    protected $fillable = ['nombrePlan', 'precio'];

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'idPlan');
    }
}
