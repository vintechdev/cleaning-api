<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'category_id' => ['nullable'],
			'name' => ['required'],
			'description' => ['required'],
			'image' => ['required', 'image'],
			'is_default_service' => ['nullable', 'boolean'],
			'active' => ['nullable', 'boolean'],
			'service_type' => ['required', Rule::in(['home','office','garden'])],
			'service_cost' => ['required'],

        ];
    }
}
