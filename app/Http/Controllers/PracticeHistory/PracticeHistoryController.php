<?php

namespace App\Http\Controllers\PracticeHistory;

use App\Http\Controllers\Controller;
use App\Models\PracticeHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PracticeHistoryController extends Controller
{
    public function store (Request $r) {
        DB::table("practice_history")->insert([
            'uuid' => Str::orderedUuid(),
            'topic_id' => $r->topic_id,
            'test_taker_email' => $r->test_taker_email,
            'test_questions' => $r->test_questions,
            'answered_questions' => $r->answered_questions,
            'start_date' => Carbon::parse($r->start_date)->toDateTimeString(),
            'completed_date' => $r->completed_date,
            'score' => $r->score,
            'passing_score' => $r->passing_score,
            'time_left' => $r->time_left
        ]);

        return response()->json(['message' => 'history added']);
    }

    public function getHistoryByTopic ($id) {
        $history = PracticeHistory::where('topic_id', $id)->paginate(10);
        if (!$history) {
            return response()->json(['error' => 'Ooops what you are looking for does not exist!'], 404);
        }
        return response()->json(['data' => $history]);
    }
}
