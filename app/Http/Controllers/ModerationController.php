<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    /**
     * Overview Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $pendingQuery = Post::where('is_approved', false);
        $reportedPostsQuery = Post::where('is_reported', true);
        $reportedCommentsQuery = Comment::where('is_reported', true);
        
        // Filter stats and recent activity for moderators
        if ($user->role === 'moderator') {
            $categoryIds = $user->moderatedCategories()->pluck('categories.id')->toArray();
            $pendingQuery->whereIn('category_id', $categoryIds);
            $reportedPostsQuery->whereIn('category_id', $categoryIds);
            $reportedCommentsQuery->whereHas('conversation.post', function($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }

        $stats = [
            'pending_posts' => $pendingQuery->count(),
            'reported_posts' => $reportedPostsQuery->count(),
            'reported_comments' => $reportedCommentsQuery->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_categories' => $user->role === 'moderator' ? $user->moderatedCategories()->count() : Category::count(),
            'urgent_approvals' => (clone $pendingQuery)->where('created_at', '<=', now()->subDay())->count(),
            'critical_flags' => (clone $reportedPostsQuery)->where('reports_count', '>=', 5)->count(),
        ];

        $totalContent = Post::count() + Comment::count();
        $totalReports = $stats['reported_posts'] + $stats['reported_comments'];
        $stats['health_score'] = $totalContent > 0 ? max(0, 100 - ($totalReports * 2)) : 100;

        $recent_reports = $reportedPostsQuery->with('user')->latest()->take(5)->get();
        $recent_pending = $pendingQuery->with('user')->latest()->take(5)->get();

        return view('moderator.dashboard', compact('stats', 'recent_reports', 'recent_pending'));
    }

    /**
     * Pending Approvals View
     */
    public function approvals()
    {
        $user = Auth::user();
        $query = Post::where('is_approved', false)->with(['user', 'category']);

        if ($user->role === 'moderator') {
            $categoryIds = $user->moderatedCategories()->pluck('categories.id')->toArray();
            $query->whereIn('category_id', $categoryIds);
        }

        $pendingPosts = $query->latest()->get();

        return view('moderator.approvals', compact('pendingPosts'));
    }

    /**
     * Flagged Reports View
     */
    public function reports()
    {
        $user = Auth::user();
        
        $reportedPostsQuery = Post::where('is_reported', true)->with(['user', 'category']);
        $reportedCommentsQuery = Comment::where('is_reported', true)->with(['user', 'conversation.post']);

        if ($user->role === 'moderator') {
            $categoryIds = $user->moderatedCategories()->pluck('categories.id')->toArray();
            $reportedPostsQuery->whereIn('category_id', $categoryIds);
            $reportedCommentsQuery->whereHas('conversation.post', function($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }

        $reportedPosts = $reportedPostsQuery->latest()->get();
        $reportedComments = $reportedCommentsQuery->latest()->get();

        return view('moderator.reports', compact('reportedPosts', 'reportedComments'));
    }

    /**
     * Team Management (Admin Only)
     */
    public function team()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $users = User::where('id', '!=', Auth::id())
            ->with('moderatedCategories')
            ->withCount(['posts as reported_posts_count' => function ($query) {
                $query->where('is_reported', true);
            }])
            ->withCount(['comments as reported_comments_count' => function ($query) {
                $query->where('is_reported', true);
            }])
            ->orderBy('name')
            ->get();
            
        $categories = Category::orderBy('label')->get();

        return view('admin.team', compact('users', 'categories'));
    }

    /**
     * Toggle Moderator Role and Assign Categories
     */
    public function toggleModerator(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin role.');
        }

        // If promoting or updating a moderator
        if ($request->has('promote')) {
            $user->role = 'moderator';
            $user->save();
            $user->moderatedCategories()->sync($request->input('category_ids', []));
            return back()->with('success', 'User promoted to moderator for selected categories.');
        } 
        
        // If revoking moderator status
        if ($user->role === 'moderator') {
            $user->role = 'user';
            $user->save();
            $user->moderatedCategories()->detach();
            return back()->with('success', 'Moderator role revoked.');
        }

        return back();
    }

    /**
     * Block or Unblock a user
     */
    public function toggleBlock(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        if ($user->role === 'admin') return back()->with('error', 'Cannot block an administrator.');

        $user->is_blocked = !$user->is_blocked;
        
        // If we are unblocking, reset the violation counter for a clean slate
        if (!$user->is_blocked) {
            $user->violations_count = 0;
        }
        
        $user->save();

        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        return back()->with('success', "User account has been {$status}.");
    }

    /* --- Actions --- */

    public function approvePost(Post $post)
    {
        $this->authorizeModeration($post);
        $post->update([
            'is_approved' => true,
            'is_approval_notified' => false, // Reset to notify the owner
            'is_reported' => false,         // Clear reported status upon approval
            'reports_count' => 0,           // Clean slate
            'published_at' => now(),        // Bring to front of feed
        ]);
        return back()->with('success', 'Post approved and published.');
    }

    public function rejectPost(Post $post)
    {
        $this->authorizeModeration($post);
        $user = $post->user;

        // Increment violation count for the author
        $user->increment('violations_count');
        
        // Auto-block threshold: 5
        if ($user->violations_count >= 5) {
            $user->update(['is_blocked' => true]);
        }

        $post->delete();

        $msg = $user->is_blocked 
            ? 'Post rejected. User reached threshold (5/5) and has been AUTO-BLOCKED.' 
            : 'Post rejected (Infraction ' . $user->violations_count . '/5). Author violation counter increased.';

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'redirect' => (url()->previous() === route('posts.show', $post)) ? route('home') : null
            ]);
        }

        return redirect()->route('home')->with('success', $msg);
    }

    public function dismissReport(Post $post)
    {
        $this->authorizeModeration($post);
        $post->update([
            'is_reported' => false,
            'is_report_notified' => true, // Mark as handled for staff
        ]);
        return back()->with('success', 'Post report dismissed.');
    }

    public function dismissCommentReport(Comment $comment)
    {
        if ($comment->conversation && $comment->conversation->post) {
            $this->authorizeModeration($comment->conversation->post);
        }
        $comment->update(['is_reported' => false]);
        return back()->with('success', 'Comment report dismissed.');
    }

    public function rejectComment(Comment $comment)
    {
        if ($comment->conversation && $comment->conversation->post) {
            $this->authorizeModeration($comment->conversation->post);
        }
        
        $user = $comment->user;

        // Increment violations
        $user->increment('violations_count');
        
        if ($user->violations_count >= 5) {
            $user->update(['is_blocked' => true]);
        }

        $comment->delete();

        $msg = $user->is_blocked 
            ? 'Comment rejected. User reached violation threshold and has been AUTO-BLOCKED.' 
            : 'Comment rejected. Author violation counter increased.';

        return back()->with('success', $msg);
    }

    /**
     * Internal helper to authorize moderation actions
     */
    protected function authorizeModeration(Post $post)
    {
        $user = Auth::user();
        if ($user->role === 'admin') return true;
        
        if ($user->role === 'moderator') {
            $isAssigned = $user->moderatedCategories()
                ->where('categories.id', $post->category_id)
                ->exists();
            if ($isAssigned) return true;
        }

        abort(403, 'You are not authorized to moderate content in this category.');
    }
}
