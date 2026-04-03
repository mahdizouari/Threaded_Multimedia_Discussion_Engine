<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Appreciation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReactionController extends Controller
{
    public function reactToPost(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:TOP,FLOP',
        ]);

        $existingReaction = Reaction::where('user_id', Auth::id())
            ->where('reactable_id', $post->id)
            ->where('reactable_type', Post::class)
            ->first();

        if ($existingReaction) {
            $existingReaction->appreciation()->update([
                'type' => $validated['type'],
            ]);
        } else {
            $reaction = Reaction::create([
                'user_id' => Auth::id(),
                'reactable_id' => $post->id,
                'reactable_type' => Post::class,
                'reacted_at' => now(),
            ]);

            Appreciation::create([
                'reaction_id' => $reaction->id,
                'type' => $validated['type'],
            ]);
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Reaction added successfully.');
    }

    public function reactToComment(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'type' => 'required|in:TOP,FLOP',
        ]);

        $existingReaction = Reaction::where('user_id', Auth::id())
            ->where('reactable_id', $comment->id)
            ->where('reactable_type', Comment::class)
            ->first();

        if ($existingReaction) {
            $existingReaction->appreciation()->update([
                'type' => $validated['type'],
            ]);
        } else {
            $reaction = Reaction::create([
                'user_id' => Auth::id(),
                'reactable_id' => $comment->id,
                'reactable_type' => Comment::class,
                'reacted_at' => now(),
            ]);

            Appreciation::create([
                'reaction_id' => $reaction->id,
                'type' => $validated['type'],
            ]);
        }

        $post = $comment->conversation->post;

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Reaction added successfully.');
    }
}