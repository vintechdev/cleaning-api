<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LoginActivityLogRequest extends FormRequest
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
            'login_activity_log_uuid' => ['required'],
			'user_id' => ['required'],
			'ip' => ['nullable'],
			'type' => ['nullable'],
			'user_agent' => ['nullable'],
			'login_time' => ['nullable', 'date_format:Y-m-d H:i:s' ],
			'user_activity_log' => ['nullable'],
			'action' => ['nullable'],
			'detail' => ['nullable'],

        ];
    }
}
