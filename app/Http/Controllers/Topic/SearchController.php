<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $r)
    {
        $topics = Topic::where('name', 'like', '%' . $r->search . '%')
            ->orWhere('description', 'like', '%' . $r->search . '%')
            ->orWhere('tags', 'like', '%' . $r->search . '%')->get();

        return response()->json($topics, 200);
    }
}
