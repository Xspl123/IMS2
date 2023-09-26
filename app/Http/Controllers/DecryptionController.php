<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\ProductsModel;

use App\Models\AdminModel;
use View;
use Str;

class DecryptionController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = AdminModel::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = Str::random(60);
          
              $user->api_token = $token;
              $user->save();

            return response([
                'token' => $token,
                'message' => 'Login success',
                'status' => 'success',
                'user' => $user,
            ], 200);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials are incorrect.',
        ])->status(401);
    }

    public function decryptValue(Request $request)
    {
        $user = Auth::user();
        
        // Enable CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');

        $object = $request->json()->all();

   

        // Check if the 'value' field is present in the JSON payload
        if (!isset($object['barcode'])) {
            return response()->json(['error' => 'Missing value field'], 400);
        }

        $scannedValue = $object['barcode'];
        $encryptionKey = 'xspl'; // Set your encryption key here

        $method = 'aes-256-cbc';
        $iv = substr(hash('sha256', $encryptionKey), 0, 16); // Generate a 16-byte IV from the encryption key
        $encrypted = base64_decode($scannedValue);
        $decryptedValue = openssl_decrypt($encrypted, $method, $encryptionKey, OPENSSL_RAW_DATA, $iv);

        // Insert the decrypted value into the live_agent table using query builder
        DB::table('live_agent')->insert([
            'barcode' => $scannedValue,
            'user_name' => $user->name,
        ]);

        return response()->json(['status'=>'success', 'scannedValue' => $scannedValue]);
    }

    public function getBarcodeValue()
    {
        $live_agent = DB::table('live_agent')->latest('create_at')->first();

       return response()->json(['live_agent'=> $live_agent], 200);
        
    }

    public function getByBarcode($barcode)
    {
         $product = ProductsModel::where('barcode', $barcode)
        ->select('name','count', 'price','rented','purchase','brand_name','price_with_gst')
        ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

}
