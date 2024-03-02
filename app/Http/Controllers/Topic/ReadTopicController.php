<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReadTopicController extends Controller
{
    public function show($uuid)
    {
        // Check if the user exists
        if (!Auth::check()) {
            return response()->json(['error' => "auth users only"], 403);
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
        if (Auth::check()) {
    
        $perPage = $r->input('per_page', 10);

        // Retrieve the total count of topics for the user
        $totalTopicsCount = Topic::where('author_id', Auth::id())
            ->when($r->has('search') && strlen($r->search) > 3, function ($query) use ($r) {
                $query->where('description', 'like', '%' . $r->search . '%')
                    ->orWhere('name', 'like', '%' . $r->search . '%');
            })
            ->count();

        $questionPools = Topic::where('author_id', Auth::id())
            ->withCount(['questions', 'practiceHistory'])
            ->when($r->has('search') && strlen($r->search) > 3, function ($query) use ($r) {
                $searchTerm = strtolower($r->search); // Convert search term to lowercase
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->whereRaw('LOWER(description) like ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('LOWER(name) like ?', ['%' . $searchTerm . '%']);
                });
            })
            ->paginate($perPage);

         return response()->json([
            'total' => $totalTopicsCount,
            'questionPools' => $questionPools,
            ], 200);
        } 
        return response()->json(['error' => "auth users only"], 403);
    }
}
