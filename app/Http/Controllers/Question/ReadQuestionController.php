<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class ReadQuestionController extends Controller
{
    public function show($id)
    {
        $question = Question::where('created_by', Auth::id())
            ->where('uuid', $id)->first();
        if (!$question) {
            return response()->json(['message' => 'not found'], 404);
        }
        
        $options = Option::where('question_id', $question->id)->get();
        $question['options'] = $options;
        return response()->json($question, 200);
    }

    public function showAllQuestionsByUserAndByQuestionPool (Request $r, $uuid) {
        $topic = Topic::where('uuid', $uuid)->first();

        $questions = Question::with('options')
            ->where('topic_id', $topic->id)
            ->where('user_id', Auth::user()->id)
            ->when($r->has('search') && count($r->search) > 3, function ($query) use ($r) {
                $query->where('question', 'like', '%' . $r->search . '%')
                    ->orWhereHas('options', function ($query) use ($r) {
                        $query->where('option_text', 'like', '%' . $r->search . '%');
                    });
            })
            ->paginate(r->input('per_page', 10));

        return response()->json($questions, 200);
    }
}
