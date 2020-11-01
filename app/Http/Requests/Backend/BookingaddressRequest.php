<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingaddressRequest extends FormRequest
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
			'address_line1' => ['nullable'],
			'address_line2' => ['required'],
			'subrub' => ['nullable'],
			'state' => ['required'],
			'postcode' => ['required'],
			'country' => ['required'],

        ];
    }
}
