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
        $topic = Topic::where('uuid', $uuid)
            ->with('setter')
            ->withCount('questions')
            ->withCount("practiceHistory")
            ->first();

        return response()->json($topic, 200);
    }

    public function showAllQuestionPoolByAuthUser(Request $r){
        $perPage = $r->input('per_page', 10);
       
        $questionPools = Topic::where('user_id', Auth::id())
            ->withCount(['questions', 'practiceHistory'])
            ->when($r->has('search') && count($r->search) > 3, function ($query) use ($r) {
                $query->where('description', 'like', '%' . $r->search . '%')
                        ->orWhere('name', 'like', '%' . $r->search . '%');
            })
            ->paginate($perPage);

        return response()->json($questionPools, 200);
    }
}
