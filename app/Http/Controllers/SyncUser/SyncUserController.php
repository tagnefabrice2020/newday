<?php

namespace App\Http\Controllers\SyncUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class SyncUserController extends Controller
{
    public function store (Request $r) {
        if ($r->type === 'user.created') {
            $user = new User;

            $user->uuid = Str::orderedUuid();
            $user->authProviderId = $r->data['id'];
            $user->name = $r->data['username'];
            $user->email = $r->data['email_addresses'][0]['email_address'];
            
            $user->role_id = 1;
        
            $save = $user->save();

            if($save) {
                return response()->json(['user' => $user]);
            }
        } else if ($r->type === 'user.updated') {
            $user = User::where('email', $r->data['email'])->first();

            if ($r->has("data.name")) {
                $user->name = $r->data['username'];
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
}
