<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class VendorStoreRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check(); 
    }

    public function rules()
    {
        return [
            'name' => 'nullable',
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
