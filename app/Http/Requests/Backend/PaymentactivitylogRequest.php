<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentactivitylogRequest extends FormRequest
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
            'payment_id' => ['required'],
			'user_id' => ['required'],
			'user_agent' => ['required'],
			'ip' => ['required'],
			'country' => ['required'],
			'city' => ['required'],
			'activity_log' => ['required'],
			'action' => ['nullable'],
			'detail' => ['nullable'],

        ];
    }
}
