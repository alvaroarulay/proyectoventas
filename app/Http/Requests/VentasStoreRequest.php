<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentasStoreRequest extends FormRequest
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
            'idcliente'=>'required|numeric',
            'num_comprobante'=>'required|numeric',
            'impuesto'=>'required|numeric',
            'total'=>'required|numeric',
            'estado'=>'required|alpha'
        ];
    }
}
