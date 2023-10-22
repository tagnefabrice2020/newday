<?php

namespace App\Http\Controllers\Bundle;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Http\Request;

class DeleteBundleController extends Controller
{
    public function destroy($uuid)
    {
        $bundle = Bundle::where("uuid", $uuid)->first();

        if ($bundle) {
            $bundle->topics()->detach($bundle->id);
            Bundle::where("uuid", $uuid)->delete();
            return response()->json(['message' => 'bundle deleted!'], 200);
        }
        return response()->json(['message' => 'not found'], 404);
    }
}
