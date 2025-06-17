<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobertura extends Model
{
    protected $table = 'cobertura';
    protected $primaryKey = 'idCobertura';
    public $timestamps = false;

    protected $fillable = [
        'ubicacionnap',
        'maxCli',
        'ActiCliente',
        'Estado',
    ];

    protected $casts = [
        'maxCli' => 'integer',
        'ActiCliente' => 'integer',
        'Estado' => 'string', 
    ];
}
