<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SaleUpdateRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'date_of_payment' => 'required',
            'price' => 'required',
            'total_amount' => 'nullable',
            'product_brand_id'=> 'nullable',
            'gst_rate' => 'nullable',
            'status' => 'required|in:ok,defected,replacement,return',
            'replace_remark' => 'nullable',
            'replacement_with'=> 'nullable',
            'replacement_to' => 'nullable',
            'replacement_product_sn' => 'nullable',
            'approved_by' => 'nullable',
            'replacement_product_vendor' => 'nullable',
            'defulty_product_name' => 'nullable',
            'defulty_product_sn' => 'nullable|defulty_product_sn|unique:sales,defulty_product_sn',
            'defulty_product_vendor' => 'nullable',
            'defulty_product_remark' => 'nullable',
            'challan_no' => 'nullable',
            'brand_name' => 'nullable',
            'cat_id' => 'nullable',
            'return_remark'
        ];
    }
}
