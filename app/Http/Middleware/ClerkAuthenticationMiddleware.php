<?php

namespace App\Http\Controllers\SyncUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SyncUserController extends Controller
{
    public function store (Request $r) {
        if ($r->type === 'user.created') {
            $user = new User;

            $user->uuid = Str::orderedUuid();
            $user->authProviderId = $r->data['id'];
            $user->name = $r->data['username'] || ($r->data['first_name'].'" "'.$r->data['last_name']);
            $user->email = $r->data['email_addresses'][0]['email_address'];
            
            $user->role_id = 1;
        
            $save = $user->save();

            if($save) {
                return response()->json(['user' => $user]);
            }
        } else if ($r->type === 'user.updated') {
            $user = User::where('email', $r->data['email_addresses'][0]['email_address'])->first();

            if ($r->has("data.username")) {
                $user->name = $r->data['username'] ;
            }

            if ($r->has("data.email_addresses")) {
                $user->email = $r->data['email_addresses'][0]['email_address'];
            }

            $save = $user->save();

            if($save) {
                return response()->json(['user' => $user]);
            }

        }
    }

    public function test2 () {
        return Auth::user();
    }
}
