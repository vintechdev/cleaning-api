<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EndusermetadatumRequest extends FormRequest
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
			'status' => ['required', Rule::in(['avtive','block','expire'])],
			'card_number' => ['required'],
			'card_name' => ['required'],
			'user_card_type' => ['required', Rule::in(['visa','master','international'])],
			'card_cvv' => ['required'],
			'user_card_expiry' => ['required', 'date_format:Y-m-d H:i:s' ],
			'user_card_last_four' => ['required'],
			'user_stripe_customer_id' => ['required'],

        ];
    }
}
