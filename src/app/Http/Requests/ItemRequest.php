<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
        /* バリデーションルール用配列 */
        $rules = [];

        if ($this->has('comment')) {
            $rules['comment'] = 'required | max:255';
        }

        return $rules;
    }
}
