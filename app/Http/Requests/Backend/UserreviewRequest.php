<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserreviewRequest extends FormRequest
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
            'user_review_for' => ['nullable'],
			'user_review_by' => ['nullable'],
			'booking_id' => ['required'],
			'rating' => ['required', Rule::in(['one','two','three','four','five'])],
			'comments' => ['nullable'],
			'published' => ['nullable'],

        ];
    }
}
