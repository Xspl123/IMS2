<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class DayBooksStoreRequest extends FormRequest
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
            'account' => 'nullable|string|max:200',
            'type' => 'nullable|in:Income,Expense,Transfer',
            'category' => 'nullable|string|max:200',
            'amount' => 'nullable|numeric',
            'payer' => 'nullable|string|max:200',
            'payee' => 'nullable|string|max:200',
            'payerid' => 'nullable|integer',
            'payeeid' => 'nullable|integer',
            'method' => 'nullable|string|max:200',
            'ref' => 'nullable|string|max:200',
            'status' => 'nullable|in:Cleared,Uncleared,Reconciled,Void',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'tax' => 'nullable|numeric',
            'date' => 'nullable|date',
            'dr' => 'nullable|numeric',
            'cr' => 'nullable|numeric',
            'bal' => 'nullable|numeric',
            'iid' => 'nullable|integer',
            'currency' => 'nullable|integer',
            'currency_symbol' => 'nullable|string|max:10',
            'currency_prefix' => 'nullable|string|max:10',
            'currency_suffix' => 'nullable|string|max:10',
            'currency_rate' => 'nullable|numeric',
            'base_amount' => 'nullable|numeric',
            'vid' => 'nullable|integer',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'updated_by' => 'nullable|integer',
            'attachments' => 'nullable|string',
            'source' => 'nullable|string|max:200',
            'rid' => 'nullable|integer',
            'pid' => 'nullable|integer',
            'archived' => 'nullable|integer',
            'trash' => 'nullable|integer',
            'flag' => 'nullable|integer',
            'c1' => 'nullable|string',
            'c2' => 'nullable|string',
            'c3' => 'nullable|string',
            'c4' => 'nullable|string',
            'c5' => 'nullable|string',
            'account_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'sys_pmethod_id' => 'nullable|integer',
        ];
    }
}
