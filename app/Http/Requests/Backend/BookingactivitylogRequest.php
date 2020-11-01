<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingactivitylogRequest extends FormRequest
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
			'user_id' => ['required'],
			'status' => ['required', Rule::in(['requested','accepted','arrived','completed','cancelled','rejected'])],
			'is_recurring' => ['required', 'boolean'],
			'booking_date' => ['required', 'date_format:Y-m-d H:i:s' ],
			'booking_time' => ['required', 'date_format:Y-m-d H:i:s' ],
			'booking_postcode' => ['required'],
			'action' => ['nullable'],
			'detail' => ['nullable'],

        ];
    }
}
