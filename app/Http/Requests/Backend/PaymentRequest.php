<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'booking_id' => ['required'],
			'stripe_charge_status' => ['required', Rule::in(['pending','completed'])],
			'charge_completion_datetime' => ['required', 'date_format:Y-m-d H:i:s' ],
			'payment_descriptor' => ['required'],
			'total_amount' => ['required'],
			'payout_status' => ['required', Rule::in(['processing','sent','returned','cancelled'])],
			'payout_date' => ['required', 'date_format:Y-m-d H:i:s' ],

        ];
    }
}
