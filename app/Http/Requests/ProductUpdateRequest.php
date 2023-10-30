<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductUpdateRequest extends FormRequest
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
            'barcode' => 'nullable',
            'name' => 'required|string',
            'description' => 'nullable',
            'brand_name' => 'required',
            'price' => 'required|integer',
            'rent_start_date' => 'nullable',
            'rent_end_date' => 'nullable',
            'price_with_gst' => 'nullable',
            "gstAmount"  => 'nullable',
            'vendor_id' => 'nullable',
            'gst_rate' => 'nullable',
            'total_amount' => 'nullable',
            'product_type'=> 'nullable',
            'product_serial_no' =>'nullable',
            'product_category_id' =>'nullable',
        ];
    }
}
