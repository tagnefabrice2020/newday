<?php

namespace App\Http\Controllers\Bundle;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Http\Request;

class UpdateBundleController extends Controller
{
    public function update(Request $r, $uuid)
    {
        $bundle = Bundle::where("uuid", $uuid)->first();

        if ($bundle) {
            $this->validate($r, [
                'author_id' => 'required|exists:users,id',
                'name' => 'required',
                'topics' => 'required|array'
            ]);

            if ($r->has('name')) {
                $bundle->name = $r->name;
            }

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
        return response()->json('not found', 404);
    }
}
