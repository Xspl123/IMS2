<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class SaleStoreRequest extends FormRequest
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
            'quantity' => 'required|integer',
            'product_id' => 'required|integer',
            'date_of_payment' => 'required',
            'price' => 'required',
            'total_amount' => 'nullable',
            "gst_amount" => 'nullable',
            "gst_rate" =>'nullable',
            'vendor_id' =>'nullable',
            'client_id' => 'nullable',
            'sn' => 'nullable'
        ];
    }
}

