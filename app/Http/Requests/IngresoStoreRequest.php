<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngresoStoreRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idproveedor' =>'required|numeric',
            'tipo_comprobante'=>'required|alpha',
            'serie_comprobante' =>'required|numeric',
            'num_comprobante' =>'required|numeric',
            'total' =>'required|numeric',
        ];
    }
}
