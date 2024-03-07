<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Auth;

class DeleteTopicController extends Controller
{
    public function destroy($id)
    {
        $topic = Topic::where('uuid', $id)->where("author_id", Auth::id())->first();

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully'], 200);
    }
}
