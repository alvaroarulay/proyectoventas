<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaUpdateRequest extends FormRequest
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
            'nombre'=>'required|alpha|unique:categorias,nombre,'.$this->id,
            'inicial'=>'required|alpha|unique:categorias,inicial,'.$this->id,
            'descripcion'=>'nullable|string'
        ];
    }
}
