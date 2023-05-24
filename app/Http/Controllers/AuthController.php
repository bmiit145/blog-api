<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        ]);

        // Generate JWT token
        $token = Auth::login($user);

        // Return response with token
        return response()->json(['token' => $token]);
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
            $token = Auth::login(Auth::user());
            return response()->json(['token' => $token]);
        }

        // Unauthorized response
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
