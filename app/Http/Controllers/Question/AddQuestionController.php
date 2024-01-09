<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Option;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AddQuestionController extends Controller
{
    public function store(Request $r)
    {
        $r->validate([
            'question' => 'required',
            'tags' => 'required',
            'options' => 'required|array|min:2',
            'options.*.option' => 'required|string',
            'options.*.isAnswer' => 'required|boolean',
        ]);

        $question = new Question;

        $question->question = $r->input('question');
        $question->topic_id = Topic::where('uuid', $r->input('topic'))->first()->id;
        $question->uuid = Str::orderedUuid();
        $question->created_by = Auth::id();

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

        if ($r->has('multipleAnswer') && $r->multipleAnswer === true){
            $question->multiple_answers = true;
        }



        $save = $question->save();

        if ($save) {
            if ($r->has('options')) {
                foreach ($r->input('options') as $optionData) {
                    $option = new Option();
                    $option->uuid = Str::orderedUuid();
                    $option->question_id = $question->id;
                    $option->option_text = $optionData['option'];
                    $option->is_correct = $optionData['isAnswer'];

                    $option->save();
                }
            }
            return response()->json(['message' => 'Question and options updated successfully'], 200);
        }


        return response()->json(['message' => 'Ooops something went wrong'], 500);
    }
}
