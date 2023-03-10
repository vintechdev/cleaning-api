<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServicecategoryRequest extends FormRequest
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
            'name' => ['required'],
			'description' => ['required'],
			'position' => ['required'],
			'active' => ['required', 'boolean'],

        ];
    }
}
