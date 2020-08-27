<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marcas extends Model
{
    protected $fillable = ['nombre','inicial','descripcion','condicion'];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
