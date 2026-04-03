<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::orderBy('label')->get();

        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'published_at' => now(),
            'is_approved' => false,
            'is_reported' => false,
        ]);

        Conversation::create([
            'post_id' => $post->id,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        $post->load([
            'user',
            'category',
            'conversation.comments.user',
            'reactions.appreciation',
        ]);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('label')->get();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function report(Post $post)
    {
        $post->update([
            'is_reported' => true,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post reported successfully.');
    }

    public function approve(Post $post)
    {
        $post->update([
            'is_approved' => true,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post approved successfully.');
    }
}