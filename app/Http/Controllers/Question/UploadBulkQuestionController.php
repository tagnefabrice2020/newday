<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class UploadBulkQuestionController extends Controller
{
    public function uploadBulkQuestions(Request $r, $uuid)
    {
        // return response()->json(gettype($r->tags), 200);
        $topic = Topic::where("uuid", $uuid)->first();


        if (!empty($topic) && $r->has('questions')) {
            foreach ($r->questions as $questionData) {
                $question = new Question();

                $question->question = $questionData['question'];
                $question->topic_id = $topic->id;
                $question->uuid = Str::orderedUuid();
                $question->created_by = Auth::id();

                if (isset($questionData['tags'])) {
                    $question->tags = implode(',', $questionData['tags']);
                }
                if (isset($questionData['correctFeedBack'])) {
                    $question->correct_feedback = $questionData['correctFeedBack'];
                }
                if (isset($questionData['inCorrectFeedBack'])) {
                    $question->incorrect_feedback = $questionData['inCorrectFeedBack'];
                }

                $save = $question->save();

                if ($save && isset($questionData['options'])) {
                    foreach ($questionData['options'] as $optionData) {
                        $option = new Option();
                        $option->uuid = Str::orderedUuid();
                        $option->question_id = $question->id;
                        $option->option_text = $optionData['option'];
                        $option->is_correct = $optionData['isAnswer'];

                        $option->save();
                    }
                }
            }

            return response()->json('done!', 201);
        } else {
            return response()->json('Ooops something went wrong!', 500);
        }
    }
}
