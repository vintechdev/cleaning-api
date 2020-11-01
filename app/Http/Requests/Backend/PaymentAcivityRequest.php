<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentAcivityRequest extends FormRequest
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
            'stripe_connected_account_id' => ['required'],
			'user_agent' => ['required'],
			'user_ip' => ['required'],
			'device' => ['required'],
			'action' => ['required'],
			'detail' => ['required'],

        ];
    }
}
