<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateUserController extends Controller
{
    public function update(Request $r, $id)
    {
        $r->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);

        if ($r->has('name')) {
            $user->name = $r->input('name');
        }

        if ($r->has('username')) {
            $user->username = $r->input('username');
        }

        if ($r->has('email')) {
            $user->email = $r->input('email');
        }

        if ($r->has('password')) {
            $user->password = Hash::make($r->input('password'));
        }

        if ($r->has('role_id')) {
            $user->role_id = $r->input('role_id');
        }

        if ($r->has('country_id')) {
            $user->country_id = $r->input('country_id');
        }

        if ($r->has('status')) {
            $user->country_id = $r->input('status');
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }
}
