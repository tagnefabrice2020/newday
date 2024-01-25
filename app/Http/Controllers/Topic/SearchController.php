<?php

namespace App\Http\Controllers\Topic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search(Request $r)
    {
        $topics = DB::table('topics')
            ->join('users', 'topics.author_id', '=', 'users.id')
            ->select(
                'topics.*',
                DB::raw('(SELECT COUNT(*) FROM questions WHERE questions.topic_id = topics.id) as questions_count'),
                DB::raw('(SELECT COUNT(*) FROM practice_history WHERE practice_history.topic_id = topics.id) as practice_history_count')
            )->where(function ($query) use ($r) {
                $query->where('topics.name', 'like', '%' . $r->search . '%')
                    ->orWhere('topics.description', 'like', '%' . $r->search . '%')
                    ->orWhere('topics.tags', 'like', '%' . $r->search . '%')
                    ->orWhere('users.username', 'like', '%' . $r->search . '%');
            })
            ->when($r->has('paid') && $r->paid, function ($query) {
                $query->where('topics.free', false);
            })
            ->when($r->has('type') && $r->type, function ($query) use ($r) {
                $query->where('topics.type', $r->type);
            })
            ->when($r->has('country') && $r->country, function ($query) use ($r) {
                $query->where('topics.country', $r->country);
            });


        $paginatedTopics = $topics->paginate(10);

        return response()->json($paginatedTopics, 200);
    }
}
