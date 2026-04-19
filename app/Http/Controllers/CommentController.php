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

        $comment = Comment::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'text' => $validated['text'],
            'commented_at' => now(),
            'is_reported' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.posts.partials.comment', ['comment' => $comment, 'post' => $post])->render()
            ]);
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment published.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin', 'moderator'])) {
            abort(403, 'Unauthorized action.');
        }

        $post = $comment->conversation->post;

        $comment->delete();

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment deleted.');
    }

    public function report(Request $request, Comment $comment)
    {
        if ($comment->user_id === Auth::id()) {
            return back()->with('error', 'You cannot report your own comment.');
        }

        $comment->increment('reports_count');
        $comment->update([
            'is_reported'   => true,
            'report_reason' => $request->input('reason', 'Other'),
        ]);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'Report submitted.']);
        }

        $post = $comment->conversation->post;

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comment reported. It will be reviewed by our moderation team.');
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $comment->update([
            'text' => $validated['text'],
        ]);

        return redirect()
            ->route('posts.show', $comment->conversation->post)
            ->with('success', 'Comment updated.');
    }
}   