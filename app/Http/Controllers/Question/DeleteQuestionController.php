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
        $question = Question::where('author_id', Auth::id())->findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully'], 200);
    }
}
