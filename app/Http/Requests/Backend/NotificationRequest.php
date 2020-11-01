<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'notification_type' => ['required'],
			'notification_name' => ['required'],
			'notification_description' => ['required'],
			'allow_sms' => ['required', 'boolean'],
			'allow_email' => ['required', 'boolean'],
			'allow_push' => ['required', 'boolean'],

        ];
    }
}
