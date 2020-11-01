<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CronjobRequest extends FormRequest
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
            'cronjob_uuid' => ['required'],
			'cronjob_url' => ['required'],
			'cronjob_path' => ['required'],
			'cronjob_schedule' => ['required', 'date_format:Y-m-d H:i:s' ],
			'cronjob_log' => ['required'],

        ];
    }
}
