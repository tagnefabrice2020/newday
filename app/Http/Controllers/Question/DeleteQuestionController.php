<?php

namespace App\Http\Controllers\Question;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class DeleteQuestionController extends Controller
{
    public function destroy($id)
    {
        $question = Question::where('created_by', Auth::id())->where('uuid', $id)->first();
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        $question->options()->delete();
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully'], 200);
    }
}
