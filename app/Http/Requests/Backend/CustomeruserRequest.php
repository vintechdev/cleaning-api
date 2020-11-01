<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomeruserRequest extends FormRequest
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
            'first_name' => ['required'],
			'last_name' => ['required'],
			'email' => ['required'],
			'password' => ['required'],
			'is_enduser' => ['required'],
			'is_provider' => ['required'],
			'date_of_birth' => ['required', 'date_format:Y-m-d H:i:s' ],
			'mobile_number' => ['required'],
			'abn' => ['nullable'],
			'description' => ['nullable'],
			// 'status' => ['required', Rule::in(['active','deactive','fraud','block'])],
			'profilepic' => ['required', 'image'],
        ];
    }
}
