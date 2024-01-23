<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadBulkQuestionController extends Controller
{
    public function uploadBulkQuestions(Request $r)
    {
        // return response()->json(gettype($r->tags), 200);
        $topic = new Topic;
       
        $topic->uuid = Str::orderedUuid();
        $topic->name = $r->name;
        $topic->author_id = 1;

        if ($r->has('tags')) {
            $topic->tags = implode(",", $r->tags);
        }

        if ($r->has('duration_per_question_in_minutes')) {
            $topic->duration_per_question_in_minutes = $r->duration_per_question_in_minutes;
        }
        if ($r->has('passing_score')) {
            $topic->passing_score = $r->passing_score;
        }
        if ($r->has('total_number_of_questions_per_session')) {
            $topic->total_number_of_questions_per_session = $r->total_number_of_questions_per_session;
        }

        if ($r->has('description')) {
            $topic->description = $r->description;
        }

        $saveTopic = $topic->save();


        if ($saveTopic && $r->has('questions')) {
            foreach ($r->questions as $questionData) {
                $question = new Question();

                $question->question = $questionData['question'];
                $question->topic_id = $topic->id;
                $question->uuid = Str::orderedUuid();
                $question->created_by = 101;

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
