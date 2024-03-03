<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
            ->where('created_by', Auth::user()->id)
            ->when($r->has('search') && strlen($r->search) > 3, function ($query) use ($r) {
                $searchTerm = strtolower($r->search);
                $query->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(question) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereHas('options', function ($query) use ($searchTerm) {
                            $query->whereRaw('LOWER(option_text) LIKE ?', ['%' . $searchTerm . '%']);
                        });
                });
            })
            ->paginate($r->input('per_page', 10));

        return response()->json($questions, 200);
    }
}
