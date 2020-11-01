<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class HackingactivityRequest extends FormRequest
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
            'count_request' => ['required'],
			'blockrequest' => ['required'],
			'trustedip' => ['required'],
			'blockip' => ['required'],
			'log_ativity' => ['required'],
			'third_party_api' => ['required'],
			'action' => ['required'],
			'detail' => ['required'],

        ];
    }
}
