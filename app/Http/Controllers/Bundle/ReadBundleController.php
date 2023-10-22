<?php

namespace App\Http\Controllers\Bundle;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Http\Request;

class ReadBundleController extends Controller
{
    public function show($uuid)
    {
        $bundle = Bundle::where('uuid', $uuid)->first();

        if ($bundle) {
            return response()->json($bundle, 200);
        }

        return response()->json(["message" => "not found!"], 404);
    }
}
