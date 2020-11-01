<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomuserRequest extends FormRequest
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
			'email' => ['required'],
			'password' => ['required'],
			'mobile_number' => ['required'],
			'social_login' => ['nullable'],
			'remember_token' => ['required'],
			'status' => ['required', Rule::in(['active','deactive','fraud','block'])],

        ];
    }
}
