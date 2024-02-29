<?php

namespace App\Http\Controllers\PracticeHistory;

use App\Http\Controllers\Controller;
use App\Models\PracticeHistory;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserPracticeHistoryController extends Controller
{
    public function practiceHistoryByUserGroupByTopics()
    {
        $topics = PracticeHistory::select(
            'test_taker_email',
            'topic_id',
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(score) as average_score'),
            DB::raw('AVG(time_left) as average_time_left')
        )->where('test_taker_email', Auth::user()->email)
            ->groupBy('test_taker_email', 'topic_id')
            ->distinct()
            ->with('topic')
            ->paginate(10);

        return response()->json($topics, 200);
    }

    public function practiceHistoryByUserAndTopic($topicUuid)
    {
        $topics = DB::table('practice_history')
            ->join('topics', 'topics.id', '=', 'practice_history.topic_id')
            ->select('practice_history.*', 'topics.name as topic_name', 'topics.passing_score as passing_score') // Adjust the columns you want to select
            ->where('topics.uuid', $topicUuid)
            ->where('practice_history.test_taker_email', Auth::user()->email)
            ->orderBy('practice_history.id', 'DESC')
            ->paginate(10);

        return response()->json($topics, 200);
    }

    public function practiceHistoryByTopics()
    {
        $topics = PracticeHistory::where('test_taker_email', Auth::user()->email)->paginate(10);

        return response()->json($topics, 200);
    }
}
