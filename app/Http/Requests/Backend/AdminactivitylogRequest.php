<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminactivitylogRequest extends FormRequest
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
            'admin_id' => ['required'],
			'admin_dec' => ['required'],
			'admin_log' => ['required'],
			'ip' => ['required'],
			'user_agent' => ['required'],
			'login_time' => ['required', 'date_format:Y-m-d H:i:s' ],
			'action' => ['nullable'],
			'detail' => ['nullable'],

        ];
    }
}
