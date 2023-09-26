<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'category' => 'nullable|string',
            'note' => 'required|string',
            'client_id' => 'required|integer',
            'city' => 'required|string',
            'billing_address' => 'required|email',
            'country' => 'nullable|string',
            'postal_code' => 'required|string',
        ];
    }
}
