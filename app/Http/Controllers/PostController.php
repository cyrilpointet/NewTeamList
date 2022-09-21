<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Team;
use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;

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

        $team = Team::find($id);
        $FcmToken = [];
        foreach ($team->members as $member) {
            if ($member->device_key !== null && $member->id !== $user->id) {
                $FcmToken[] = $member->device_key;
            }
        }
        Larafirebase::withAdditionalData([
            'item' => 'TEAM',
            'id' => $team->id,
        ])
            ->sendMessage($FcmToken);

        return response($post);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::find($id);
        $post->team;
        $team = Team::find($post->team->id);
        $post->delete();

        $user = $request->user();
        $FcmToken = [];
        foreach ($team->members as $member) {
            if ($member->device_key !== null && $member->id !== $user->id) {
                $FcmToken[] = $member->device_key;
            }
        }
        Larafirebase::withAdditionalData([
            'item' => 'TEAM',
            'id' => $team->id,
        ])
            ->sendMessage($FcmToken);

        return response([
            'message' => 'Post deleted'
        ], 200);
    }
}
