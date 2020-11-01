<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingrecurringpatternRequest extends FormRequest
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
			'recurring_type' => ['nullable'],
			'recurring_separation_count' => ['nullable'],
			'max_number_of_occurrences' => ['nullable'],
			'day_of_week' => ['nullable'],
			'week_of_month' => ['nullable'],
			'day_of_month' => ['nullable'],
			'month_of_year' => ['nullable'],

        ];
    }
}
