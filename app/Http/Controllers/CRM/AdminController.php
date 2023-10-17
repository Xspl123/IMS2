<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use View;


class AdminController extends Controller
{
    // private AdminService $adminService;


    

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function showLoginForm()
    {
        return View::make('admin.login');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('admins')],
            'password' => 'required|min:8',
        ]);

        $user = new AdminModel();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json(['message' => 'Registration successful'], 201);
    }

    public function processLoginAdmin(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                throw new ValidationException($validator);
            } else {
                return redirect('login')->withErrors($validator)->withInput();
            }
        }

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = Str::random(60);

            $user->api_token = $token;
            $user->save();

            if ($request->expectsJson()) {
                DB::table('live_agent')->insert([
                    'user_name' => $user->name, 
                ]);

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                ]);
            } else {
                return redirect('/');
            }
        } else {
            $errorMessage = 'Wrong email or password';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                ], Response::HTTP_UNAUTHORIZED);
            } else {
                return redirect('login')->with('message-error', $errorMessage);
            }
        }
    }

    // public function processLoginAdmin(Request $request)
    // {
    //     //TODO validation request
    //     if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
    //         return Redirect::to('/');
    //     } else {
    //         Session::flash('message-error', 'Wrong email or password!');
    //         return Redirect::to('login');
    //     }
    // }

    public function logout()
    {  

        Session::flash('message-success', 'You have been logged out form system.');
        Auth::logout();
        return Redirect::to('login');
    }

    public function renderChangePasswordView()
    {
        return View::make('admin.passwords.reset');
    }

    public function processChangePassword(Request $request)
    {
        if($request->get('old_password') == null || $request->get('new_password') == null || $request->get('confirm_password') == null) {
            Session::flash('message_danger', 'All fields are required.');
            return Redirect::to('password/reset');
        }

        if($this->adminService->loadValidatePassword($request->get('old_password'), $request->get('new_password'), $request->get('confirm_password'), $this->getAdminId())) {
            Session::flash('message_success', 'Your password has been changed.');
            return Redirect::to('password/reset');

        } else {
            Session::flash('message_danger', 'You write wrong password!');
            return Redirect::to('password/reset');
        }
    }

    function decodeAdmin()  
    {
        echo "hello";
    }
}
