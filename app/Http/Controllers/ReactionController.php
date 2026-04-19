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
            $appreciation = $existingReaction->appreciation;
            if ($appreciation->type === $validated['type']) {
                $existingReaction->delete(); // Toggle off
            } else {
                $appreciation->update(['type' => $validated['type']]);
            }
        } else {
            $reaction = Reaction::create([
                'user_id' => Auth::id(),
                'reactable_id' => $post->id,
                'reactable_type' => Post::class,
                'reacted_at' => now(),
            ]);

            $reaction->appreciation()->create([
                'type' => $validated['type'],
            ]);
        }

        if ($request->wantsJson()) {
            $likes = Reaction::where('reactable_id', $post->id)
                ->where('reactable_type', Post::class)
                ->whereHas('appreciation', function($q) { $q->where('type', 'TOP'); })
                ->count();
            $dislikes = Reaction::where('reactable_id', $post->id)
                ->where('reactable_type', Post::class)
                ->whereHas('appreciation', function($q) { $q->where('type', 'FLOP'); })
                ->count();
            $userReaction = Reaction::where('user_id', Auth::id())
                ->where('reactable_id', $post->id)
                ->where('reactable_type', Post::class)
                ->first();
            return response()->json([
                'likes' => $likes,
                'dislikes' => $dislikes,
                'userReaction' => $userReaction ? $userReaction->appreciation->type : null
            ]);
        }

        return back()->with('success', 'Reaction updated.');
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
            $appreciation = $existingReaction->appreciation;
            if ($appreciation->type === $validated['type']) {
                $existingReaction->delete(); // Toggle off
            } else {
                $appreciation->update(['type' => $validated['type']]);
            }
        } else {
            $reaction = Reaction::create([
                'user_id' => Auth::id(),
                'reactable_id' => $comment->id,
                'reactable_type' => Comment::class,
                'reacted_at' => now(),
            ]);

            $reaction->appreciation()->create([
                'type' => $validated['type'],
            ]);
        }

        if ($request->wantsJson()) {
            $likes = Reaction::where('reactable_id', $comment->id)
                ->where('reactable_type', Comment::class)
                ->whereHas('appreciation', function($q) { $q->where('type', 'TOP'); })
                ->count();
            $dislikes = Reaction::where('reactable_id', $comment->id)
                ->where('reactable_type', Comment::class)
                ->whereHas('appreciation', function($q) { $q->where('type', 'FLOP'); })
                ->count();
            $userReaction = Reaction::where('user_id', Auth::id())
                ->where('reactable_id', $comment->id)
                ->where('reactable_type', Comment::class)
                ->first();
            return response()->json([
                'likes' => $likes,
                'dislikes' => $dislikes,
                'userReaction' => $userReaction ? $userReaction->appreciation->type : null
            ]);
        }

        return back()->with('success', 'Reaction updated.');
    }
}