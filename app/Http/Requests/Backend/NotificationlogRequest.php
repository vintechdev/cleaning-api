<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class NotificationlogRequest extends FormRequest
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
            'notification_type' => ['required'],
			'notification_message' => ['required'],
			'recipient_user_id' => ['required'],
			'status' => ['required', Rule::in(['read','unread'])],
			'action' => ['required'],
			'detail' => ['required'],

        ];
    }
}
