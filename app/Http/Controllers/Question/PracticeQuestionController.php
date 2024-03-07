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
use Illuminate\Support\Facades\Auth;

class PracticeQuestionController extends Controller
{
    public function newPracticeHistoryQuestion(Request $r, $uuid)
    {
        $topic = Topic::where('uuid', $uuid)->first();

        if (!$topic) {
            return response()->json(['message' => 'topic not found'], 404);
        }

        // get last practice test based on the topic of practice test choosen.
        $practice_history = PracticeHistory::where('test_taker_email', Auth::user()->email)
            ->where('topic_id', $topic->id)
            ->whereNull('completed_date')
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$practice_history) {
            $question = Question::where('topic_id', $topic->id)
                ->with('options')
                ->orderByRaw('RAND()')
                ->limit($r->numberOfQuestions ?? $topic->total_number_of_questions_per_session)
                ->get();

            $practice_progress = new PracticeHistory();

            $practice_progress->uuid = Str::orderedUuid();
            $practice_progress->topic_id = $topic->id;
            $practice_progress->test_taker_email = Auth::user()->email;
            $practice_progress->test_questions = json_encode($question);
            $practice_progress->answered_questions = "[]";
            $practice_progress->start_date = now();
            $practice_progress->completed_date = null;
            $practice_progress->current_index = 0;
            $practice_progress->score = null;
            $practice_progress->passing_score = $topic->passing_score;
            $practice_progress->time_left = ($topic->total_number_of_questions_per_session * $topic->duration_per_question_in_minutes) * 60 * 1000;

            if ($r->has('immediateAnswerFeedback')) {
                $practice_progress->iaf = true;
            }

            if ($r->has('numberOfQuestions')) {
                $practice_progress->time_left = ($r->numberOfQuestions * $topic->duration_per_question_in_minutes) * 60 * 1000;
            }

            $save = $practice_progress->save();

            if ($save) return response()->json(["uuid" => $practice_progress->uuid], 201);
        } else {
            return response()->json(["uuid" => $practice_history->uuid], 201);
        }
    }

    public function updatePracticeHistory(Request $r, $uuid)
    {
        $practice_history = PracticeHistory::where('uuid', $uuid)->first();

        if (!$practice_history) {
            return response()->json(['message' => 'not found']);
        }

        if ($r->has('test_questions')) {
            $practice_history->test_questions = $r->test_questions;
        }

        if ($r->has('answered_questions')) {
            $practice_history->answered_questions = $r->answered_questions;
        }

        if ($r->has('current_index')) {
            $practice_history->current_index = $r->current_index;
        }

        if ($r->has('completed_date')) {
            $practice_history->completed_date = Carbon::parse($r->completed_date)->toDateTimeString();
        }

        if ($r->has('score')) {
            $practice_history->score = $r->score;
        }

        if ($r->has('time_left')) {
            $practice_history->time_left = $r->time_left;
        }

        $save = $practice_history->save();

        if ($save) {
            return response()->json(['message' => $practice_history]);
        }
    }

    public function getAllExamPracticesByTopicIdAndOwner(Request $r, $topicUuid, $authorEmail)
    {
        $perPage = $r->input('per_Page');

        $practice_histories = PracticeHistory::where("topic_id", $topicUuid)
            ->where('test_taker_email', $authorEmail)
            ->orderBy('start_date', 'desc')
            ->paginate($perPage);

        return response()->json($practice_histories, 200);
    }

    public function getSingle($uuid)
    {
        $practice_history = PracticeHistory::where('uuid', $uuid)->with('topic')->first();

        if (!$practice_history) {
            return response()->json(['message' => 'not found!'], 404);
        }

        return response()->json($practice_history, 200);
    }
}
