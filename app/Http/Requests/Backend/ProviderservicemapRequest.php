<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProviderservicemapRequest extends FormRequest
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
            'service_id' => ['required'],
			'provider_user_id' => ['required'],
			'experience_in_months' => ['nullable'],
			'service_offering_description' => ['required'],
			'type' => ['required', Rule::in(['billingrateperhour','billingrateonetime'])],
			'amount' => ['required'],

        ];
    }
}
