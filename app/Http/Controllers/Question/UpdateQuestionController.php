<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UpdateQuestionController extends Controller
{
    public function update(Request $r, $id)
    {
        $question = Question::where('created_by', Auth::id())->where('uuid', $id)->first();

        if (empty($question)) {
            return response()->json(['message' => 'Not Found!'], 404);
        }

        if ($r->has('question')) {
            $question->question = $r->input('question');
        }
        if ($r->input('tags')) {
            $question->tags = implode('-', $r->input('tags'));
        }
        if ($r->input('correct_feedback')) {
            $question->correct_feedback = $r->input('correct_feedback');
        }
        if ($r->input('incorrect_feedback')) {
            $question->incorrect_feedback = $r->input('incorrect_feedback');
        }
        if ($r->has('question_answer')) {
            $question->answer = $r->input('question_answer');
        }

        $question->save();
        
        if ($r->has('options')) {
            // Delete existing options for the question
            Option::where('question_id', $question->id)->delete();

            foreach ($r->input('options') as $optionData) {
                $option = new Option();
                $option->question_id = $question->id;
                $option->uuid = Str::orderedUuid();
                $option->option_text = $optionData['option'];
                $option->is_correct = $optionData['isAnswer'];
                $option->save();
            }
        }

        return response()->json(['question' => $question], 200);
    }
    
}
