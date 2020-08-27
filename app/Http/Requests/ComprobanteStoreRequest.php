<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComprobanteStoreRequest extends FormRequest
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
     *$comprobante->nombre = $request->nombre;
     * $comprobante->num_autorizacion = $request->autorizacion;
     *   $comprobante->llave = $request->llave;
      *  $comprobante->cantidad=$request->cantidad;
       * $comprobante->fecha_lim_emision = $request->fechalim;
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'=>'required|string',
            'autorizacion'=>'nullable|numeric',
            'llave'=>'nullable|string|unique:comprobantes,llave|max:255',
            'cantidad'=>'required|numeric',
            'fechalim'=>'required|date'
        ];
    }
}
