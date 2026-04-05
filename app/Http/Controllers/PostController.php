<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category', 'reactions.appreciation'])
            ->withCount('comments')
            ->whereHas('user', function ($q) {
                $q->where('role', 'user');
            })
            ->where(function ($q) {
                $q->where('is_approved', true);
            });

        // No automatic interest filter - show ALL posts by default.
        // Users filter by interest using the navbar category pills.

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $sort = $request->get('sort', 'new');

        switch ($sort) {
            case 'following':
                if (Auth::check()) {
                    $interestIds = Auth::user()->interests()->pluck('categories.id')->toArray();
                    if (!empty($interestIds)) {
                        $query->whereIn('category_id', $interestIds);
                    }
                }
                $query->latest();
                break;
            case 'hot':
                $query->withCount([
                    'reactions as upvotes_count' => function ($q) {
                        $q->whereHas('appreciation', function ($query) {
                            $query->where('type', 'TOP');
                        });
                    }
                ])->orderByDesc('upvotes_count')->latest();
                break;
            case 'best':
                $query->orderByDesc('comments_count')->latest();
                break;
            case 'new':
            default:
                $query->latest(); // Newest first
                break;
        }

        $posts = $query->get();

        // Fetch Personalized Trending Posts (Top 6 by Engagement, filtered by interests if available)
        $trendingQuery = Post::query()
            ->where('is_approved', true)
            ->whereHas('user', function ($q) {
                $q->where('role', 'user');
            })
            ->withCount(['reactions', 'comments']);

        if (Auth::check()) {
            $interestIds = Auth::user()->interests()->pluck('categories.id')->toArray();
            if (!empty($interestIds)) {
                // Prioritize posts in user's interest categories
                $trendingQuery->orderByRaw('category_id IN (' . implode(',', $interestIds) . ') DESC');
            }
        }

        $trendingPosts = $trendingQuery
            ->orderByRaw('(reactions_count + comments_count) DESC')
            ->take(6)
            ->get();

        return view('user.welcome', compact('posts', 'trendingPosts', 'sort'));
    }

    public function create()
    {
        $categories = Category::orderBy('label')->get();

        return view('user.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'media' => 'nullable|image|max:10240', // 10MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('media')) {
            $imagePath = $request->file('media')->store('posts', 'public');
        }

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
            'published_at' => now(),
            'is_approved' => in_array(Auth::user()->role, ['admin', 'moderator']),
            'is_reported' => false,
        ]);

        Conversation::create([
            'post_id' => $post->id,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Your post has been submitted. It will be visible after validation.');
    }

    public function show(Post $post)
    {
        $user = Auth::user();
        if (!$post->is_approved) {
            $allowed = $user && (
                (int) $post->user_id === (int) $user->id
                || in_array($user->role ?? '', ['admin', 'moderator'], true)
            );
            if (!$allowed) {
                abort(404);
            }
        }

        $post->load([
            'user',
            'category',
            'conversation.comments' => function ($q) {
                $q->latest();
            },
            'conversation.comments.user',
            'reactions.appreciation',
            'conversation.comments.reactions.appreciation',
        ]);

        return view('user.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('label')->get();

        return view('user.posts.edit', compact('post', 'categories'));
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
        if ($post->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin', 'moderator'])) {
            abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function report(Post $post)
    {
        if ($post->user_id === Auth::id()) {
            return back()->with('error', 'You cannot report your own post.');
        }

        $post->increment('reports_count');
        $post->update(['is_reported' => true]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Report recorded. The content will be hidden automatically if it accumulates too many reports.');
    }

    public function approve(Post $post)
    {
        if (!in_array(Auth::user()->role, ['admin', 'moderator'])) {
            abort(403, 'Only administrators and moderators can approve posts.');
        }

        $post->update([
            'is_approved' => true,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post approved successfully.');
    }
}