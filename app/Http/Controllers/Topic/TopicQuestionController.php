<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

class TopicQuestionController extends Controller
{
    public function index(Request $r, $uuid)
    {
        $topicQuestions = Question::with('options')
            ->where('topic_id', function ($query) use ($uuid) {
                $query->select('id')
                    ->from('topics')
                    ->where('uuid', $uuid);
            })
            ->paginate($r->perPage);
        return $topicQuestions;
    }
}
