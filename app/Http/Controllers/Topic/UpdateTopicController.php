<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UpdateTopicController extends Controller
{
    public function update(Request $r, $uuid)
    {
        $topic = Topic::where('uuid', $uuid)
                    ->where('author_id', Auth::id())
                    ->first();

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'tags' => 'required',
            'type' => 'required|in:mcq,question_answer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if ($r->has('name')) {
            $topic->name = $r->name;
        }
        if ($r->has('tags')) {
            $topic->tags = implode(",", $r->tags);
        }
        if ($r->has('type') && $topic->questions()->count() === 0) {
            $topic->type = $r->type;
        }
        if ($r->has('description')) {
            $topic->description = $r->description;
        }
        if ($r->has('duration_per_question_in_minutes')) {
            $topic->duration_per_question_in_minutes = $r->duration_per_question_in_minutes;
        }
        if ($r->has('passing_score')) {
            $topic->passing_score = $r->passing_score;
        }
        if ($r->has('total_number_of_questions_per_session')) {
            $topic->total_number_of_questions_per_session = $r->total_number_of_questions_per_session;
        }
        
        $save = $topic->save();

        $topic = $topic->withCount(['questions', 'practiceHistory'])
                    ->where('uuid', $uuid)
                    ->where('author_id', Auth::id())
                    ->first();
                    
        if ($save) {
            return response()->json(['topic' => $topic], 201);
        }
        return response()->json(['message' => 'Oops something went wrong'], 500);
    }
}
