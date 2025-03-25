<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

class loginController extends Controller
{
    public function loginMain()
    {
        return view('login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }
        return redirect('/login')->with('error', 'Invalid Username or Password!');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_username' => 'required|string|max:255|unique:users,username',
        ], [
            'new_username.unique' => 'The username is already taken.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        User::create([
            'name' => $request->new_name,
            'username' => $request->new_username,
            'email' => $request->new_email,
            'password' => Hash::make($request->new_password),
        ]);
    
        return response()->json(['success' => 'Account created successfully!']);
    }

    public function logout(Request $request)
    {
        $message = 'Logged out successfully!';
    
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 
    
        return redirect()->route('login')->with('success', $message);
    }

}
