<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingrequestproviderRequest extends FormRequest
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
			'provider_user_id' => ['required'],
			'status' => ['required', Rule::in(['accepted','rejected','pending'])],
			'provider_comment' => ['nullable'],
			'visible_to_enduser' => ['nullable', 'boolean'],

        ];
    }
}
