<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreTopicController extends Controller
{
    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'tags' => 'required',
            'type' => 'required|in:mcq,question_answer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $topic = new Topic;

        $topic->uuid = Str::orderedUuid();
        $topic->name = $r->name;
        $topic->author_id = Auth::id();
        $topic->tags = implode(",", $r->tags);
        if ($r->has('description')) {
            $topic->description = $r->description;
        }
        $save = $topic->save();
        if ($save) {
            return response()->json(['topic' => $topic], 201);
        }
        return response()->json(['message' => 'Oops something went wrong'], 500);
    }
}
