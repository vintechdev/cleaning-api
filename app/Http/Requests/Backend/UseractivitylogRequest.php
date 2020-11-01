<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UseractivitylogRequest extends FormRequest
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
            'user_id' => ['required'],
			'login_id' => ['required'],
			'user_ip' => ['required'],
			'user_times' => ['required', 'date_format:Y-m-d H:i:s' ],
			'user_agent' => ['required'],
			'description' => ['required'],
			'action' => ['nullable'],
			'detail' => ['nullable'],

        ];
    }
}
