<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreUserController extends Controller
{
    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        
        $user->uuid = Str::orderedUuid();
        $user->name = $r->input('name');
        $user->username = $r->input('username');
        $user->email = $r->input('email');
        $user->password = Hash::make($r->input('password'));
        $user->role_id = 3;
        $user->country_id = $r->input('country_id');
        $user->save();

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
}
