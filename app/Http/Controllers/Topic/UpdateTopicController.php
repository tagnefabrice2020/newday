<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UpdateTopicController extends Controller
{
    public function update(Request $r, $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'tags' => 'required',
            'type' => 'required|in:mcq,question_answer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if ($r->has('name')) {
            $topic->name = $r->name;
        }
        if ($r->has('tags')) {
            $topic->tags = implode(",", $r->tags);
        }
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
