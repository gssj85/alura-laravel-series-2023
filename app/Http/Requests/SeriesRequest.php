<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class SeriesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'min:3'],
//            'cover' => [File::image()]
        ];
    }

//    /**
//     * Get custom messages for validator errors.
//     *
//     * @return array
//     */
//    public function messages()
//    {
//        return [
//            'nome.*' => 'O campo nome é obrigatório e precisa de pelo menos 3 caracteres',
//            'nome.min' => 'O campo nome precisa de pelo menos :min caracteres'
//        ];
//    }
}
