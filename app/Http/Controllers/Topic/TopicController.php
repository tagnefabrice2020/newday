<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $userId = Auth::id();

        // Check if the user exists
        if (!$userId) {
            return response()->json(['message' => 'User not found'], 404);
        }

       // $topics = Topic::where('author_id', $userId)->paginate($perPage);

        $topics = Topic::where('author_id', $userId)->get();

        return response()->json($topics, 200);
    }
}
