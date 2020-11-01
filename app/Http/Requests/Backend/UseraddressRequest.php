<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UseraddressRequest extends FormRequest
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
			'type' => ['required', Rule::in(['home','office','business'])],
			'address_line1' => ['required'],
			'address_line2' => ['required'],
			'subrub' => ['nullable'],
			'state' => ['required'],
			'postcode' => ['required'],
			'country' => ['required'],

        ];
    }
}
