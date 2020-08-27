<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComprobanteUpdateRequest extends FormRequest
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
            'nombre'=>'required|string',
            'autorizacion'=>'nullable|numeric',
            'llave'=>'nullable|string|unique:comprobantes,llave|max:255,'.$this->id,
            'cantidad'=>'required|numeric',
            'fechalim'=>'required|date'
        ];
    }
}
