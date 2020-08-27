<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'idingreso', 
        'idarticulo',
        'precio_compra',
        'precio_venta',
        'stock'
    ];
    public $timestamps = false;

}
