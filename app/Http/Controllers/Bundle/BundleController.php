<?php

namespace App\Http\Controllers\Bundle;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index () {
        $bundles = Bundle::where("author_id", Auth::id())->withCount('topics')->paginate(10);

        return response()->json($bundles, 200);
    }
}
