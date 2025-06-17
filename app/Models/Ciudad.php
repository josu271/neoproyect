<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $primaryKey = 'idCiudad';
    public $timestamps = false;

    protected $fillable = ['NombreCiudad'];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'idCiudad');
    }
}
