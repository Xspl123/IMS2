<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VendorUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'gst_number' => 'nullable',
            'city' => 'required|string',
            'billing_address' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string',
            'fax' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'required|string',
        ];
    }
}
