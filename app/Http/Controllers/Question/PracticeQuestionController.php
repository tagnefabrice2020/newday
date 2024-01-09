<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\PracticeHistory;
use App\Models\Question;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PracticeQuestionController extends Controller
{
    public function newPracticeHistoryQuestion($id, $email)
    {
        $topic = Topic::where('uuid', $id)->first();

        if (!$topic) {
            return response()->json(['message' => 'topic not found'], 404);
        }

        // get last practice test based on the topic of practice test choosen.
        $practice_history = PracticeHistory::where('test_taker_email', $email)
            ->where('topic_id', $topic->id)
            ->where('complete', false)
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$practice_history) {
            $question = Question::where('topic_id', $topic->id)
                ->with('options')
                ->random()
                ->limit($topic->total_number_of_questions_per_session ?? 50)
                ->get();

            $practice_progress = new PracticeHistory();

            $practice_progress->uuid = Str::orderedUuid();
            $practice_progress->topic_id = $topic->topic_id;
            $practice_progress->test_taker_email = $email;
            $practice_progress->test_questions = json_encode($question);
            $practice_progress->answered_questions = "[]";
            $practice_progress->start_date = now();
            $practice_progress->completed_date = null;
            $practice_progress->score = 0;
            $practice_progress->passing_score = $topic->passing_score;
            $practice_progress->time_left = ($topic->total_number_of_questions_per_session * $topic->duration_per_question_in_minutes);

            $save = $practice_progress->save();

            if ($save) return response()->json($practice_progress->uuid, 201);
        } else {
            return response()->json($practice_history->uuid, 201);
        }
    }

    public function updatePracticeHistory(Request $r, $uuid) {
        $practice_history = PracticeHistory::where('uuid', $uuid)->first();

        if (!$practice_history) {
            return response()->json(['message' => 'not found']);
        }

        if ($r->has('test_questions')) {
            $practice_history->test_questions = $r->test_questions;
        }

        if ($r->has('answered_questions')) {
            $practice_history->answered_questions = $r->answer_questions;
        }

        if ($r->has('completed_date')) {
            $practice_history->completed_date = Carbon::parse($r->completed_date)->toDateTimeString();
        }

        if ($r->has('score')) {
            $practice_history->score = $r->score;
        }

        $practice_history->time_left = ($r->time_left);

        $save = $practice_history->save();

        if  ($save) {
            return response()->json(['message' => $practice_history]);
        }
    }

    public function getAllExamPracticesByTopicIdAndOwner (Request $r, $topicUuid, $authorEmail) {
        $perPage = $r->input('per_Page');
        
        $practice_histories = PracticeHistory::where("topic_id", $topicUuid)
            ->where('test_taker_email', $authorEmail)
            ->orderBy('start_date', 'desc')
            ->paginate($perPage);

        return response()->json($practice_histories, 200);
    }
}
