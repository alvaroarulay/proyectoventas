<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    //protected $table = 'dosificaciones';
    //protected $primaryKey = 'id';
    
    protected $fillable = ['nombre','num_autorizacion','llave','cantidad','estado','fecha_lim_emision'];
    protected $dates = ['created_at', 'updated_at', 'fecha_lim_emision'];
}
