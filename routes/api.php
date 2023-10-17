<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CRM\AdminController;
use App\Http\Controllers\DecryptionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [AdminController::class, 'register']);
Route::post('/login', [DecryptionController::class, 'login']);
// Route::get('/getBarcodeValue', [DecryptionController::class, 'getBarcodeValue']);



Route::middleware(['auth:api'])->group(function(){

   Route::post('/decode', [DecryptionController::class, 'decryptValue']);
   

});