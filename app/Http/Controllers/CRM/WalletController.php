<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
use App\WalletHistory;
use Illuminate\Support\Facades\DB; 
class WalletController extends Controller
{
    
        public function updateWallet(Request $request)
        {
            $adminId = auth()->id();
            $rules = [
                'amount' => 'required|numeric|min:0',
            ];
            $messages = [
                'amount.required' => 'The amount field is required.',
                'amount.numeric' => 'The amount must be a numeric value.',
                'amount.min' => 'The amount must be greater than or equal to 0.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('message_danger', 'Validation error(s): Please check the form for errors.');
            }
            $amount = $request->input('amount');
            $wallet = Wallet::first();
            if ($wallet == null) {
                Wallet::create([
                    'admin_id' => $adminId,
                    'amount' => $amount,
                ]);
            } else {
                $wallet->update([
                    'amount' => \DB::raw("amount + $amount"),
                ]);
            }
            // Create an entry in wallet_histories
            DB::table('wallet_histories')->insert([
                'admin_id' => $adminId,
                'amount' => $amount,
                'transaction_type' => 'Expense'
            ]);
            return redirect()->back()->with('message_success', 'Wallet amount updated successfully');
       }
       public function getDetails()
        {
            $walletHistories = DB::table('wallet_histories')
                ->join('admins', 'wallet_histories.admin_id', '=', 'admins.id')
                ->select('wallet_histories.amount', 'wallet_histories.created_at','admins.name as admin_name')
                ->get();
              //dd($walletHistories);
            return view('wallet_history', compact('walletHistories'));
        }
 
}
