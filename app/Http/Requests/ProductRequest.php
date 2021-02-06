<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = [
            'name' => 'required|min:3|max:255',
            'code' => 'required|min:3|max:255|unique:products,code',
            'category_id' => 'required',
            'price' => 'required|numeric|min:1',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'price.required' => 'Цена не может быть пустой',
            'required' => 'Поле :attribute обязательно для заполнения',
            'min' => 'Поле :attribute должно содержать минимум :min символов',
            'code.min' => 'Поле "Код" должно содержать минимально 3 символа',
        ];
    }
}
