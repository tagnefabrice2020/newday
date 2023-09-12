<?php

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class UpdateOptionController extends Controller
{
    public function update(Request $r, $id)
    {
        $option = Option::where('uuid', $id)->first();

        if ($r->has('option')) {
            $option->option_text = $r->option;
        }

        $save = $option->save();

        if ($save) {
            return response()->json($option, 200);
        }
    }
}
