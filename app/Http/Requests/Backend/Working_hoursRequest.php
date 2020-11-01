<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class Working_hoursRequest extends FormRequest
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
            'provider_id' => ['required'],
			'working_days' => ['required', Rule::in(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'])],
			'start_time' => ['required'],
            'end_time' => ['required'],

        ];
    }
}
