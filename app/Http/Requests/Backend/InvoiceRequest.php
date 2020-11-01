<?php

namespace App\Http\Requests\Backend;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'invoice_uuid' => ['required'],
			'user_id' => ['required'],
			'date_printed' => ['required', 'date_format:Y-m-d H:i:s' ],
			'po_number' => ['required'],
			'invoice_number' => ['required'],
			'terms' => ['required'],
			'description' => ['required'],
			'quantity' => ['required'],
			'cost' => ['required'],
			'late_fees' => ['required'],
			'taxes' => ['required'],
			'total_due' => ['required'],

        ];
    }
}
