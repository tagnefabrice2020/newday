<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AddQuestionController extends Controller
{
    public function store(Request $r)
    {
        $r->validate([
            'topic' => 'required|existes:topics,id',
            'question' => 'required',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = new Question;

        $question->question = $r->input('question');
        $question->topic_id = $r->input('topic_id');
        $question->uuid = Str::orderedUuid();
        $question->created_by = Auth::id();
        
        if ($r->input('tags')) {
            $question->tags = $r->input('tags');
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
            foreach ($r->input('options') as $optionData) {
                $option = new Option();
                $option->question_id = $question->id;
                $option->option_text = $optionData['option_text'];
                $option->is_correct = $optionData['is_correct'];

                $option->save();
            }
        }


        return response()->json(['message' => 'Question and options updated successfully'], 200);
    }
}
