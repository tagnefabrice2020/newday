<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class DeleteTopicController extends Controller
{
    public function destroy($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully'], 200);
    }
}
