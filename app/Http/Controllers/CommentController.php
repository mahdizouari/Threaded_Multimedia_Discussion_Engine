<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $conversation = $post->conversation;

        if (!$conversation) {
            $conversation = Conversation::create([
                'post_id' => $post->id,
                'created_at' => now(),
            ]);
        }

        Comment::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'text' => $validated['text'],
            'commented_at' => now(),
            'is_reported' => false,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $post = $comment->conversation->post;

        $comment->delete();

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment deleted successfully.');
    }

    public function report(Comment $comment)
    {
        $comment->update([
            'is_reported' => true,
        ]);

        $post = $comment->conversation->post;

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment reported successfully.');
    }
}   