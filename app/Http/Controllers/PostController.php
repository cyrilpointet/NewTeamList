<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $user = $request->user();

        $post = Post::create([
            'title' => $request->title,
            'team_id' => $id,
            'user_id' => $user->id
        ]);

        return response($post);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::find($id);
        $post->delete();
        return response([
            'message' => 'Post deleted'
        ], 200);
    }
}
