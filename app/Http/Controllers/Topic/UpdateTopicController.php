<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateTopicController extends Controller
{
    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'author_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $topic->update($request->all());

        return response()->json($topic, 200);
    }
}
