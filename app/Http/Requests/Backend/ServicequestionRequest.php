<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServicequestionRequest extends FormRequest
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
			'question_type' => ['required', Rule::in(['home','office','room'])],
			'question_values' => ['required', Rule::in(['text','radio','checkbox','list'])],
			'title' => ['required'],
			'question' => ['required'],
			'description' => ['nullable'],

        ];
    }
}
