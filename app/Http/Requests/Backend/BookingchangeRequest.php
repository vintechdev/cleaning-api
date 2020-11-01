<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingchangeRequest extends FormRequest
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
			'is_rescheduled' => ['nullable', 'boolean'],
			'is_cancelled' => ['nullable', 'boolean'],
			'booking_date' => ['required', 'date_format:Y-m-d H:i:s' ],
			'booking_time' => ['required', 'date_format:Y-m-d H:i:s' ],
			'number_of_hours' => ['nullable'],
			'agreed_service_amount' => ['nullable'],
			'comments' => ['nullable'],
			'changed_by_user' => ['required'],

        ];
    }
}
