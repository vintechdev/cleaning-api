<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReportedincidentRequest extends FormRequest
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
            'userid' => ['required'],
			'booking_id' => ['required'],
			'reported_by' => ['required'],
			'type' => ['required', Rule::in(['user','provider','fraud','incident'])],
			'comments' => ['required'],
			'usernotes' => ['required'],
			'status' => ['required', Rule::in(['resolved','open','closed','inprogress'])],
			'adminnote' => ['required'],

        ];
    }
}
