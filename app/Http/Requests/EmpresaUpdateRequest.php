<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *    $table->increments('id');
     *       $table->string('nombre_empresa');
      *      $table->string('nit');
       *     $table->string('representante_legal');
        *    $table->string('direccion');
         *   $table->string('telefono',20);
          *  $table->string('email',50);
           * $table->string('actividad');
          *  $table->string('image_name');
         **   $table->integer('iva');
        *    $table->integer('ganancia');
       *     $table->integer('mantenimiento');
     * @return array
     */
    public function rules()
    {
        return [
            'nombre' =>'required|string',
            'nit'=>'required|numeric',
            'representante_legal'=>'required|string',
            'direccion'=>'required|string',
            'telefono'=>'required|numeric',
            'email'    => 'required|email',
            'actividad'=>'required|string',
            'image'=>'required|string',
            'iva'=>'required|numeric',
            'mantenimiento'=>'required|numeric'
        ];
    }
}
