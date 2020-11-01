<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PayoutRequest extends FormRequest
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
            'payout_datetime' => ['required', 'date_format:Y-m-d H:i:s' ],
			'payout_amount' => ['required'],
			'provider_user_id' => ['required'],
			'payout_status' => ['required', Rule::in(['processing','sent','returned','cancelled'])],

        ];
    }
}
