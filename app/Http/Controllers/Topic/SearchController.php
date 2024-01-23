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
            ->join('users', 'topics.user_id', '=', 'users.id') 
            ->select('topics.*') 
            ->withCount(['questions', 'practiceHistory'])
            ->where('topics.name', 'like', '%' . $r->search . '%')
            ->orWhere('topics.description', 'like', '%' . $r->search . '%')
            ->orWhere('topics.tags', 'like', '%' . $r->search . '%')
            ->orWhere('users.username', 'like', '%' . $r->search . '%');

        if ($r->has('payment') && $r->payment) {
            $topics->where('topics.paid', true);
        }

        if ($r->has('type') && $r->type) {
            $topics->where('topics.type', $r->type);
        }

        if($r->has('country') && $r->country) {
            $topics->where('topics.country', $r->country);
        }

        $topics->paginate(10);

        return response()->json($topics, 200);
    }
}
