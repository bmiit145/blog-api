<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
// use Tymon\JWTAuth\Contracts\Providers\Auth;

class AuthController extends Controller
{
    // signup into the system
    public function signup(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'api_token' => Str::random(60),
        ]);

        // Generate JWT token
        $token = Auth::login($user);

        // Return response with token
        return response()->json(['token' => $token , 'api_token' => Auth::user()]);
    }

    // signin into the system
    public function login(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check credentials and generate JWT token
        if (Auth::attempt($validatedData)) {
            Auth::login(Auth::user());
            // $token = Auth::user()->createToken('authToken')->plainTextToken;
            return response()->json(['token' => 'api_key']);
        }

        // Unauthorized response
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
