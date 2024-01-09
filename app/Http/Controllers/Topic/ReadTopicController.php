<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class ReadTopicController extends Controller
{
    public function show($uuid)
    {
        $userId = Auth::id();

        // Check if the user exists
        if (!$userId) {
            return response()->json(["message" => "Topic not found"], 404);
        }

        $topic = Topic::where('author_id', $userId)->where('uuid', $uuid)->withCount('questions')->first();

        return response()->json($topic, 200);
    }

    public function showSingleTopicInfo ($uuid) {
        $topic = Topic::where('uuid', $uuid)->with('setter')->withCount('questions')->withCount("practiceHistory")->first();

        return response()->json($topic, 200);
    }
}
