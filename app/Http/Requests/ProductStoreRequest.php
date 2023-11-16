<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class ProductStoreRequest extends FormRequest
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
            'brand_name' => 'nullable',
            'price' => 'required|integer',
            'rent_start_date' => 'nullable',
            'rent_end_date' => 'nullable',
            'price_with_gst' => 'nullable',
            "gstAmount"  => 'nullable',
            'vendor_id' => 'required',
            'gst_rate' => 'nullable',
            'total_amount' => 'nullable',
            'product_serial_no'=> 'nullable',
            'catgory_name' => 'nullable',
            'product_serial_no'=>'required|unique:products,product_serial_no',
            'product_type' => 'nullable',
            'mac_address' => 'nullable'
            
        ];
        
    }
}
