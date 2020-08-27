<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticuloStoreRequest extends FormRequest
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
     *  imglocal
     * @return array
     */
    public function rules()
    {
        return [
            'idcategoria'=>'required|numeric',
            'idmarca'=>'required|numeric',
            'idunidad'=>'required|numeric',
            'codigo'=>'required|string|unique:articulos,codigo',
            'nombre'=>'required|string|unique:articulos,nombre',
            'descripcion'=>'nullable|string'
        ];
    }
}
