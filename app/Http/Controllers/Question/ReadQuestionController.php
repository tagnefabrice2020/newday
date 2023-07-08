<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class ReadQuestionController extends Controller
{
    public function show($id)
    {
        $question = Question::where('author_id', Auth::id())->findOrFail($id);
        $options = Option::where('question_id', $question->id)->get();
        $question['options'] = $options;
        return response()->json($question, 200);
    }
}
