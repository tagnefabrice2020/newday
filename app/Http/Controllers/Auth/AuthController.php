<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $r)
    {
         
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = new User();
        
        $user->uuid = Str::orderedUuid();
        $user->name = $r->input('name');
        $user->email = $r->input('email');
        $user->password = Hash::make($r->input('password'));
        $user->role_id = 3;
       
        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            // Revoke the current user's token
            /** @var \App\Models\User $user **/
            $user = Auth::user();

            // Make sure the user has an access token
            if ($user->currentAccessToken()) {
                $user->tokens()->where('name', $user->currentAccessToken()->name)->delete();
            }
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
    }
}
