<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable =[
        'idcategoria','idmarca','idunidad','codigo','nombre','image','stock','precio','descripcion','condicion'
    ];
    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
    public function marca(){
        return $this->belongsTo('App\Marcas');
    }
    public function unidad(){
        return $this->belongsTo('App\Unidades');
    }
}
