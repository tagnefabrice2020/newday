<?php

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class ReadOptionController extends Controller
{
    public function show($id)
    {
        $option = Option::where('uuid', $id)->first();

        return response()->json($option, 200);
    }
}
