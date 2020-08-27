<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvedorUpdateRequest extends FormRequest
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
            'nombre'=>'required|string|unique:personas,nombre,'.$this->id,
            'num_documento'=>'required|numeric|unique:personas,num_documento,'.$this->id,
            'direccion'=>'nullable|string',
            'telefono'=>'required|numeric',
            'contacto'=>'required|string|unique:proveedores,contacto,'.$this->id,
            'telefono_contacto'=>'required|numeric'
        ];
    }
}
