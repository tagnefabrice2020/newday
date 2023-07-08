<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UpdateQuestionController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = Question::where('author_id', Auth::id())->findOrFail($id);
        $question->question = $request->input('question');
        $question->save();

        // Delete existing options for the question
        Option::where('question_id', $question->id)->delete();

        foreach ($request->input('options') as $optionData) {
            $option = new Option();
            $option->question_id = $question->id;
            $option->option_text = $optionData['option_text'];
            $option->is_correct = $optionData['is_correct'];
            $option->save();
        }

        return response()->json(['message' => 'Question and options updated successfully'], 200);
    }
}
