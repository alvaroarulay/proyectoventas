<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable =[
        'codigo_control',
        'codigo_qr', 
        'idcomprobantes',
        'condicion'
    ];
}
