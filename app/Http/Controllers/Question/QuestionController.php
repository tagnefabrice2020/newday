<?php

namespace App\Http\Controllers\Question;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        return Auth::id();
        $perPage = $request->input('per_page', 10);
        $questions = Question::where('created_by', Auth::id())->with(['options'])->paginate($perPage);
        return response()->json($questions, 200);
    }

    // public function 
}
