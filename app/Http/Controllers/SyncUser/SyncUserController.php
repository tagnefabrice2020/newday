<?php

namespace App\Http\Controllers\SyncUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SyncUserController extends Controller
{
    public function store (Request $r) {
        if ($r->type === 'user.created') {
            $user = new User;

            $user->uuid = Str::orderedUuid();
            $user->authProviderId = $r->data['id'];
            $user->name = $r->data['username'];
            $user->email = $r->data['email_addresses']['email_address'];
            $user->image = $r->data['profile_image_url'];
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

            if ($r->has("data.email")) {
                $user->email = $r->data['email_addresses']['email_address'];
            }

            if ($r->has("data.profile_image_url")) {
                $user->image = $r->data['profile_image_url'];
            }

            $save = $user->save();

            if($save) {
                return response()->json(['user' => $user]);
            }

        }
    }
}
