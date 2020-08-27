<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
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
            'nombre' =>'required|string|unique:personas,nombre,'.$this->id,
            'email'    => 'email|unique:personas,email,'.$this->id,
            'num_documento' => 'required|numeric|unique:personas,num_documento,'.$this->id,
            'telefono' => 'numeric',
            'direccion' => 'string'
            ];
    }
}
