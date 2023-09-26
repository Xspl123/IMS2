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
            'gst_number' => 'required|integer',
            'city' => 'required|string',
            'billing_address' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string',
            'fax' => 'required|string',
            'description' => 'required|string',
            'phone' => 'required|string',
            
        ];
    }
}
