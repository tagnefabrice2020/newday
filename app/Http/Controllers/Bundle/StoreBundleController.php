<?php

namespace App\Http\Controllers\Bundle;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class StoreBundleController extends Controller
{
    public function store(Request $r)
    {
        $this->validate($r, [
            'author_id' => 'required|exists:users,id',
            'name' => 'required',
            'topics' => 'required|array'
        ]);

        $bundle = new Bundle();

        $bundle->author_id = Auth::id();
        $bundle->name = $r->name; // bundle description
        $bundle->uuid = Str::orderedUuid();

        if ($r->has('price')) {
            $bundle->price = $r->price;
        }

        if ($r->has('currency')) {
            $bundle->currency_id = $r->currency;
        }

        $save = $bundle->save();

        if ($save) {
            foreach ($r->topics as $topic) {
                $bundle->topics()->attach($topic);
            }

            return response()->json($bundle, 200);
        }
    }
}
