<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
			'booking_status_id' => ['required'],
			'description' => ['nullable'],
			'is_recurring' => ['required', 'boolean'],
			'parent_event_id' => ['required'],
			'booking_datetime' => ['required', 'date_format:Y-m-d H:i:s' ],
			'booking_postcode' => ['required'],
			'booking_provider_type' => ['required', Rule::in(['freelancer','agency'])],
			'plan_type' => ['required', Rule::in(['just once','weekly','biweekly','monthly'])],
			'promocode' => ['required'],
			'completed_work_total_cost' => ['required'],
			'completed_work_service_fee' => ['required'],

        ];
    }
}
