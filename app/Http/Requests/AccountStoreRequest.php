<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class AccountStoreRequest extends FormRequest
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
            'account' => 'required|string|max:100',
            'description' => 'required|string|max:200',
            'balance' => 'required|numeric',
            'bank_name' => 'nullable|string|max:200',
            'account_number' => 'nullable|string|max:200',
            'currency' => 'nullable|string|max:20',
            'branch' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:200',
            'contact_person' => 'nullable|string|max:200',
            'contact_phone' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:200',
            'ib_url' => 'nullable|string|max:200',
            'created' => 'nullable|date',
            'notes' => 'nullable|string',
            'sorder' => 'nullable|integer',
            'e' => 'nullable|string|max:200',
            'token' => 'nullable|string|max:200',
            'status' => 'nullable|string', // Assuming status is a string field
            
        ];
    }
}
