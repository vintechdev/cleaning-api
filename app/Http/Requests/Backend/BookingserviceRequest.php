<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingserviceRequest extends FormRequest
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
			'service_id' => ['required'],
			'initial_number_of_hours' => ['nullable'],
			'initial_service_cost' => ['required'],
			'final_number_of_hours' => ['nullable'],
			'final_service_cost' => ['required'],

        ];
    }
}
