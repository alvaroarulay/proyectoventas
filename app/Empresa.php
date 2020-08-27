<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'configuraciones';
    protected $fillable = ['nombre_empresa','nit','representante_legal','direccion','telefono','email','actividad','iva','ganacia','mantenimiento'];
}
